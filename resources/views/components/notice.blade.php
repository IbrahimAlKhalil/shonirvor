@if($notices->isNotEmpty())
    <div class="row bg-warning marquee-parent mx-0 w-100">
        <div class="col-12 marquee py-2 px-0">
            <span style="animation-duration: {{ $noticeStrCount*50 }}ms">@foreach($notices as $notice) ** {{ $notice->say }} &nbsp;&nbsp;&nbsp;&nbsp;@endforeach</span>
        </div>
    </div>
@endif