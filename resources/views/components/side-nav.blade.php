<div class="list-group">
    @foreach($navs as $nav)
        <a href="{{ route($nav['route']) }}" class="list-group-item @if(request()->url() == route($nav['route'])){{ 'active' }}@endif">
            {{ $nav['text'] }}
        </a>
    @endforeach
</div>