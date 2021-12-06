<x-dashboard-layout>

    <div class="mt-2">
        <x-form-section>

            <x-slot name="title">{{ __("Create a new report") }}</x-slot>

            <x-slot name="description">
                {{ __("You can register a new report.") }}
            </x-slot>

            <x-slot name="form">
                <form method="POST" action="{{ route('report.store') }}" enctype="multipart/form-data"
                      class="grid grid-cols-6 gap-6">
                    @csrf
                    <!--Title-->
                    <div class="col-span-6">
                        <x-label for="title" :value="__('Title')"/>

                        <x-input id="title"
                                 class="block mt-2 w-full"
                                 type="text"
                                 name="title"
                                 :value="old('title')"
                                 placeholder="Enter the title"
                                 maxlength="45"
                                 required/>

                        <x-input-error for="title" class="mt-2"/>
                    </div>

                    <!--Description-->
                    <div class="col-span-6">
                        <x-label for="description" :value="__('Description')"/>

                        <x-text-area id="description"
                                  name="description"
                                  class="block mt-2 w-full"
                                  rows="6"
                                  placeholder="Enter the description"
                                  maxlength="255"
                                  required>{{old('description')}}</x-text-area>

                        <x-input-error for="description" class="mt-2"/>
                    </div>

                    <!--Image-->
                    <div class="col-span-6">
                        <x-label for="image">
                            {{ __('Image') }}
                            <span class="text-sm ml-2 text-gray-400"> ({{ __('Optional') }})</span>
                        </x-label>

                        <x-input id="image"
                                 class="block mt-2 w-full"
                                 type="file"
                                 name="image"/>

                        <x-input-error for="image" class="mt-2"/>
                    </div>

                    <!--Actions-->
                    <div class="col-span-6 flex justify-end">
                        <x-button class="min-w-max">{{ __('Create') }}</x-button>
                    </div>
                </form>
            </x-slot>

        </x-form-section>
    </div>
</x-dashboard-layout>
