<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Manage Permissions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                <div class="space-y-10">
                    <div class="px-4 py-5 bg-white shadow sm:p-6 sm:rounded-lg">
                        <div class="space-y-6">
                            @foreach ($permissions as $permission)
                                <div class="flex items-center justify-between">
                                    <div>
                                        {{ $permission->name }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end px-4 py-3 text-right bg-gray-50 sm:px-6">
                    {{ $permissions->links() }}
                    <button class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25">
                        <a href="{{ route('permissions.create') }}">{{ __('Create Permission') }}</a>
                    </button>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>