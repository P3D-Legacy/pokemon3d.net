<div class='text-slate-300'>
    @foreach($party as $pokemon)
        <p>{{ $pokemon->pokemon }}: {{ $pokemon->nickname }}</p>
    @endforeach
</div>
