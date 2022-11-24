<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
</head>
<body>
    <header style="text-align: center;">
        @php
            // $url = route('view.settings')."/cop";
            $url = base64_encode(file_get_contents(public_path('/storage/settings/cop.png')));
        @endphp
        <img src="data:image/png;base64,{{ $url }}" class="header-img">
    </header>
    <p style="text-decoration: underline; text-align: center; font-weight: bold;">
        Rekap Bulanan Pelayanan Pemerintahan Desa Sentul, Kec. Sumebersuko, Kab. Lumajang
    </p>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Pelayanan</th>
                <th scope="col">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($service as $item)
                <tr>
                    <td>{{ $item['service'] }}</td>
                    <td>{{ $item['amount'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <p style="text-decoration: underline; text-align: center; font-weight: bold;">Tabel Grafik</p>
    <br>
    <!-- PIE CHART -->
    <div class="card-body" style="text-align: center;">
        <img src="{{ $pie_chart }}" style="width: 100%" />
    </div>
    <br>
    <!-- BAR CHART -->
    <div class="card-body" style="text-align: center;">
        <img src="{{ $bar_chart }}" style="width: 100%" />
    </div>
    <br>
    <p style="text-decoration: underline; text-align: center; font-weight: bold;">Riwayat Pelayanan Bulan ini</p>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Pelayanan</th>
                <th scope="col">Nama Warga</th>
                <th scope="col">NIK</th>
                <th scope="col">Dilayani</th>
                <th scope="col">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($service_history as $item)
                <tr>
                    <td>{{ $item['mstrServiceType']->name }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['nik'] }}</td>
                    <td>{{ $item['serviced_by'] }}</td>
                    <td>{{ $item['notes'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

<style>
    @page {
        margin: 0cm 0cm;
    }

    body {
        /* margin-top: 2cm; */
        margin-top: 0cm;
        margin-left: 2cm;
        margin-right: 2cm;
        margin-bottom: 2cm;
    }

    .header-img {
        /* position: fixed; */
        top: 0cm;
        left: 0cm;
        right: 0cm;
        height: 100px;
        /* top: 0cm;
        left: 0cm;
        right: 0cm;
        height: 2cm; */

        /** Extra personal styles **/
        /* background-color: #03a9f4; */
        /* background-image: url("{{ asset('storage/settings/cop.png') }}"); */
        /* background-image: url("https://1.bp.blogspot.com/-UFw0zqac1Go/WFKwYlAcZHI/AAAAAAAACu8/EEoo1tyhZKogPJpDwa1AtbV1xWGUD3YGACLcB/s1600/KOP%2BSURAT.png"); */
        /* background-image: url("{{ $cop }}"); */
        color: white;
        text-align: center;
        /* line-height: 1.5cm; */
    }

    .table {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    .table td, .table th {
        border: 1px solid #ddd;
        padding: 8px;
        font-size: 14px;
    }

    .table tr:nth-child(even){background-color: #f2f2f2;}

    .table tr:hover {background-color: #ddd;}

    .table th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #8EAADB;
        color: black;
        font-size: 15px;
    }
</style>

<script>
    // $("header").attr("background-image", "url('{{ $cop }}')");
    // $("header").attr("src", "data:image/png;base64, "+base64Image);
</script>

</html>
