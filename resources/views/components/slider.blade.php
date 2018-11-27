<div id="slider" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        @foreach($sliders as $key => $slider)
            <li data-target="#slider" data-slide-to="{{ $key }}" class="@if($loop->first){{ 'active' }}@endif"></li>
        @endforeach
    </ol>
    <div class="carousel-inner">
        @foreach($sliders as $slider)
            @php($data = json_decode($slider->data))
            <div class="carousel-item @if($loop->first){{ 'active' }}@endif">
                <a href="{{ $data->link }}">
                    <img class="d-block w-100" src="{{ asset('storage/'.$data->image) }}">
                </a>
            </div>
        @endforeach
    </div>
</div>