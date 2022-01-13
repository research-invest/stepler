<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
        <tr>
            <th scope="col">Pair</th>
            <th scope="col">Avg</th>
            <th scope="col">Count</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($pairs as $pair)
            <tr>
                <td>{{ $pair->pair}}</td>
                <td>{{ $pair->average}}</td>
                <td>{{ $pair->count}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        @if($paginate['prev'])
            <button data-value="{{$paginate['prev']}}" class="btn btn-link me-md-2 paginate-pairs" type="button">Prev
            </button>
        @endif

        @if($paginate['next'])
                <button data-value="{{$paginate['next']}}" class="btn btn-link paginate-pairs" type="button">Next</button>
        @endif
    </div>

    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="faq-block card-body">
                    <div id="container-quotes-by-period"></div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var categories = <?= json_encode($dynamics['categories'])?>;
        var series = <?= json_encode($dynamics['series'])?>;

        Highcharts.chart('container-quotes-by-period', {
            chart: {
                type: 'line'
            },
            title: {
                text: 'AVG Quotes by period'
            },
            xAxis: {
                categories: categories
            },
            yAxis: {
                title: {
                    text: 'AVG'
                }
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: false
                    },
                    enableMouseTracking: true
                }
            },
            series: series
        });
    </script>

</div>
