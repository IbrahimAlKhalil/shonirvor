<div class="card">
    <p class="card-header h5">ভিজিটর পরিসংখ্যান</p>
    <div class="card-body">
        <div class="row">
            <div class="col-6 card-text">আজকে</div>
            <div class="col-6 card-text text-center">{{ en2bnNumber($visitor['today']) }}</div>
            <div class="col-6 card-text">এই মাসে</div>
            <div class="col-6 card-text text-center">{{ en2bnNumber($visitor['thisMonth']) }}</div>
            <div class="col-6 card-text">এই বসরে</div>
            <div class="col-6 card-text text-center">{{ en2bnNumber($visitor['thisYear']) }}</div>
        </div>
    </div>
</div>