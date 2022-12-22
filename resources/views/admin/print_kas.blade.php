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
        Laporan Arus Kas {{ $periode }}
    </h5>
    <hr>
    <table class="table table-bordered align-middle mb-0">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Saldo Akhir</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                @php
                    $no = 1;
                    $date = date_create($item->tanggal);
                @endphp
                <tr>
                    <td>{{ $no }}</td>
                    <td>{{ date_format($date, 'd M Y') }}</td>
                    <td>
                        {{ $item->jenis == '1' ? 'Debit' : 'Kredit' }}
                    </td>
                    <td>
                        Rp. {{ number_format($item->jumlah, 0, ',', '.') }}
                    </td>
                    <td>
                        Rp. {{ number_format($item->saldo_akhir, 0, ',', '.') }}
                    </td>
                    <td>{{ $item->keterangan == null ? 'Tidak ada keterangan' : $item->keterangan }}
                    </td>
                </tr>
                @php
                    $no++;
                @endphp
            @empty
                <tr>
                    <td colspan="6" class="text-center">
                        Tidak Ada Data
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
