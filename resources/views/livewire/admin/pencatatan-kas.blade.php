<div wire:init='loadPosts'>
    {{-- In work, do what you enjoy. --}}
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah <i
                            class="bx bx-plus"></i></button>
                </div>
                <div class="col">
                    <div class="row">
                        <div class="col">
                            <form class="d-flex" role="search">
                                <input class="form-control me-2" wire:model.lazy='filter_tanggal' type="month">
                            </form>
                        </div>
                        <div class="col">
                            <a class="btn btn-secondary" href="{{ route('admin.print_kas', $filter_tanggal) }}"
                                target="_blank">Print <i class="bx bx-printer"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="dropdown float-end">
                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bx bx-filter"></i>
                            {{ $filter_status == '3' ? 'Semua' : ($filter_status == '1' ? 'Debit' : 'Kredit') }}
                        </a>

                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <li><a class="dropdown-item" href="#" wire:click='filterStatus("3")'>Semua</a>
                            </li>
                            <li><a class="dropdown-item" href="#" wire:click='filterStatus("1")'>Debit</a>
                            </li>
                            <li><a class="dropdown-item" href="#" wire:click='filterStatus("2")'>Kredit</a></li>
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
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Saldo Akhir</th>
                            <th>Keterangan</th>
                            {{-- <th>Aksi</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @if (empty($data))
                            <tr>
                                <td colspan="6" class="text-center">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                </td>
                            </tr>
                        @else
                            @forelse ($data as $key => $item)
                                @php
                                    $date = date_create($item->tanggal);
                                @endphp
                                <tr>
                                    <td>{{ $data->firstItem() + $key }}</td>
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
                                    {{-- <td>
                                        <button wire:click='edit("{{ $item->id }}")' data-bs-toggle="modal"
                                            data-bs-target="#modalUbah" class="btn btn-sm btn-warning"><i
                                                class="bx bx-pencil"></i></button>
                                        <button class="btn btn-sm btn-danger" wire:loading.attr="disabled"
                                            wire:click="triggerConfirm('{{ $item->id }}')"><i
                                                class="bx bx-x"></i></button>
                                    </td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">
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
        <div class="modal-dialog">
            <form wire:submit.prevent='store'>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Catat Kas</h5>
                        <button type="button" wire:click='resetFields' class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <input required class="form-control @error('tanggal') is-invalid @enderror" type="date"
                                wire:model.defer='tanggal'>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <select required class="form-select @error('jenis') is-invalid @enderror"
                                wire:model.defer='jenis'>
                                <option value="">-- Pilih Jenis Kas --</option>
                                <option value="1">Debit</option>
                                <option value="2">Kredit</option>
                            </select>
                            @error('jenis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <input required class="form-control @error('jumlah') is-invalid @enderror" type="number"
                                wire:model.defer='jumlah' placeholder="Jumlah" min="0">
                            @error('jumlah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" wire:model.defer='keterangan'
                                placeholder="Keterangan" cols="30" rows="5"></textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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

    {{-- <!-- Update-->
    <div wire:ignore.self class="modal fade" id="modalUbah" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="modalUbah" aria-hidden="true">
        <div class="modal-dialog">
            <form wire:submit.prevent='update'>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update</h5>
                        <button type="button" wire:click='resetFields' class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <input required class="form-control @error('tanggal') is-invalid @enderror" type="date"
                                wire:model.defer='tanggal'>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <select required class="form-select @error('jenis') is-invalid @enderror"
                                wire:model.defer='jenis'>
                                <option value="">-- Pilih Jenis Kas --</option>
                                <option value="1">Debet</option>
                                <option value="2">Kredit</option>
                                <option value="3">Saldo</option>
                            </select>
                            @error('jenis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <input required class="form-control @error('jumlah') is-invalid @enderror" type="number"
                                wire:model.defer='jumlah' placeholder="Jumlah" min="0">
                            @error('jumlah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" wire:model.defer='keterangan'
                                placeholder="Keterangan" cols="30" rows="5"></textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
    </div> --}}
</div>
