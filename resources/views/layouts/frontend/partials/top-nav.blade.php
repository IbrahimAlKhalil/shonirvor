<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <a class="navbar-brand" href="{{ route('frontend.home') }}">{{ config('app.name') }}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault"
            aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item @if(request()->url() == route('frontend.home')){{ 'active' }}@endif">
                <a class="nav-link" href="{{ route('frontend.home') }}">হোম</a>
            </li>
            @auth
            <li class="nav-item @if(request()->url() == route('backend.home')){{ 'active' }}@endif">
                <a class="nav-link" href="{{ route('backend.home') }}">ড্যাশবোর্ড</a>
            </li>
            @endauth
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle @if(request()->url() == route('frontend.ind-service.index') || request()->url() == route('frontend.org-service.index')){{ 'active' }}@endif" href="javascript:" id="navbarDropdown" role="button" data-toggle="dropdown">সার্ভিস</a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item @if(request()->url() == route('frontend.ind-service.index')){{ 'active' }}@endif" href="{{ route('frontend.ind-service.index') }}">বেক্তিগত সার্ভিস</a>
                    <a class="dropdown-item @if(request()->url() == route('frontend.org-service.index')){{ 'active' }}@endif" href="{{ route('frontend.org-service.index') }}">প্রাতিষ্ঠানিক সার্ভিস</a>
                </div>
            </li>
            @guest
            <li class="nav-item @if(request()->url() == route('login')){{ 'active' }}@endif">
                <a class="nav-link" href="{{ route('login') }}">লগিন</a>
            </li>
            <li class="nav-item @if(request()->url() == route('register')){{ 'active' }}@endif">
                <a class="nav-link" href="{{ route('register') }}">রেজিস্ট্রেশান</a>
            </li>
            @else
                <li class="nav-item @if(request()->url() == route('profile.index')){{ 'active' }}@endif">
                    <a class="nav-link" href="{{ route('profile.index') }}">প্রোফাইল</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">লগ আউট</a>
                    <form action="{{ route('logout') }}" method="post" id="logout-form" class="hidden">
                        {{ csrf_field() }}
                    </form>
                </li>
            @endguest
        </ul>
    </div>
</nav>