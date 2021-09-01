<x-guest-layout>
  <div class="pt-4 bg-gray-100">
      <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
          <div>
              <a href="{{ route('home') }}">
                  <x-jet-authentication-card-logo />
              </a>
          </div>

          <div class="w-full sm:max-w-2xl mt-6 mb-10 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg prose">

            <div class="text-left pb-4 mt-4">
              <a class="text-sm leading-5 text-gray-700 hover:underline" href="{{ url()->previous() }}">Back to blog</a>
            </div>

            <h1 class="text-5xl text-center ">{{$post->title}}</h1>
            <p class="text-sm text-center leading-5 text-gray-700 mt-3">Posted {{$post->updated_at->format('d/m/Y')}}</p>
            <article class="markdown-body">
              {!! $post->body_html !!}
            </article>
          </div>
        </div>
    </div>
</x-guest-layout>
