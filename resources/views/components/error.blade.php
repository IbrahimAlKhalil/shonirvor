@if(session()->has('error'))
    <div class="alert alert-danger text-center rounded-0">
        {!! session()->get('error') !!}
    </div>
@endif