<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'login_field' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $user = $this->searchForAUserByEmailOrUsername($this->input('login_field'));

        if (
            !$this->verifyUserRole($user, 'prisoner')
            && $this->verifyUserState($user)
            && $this->verifyUserPassword($user, $this->input('password'))
        ) {
            Auth::login($user, $this->boolean('remember'));
            RateLimiter::clear($this->throttleKey());
        }

        RateLimiter::hit($this->throttleKey());
        throw ValidationException::withMessages([
            'login_field' => __('auth.failed'),
        ]);
    }

    public function searchForAUserByEmailOrUsername(string $user_data): ?User
    {
        return User::where('email', $user_data)
            ->orWhere('username', $user_data)
            ->first();
    }

    public function verifyUserPassword(User|null $user, string $password): bool
    {
        return !is_null($user) && Hash::check($password, $user->password);
    }

    public function verifyUserState(User|null $user): bool
    {
        return !is_null($user) && $user->state;
    }

    public function verifyUserRole(User|null $user, string $role): bool
    {
        return !is_null($user) && $user->hasRole($role);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey(): string
    {
        return Str::lower($this->input('email')) . '|' . $this->ip();
    }
}
