<div class="card">
    <p class="card-header h5">Visitor Counter</p>
    <div class="card-body">
        <div class="row">
            <div class="col-6 card-text">Today</div>
            <div class="col-6 card-text text-center">{{ $visitor['today'] }}</div>
            <div class="col-6 card-text">This Month</div>
            <div class="col-6 card-text text-center">{{ $visitor['thisMonth'] }}</div>
            <div class="col-6 card-text">This Year</div>
            <div class="col-6 card-text text-center">{{ $visitor['thisYear'] }}</div>
        </div>
    </div>
</div>