<div>
    {{-- The best athlete wants his opponent at his best. --}}
    <div class="card">
        <div class="card-body">
            <div class="form-body">
                <form wire:submit.prevent="change_password">
                    <div class="row g-3">
                        <div class=" col-12">
                            <label for="inputChoosePassword" class="form-label">Password Baru</label>
                            <div class="input-group" id="show_hide_password">
                                <input id="password" placeholder="Password Baru" type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    wire:model.defer='password' required>
                                <a href="javascript:;" class="input-group-text bg-transparent"><i
                                        class='bx bx-hide'></i></a>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="inputChoosePassword" class="form-label">Konfirmasi
                                Password</label>
                            <div class="input-group" id="show_hide_password2">
                                <input id="password" placeholder="Konfirmasi Password" type="password"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    wire:model.defer="password_confirmation" required>
                                <a href="javascript:;" class="input-group-text bg-transparent"><i
                                        class='bx bx-hide'></i></a>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-inline">
                                <button type="submit" class="btn btn-primary"><i class="bx bx-edit"></i> Ubah</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
