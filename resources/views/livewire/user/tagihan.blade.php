<div wire:init='loadPosts'>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
    <div class="card">
        <div class="card-header">
            <a class="btn btn-secondary" href="{{ route('user.print_tagihan', [$filter_status, $filter_tanggal]) }}"
                target="_blank">Print <i class="bx bx-printer"></i></a>
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
                                        @if ($item->status == false && $item->bukti_transfer == null)
                                            <button wire:click='$set("temp_id","{{ $item->id }}")'
                                                data-bs-toggle="modal" data-bs-target="#modalUbah"
                                                class="btn btn-sm btn-primary">Bayar</button>
                                        @endif
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

    <!-- Update-->
    <div wire:ignore.self class="modal fade" id="modalUbah" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="modalUbah" aria-hidden="true">
        <div class="modal-dialog">
            <form wire:submit.prevent='update' enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Upload Bukti Pembayaran</h5>
                        <button type="button" wire:click='resetFields' class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true"
                            x-on:livewire-upload-finish="isUploading = false"
                            x-on:livewire-upload-error="isUploading = false"
                            x-on:livewire-upload-progress="progress = $event.detail.progress">
                            <input required type="file" wire:model.defer='bukti_transfer'>
                            <!-- Progress Bar -->
                            <div x-show="isUploading">
                                <progress max="100" x-bind:value="progress"></progress>
                            </div>
                        </div>
                        <br>
                        <small class="text-muted">PNG | JPG | JPEG</small>
                        @error('bukti_transfer')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click='resetFields' class="btn btn-secondary"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr='hidden'>Upload</button>
                        <button class="btn btn-primary" wire:loading='bukti_transfer'> <span
                                class="spinner-border spinner-border-sm" role="status"
                                aria-hidden="true"></span></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
