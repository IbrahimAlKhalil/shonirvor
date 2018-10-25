@if(session()->has('success'))
    <div class="alert alert-success text-center rounded-0">
        {!! session()->get('success') !!}
    </div>
@endif