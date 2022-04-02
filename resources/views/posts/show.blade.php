<x-guest-layout>
  <div class="pt-4 bg-gray-100">
      <div class="flex flex-col items-center min-h-screen pt-6 sm:pt-0">
          <div>
              <a href="{{ route('home') }}">
                  <x-jet-authentication-card-logo />
              </a>
          </div>

          <div class="w-full p-6 mt-6 mb-10 overflow-hidden prose bg-white shadow-md sm:max-w-2xl sm:rounded-lg">

            <div class="pb-4 mt-4 text-left">
              <a class="text-sm leading-5 text-gray-700 hover:underline" href="{{ url()->previous() }}">Back to blog</a>
            </div>

            <h1 class="text-5xl text-center ">{{ $post->title }}</h1>
            <p class="mt-3 text-sm leading-5 text-center text-gray-700">Posted {{$post->updated_at->isoFormat('LL')}}</p>
            <article class="markdown-body">
              {!! $post->body_html !!}
            </article>
          </div>
        </div>
    </div>
</x-guest-layout>
