@foreach($ads as $ad)
    <div class="row">
        <div class="col-12 mb-3 mb-md-5">
            <a href="{{ $ad->url }}" target="_blank">
                <img src="{{ asset('storage/'.$ad->image) }}" class="img-fluid rounded w-100">
            </a>
        </div>
    </div>
@endforeach