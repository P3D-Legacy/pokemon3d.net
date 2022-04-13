<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200 dark:text-gray-200">
            @lang('Tags')
        </h2>
    </x-slot>

    <div>
        <div class="px-4 py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="block mb-8">
                <a href="{{ route('tags.create') }}" class="px-4 py-2 font-bold text-white bg-green-500 rounded hover:bg-green-700">@lang('Create')</a>
            </div>
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <div class="overflow-hidden border-b border-gray-200 dark:border-gray-800 shadow sm:rounded-lg">
                            <table class="w-full min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                                <thead>
                                <tr>
                                    <th scope="col" width="50" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-200 uppercase bg-gray-50 dark:bg-black">
                                        ID
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-200 uppercase bg-gray-50 dark:bg-black">
                                        @lang('Name')
                                    </th>
                                    <th scope="col" width="200" class="px-6 py-3 bg-gray-50 dark:bg-black">

                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-black divide-y divide-gray-200 dark:divide-gray-800">
                                    @foreach ($tags as $tag)
                                        <tr>
                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100 whitespace-nowrap">
                                                {{ $tag->id }}
                                            </td>

                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100 whitespace-nowrap">
                                                {{ $tag->name }}
                                            </td>

                                            <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                                <a href="{{ route('tags.show', $tag->id) }}" class="px-2 py-1 font-bold text-white bg-blue-400 rounded hover:bg-blue-600">@lang('View')</a>
                                                <a href="{{ route('tags.edit', $tag->id) }}" class="px-2 py-1 font-bold text-white bg-yellow-400 rounded hover:bg-yellow-600">@lang('Edit')</a>
                                                <form class="inline-block" action="{{ route('tags.destroy', $tag->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="px-2 py-1 font-bold text-white bg-red-400 rounded hover:bg-red-600">@lang('Delete')</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="py-3">
                {{ $tags->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
