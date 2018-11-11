<div class="list-group">
    @foreach($navs as $nav)
        <a href="{{ $nav['url'] }}"
           class="list-group-item text-truncate @if(request()->url() == explode('?', $nav['url'])[0]){{ 'active' }}@endif">
            {{ $nav['text'] }}@isset($nav['suffix']){!! $nav['suffix'] !!}@endisset
        </a>
    @endforeach
</div>