@extends('layout.main')
@section('title', 'Edit Skin')
     
@section('content')
<div class="row">
    <div class="col">
        <div class="card my-2">
            <div class="card-header">Edit Skin</div>
            <div class="card-body">
                <form role="form" action="{{ route('skin-update', $skin->uuid) }}" method="post">
                    <div class="form-group mb-3">
                        <label for="formName" class="form-label">Name</label>
                        <input class="form-control" type="text" id="formName" name="name" value="{{ old('public') ? old('public') : $skin->name }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="checkPublic" name="public" @if (old('public') ?? $skin->public) {{'checked'}} @endif>
                            <label class="form-check-label" for="checkPublic">Public <span class="text-muted">Other users will be able to see this</span></label>
                        </div> 
                        @error('public')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @csrf
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection