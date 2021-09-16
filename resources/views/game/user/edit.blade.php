@extends('layouts.main')
@section('title', 'Admin')
     
@section('content')
<div class="container max-w-md mx-auto">
    <div class="mt-4 overflow-hidden bg-white shadow-lg rounded-xl md:mt-8 md:mb-4">
        <div class="px-6 py-4">
            <h4 class="mb-3 text-xl font-semibold tracking-tight text-gray-800">Edit user</h4>
            <form role="form" action="{{ route('user-update', $user->gjid) }}" method="post">
                <div class="mb-3 opacity-50 pointer-events-none">
                    <label for="gjid" class="text-gray-700">Game Jolt ID</label>
                    <input class="flex-1 w-full px-4 py-2 text-base text-gray-700 placeholder-gray-400 bg-white border border-transparent border-gray-300 rounded-lg shadow-sm appearance-none  focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent" type="text" id="gjid" value="{{ $user->gjid }}" disabled="">
                </div>
                <div class="mb-3 opacity-50 pointer-events-none">
                    <label for="gju" class="text-gray-700">Game Jolt Username</label>
                    <input class="flex-1 w-full px-4 py-2 text-base text-gray-700 placeholder-gray-400 bg-white border border-transparent border-gray-300 rounded-lg shadow-sm appearance-none  focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent" type="text" id="gju" value="{{ $user->gju }}" disabled="">
                </div>
                <div class="mb-3">
                    <label for="is_admin" class="text-gray-700">Is Admin <small class="ml-2 text-sm text-gray-500">0 = False & 1 = True</small></label>
                    <input class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="number" min="0" max="1" id="is_admin" name="is_admin" value="{{ $user->is_admin }}">
                    @error('is_admin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                @csrf
                <button type="submit" class="w-full px-6 py-2 text-green-100 transition-colors duration-150 bg-green-700 rounded-lg focus:shadow-outline hover:bg-green-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Save
                </button>
            </form>
        </div>
    </div>
</div>

@endsection