<x-jet-action-section>
    <x-slot name="title">
        <span id="supersamler">{{ __('Consents') }}</span>
    </x-slot>

    <x-slot name="description">
        {{ __('We will always send you important information about your account. But we are required by law to obtain consent for other things.') }}
    </x-slot>

    <x-slot name="content">
        @foreach($consents as $consent => $text)
            <div class="flex items-center my-2">
                <button
                    @if($this->consentGiven($consent) && substr($consent, 0, 4) == 'tos.')
                        disabled
                    @else
                        wire:click="toggleConsent('{{$consent}}')"
                    @endif
                    type="button"
                    class="
                    @if($this->consentGiven($consent))
                        {{ (substr($consent, 0, 4) == 'tos.') ? 'bg-green-100 cursor-not-allowed' : 'bg-green-800' }}
                    @else
                        bg-gray-200
                    @endif
                    relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-800"
                    aria-pressed="false"
                    aria-labelledby="annual-billing-label"
                >
                    <span class="sr-only">Use setting</span>
                    <span aria-hidden="true" class="{{ ($this->consentGiven($consent)) ? 'translate-x-5' : 'translate-x-0' }} pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"></span>
                </button>
                <span class="ml-3" id="annual-billing-label">
                    <span class="text-sm font-medium text-gray-900">{!! $text !!}</span>
                </span>
            </div>
        @endforeach
    </x-slot>

</x-jet-action-section>
