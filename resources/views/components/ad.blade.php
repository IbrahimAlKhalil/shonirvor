@foreach($ads as $ad)
    <div class="row">
        <div class="col-12 mb-5">
            <a href="{{ $ad->url }}" target="_blank">
                <img src="{{ asset('storage/'.$ad->image) }}" class="img-fluid rounded">
            </a>
        </div>
    </div>
@endforeach