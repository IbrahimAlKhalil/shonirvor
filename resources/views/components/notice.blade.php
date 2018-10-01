<div class="row bg-warning marquee-parent mr-0">
    <div class="col-12 marquee py-2 px-0">
        @foreach($notices as $notice) ** {{ $notice->say }} &nbsp;&nbsp;&nbsp;&nbsp;@endforeach
    </div>
</div>