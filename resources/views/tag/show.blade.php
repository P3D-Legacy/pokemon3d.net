<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-800 dark:text-slate-200">
            @lang('Show')
        </h2>
    </x-slot>

    <div>
        <div class="px-4 py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="block mb-8">
                <a href="{{ route('tags.index') }}" class="px-4 py-2 font-bold text-black bg-slate-200 rounded hover:bg-slate-300">@lang('Back to list')</a>
            </div>
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <div class="overflow-hidden border-b border-slate-200 shadow sm:rounded-lg">
                            <table class="w-full min-w-full divide-y divide-slate-200">
                                <tr class="border-b">
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-slate-500 uppercase bg-slate-50">
                                        ID
                                    </th>
                                    <td class="px-6 py-4 text-sm text-slate-900 bg-white divide-y divide-slate-200 whitespace-nowrap">
                                        {{ $tag->id }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-slate-500 uppercase bg-slate-50">
                                        @lang('Name')
                                    </th>
                                    <td class="px-6 py-4 text-sm text-slate-900 bg-white divide-y divide-slate-200 whitespace-nowrap">
                                        {{ $tag->name }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
