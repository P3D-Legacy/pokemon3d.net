@extends('layout.main')
@section('title', 'Home')
     
@section('content')
<div class="row">
    <div class="col-lg-3">
        <div class="card mb-3">
            <div class="card-header">Your Current Skin<a class="btn btn-sm btn-outline-danger float-end" href="{{ route('player-skin-destroy') }}" role="button"><i class="fas fa-user-slash"></i> Delete</a></div>
            <div class="card-body">
                @if(File::exists(public_path('player/'.$id.'.png')))
                    <img src="{{ asset('player/'.$id.'.png') }}?r={{ \Carbon\Carbon::now()->timestamp }}">
                @else
                    <p>We could not find a skin for your account.</p>
                    <p><a href="{{ route('import', $id) }}">Do you want to import the skin from the old site?</a></p>
                @endif
            </div>
            @if(File::exists(public_path('player/'.$id.'.png')))
                <div class="card-footer">
                    <a class="btn btn-sm btn-outline-info" href="{{ route('player-skin-duplicate') }}" role="button"><i class="fas fa-file-download"></i> Save to "My skins"</a>
                    
                </div>
            @endif
        </div>
        <div class="card mb-3">
            <div class="card-header">Admin Skin Deletion Activity</div>
            <div class="card-body">
                @if($activity->count())
                    @foreach ($activity as $log)
                        <p class="m-0">{{ $log->created_at }}: {{ $log->properties['reason'] }}</p>
                    @endforeach
                @else
                    <p class="m-0">Nothing found.</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card mb-3">
            <div class="card-header">Skin Information</div>
            <div class="card-body">
                <p>Want to make your own skin? <a href="{{ asset('img/template.png') }}">Download this template</a> to get started.</p>
                <h6>File Validation</h6>
                <ul>
                    <li>Less than 2MB</li>
                    <li>Has to be a PNG-file</li>
                    <li>Dimensions ratio of 3/4</li>
                </ul>
                <h6>Rules</h6>
                <ul>
                    <li>Every part (for a 96x128 sprite, every 32x32 portion) of the skin has to contain at least one pixel that is not transparent.</li>
                    <li>You have to own the rights to use the image you upload.</li>
                    <li>The image must not contain any sexual or harassing content.</li>
                </ul>
                <p>If all of the above rules apply to your skin and you upload it, you transfer all rights to the P3D Team. We can alter and delete your skin as long as it stays on our servers.</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3">        
        <div class="card mb-3">
            <div class="card-header"><img src="{{ asset('img/gamejolt-logo-light-1x.png') }}"> <strong>Account</strong></div>
            <div class="card-body">
                <p class="m-0">
                    <img src="{{ $avatar_url ?? '' }}"><br>
                    Type: {{ $type ?? '' }}<br>
                    Signed up: {{ $signed_up ?? '' }}<br>
                    Last logged in: {{ $last_logged_in ?? '' }}
                </p>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header">Game Information</div>
            <div class="card-body">
                The latest game release is <span class="badge bg-primary">{{ GitHubHelper::getVersion() }}</span> and was released <span class="badge bg-secondary">{{ \Carbon\Carbon::parse(GitHubHelper::getReleaseDate())->diffForHumans() }}</span>. Download this release <a href="{{ GitHubHelper::getDownloadUrl() }}">here</a>.
            </div>
        </div>
    </div>
</div>

@endsection