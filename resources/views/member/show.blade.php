<x-app-layout>

    <div>
        <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto">
                <div class="w-full">
                    <div class="w-full h-48 bg-green-600 rounded-t-lg bg-spring"></div>
                    <div class="absolute ml-5 -mt-20">
                        <div class="overflow-hidden bg-gray-200 border border-b border-gray-300 rounded-lg shadow-md shadow-black/25 w-36 h-36 border-primary">
                            <img class="object-cover w-full" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" />
                        </div>
                    </div>
                </div>

                <div class="flex flex-col p-5 pt-20 bg-white border rounded-b-lg dark:bg-slate-900 border-slate-300 dark:border-slate-800">
                    <div class="text-4xl font-semibold text-gray-800 dark:text-slate-200">{{ $user->username }}</div>
                    @if($user->settings()->get('name'))<div class="text-2xl text-gray-800 dark:text-slate-200">{{ $user->name }}</div>@endif
                    <div class="mt-2 text-sm text-gray-400">
                        <div class="flex flex-row items-center ml-auto space-x-2">
                            <div>{{ __('Joined') }}: {{ $user->created_at->diffForHumans() }}</div>
                            <div class="w-1 h-1 bg-gray-400 rounded-full"></div>
                            <div>{{ __('Last online') }}: {{ $user->last_active_at ? $user->last_active_at ->diffForHumans() : 'Never.' }}</div>
                        </div>
                    </div>
                    <div class="flex gap-2 mt-3">
                        @foreach($user->unlockedAchievements() as $achievement)
                            <x-achievement :achievement="$achievement" />
                        @endforeach
                    </div>
                    <div x-data="{ activeTab:1, tabs: [
                        { id: 1, label: 'About' },
                        { id: 2, label: 'Connected Accounts' },
                        { id: 3, label: 'In-Game Trophies' },
                    ]}">
                        <ul class="flex items-center w-full my-4">
                            <template x-for="(tab, tab.id) in tabs" :key="tab.id">
                                <li class="px-4 py-2 text-gray-500 border-b-2 cursor-pointer" :class="activeTab===tab.id ? 'text-green-500 border-green-500' : ''" @click="activeTab = tab.id" x-text="tab.label"></li>
                            </template>
                        </ul>
                        <div class="flex w-full dark:text-slate-50">
                            <div x-show="activeTab===1">
                                @if($user->about)
                                    <div class="mt-2 mb-1 font-medium underline decoration underline-offset-4">About</div>
                                    {{ $user->about }}
                                @endif
                                @if($user->gender)
                                    <div class="mt-2 mb-1 font-medium underline decoration underline-offset-4">Gender</div>
                                    @switch($user->gender)
                                        @case(0)
                                            <span>No selection</span>
                                            @break
                                        @case(1)
                                            <span>Male</span>
                                            @break
                                        @case(2)
                                            <span>Female</span>
                                            @break
                                        @case(3)
                                            <span>Genderless</span>
                                            @break
                                        @default
                                            <span>Unknown</span>
                                    @endswitch
                                @endif
                                @if($user->location)
                                    <div class="mt-2 mb-1 font-medium underline decoration underline-offset-4">Location</div>
                                    {{ $user->location }}
                                @endif
                                @if($user->birthdate && $user->settings()->get('birthdate') || $user->birthdate && $user->settings()->get('age'))
                                    <div class="mt-2 mb-1 font-medium underline decoration underline-offset-4">Birthday</div>
                                    <div>{{ $user->settings()->get('birthdate') ? $user->birthdate->format('d M Y') :'' }}</div>
                                    <div>{{ $user->settings()->get('age') ? $user->birthdate->age.' years old' : '' }}</div>
                                @endif
                            </div>
                            <div x-show="activeTab===2">
                                @if($user->gamejolt)
                                    <div class="pt-2 mt-2 border-t border-slate-200 dark:border-slate-700">
                                        <img src="{{ asset('img/gamejolt-logo-light-1x.png') }}" class="inline-block">
                                        <p>Username: <a class="hover:underline hover:text-slate-300" href="https://gamejolt.com/{{ '@'.$user->gamejolt->username }}" target="_blank">{{ $user->gamejolt->username }}</a></p>
                                    </div>
                                @endif
                                @if($user->discord)
                                    <div class="pt-2 mt-2 border-t border-slate-200 dark:border-slate-700">
                                        <svg class="w-auto h-10" viewBox="0 0 292 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0)">
                                                <path d="M61.7958 16.494C57.0736 14.2846 52.0244 12.6789 46.7456 11.7646C46.0973 12.9367 45.3399 14.5132 44.8177 15.7673C39.2062 14.9234 33.6463 14.9234 28.138 15.7673C27.6159 14.5132 26.8413 12.9367 26.1872 11.7646C20.9027 12.6789 15.8477 14.2905 11.1255 16.5057C1.60078 30.8988 -0.981215 44.9344 0.309785 58.7707C6.62708 63.4883 12.7493 66.3541 18.7682 68.2294C20.2543 66.1841 21.5797 64.0099 22.7215 61.7185C20.5469 60.8922 18.4641 59.8725 16.4961 58.6887C17.0182 58.3019 17.5289 57.8975 18.0223 57.4814C30.0257 63.0957 43.0677 63.0957 54.9277 57.4814C55.4269 57.8975 55.9375 58.3019 56.4539 58.6887C54.4801 59.8783 52.3916 60.898 50.217 61.7244C51.3588 64.0099 52.6785 66.19 54.1703 68.2352C60.195 66.3599 66.3229 63.4942 72.6402 58.7707C74.155 42.7309 70.0525 28.8242 61.7958 16.494ZM24.3568 50.2615C20.7535 50.2615 17.7985 46.8976 17.7985 42.8012C17.7985 38.7048 20.6904 35.3351 24.3568 35.3351C28.0233 35.3351 30.9782 38.6989 30.9151 42.8012C30.9208 46.8976 28.0233 50.2615 24.3568 50.2615ZM48.5932 50.2615C44.9899 50.2615 42.0349 46.8976 42.0349 42.8012C42.0349 38.7048 44.9267 35.3351 48.5932 35.3351C52.2596 35.3351 55.2146 38.6989 55.1515 42.8012C55.1515 46.8976 52.2596 50.2615 48.5932 50.2615Z" fill="currentColor"/>
                                                <path d="M98.0293 26.1707H113.693C117.469 26.1707 120.659 26.7743 123.276 27.9757C125.886 29.177 127.843 30.8531 129.14 32.998C130.436 35.1429 131.09 37.5984 131.09 40.3645C131.09 43.072 130.413 45.5275 129.059 47.7251C127.705 49.9286 125.645 51.6692 122.874 52.9526C120.103 54.236 116.671 54.8806 112.569 54.8806H98.0293V26.1707ZM112.408 47.5845C114.95 47.5845 116.907 46.934 118.272 45.6388C119.638 44.3378 120.321 42.568 120.321 40.3235C120.321 38.243 119.712 36.5845 118.496 35.3421C117.28 34.0997 115.438 33.4727 112.976 33.4727H108.076V47.5845H112.408Z" fill="currentColor"/>
                                                <path d="M154.541 54.8456C152.372 54.2713 150.415 53.4391 148.677 52.3432V45.5335C149.991 46.5707 151.752 47.4264 153.961 48.1003C156.17 48.7684 158.305 49.1024 160.37 49.1024C161.334 49.1024 162.063 48.9735 162.556 48.7156C163.05 48.4578 163.297 48.1472 163.297 47.7897C163.297 47.3795 163.165 47.0396 162.895 46.7641C162.625 46.4887 162.103 46.2601 161.329 46.0667L156.509 44.9591C153.749 44.3028 151.792 43.3944 150.628 42.2282C149.463 41.0678 148.883 39.5441 148.883 37.6571C148.883 36.0689 149.388 34.6918 150.41 33.5138C151.425 32.3359 152.871 31.4275 154.747 30.7887C156.624 30.1441 158.815 29.8218 161.334 29.8218C163.583 29.8218 165.643 30.0679 167.52 30.5602C169.396 31.0525 170.945 31.6795 172.179 32.4472V38.8878C170.916 38.1201 169.47 37.5165 167.818 37.0593C166.171 36.6081 164.479 36.3854 162.734 36.3854C160.215 36.3854 158.959 36.8249 158.959 37.6981C158.959 38.1084 159.154 38.4131 159.544 38.6182C159.934 38.8233 160.651 39.0343 161.69 39.257L165.706 39.9954C168.329 40.4584 170.285 41.273 171.57 42.4333C172.856 43.5937 173.498 45.3108 173.498 47.5846C173.498 50.0752 172.437 52.0502 170.308 53.5153C168.179 54.9804 165.161 55.7129 161.248 55.7129C158.947 55.7071 156.71 55.4199 154.541 54.8456Z" fill="currentColor"/>
                                                <path d="M182.978 53.9839C180.678 52.8352 178.939 51.2764 177.78 49.3073C176.621 47.3382 176.036 45.123 176.036 42.6616C176.036 40.2003 176.638 37.9968 177.843 36.057C179.048 34.1172 180.815 32.5935 183.145 31.4859C185.474 30.3783 188.257 29.8274 191.499 29.8274C195.515 29.8274 198.849 30.6889 201.5 32.4118V39.919C200.565 39.2626 199.474 38.7293 198.229 38.3191C196.984 37.9089 195.653 37.7037 194.23 37.7037C191.74 37.7037 189.795 38.1667 188.389 39.0985C186.983 40.0303 186.278 41.2434 186.278 42.7495C186.278 44.2263 186.96 45.4336 188.326 46.383C189.692 47.3265 191.671 47.8012 194.27 47.8012C195.607 47.8012 196.927 47.6019 198.229 47.2093C199.526 46.8108 200.645 46.3244 201.58 45.75V53.011C198.637 54.816 195.223 55.7185 191.338 55.7185C188.068 55.7068 185.279 55.1325 182.978 53.9839Z" fill="currentColor"/>
                                                <path d="M211.518 53.9841C209.2 52.8355 207.433 51.2649 206.216 49.2665C205 47.2681 204.386 45.0412 204.386 42.5798C204.386 40.1185 204.994 37.9208 206.216 35.9928C207.438 34.0647 209.194 32.5527 211.501 31.4568C213.801 30.3609 216.55 29.8159 219.734 29.8159C222.919 29.8159 225.667 30.3609 227.968 31.4568C230.269 32.5527 232.025 34.053 233.23 35.9693C234.435 37.8857 235.037 40.0833 235.037 42.574C235.037 45.0353 234.435 47.2623 233.23 49.2606C232.025 51.259 230.263 52.8296 227.945 53.9783C225.627 55.1269 222.89 55.7012 219.729 55.7012C216.567 55.7012 213.83 55.1327 211.518 53.9841ZM223.722 46.7055C224.698 45.7093 225.191 44.3907 225.191 42.7498C225.191 41.1089 224.703 39.802 223.722 38.835C222.747 37.8622 221.415 37.3758 219.729 37.3758C218.013 37.3758 216.67 37.8622 215.689 38.835C214.714 39.8079 214.226 41.1089 214.226 42.7498C214.226 44.3907 214.714 45.7093 215.689 46.7055C216.665 47.7018 218.013 48.2058 219.729 48.2058C221.415 48.1999 222.747 47.7018 223.722 46.7055Z" fill="currentColor"/>
                                                <path d="M259.17 31.3395V40.2004C258.149 39.5147 256.829 39.1748 255.194 39.1748C253.053 39.1748 251.401 39.8371 250.253 41.1615C249.1 42.486 248.526 44.5488 248.526 47.3383V54.8865H238.686V30.8883H248.326V38.5185C248.859 35.7289 249.726 33.672 250.919 32.3416C252.107 31.0172 253.644 30.355 255.515 30.355C256.932 30.355 258.149 30.6832 259.17 31.3395Z" fill="currentColor"/>
                                                <path d="M291.864 25.3503V54.8866H282.023V49.5127C281.191 51.5345 279.929 53.0758 278.231 54.1306C276.532 55.1797 274.432 55.7071 271.942 55.7071C269.716 55.7071 267.777 55.1562 266.118 54.0486C264.46 52.941 263.181 51.4232 262.28 49.4951C261.385 47.567 260.931 45.387 260.931 42.9491C260.903 40.435 261.379 38.1787 262.36 36.1803C263.336 34.1819 264.718 32.6231 266.497 31.5037C268.276 30.3844 270.307 29.8218 272.585 29.8218C277.273 29.8218 280.417 31.9022 282.023 36.0572V25.3503H291.864ZM280.555 46.5415C281.559 45.5452 282.058 44.2501 282.058 42.6678C282.058 41.1382 281.57 39.8899 280.595 38.9347C279.619 37.9795 278.282 37.4989 276.601 37.4989C274.943 37.4989 273.618 37.9853 272.625 38.9581C271.632 39.931 271.139 41.1909 271.139 42.7498C271.139 44.3087 271.632 45.5804 272.625 46.5649C273.618 47.5494 274.926 48.0417 276.561 48.0417C278.219 48.0359 279.55 47.5377 280.555 46.5415Z" fill="currentColor"/>
                                                <path d="M139.382 33.4432C142.091 33.4432 144.288 31.4281 144.288 28.9424C144.288 26.4567 142.091 24.4417 139.382 24.4417C136.672 24.4417 134.476 26.4567 134.476 28.9424C134.476 31.4281 136.672 33.4432 139.382 33.4432Z" fill="currentColor"/>
                                                <path d="M134.472 36.5435C137.478 37.8679 141.208 37.9265 144.283 36.5435V55.0154H134.472V36.5435Z" fill="currentColor"/>
                                            </g>
                                            <defs>
                                                <clipPath id="clip0">
                                                    <rect width="292" height="56.4706" fill="white" transform="translate(0 11.7646)"/>
                                                </clipPath>
                                            </defs>
                                        </svg>
                                        <div class="flex mb-5 text-gray-600 bg-white rounded shadow dark:text-gray-200 dark:bg-black w-max">
                                            <div class="self-center p-2 pr-1">
                                                <img data="picture" class="w-12 h-12 rounded-full" src="{{ $user->discord->avatar }}" alt="{{ $user->discord->username }}" />
                                            </div>
                                            <div class="self-center w-64 p-2">
                                                {{ $user->discord->username }}
                                                <div class="-mt-1 text-sm text-gray-400">#{{ $user->discord->discriminator }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if($user->twitter)
                                    <div class="pt-2 mt-2 border-t border-slate-200 dark:border-slate-700">
                                        <svg class="inline-block w-auto h-10 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 248 204">
                                            <g>
                                                <path d="M221.95,51.29c0.15,2.17,0.15,4.34,0.15,6.53c0,66.73-50.8,143.69-143.69,143.69v-0.04   C50.97,201.51,24.1,193.65,1,178.83c3.99,0.48,8,0.72,12.02,0.73c22.74,0.02,44.83-7.61,62.72-21.66   c-21.61-0.41-40.56-14.5-47.18-35.07c7.57,1.46,15.37,1.16,22.8-0.87C27.8,117.2,10.85,96.5,10.85,72.46c0-0.22,0-0.43,0-0.64   c7.02,3.91,14.88,6.08,22.92,6.32C11.58,63.31,4.74,33.79,18.14,10.71c25.64,31.55,63.47,50.73,104.08,52.76   c-4.07-17.54,1.49-35.92,14.61-48.25c20.34-19.12,52.33-18.14,71.45,2.19c11.31-2.23,22.15-6.38,32.07-12.26   c-3.77,11.69-11.66,21.62-22.2,27.93c10.01-1.18,19.79-3.86,29-7.95C240.37,35.29,231.83,44.14,221.95,51.29z" fill="currentColor"/>
                                            </g>
                                        </svg> Twitter
                                        <div class="flex mb-5 text-gray-600 bg-white rounded shadow dark:text-gray-200 dark:bg-black w-max">
                                            <div class="self-center p-2 pr-1">
                                                <img data="picture" class="w-12 h-12 rounded-full" src="{{ $user->twitter->avatar }}" alt="{{ $user->twitter->username }}" />
                                            </div>
                                            <div class="self-center w-64 p-2">
                                                <a class="hover:underline" href="https://twitter.com/{{ $user->twitter->username }}">{{ $user->twitter->name }}</a>
                                                <div class="-mt-1 text-sm text-gray-400">{{ '@'.$user->twitter->username }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if($user->facebook)
                                    <div class="pt-2 mt-2 border-t border-slate-200 dark:border-slate-700">
                                        <svg class="inline-block w-auto h-10 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14222 14222">
                                            <g>
                                                <path d="M14222 7111c0,-3927 -3184,-7111 -7111,-7111 -3927,0 -7111,3184 -7111,7111 0,3549 2600,6491 6000,7025l0 -4969 -1806 0 0 -2056 1806 0 0 -1567c0,-1782 1062,-2767 2686,-2767 778,0 1592,139 1592,139l0 1750 -897 0c-883,0 -1159,548 -1159,1111l0 1334 1972 0 -315 2056 -1657 0 0 4969c3400,-533 6000,-3475 6000,-7025z" fill="currentColor"/>
                                                <path d="M9879 9167l315 -2056 -1972 0 0 -1334c0,-562 275,-1111 1159,-1111l897 0 0 -1750c0,0 -814,-139 -1592,-139 -1624,0 -2686,984 -2686,2767l0 1567 -1806 0 0 2056 1806 0 0 4969c362,57 733,86 1111,86 378,0 749,-30 1111,-86l0 -4969 1657 0z" fill="none"/>
                                            </g>
                                        </svg> Facebook
                                        <div class="flex mb-5 text-gray-600 bg-white rounded shadow dark:text-gray-200 dark:bg-black w-max">
                                            <div class="self-center p-2 pr-1">
                                                <img data="picture" class="w-12 h-12 rounded-full" src="{{ $user->facebook->avatar }}" alt="{{ $user->facebook->name }}" />
                                            </div>
                                            <div class="self-center w-64 p-2">
                                                {{ $user->facebook->name }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div x-show="activeTab===3">
                                @if($user->gamejolt)
                                    @if($user->gamejolt->trophies->count() > 0)
                                        <div class="w-full mb-2 text-gray-800 dark:text-slate-200">Completed {{ $user->gamejolt->trophies->where('achieved', true)->count() }} of {{ $user->gamejolt->trophies->count() }} trophies</div>
                                        <div class="grid grid-cols-2 gap-4">
                                            @foreach ($user->gamejolt->trophies as $trophy)
                                                <div class="flex flex-col items-center justify-center p-5 rounded-md shadow bg-gray-50 shrink dark:bg-black">
                                                    <img src="{{ $trophy->image_url }}" alt="img" title="img" class="object-cover w-20 h-20 border-2 rounded-md {{ $trophy->difficulty=='Bronze' ? 'border-yellow-800' : '' }}{{ $trophy->difficulty=='Silver' ? 'border-slate-400' : '' }}{{ $trophy->difficulty=='Gold' ? 'border-yellow-500' : '' }}{{ $trophy->difficulty=='Platinum' ? 'border-slate-600' : '' }}">
                                                    <h4 class="my-2 font-bold underline decoration-2 underline-offset-4 {{ $trophy->difficulty=='Bronze' ? 'text-yellow-800' : '' }}{{ $trophy->difficulty=='Silver' ? 'text-slate-400' : '' }}{{ $trophy->difficulty=='Gold' ? 'text-yellow-500' : '' }}{{ $trophy->difficulty=='Platinum' ? 'text-slate-600' : '' }}">
                                                        @if($trophy->achieved)
                                                            <span class="tooltip">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 text-green-500 dark:text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                                </svg>
                                                                <span class="p-3 -mt-4 -ml-3 text-sm text-gray-900 bg-green-200 rounded tooltip-text">{{ __('Achieved') }}</span>
                                                            </span>
                                                        @endif{{ $trophy->title }}
                                                    </h4>
                                                    <div class="text-sm text-center">{{ $trophy->description }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        {{ __('No Trophies') }}
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
