<div>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
    @if (auth()->user()->role == '1')
        <div class="card mb-3" style="max-width: 540px;">
            <div class="row g-0">
                <div class="col-md-4 text-center">
                    <i class="bx bx-user" style="font-size: 128px"></i>
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">Profile Admin</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td>Nama</td>
                                <td>: {{ auth()->user()->name }}</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>: {{ auth()->user()->alamat }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card mb-3" style="max-width: 540px;">
            <div class="row g-0">
                <div class="col-md-4">
                    <i class="bx bx-user" style="font-size: 128px"></i>
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">Profile Pelanggan</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td>Nomor Rumah</td>
                                <td>: {{ auth()->user()->username }}</td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td>: {{ auth()->user()->name }}</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>: {{ auth()->user()->alamat }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
