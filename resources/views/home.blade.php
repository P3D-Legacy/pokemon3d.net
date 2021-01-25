@extends('layout.main')
@section('title', 'Home')
     
@section('content')
<div class="row">
    <div class="col-lg-3">
        <div class="card my-2">
            <div class="card-header">Your current skin</div>
            <div class="card-body">
                @if(File::exists(public_path('player/'.$id.'.png')))
                    <img src="{{ asset('player/'.$id.'.png') }}">
                @else
                    <p>We could not find a skin for your account.</p>
                    <p><a href="{{ route('import', $id) }}">Do you want to import the skin from the old site?</a></p>
                @endif
            </div>
            @if(File::exists(public_path('player/'.$id.'.png')))
                <div class="card-footer">
                    <a class="btn btn-sm btn-outline-danger float-end" href="{{ route('skin-destroy') }}" role="button">Delete current skin</a>
                </div>
            @endif
        </div>
        <div class="card my-2">
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
    <div class="col-lg-6">
        <div class="card my-2">
            <div class="card-header">Upload skin</div>
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
        <div class="card my-2">
            <div class="card-header">Information</div>
            <div class="card-body">
                <p>Want to make your own skin? <a href="{{ asset('img/template.png') }}">Download this template</a> to get started.</p>
                <h6>Rules</h6>
                <ul>
                    <li>Every part (for a 96x128 sprite, every 32x32 portion) of the skin has to contain at least one pixel that is not transparent.</li>
                    <li>You have to own the rights to use the image you upload.</li>
                    <li>The image must not contain any sexual or harassing content.</li>
                    <li>If all of the above rules apply to your skin and you upload it, you transfer all rights to Kolben Games. We can alter and delete your skin as long as it stays on our servers.</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card my-2">
            <div class="card-header">Game</div>
            <div class="card-body">
                The latest game release is <span class="badge bg-primary">{{ $game_version ?? 'N/A' }}</span> and was released <span class="badge bg-secondary">{{ \Carbon\Carbon::parse($game_release_date)->diffForHumans() ?? 'N/A' }}</span>. Download the game <a href="https://github.com/P3D-Legacy/P3D-Legacy">here</a>.
            </div>
        </div>
    </div>
</div>

@endsection