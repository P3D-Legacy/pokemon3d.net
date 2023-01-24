<x-app-layout>
    @forelse($users as $user)
        {{ $user->name }}
    @empty
        <p>No users found</p>
    @endforelse
</x-app-layout>
