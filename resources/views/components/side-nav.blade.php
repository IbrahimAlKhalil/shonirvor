<div class="list-group">
    @foreach($navs as $nav)
        <a href="{{ $nav['url'] }}"
           class="list-group-item text-truncate @if(request()->url() == $nav['url']){{ 'active' }}@endif">
            {{ $nav['text'] }}@isset($nav['after']){!! $nav['after'] !!}@endisset
        </a>
    @endforeach
</div>