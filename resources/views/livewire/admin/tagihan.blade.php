<div wire:init='loadPosts'>
    {{-- In work, do what you enjoy. --}}
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-2">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah <i
                            class="bx bx-plus"></i></button>
                </div>
                <div class="col-md-5">
                    <div class="row">
                        <div class="col">
                            <form class="d-flex" role="search">
                                <input class="form-control me-2" wire:model.lazy='filter_tanggal' type="month">
                            </form>
                        </div>
                        <div class="col">
                            <a class="btn btn-secondary"
                                href="{{ route('admin.print_tagihan', [$filter_status, $filter_tanggal]) }}"
                                target="_blank">Print <i class="bx bx-printer"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="dropdown">
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
                <div class="col-md-3">
                    <form class="d-flex" role="search">
                        <input class="form-control me-2" wire:model.lazy='search' type="search"
                            placeholder="Cari Nomor Rumah" aria-label="Search">
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor Rumah</th>
                            <th>Nama Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Lama Tunggakan</th>
                            <th>Total Tunggakan</th>
                            <th class="text-center">Bukti</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (empty($data))
                            <tr>
                                <td colspan="8" class="text-center">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                </td>
                            </tr>
                        @else
                            @forelse ($data as $key => $item)
                                @php
                                    $date = date_create($item->tanggal);
                                    $diff = $date->diff(now());
                                    $yearsInMonths = $diff->format('%r%y') * 12;
                                    $months = $diff->format('%r%m');
                                    $totalMonths = $yearsInMonths + $months;
                                @endphp
                                <tr>
                                    <td>{{ $data->firstItem() + $key }}</td>
                                    <td>{{ $item->user->username }}</td>
                                    <td>{{ $item->user->name }}</td>
                                    <td>{{ date_format($date, 'd M Y') }}</td>
                                    <td>{{ $totalMonths }} Bulan</td>
                                    <td>
                                        Rp.
                                        {{ number_format($item->tarif * ($item->meteran_akhir - $item->meteran_awal) + $item->biaya_admin, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        @if ($item->bukti_transfer != null)
                                            <a href="{{ asset('storage/bukti_transfer/' . $item->bukti_transfer) }}"
                                                target="_blank">Lihat</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->status == true)
                                            <span class="badge bg-success">Lunas</span>
                                        @elseif($item->status == false && $item->bukti_transfer != null)
                                            <button class="btn btn-sm btn-sm btn-info" wire:loading.attr="disabled"
                                                wire:click="triggerValidate('{{ $item->id }}','{{ $item->bukti_transfer }}')"><i
                                                    class="bx bx-check"></i></button>
                                        @else
                                            <button wire:click='edit("{{ $item->id }}")' data-bs-toggle="modal"
                                                data-bs-target="#modalUbah" class="btn btn-sm btn-warning"><i
                                                    class="bx bx-pencil"></i></button>
                                            <button class="btn btn-sm btn-sm btn-danger" wire:loading.attr="disabled"
                                                wire:click="triggerConfirm('{{ $item->id }}')"><i
                                                    class="bx bx-x"></i></button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">
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
    <!-- Modal Tambah-->
    <div wire:ignore.self class="modal fade" id="modalTambah" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="ModalTambah" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form wire:submit.prevent='store'>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal Tambah</h5>
                        <button type="button" wire:click='resetFields' class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Nomor Rumah</label>
                                <input required wire:model.lazy='nomor_rumah' class="form-control"
                                    list="datalistOptions" placeholder="Cari nomor rumah">
                                <datalist id="datalistOptions">
                                    @foreach ($data_rumah as $item)
                                        <option value="{{ $item->username }}"></option>
                                    @endforeach
                                </datalist>
                                @error('nomor_rumah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Nama Pelanggan</label>
                                <input required class="form-control @error('name') is-invalid @enderror"
                                    type="text" readonly value="{{ $nama_pelanggan }}" placeholder="Nama Lengkap"
                                    aria-label="name">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input required class="form-control @error('tanggal') is-invalid @enderror" type="date"
                                wire:model.defer='tanggal' aria-label="tanggal">
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Meteran Awal</label>
                                <input required class="form-control @error('meteran_awal') is-invalid @enderror"
                                    type="number" min="0" wire:model.lazy='meteran_awal' step="any"
                                    wire:change='calculate()' aria-label="meteran_awal">
                                @error('meteran_awal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col">
                                <label class="form-label">Meteran Akhir</label>
                                <input required class="form-control @error('meteran_akhir') is-invalid @enderror"
                                    type="number" min="0" wire:model.lazy='meteran_akhir' step="any"
                                    wire:change='calculate()' aria-label="meteran_akhir">
                                @error('meteran_akhir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col">
                                <label class="form-label">Pemakaian</label>
                                <input required class="form-control" type="number" min="0" readonly
                                    value="{{ $pemakaian }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label class="form-label">Biaya Tagihan</label>
                                <input required class="form-control" type="number" min="0" readonly
                                    value="{{ $tagihan }}">
                            </div>
                            <div class="col">
                                <label class="form-label">Tanggal bayar</label>
                                <input
                                    class="form-control {{ $status == 1 ? 'required' : '' }} @error('tanggal_bayar') is-invalid @enderror"
                                    type="date" wire:model.defer='tanggal_bayar' aria-label="tanggal_bayar">
                                @error('tanggal_bayar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col">
                                <label class="form-label">Status</label>
                                <select required class="form-select @error('status') is-invalid @enderror"
                                    wire:model.lazy='status'>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="1">Lunas</option>
                                    <option value="0">Belum Lunas</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click='resetFields' class="btn btn-secondary"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Ubah-->
    <div wire:ignore.self class="modal fade" id="modalUbah" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="modalUbah" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form wire:submit.prevent='update'>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal Ubah</h5>
                        <button type="button" wire:click='resetFields' class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Nomor Rumah</label>
                                <input required wire:model.defer='nomor_rumah' readonly class="form-control"
                                    list="datalistOptions">
                                @error('nomor_rumah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Nama Pelanggan</label>
                                <input required class="form-control @error('name') is-invalid @enderror"
                                    type="text" readonly value="{{ $nama_pelanggan }}" placeholder="Nama Lengkap"
                                    aria-label="name">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input required class="form-control @error('tanggal') is-invalid @enderror" type="date"
                                wire:model.defer='tanggal' aria-label="tanggal" width="300px">
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Meteran Awal</label>
                                <input required class="form-control @error('meteran_awal') is-invalid @enderror"
                                    type="number" min="0" wire:model.lazy='meteran_awal' step="any"
                                    wire:change='calculate()' aria-label="meteran_awal">
                                @error('meteran_awal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col">
                                <label class="form-label">Meteran Akhir</label>
                                <input required class="form-control @error('meteran_akhir') is-invalid @enderror"
                                    type="number" min="0" wire:model.lazy='meteran_akhir' step="any"
                                    wire:change='calculate()' aria-label="meteran_akhir">
                                @error('meteran_akhir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col">
                                <label class="form-label">Pemakaian</label>
                                <input required class="form-control" type="number" min="0" readonly
                                    value="{{ $pemakaian }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label class="form-label">Biaya Tagihan</label>
                                <input required class="form-control" type="number" min="0" readonly
                                    value="{{ $tagihan }}">
                            </div>
                            <div class="col">
                                <label class="form-label">Tanggal bayar</label>
                                <input {{ $status == 1 ? 'required' : '' }}
                                    class="form-control @error('tanggal_bayar') is-invalid @enderror" type="date"
                                    wire:model.defer='tanggal_bayar' aria-label="tanggal_bayar">
                                @error('tanggal_bayar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col">
                                <label class="form-label">Status</label>
                                <select required class="form-select @error('status') is-invalid @enderror"
                                    wire:model.lazy='status'>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="1">Lunas</option>
                                    <option value="0">Belum Lunas</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click='resetFields' class="btn btn-secondary"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
