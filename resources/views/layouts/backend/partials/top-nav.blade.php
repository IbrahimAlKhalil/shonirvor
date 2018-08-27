<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="{{ route('backend.home') }}">{{ config('app.name') }}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item @if(request()->url() == route('frontend.home')){{ 'active' }}@endif">
                <a class="nav-link" href="{{ route('frontend.home') }}">Home</a>
            </li>
            <li class="nav-item @if(request()->url() == route('backend.home')){{ 'active' }}@endif">
                <a class="nav-link" href="{{ route('backend.home') }}">Backend</a>
            </li>
        </ul>
    </div>
</nav>