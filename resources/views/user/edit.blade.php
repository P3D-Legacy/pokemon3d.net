@extends('layout.main')
@section('title', 'Admin')
     
@section('content')
<div class="row">
    <div class="col-12 col-lg-4">
        <div class="card my-2">
            <div class="card-header">Edit user</div>
            <div class="card-body">
                <form role="form" action="{{ route('user-update', $user->gjid) }}" method="post">
                    <div class="form-group mb-3">
                        <label for="gjid" class="form-label">Game Jolt ID</label>
                        <input class="form-control" type="text" id="gjid" value="{{ $user->gjid }}" readonly="readonly">
                    </div>
                    <div class="form-group mb-3">
                        <label for="gju" class="form-label">Game Jolt Username</label>
                        <input class="form-control" type="text" id="gju" value="{{ $user->gju }}" readonly="readonly">
                    </div>
                    <div class="form-group mb-3">
                        <label for="is_admin" class="form-label">Is Admin <small class="text-muted">0 = False & 1 = True</small></label>
                        <input class="form-control" type="number" min="0" max="1" id="is_admin" name="is_admin" value="{{ $user->is_admin }}">
                        @error('is_admin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @csrf
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection