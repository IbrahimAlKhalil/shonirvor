<nav class="navbar navbar-expand-md navbar-dark bg-secondary">
    <a class="navbar-brand" href="{{ route('home') }}">
        <img src="{{ asset('assets/images/logo.png') }}" style="width: 35px" alt="Logo">
        {{ config('app.name') }}
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault"
            aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item @if(request()->is('dashboard')){{ 'active' }}@endif">
                <a class="nav-link" href="{{ route('dashboard') }}">ড্যাশবোর্ড</a>
            </li>
            <li class="nav-item @if(request()->is('dashboard/users*')){{ 'active' }}@endif">
                <a class="nav-link" href="{{ route('backend.users.index') }}">ইউজার</a>
            </li>
            <li class="nav-item @if(request()->is('dashboard/service-providers*')){{ 'active' }}@endif">
                <a class="nav-link" href="{{ route('service-filter') }}">সার্ভিস</a>
            </li>
            <li class="nav-item dropdown @if(request()->is('dashboard/requests/*')){{ 'active' }}@endif">
                <a class="nav-link dropdown-toggle" href="javascript:" id="packageDropdown" role="button"
                   data-toggle="dropdown">
                    রিকোয়েস্ট
                </a>
                <div class="dropdown-menu" aria-labelledby="packageDropdown">
                    <a class="dropdown-item @if(request()->is('dashboard/requests/individual*') || request()->get('type') == 3 || (request()->is('dashboard/requests/top-service') && request()->get('type') != 4)){{ 'active' }}@endif"
                       href="{{ route('backend.request.ind-service-request.index') }}">ব্যাক্তিগত সার্ভিস</a>
                    <a class="dropdown-item @if(request()->is('dashboard/requests/organization*')  || request()->get('type') == 4){{ 'active' }}@endif"
                       href="{{ route('backend.request.org-service-request.index') }}">প্রাতিষ্ঠানিক সার্ভিস</a>
                    <a class="dropdown-item @if(request()->is('dashboard/requests/ad*')){{ 'active' }}@endif"
                       href="{{ route('backend.request.ad.index') }}">বিজ্ঞাপন</a>

                </div>
            </li>
            <li class="nav-item @if(request()->is('dashboard/*category*')){{ 'active' }}@endif">
                <a class="nav-link" href="{{ route('individual-category.index') }}">ক্যাটাগরি</a>
            </li>
            <li class="nav-item dropdown @if(request()->is('dashboard/packages/*')){{ 'active' }}@endif">
                <a class="nav-link dropdown-toggle" href="javascript:" id="packageDropdown" role="button"
                   data-toggle="dropdown">
                    প্যাকেজ
                </a>
                <div class="dropdown-menu" aria-labelledby="packageDropdown">
                    <a class="dropdown-item @if(request()->is('dashboard/packages/ind-service')){{ 'active' }}@endif"
                       href="{{ route('backend.package.ind-service.index') }}">ব্যাক্তিগত সার্ভিস</a>
                    <a class="dropdown-item @if(request()->is('dashboard/packages/org-service')){{ 'active' }}@endif"
                       href="{{ route('backend.package.org-service.index') }}">প্রাতিষ্ঠানিক সার্ভিস</a>
                    <a class="dropdown-item @if(request()->is('dashboard/packages/ind-top-service')){{ 'active' }}@endif"
                       href="{{ route('backend.package.ind-top-service.index') }}">ব্যাক্তিগত টপ সার্ভিস</a>
                    <a class="dropdown-item @if(request()->is('dashboard/packages/org-top-service')){{ 'active' }}@endif"
                       href="{{ route('backend.package.org-top-service.index') }}">প্রাতিষ্ঠানিক টপ সার্ভিস</a>
                    <a class="dropdown-item @if(request()->is('dashboard/packages/referrer')){{ 'active' }}@endif"
                       href="{{ route('backend.package.referrer.index') }}">রেফারার</a>
                    <a class="dropdown-item @if(request()->is('dashboard/packages/ad')){{ 'active' }}@endif"
                       href="{{ route('backend.package.ad.index') }}">বিজ্ঞাপন</a>
                </div>
            </li>
            <li class="nav-item @if(request()->is('dashboard/area/*')){{ 'active' }}@endif">
                <a class="nav-link" href="{{ route('backend.area.division') }}">এলাকা</a>
            </li>
            <li class="nav-item @if(request()->is('dashboard/contents/*')){{ 'active' }}@endif">
                <a class="nav-link" href="{{ route('contents.slider.index') }}">কন্টেন্ট ম্যানেজমেন্ট</a>
            </li>
            <li class="nav-item @if(request()->is('dashboard/notice*')){{ 'active' }}@endif">
                <a class="nav-link" href="{{ route('backend.notice.index') }}">নোটিশ</a>
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