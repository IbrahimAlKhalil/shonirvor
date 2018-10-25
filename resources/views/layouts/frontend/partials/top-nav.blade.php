<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <a class="navbar-brand" href="{{ route('home') }}">{{ config('app.name') }}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault"
            aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item @if(request()->is('/')){{ 'active' }}@endif">
                <a class="nav-link" href="{{ route('home') }}">হোম</a>
            </li>
            @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">ড্যাশবোর্ড</a>
                </li>
            @endauth
            @auth
                <li class="nav-item @if(request()->is('profile')){{ 'active' }}@endif">
                    <a class="nav-link" href="{{ route('profile.index') }}">প্রোফাইল</a>
                </li>
                @if($myServiceLink)
                    <li class="nav-item @if(request()->is('my-services')){{ 'active' }}@endif">
                        <a class="nav-link" href="{{ $myServiceLink }}">সার্ভিস সমূহ</a>
                    </li>
                @endif
                <li class="nav-item @if(request()->is('*service*registration*')){{ 'active' }}@endif">
                    <a class="nav-link" href="{{ route('service-registration-instruction') }}">সার্ভিস রেজিস্ট্রেশান</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">লগ আউট</a>
                    <form action="{{ route('logout') }}" method="post" id="logout-form" class="hidden">
                        {{ csrf_field() }}
                    </form>
                </li>
            @else
                <li class="nav-item @if(request()->is('login')){{ 'active' }}@endif">
                    <a class="nav-link" href="{{ route('login') }}">লগিন</a>
                </li>
                <li class="nav-item @if(request()->is('register')){{ 'active' }}@endif">
                    <a class="nav-link" href="{{ route('register') }}">রেজিস্ট্রেশান</a>
                </li>
            @endauth
        </ul>
    </div>
</nav>