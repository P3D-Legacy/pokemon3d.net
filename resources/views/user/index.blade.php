@extends('layout.main')
@section('title', 'Users')
     
@section('content')
<div class="row">
    <div class="col-12">
        <table class="table table-bordered table-responsive my-3">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Game Jolt ID</th>
                    <th scope="col">Game Jolt Username</th>
                    <th scope="col">Is Admin</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <th scope="row">{{ $user->id }}</th>
                        <td>{{ $user->gjid }}</td>
                        <td>{{ $user->gju }}</td>
                        <td>{{ ($user->is_admin) ? 'Yes' : 'No' }}</td>
                        <td><a class="btn btn-warning" href="{{ route('user-edit', $user->id) }}"><i class="fas fa-edit"></i> Edit</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection