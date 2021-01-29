@extends('layout.main')
@section('title', 'Create Skin')
     
@section('content')
<div class="row">
    <div class="col">
        <div class="card my-2">
            <div class="card-header">Create Skin</div>
            <div class="card-body">
                <form role="form" action="{{ route('skin-store') }}" method="post" enctype="multipart/form-data">
                    <div class="form-group mb-3">
                        <label for="formFile" class="form-label">Select your skin</label>
                        <input class="form-control" type="file" id="formFile" name="image">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="formName" class="form-label">Name</label>
                        <input class="form-control" type="text" id="formName" name="name" value="{{ old('name') ? old('name') : '' }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="checkPublic" name="public" @if (old('public')) {{'checked'}} @endif>
                            <label class="form-check-label" for="checkPublic">Public <span class="text-muted">Other users will be able to see this</span></label>
                        </div> 
                        @error('public')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="checkRules" name="rules">
                            <label class="form-check-label" for="checkRules"><strong>I accept and understand the rules</strong> for uploading a custom skin</label>
                        </div> 
                        @error('rules')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @csrf
                    <button type="submit" class="btn btn-success"><i class="fas fa-upload"></i> Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection