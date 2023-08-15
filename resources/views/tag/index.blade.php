<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-800 dark:text-slate-200 dark:text-slate-200">
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
                        <div class="overflow-hidden border-b border-slate-200 dark:border-slate-800 shadow sm:rounded-lg">
                            <table class="w-full min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                                <thead>
                                <tr>
                                    <th scope="col" width="50" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-slate-500 dark:text-slate-200 uppercase bg-slate-50 dark:bg-black">
                                        ID
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-slate-500 dark:text-slate-200 uppercase bg-slate-50 dark:bg-black">
                                        @lang('Name')
                                    </th>
                                    <th scope="col" width="200" class="px-6 py-3 bg-slate-50 dark:bg-black">

                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-black divide-y divide-slate-200 dark:divide-slate-800">
                                    @foreach ($tags as $tag)
                                        <tr>
                                            <td class="px-6 py-4 text-sm text-slate-900 dark:text-slate-100 whitespace-nowrap">
                                                {{ $tag->id }}
                                            </td>

                                            <td class="px-6 py-4 text-sm text-slate-900 dark:text-slate-100 whitespace-nowrap">
                                                {{ $tag->name }}
                                            </td>

                                            <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                                <a href="{{ route('tags.show', $tag->id) }}" class="w-full px-2 py-1 mr-1 text-sm font-semibold text-center text-white transition duration-200 ease-in bg-blue-600 rounded-lg shadow-md hover:bg-blue-700 focus:ring-blue-500 focus:ring-offset-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    @lang('View')
                                                </a>
                                                <a href="{{ route('tags.edit', $tag->id) }}" class="px-2 py-1 mr-1 text-sm font-semibold text-center text-white transition duration-200 ease-in bg-yellow-600 rounded-lg shadow-md hover:bg-yellow-700 focus:ring-yellow-500 focus:ring-offset-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    @lang('Edit')
                                                </a>
                                                <form class="inline-block" action="{{ route('tags.destroy', $tag->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="w-full px-2 py-1 mr-1 text-sm font-semibold text-center text-white transition duration-200 ease-in bg-red-600 rounded-lg shadow-md hover:bg-red-700 focus:ring-red-500 focus:ring-offset-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        @lang('Delete')
                                                    </button>
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
