<nav class="navbar navbar-expand-md navbar-dark bg-secondary">
    <a class="navbar-brand" href="{{ route('backend.home') }}">{{ config('app.name') }}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault"
            aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item @if(request()->url() == route('frontend.home')){{ 'active' }}@endif">
                <a class="nav-link" href="{{ route('frontend.home') }}">হোম</a>
            </li>
            <li class="nav-item @if(request()->url() == route('backend.home')){{ 'active' }}@endif">
                <a class="nav-link" href="{{ route('backend.home') }}">ড্যাশবোর্ড</a>
            </li>
            <li class="nav-item @if(request()->url() == route('individual-service.index') || request()->url() == route('organization-service.index')){{ 'active' }}@endif">
                <a class="nav-link" href="{{ route('individual-service.index') }}">সার্ভিস সমূহ</a>
            </li>
            <li class="nav-item @if(request()->url() == route('individual-category.index') || request()->url() == route('organization-category.index')){{ 'active' }}@endif">
                <a class="nav-link" href="{{ route('individual-category.index') }}">ক্যাটাগরি</a>
            </li>
            <li class="nav-item @if(request()->url() == route('notification.show')){{ 'active' }}@endif">
                <a class="nav-link" href="{{ route('notification.show') }}">নোটিফিকেশন @if($notificationCount)<span class="badge badge-light">{{ $notificationCount }}</span>@endif</a>
            </li>
            @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">লগ আউট</a>
                    <form action="{{ route('logout') }}" method="post" id="logout-form" class="hidden">
                        {{ csrf_field() }}
                    </form>
                </li>
            @endauth
        </ul>
    </div>
</nav>