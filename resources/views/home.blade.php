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
        </div>
    </div>
    <div class="col-6">
        <div class="card bg-danger">
            <div class="card-header">Not working at the moment!</div>
            <div class="card-body">
                <h5 class="card-title">Upload skin</h5>
                <h6 class="card-subtitle mb-2 text-muted">Want to make your own skin? <a href="{{ asset('img/template.png') }}">Download this template</a> to get started.</h6>
                <form role="form" action="" method="post" enctype="multipart/form-data">
                  <div class="form-group mb-3">
                      <label for="formFile" class="form-label">Select your skin</label>
                      <input class="form-control" type="file" id="formFile">
                  </div>
                  <div class="form-group mb-3">
                      <div class="form-check form-switch">
                          <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                          <label class="form-check-label" for="flexSwitchCheckDefault"><strong>I accept and understand the rules</strong> for uploading a custom skin</label>
                        </div>
                  </div>
              </form>
            </div>
        </div>
    </div>
</div>
@endsection