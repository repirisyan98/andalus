<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Kas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>

<body>
    <h5 class="text-center">
        Laporan Tagihan {{ $filter_tanggal }} <br> Status :
        {{ $filter_status == '1' ? 'Lunas' : ($filter_status == '0' ? 'Belum Lunas' : 'Semua') }}
    </h5>
    <p>Nama : {{ $user->name }} <br> Nomor Rumah : {{ $user->username }}</p>
    <hr>
    <table class="table table-bordered" style="font-size: 12px">
        <thead>
            <tr>
                <th>NO</th>
                <th>M AWAL</th>
                <th>M AKHIR</th>
                <th>TOTAL</th>
                <th>TARIF</th>
                <th>ADMIN</th>
                <th>TAGIHAN</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @forelse ($data as $item)
                @php
                    
                    $pemakaian = $item->meteran_akhir - $item->meteran_awal;
                @endphp
                <tr>
                    <td>{{ $no }}</td>
                    <td>
                        {{ $item->meteran_awal }}
                    </td>
                    <td>
                        {{ $item->meteran_akhir }}
                    </td>
                    <td>
                        {{ $pemakaian }}
                    </td>
                    <td>
                        Rp. {{ number_format($item->tarif, 0, ',', '.') }}
                    </td>
                    <td>
                        Rp. {{ number_format($item->biaya_admin, 0, ',', '.') }}
                    </td>
                    <td>
                        Rp. {{ number_format($item->tarif * $pemakaian + $item->biaya_admin, 0, ',', '.') }}
                    </td>
                </tr>
                @php
                    $no++;
                @endphp
            @empty
                <tr>
                    <td colspan="7" class="text-center">
                        Tidak Ada Data
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
