<div class="list-group">
    @foreach($navs as $nav)
        <a href="{{ $nav['url'] }}" class="list-group-item @if(request()->url() == $nav['url']){{ 'active' }}@endif">
            {{ $nav['text'] }}
        </a>
    @endforeach
</div>