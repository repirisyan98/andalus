<div wire:init='loadPosts'>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <a href="{{ route('admin.data_pelanggan') }}"><i class="bx bx-arrow-to-left"></i> Kembali</a>
                </div>
                <div class="col">
                    <div class="row">
                        <div class="col">
                            <form class="d-flex" role="search">
                                <input class="form-control me-2" wire:model.lazy='filter_tanggal' type="month"
                                    style="width: 200px">
                            </form>
                        </div>
                        <div class="col">
                            <a class="btn btn-secondary"
                                href="{{ route('admin.detail_print_tagihan', [$filter_status, $user_id, $filter_tanggal]) }}"
                                target="_blank">Print <i class="bx bx-printer"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="dropdown float-end">
                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bx bx-filter"></i>
                            {{ $filter_status == '2' ? 'Semua' : ($filter_status == '1' ? 'Lunas' : 'Belum Lunas') }}
                        </a>

                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <li><a class="dropdown-item" href="#" wire:click='filterStatus("2")'>Semua</a>
                            </li>
                            <li><a class="dropdown-item" href="#" wire:click='filterStatus("1")'>Lunas</a>
                            </li>
                            <li><a class="dropdown-item" href="#" wire:click='filterStatus("0")'>Belum
                                    Lunas</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Periode</th>
                            <th>Meteran Awal</th>
                            <th>Meteran Akhir</th>
                            <th>Pemakaian</th>
                            <th>Tarif</th>
                            <th>Biaya Admin</th>
                            <th>Total Tagihan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (empty($data))
                            <tr>
                                <td colspan="9" class="text-center">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                </td>
                            </tr>
                        @else
                            @forelse ($data as $key => $item)
                                @php
                                    $date = date_create($item->tanggal);
                                    $pemakaian = $item->meteran_akhir - $item->meteran_awal;
                                @endphp
                                <tr>
                                    <td>{{ $data->firstItem() + $key }}</td>
                                    <td>{{ date_format($date, 'M Y') }}</td>
                                    <td>{{ $item->meteran_awal }}</td>
                                    <td>{{ $item->meteran_akhir }}</td>
                                    <td>{{ $pemakaian }}</td>
                                    <td>Rp. {{ number_format($item->tarif, 0, ',', '.') }}</td>
                                    <td>Rp. {{ number_format($item->biaya_admin, 0, ',', '.') }}</td>
                                    <td>Rp.
                                        {{ number_format($item->tarif * $pemakaian + $item->biaya_admin, 0, ',', '.') }}
                                    </td>
                                    <td><span
                                            class="badge bg-{{ $item->status == '1' ? 'success' : 'danger' }}">{{ $item->status == '1' ? 'Lunas' : 'Belum Lunas' }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">
                                        Tidak Ada Data
                                    </td>
                                </tr>
                            @endforelse
                        @endif
                    </tbody>
                </table>
            </div>
            @if ($readyToLoad == true)
                <div>
                    {{ $data->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
