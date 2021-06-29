@extends('layout.main')
@section('title', 'Create Skin')
     
@section('content')
<div class="container mx-auto max-w-md">
    <div class="overflow-hidden rounded-xl shadow-lg bg-white mt-4 md:mt-8 md:mb-4">
        <div class="px-6 py-4">
            <h4 class="mb-3 text-xl font-semibold tracking-tight text-gray-800">Create Skin</h4>
            <form role="form" action="{{ route('skin-store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="formName" class="text-gray-700">Name</label>
                    <input class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="text" id="formName" name="name" value="{{ old('name') ? old('name') : '' }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="formFile" class="text-gray-700">Select image file</label>
                    <input class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="file" id="formFile" name="image">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 mt-6">
                    <input class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-offset-0 focus:ring-green-200 focus:ring-opacity-50" type="checkbox" id="checkPublic" name="public" @if (old('public')) {{'checked'}} @endif>
                    <label class="text-gray-700" for="checkPublic">Public <span class="text-gray-500 text-sm ml-2">Other users will be able to see this skin</span></label>
                    @error('public')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <input class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-offset-0 focus:ring-green-200 focus:ring-opacity-50" type="checkbox" id="checkRules" name="rules">
                    <label class="text-gray-700" for="checkRules"><strong>I accept and understand the rules</strong> for uploading a custom skin</label>
                    <p class="text-gray-500 my-4 text-center text-sm">Read the rules on the <a href="{{ route('home') }}" class="text-green-500">home page</a>.</p>
                    @error('rules')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="py-2 px-4 flex justify-center items-center bg-green-600 hover:bg-green-700 focus:ring-green-500 focus:ring-offset-red-200 text-white w-full transition ease-in duration-200 text-center text-base font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2  rounded-lg ">
                    <svg width="20" height="20" fill="currentColor" class="mr-2" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1344 1472q0-26-19-45t-45-19-45 19-19 45 19 45 45 19 45-19 19-45zm256 0q0-26-19-45t-45-19-45 19-19 45 19 45 45 19 45-19 19-45zm128-224v320q0 40-28 68t-68 28h-1472q-40 0-68-28t-28-68v-320q0-40 28-68t68-28h427q21 56 70.5 92t110.5 36h256q61 0 110.5-36t70.5-92h427q40 0 68 28t28 68zm-325-648q-17 40-59 40h-256v448q0 26-19 45t-45 19h-256q-26 0-45-19t-19-45v-448h-256q-42 0-59-40-17-39 14-69l448-448q18-19 45-19t45 19l448 448q31 30 14 69z">
                        </path>
                    </svg>
                    Upload
                </button>
            </form>
        </div>
    </div>
</div>
@endsection