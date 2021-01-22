@extends('layout.main')
@section('title', 'Home')
     
@section('content')
<div class="row">
    <div class="col-3">
        <div class="card">
            <div class="card-header">Your Game Jolt Account</div>
            <div class="card-body">
                <p>ID: {{ $id ?? '' }}</p>
                <p>Type: {{ $type ?? '' }}</p>
                <p>Signed up: {{ $signed_up ?? '' }}</p>
                <p>Last logged in: {{ $last_logged_in ?? '' }}</p>
                <p><img src="{{ $avatar_url ?? '' }}"></p>
            </div>
        </div>
    </div>
    <div class="col-3">
        <div class="card">
            <div class="card-header">Your current skin</div>
            <div class="card-body">
                @if(File::exists(public_path('skins/'.$id.'.png')))
                    <img src="{{ asset('skins/'.$id.'.png') }}">
                @else
                    <p>We could not find a skin for your account.</p>
                    <p><a href="{{ route('import', $id) }}">Do you want to import the skin from the old site?</a></p>
                @endif
            </div>
            @if(File::exists(public_path('skins/'.$id.'.png')))
                <div class="card-footer">
                    <a class="btn btn-sm btn-outline-danger float-end" href="{{ route('skin-destroy') }}" role="button">Delete current skin</a>
                </div>
            @endif
        </div>
    </div>
    <div class="col-6">
        <div class="card">
            <div class="card-header">Upload skin</div>
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Want to make your own skin? <a href="{{ asset('img/template.png') }}">Download this template</a> to get started.</h6>
                <form role="form" action="{{ route('skin-store') }}" method="post" enctype="multipart/form-data">
                    <div class="form-group mb-3">
                        <label for="formFile" class="form-label">Select your skin</label>
                        <input class="form-control" type="file" id="formFile" name="image">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" name="rules">
                            <label class="form-check-label" for="flexSwitchCheckDefault"><strong>I accept and understand the rules</strong> for uploading a custom skin</label>
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