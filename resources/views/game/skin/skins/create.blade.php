@extends('layouts.main')
@section('title', 'Create Skin')
     
@section('content')
<div class="container max-w-md mx-auto">
    <div class="mt-4 overflow-hidden bg-white shadow-lg rounded-xl md:mt-8 md:mb-4">
        <div class="px-6 py-4">
            <h4 class="mb-3 text-xl font-semibold tracking-tight text-gray-800">Create Skin</h4>
            <form role="form" action="{{ route('skin-store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="formName" class="text-gray-700">Name</label>
                    <input class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="text" id="formName" name="name" value="{{ old('name') ? old('name') : '' }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="formFile" class="text-gray-700">Select image file</label>
                    <input class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="file" id="formFile" name="image">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mt-6 mb-3">
                    <input class="text-green-600 border-gray-300 rounded shadow-sm focus:border-green-300 focus:ring focus:ring-offset-0 focus:ring-green-200 focus:ring-opacity-50" type="checkbox" id="checkPublic" name="public" @if (old('public')) {{'checked'}} @endif>
                    <label class="text-gray-700" for="checkPublic">Public <span class="ml-2 text-sm text-gray-500">Other users will be able to see this skin</span></label>
                    @error('public')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <input class="text-green-600 border-gray-300 rounded shadow-sm focus:border-green-300 focus:ring focus:ring-offset-0 focus:ring-green-200 focus:ring-opacity-50" type="checkbox" id="checkRules" name="rules">
                    <label class="text-gray-700" for="checkRules"><strong>I accept and understand the rules</strong> for uploading a custom skin</label>
                    <p class="my-4 text-sm text-center text-gray-500">Read the rules on the <a href="{{ route('home') }}" class="text-green-500">home page</a>.</p>
                    @error('rules')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="flex items-center justify-center w-full px-4 py-2 text-base font-semibold text-center text-white transition duration-200 ease-in bg-green-600 rounded-lg shadow-md hover:bg-green-700 focus:ring-green-500 focus:ring-offset-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Upload
                </button>
            </form>
        </div>
    </div>
</div>
@endsection