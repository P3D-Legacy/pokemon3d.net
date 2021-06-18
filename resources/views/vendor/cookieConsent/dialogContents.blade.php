<div class="js-cookie-consent absolute left-0 bottom-0 w-64 bg-white rounded-lg shadow-2xl p-6 ml-8 mb-8 z-50">
    <div class="w-16 mx-auto relative -mt-12 mb-3">
        <img class="-mt-1" src="{{ asset('/img/cookie.svg') }}" alt="cookie"/>
    </div>
    <span class="w-full  block leading-normal text-gray-800 text-md mb-3">
        {!! trans('cookieConsent::texts.message') !!}
    </span>
    <div class="flex items-center justify-between">
        <button type="button" class="js-cookie-consent-agree py-2 px-4 bg-indigo-600 hover:bg-gr-700 focus:ring-indigo-500 focus:ring-offset-indigo-200 text-white w-full transition ease-in duration-200 text-center text-base font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 rounded-lg">
            {{ trans('cookieConsent::texts.agree') }}
        </button>
    </div>
</div>