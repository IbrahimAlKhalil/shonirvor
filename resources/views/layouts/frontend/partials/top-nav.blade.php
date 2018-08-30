<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <a class="navbar-brand" href="{{ route('frontend.home') }}">{{ config('app.name') }}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault"
            aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item @if(request()->url() == route('frontend.home')){{ 'active' }}@endif">
                <a class="nav-link" href="{{ route('frontend.home') }}">Home</a>
            </li>

            <li class="nav-item @if(request()->url() == route('registration.dealer.index')){{ 'active' }}@endif">
                <a class="nav-link" href="{{ route('registration.dealer.index') }}">Dealer Registrarion</a>
            </li>

            <li class="nav-item @if(request()->url() == route('registration.service-provider.agreement')){{ 'active' }}@endif">
                <a class="nav-link" href="{{ route('registration.service-provider.agreement') }}">SP
                    Registrarion</a>
            </li>

            <li class="nav-item @if(request()->url() == route('backend.home')){{ 'active' }}@endif">
                <a class="nav-link" href="{{ route('backend.home') }}">Backend</a>
            </li>

            @auth()
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}">Logout</a>
                </li>
            @endauth

        </ul>
    </div>
</nav>