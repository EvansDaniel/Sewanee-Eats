<!-- top tiles -->
<div class="row tile_count">
    @foreach($stats->getStats() as $stat)
        <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"><i class="fa fa-user"></i> {{ $stat->getName() }}</span>
            <div class="count">{{ $stat->getStatDesc() }}</div>
            <span class="count_bottom"><i class="green">4% </i> From last Week</span>
        </div>
    @endforeach
</div>
<!-- /top tiles -->