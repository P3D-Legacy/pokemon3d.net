@props(['style' => session('flash.bannerStyle', 'success'), 'message' => session('flash.banner')])

<div x-data="{{ json_encode(['show' => true, 'style' => $style, 'message' => $message]) }}"
            :class="{ 'bg-green-500': style == 'success', 'bg-red-700': style == 'danger', 'bg-yellow-700': style == 'warning', 'bg-blue-500': style == 'info' }"
            style="display: none;"
            x-show="show && message"
            x-init="
                document.addEventListener('banner-message', event => {
                    style = event.detail.style;
                    message = event.detail.message;
                    show = true;
                });
            ">
    <div class="max-w-screen-xl px-3 py-2 mx-auto sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-between">
            <div class="flex items-center flex-1 w-0 min-w-0">
                <span class="flex p-2 rounded-lg" :class="{ 'bg-green-600': style == 'success', 'bg-red-600': style == 'danger', 'bg-yellow-600': style == 'warning', 'bg-blue-600': style == 'info' }">
                    @if(session('flash.bannerStyle') == 'success')
                        <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @elseif(session('flash.bannerStyle') == 'danger')
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @elseif(session('flash.bannerStyle') == 'warning')
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    @elseif(session('flash.bannerStyle') == 'info')
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @endif
                </span>

                <p class="ml-3 text-sm font-medium text-white truncate" x-text="message"></p>
            </div>

            <div class="flex-shrink-0 sm:ml-3">
                <button
                    type="button"
                    class="flex p-2 -mr-1 transition rounded-md focus:outline-none sm:-mr-2"
                    :class="{ 'hover:bg-green-600 focus:bg-green-600': style == 'success', 'hover:bg-red-600 focus:bg-red-600': style == 'danger', 'hover:bg-yellow-600 focus:bg-yellow-600': style == 'warning', 'hover:bg-blue-600 focus:bg-blue-600': style == 'info' }"
                    aria-label="Dismiss"
                    x-on:click="show = false">
                    <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
