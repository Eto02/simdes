@extends('layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Laporan</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    {{-- <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard v1</li> --}}
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <label>Filter Bulan :</label>
            <div class="form-group col-md-3">
                <div class="input-group input-group-sm date" id="date" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input" data-target="#date" />
                    <div class="input-group-append" data-target="#date" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
            
            <div class="form-group col-md-2">
                <button type="button" class="btn btn-primary btn-block btn-sm" onclick="generateChart()">Lihat Grafik</button>
            </div>
            <div class="form-group col-md-2">
                <button type="button" class="btn btn-primary btn-block btn-sm" onclick="print()"><i class="fa fa-print"></i> Cetak</button>
            </div>
        </div>

        <!-- PIE CHART -->
        <div class="card-body">
            <canvas id="pieChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
        </div>
        <!-- BAR CHART -->
        <div class="card-body">
            <canvas id="barChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->

<script>
    var pieChart, barChart;

    $('#date').datetimepicker({
        viewMode: 'months',
        format: 'MMM YYYY'
    });

    generateChart();

    function generateChart() {
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            url: "{{ route('report.get_data') }}",
            type: "GET",
            data: {
                date: $("#date").find("input").val()
            },
            dataType: "json",
            success: function (res) {
                setPieChart(res.data);
                setBarChart(res.data);
            }
        });
    }

    function setPieChart(result) {
        var donutData = {
            labels: result.labels,
            datasets: [
                {
                    data: result.data,
                    backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
                }
            ]
        }
        if (pieChart) {
            pieChart.destroy();
        }
        var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
        var pieData = donutData;
        var pieOptions = {
            maintainAspectRatio : false,
            responsive : true
        }
        pieChart = new Chart(pieChartCanvas, {
            type: 'pie',
            data: pieData,
            options: pieOptions
        })
    }

    function setBarChart(result) {
        var areaChartData = {
            labels  : result.labels,
            datasets: [
                {
                    label : 'Digital Goods',
                    backgroundColor : 'rgba(60,141,188,0.9)',
                    borderColor : 'rgba(60,141,188,0.8)',
                    pointRadius : false,
                    pointColor : '#3b8bba',
                    pointStrokeColor : 'rgba(60,141,188,1)',
                    pointHighlightFill : '#fff',
                    pointHighlightStroke : 'rgba(60,141,188,1)',
                    data : result.data
                }
            ]
        }
        if (barChart) {
            barChart.destroy();
        }
        var barChartCanvas = $('#barChart').get(0).getContext('2d')
        var barChartData = $.extend(true, {}, areaChartData)

        var barChartOptions = {
            responsive : true,
            maintainAspectRatio : false,
            datasetFill : false
        }

        barChart = new Chart(barChartCanvas, {
            type: 'bar',
            data: barChartData,
            options: barChartOptions
        })
    }

    function print() {
        var pieBase64, barBase64;
        $.ajax({
            url: "{{ route('report.base64url_encode') }}",
            type: "GET",
            data: {
                data: [
                    pieChart.toBase64Image(),
                    barChart.toBase64Image()
                ],
            },
            success: function (res) {
                console.log(res);
                pieBase64 = res[0];
                barBase64 = res[1];

                var date = $("#date").find("input").val()
                if (date) {
                    window.open("{{ route('report.print') }}?date="+date+"&pie="+pieBase64+"&bar="+barBase64, "_blank");
                } else {
                    window.open("{{ route('report.print') }}?pie="+pieBase64+"&bar="+barBase64, "_blank");
                }
            }
        });
    }
</script>

@endsection
