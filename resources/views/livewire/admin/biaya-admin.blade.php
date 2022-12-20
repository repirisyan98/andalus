<div wire:init='loadPosts'>
    {{-- In work, do what you enjoy. --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Biaya Admin</th>
                            <th>Tarif</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (empty($data))
                            <tr>
                                <td colspan="3" class="text-center">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                </td>
                            </tr>
                        @else
                            @forelse ($data as $item)
                                <tr>
                                    <td>Rp. {{ number_format($item->biaya_admin, 0, ',', '.') }}</td>
                                    <td>Rp. {{ number_format($item->tarif, 0, ',', '.') }}</td>
                                    <td>
                                        <button wire:click='edit("{{ $item->id }}")' data-bs-toggle="modal"
                                            data-bs-target="#modalUbah" class="btn btn-sm btn-warning"><i
                                                class="bx bx-pencil"></i></button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">
                                        Tidak Ada Data
                                    </td>
                                </tr>
                            @endforelse
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Ubah-->
    <div wire:ignore.self class="modal fade" id="modalUbah" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="modalUbah" aria-hidden="true">
        <div class="modal-dialog">
            <form wire:submit.prevent='update'>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal Ubah</h5>
                        <button type="button" wire:click='resetFields' class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Biaya Admin</label>
                            <input required class="form-control @error('biaya_admin') is-invalid @enderror"
                                type="number" wire:model.defer='biaya_admin' placeholder="Biaya Admin" min="0">
                            @error('biaya_admin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Tarif</label>
                            <input required class="form-control @error('tarif') is-invalid @enderror" type="number"
                                wire:model.defer='tarif' placeholder="Biaya Admin" min="0">
                            @error('tarif')
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
</div>
