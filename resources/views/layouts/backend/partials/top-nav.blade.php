<nav class="navbar navbar-expand-md navbar-dark bg-secondary">
    <a class="navbar-brand" href="{{ route('dashboard') }}">{{ config('app.name') }}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault"
            aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('home') }}">হোম</a>
            </li>
            <li class="nav-item @if(request()->is('dashboard')){{ 'active' }}@endif">
                <a class="nav-link" href="{{ route('dashboard') }}">ড্যাশবোর্ড</a>
            </li>
            <li class="nav-item @if(request()->is('dashboard/individual-service*') || request()->is('dashboard/organization-service*')){{ 'active' }}@endif">
                <a class="nav-link" href="{{ route('individual-service.index') }}">সার্ভিস সমূহ</a>
            </li>
            <li class="nav-item @if(request()->is('dashboard/*category*')){{ 'active' }}@endif">
                <a class="nav-link" href="{{ route('individual-category.index') }}">ক্যাটাগরি</a>
            </li>
            <li class="nav-item dropdown @if(request()->is('dashboard/packages/*')){{ 'active' }}@endif">
                <a class="nav-link dropdown-toggle" href="javascript:" id="packageDropdown" role="button" data-toggle="dropdown">
                    প্যাকেজ
                </a>
                <div class="dropdown-menu" aria-labelledby="packageDropdown">
                    <a class="dropdown-item @if(request()->is('dashboard/packages/service')){{ 'active' }}@endif" href="{{ route('backend.package.service.index') }}">সার্ভিস</a>
                    <a class="dropdown-item @if(request()->is('dashboard/packages/top-service')){{ 'active' }}@endif" href="{{ route('backend.package.top-service.index') }}">টপ সার্ভিস</a>
                    <a class="dropdown-item @if(request()->is('dashboard/packages/referrer')){{ 'active' }}@endif" href="{{ route('backend.package.referrer.index') }}">রেফারার</a>
                    <a class="dropdown-item @if(request()->is('dashboard/packages/ad')){{ 'active' }}@endif" href="{{ route('backend.package.ad.index') }}">এড</a>
                </div>
            </li>
            <li class="nav-item @if(request()->is('dashboard/area/*')){{ 'active' }}@endif">
                <a class="nav-link" href="{{ route('backend.area.division') }}">এলাকা</a>
            </li>
            <li class="nav-item @if(request()->is('dashboard/contents/*')){{ 'active' }}@endif">
                <a class="nav-link" href="{{ route('contents.slider.index') }}">কন্টেন্ট ম্যানেজমেন্ট</a>
            </li>
            <li class="nav-item @if(request()->is('dashboard/ad*')){{ 'active' }}@endif">
                <a class="nav-link" href="{{ route('backend.ad.index') }}">বিজ্ঞাপন</a>
            </li>
            <li class="nav-item @if(request()->is('dashboard/notice*')){{ 'active' }}@endif">
                <a class="nav-link" href="{{ route('backend.notice.index') }}">নোটিশ</a>
            </li>
            <li class="nav-item @if(request()->is('dashboard/notifications')){{ 'active' }}@endif">
                <a class="nav-link" href="{{ route('notification.show') }}">নোটিফিকেশন @if($notificationCount)<span
                            class="badge badge-light">{{ $notificationCount }}</span>@endif</a>
            </li>
            @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">লগ আউট</a>
                    <form action="{{ route('logout') }}" method="post" id="logout-form" class="hidden">
                        {{ csrf_field() }}
                    </form>
                </li>
            @endauth
        </ul>
    </div>
</nav>