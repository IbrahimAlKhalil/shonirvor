<div id="slider" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#slider" data-slide-to="0" class="active"></li>
        <li data-target="#slider" data-slide-to="1"></li>
        <li data-target="#slider" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
        @foreach($sliders as $slider)
            @php($data = json_decode($slider->data))
            <div class="carousel-item @if($loop->first){{ 'active' }}@endif">
                <a href="{{ $data->link }}" target="_blank">
                    <img class="d-block w-100" src="{{ asset('storage/'.$data->image) }}">
                </a>
            </div>
        @endforeach
    </div>
</div>