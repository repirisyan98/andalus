<div wire:init='loadPosts'>
    {{-- Be like water. --}}
    @if (auth()->user()->role == '1')
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Jumlah Pelanggan</p>
                                <h4 class="my-1 text-info">
                                    @if ($readyToLoad == false)
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                    @else
                                        {{ $user }}
                                    @endif
                                </h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto"><i
                                    class='bx bxs-group'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Tagihan Lunas</p>
                                <h4 class="my-1 text-success">
                                    @if ($readyToLoad == false)
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                    @else
                                        {{ $tagihan->where('status', true)->count() }}
                                    @endif
                                </h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i
                                    class='bx bxs-check-circle'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-danger">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Tagihan Belum Lunas</p>
                                <h4 class="my-1 text-danger">
                                    @if ($readyToLoad == false)
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                    @else
                                        {{ $tagihan->where('status', false)->count() }}
                                    @endif
                                </h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto"><i
                                    class='bx bxs-x-circle'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Validasi Tagihan</p>
                                <h4 class="my-1 text-warning">
                                    @if ($readyToLoad == false)
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                    @else
                                        {{ $tagihan->where('status', false)->where('bukti_transfer', '!=', null)->count() }}
                                    @endif
                                </h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"><i
                                    class='bx bxs-archive'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        @if ($readyToLoad == true)
            @forelse ($tunggakan as $item)
                @php
                    $date = date_create($item->tanggal);
                    $diff = $date->diff(now());
                    $yearsInMonths = $diff->format('%r%y') * 12;
                    $months = $diff->format('%r%m');
                    $totalMonths = $yearsInMonths + $months;
                @endphp
                @if ($totalMonths > 0)
                    <div class="alert alert-danger" role="alert">
                        Kamu memiliki tunggakan bulan <b>{{ date_format($date, 'M') }} </b> dengan total <b> Rp.
                            {{ number_format($item->tarif * ($item->meteran_akhir - $item->meteran_awal) + $item->biaya_admin, 0, ',', '.') }}</b>
                        Harap segera lakukan pembayaran
                    </div>
                @endif
            @empty
                <div class="alert alert-info" role="alert">
                    Tidak ada tunggakan
                </div>
            @endforelse
        @endif
        <hr>
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Jumlah Tagihan</p>
                                <h4 class="my-1 text-info">
                                    @if ($readyToLoad == false)
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                    @else
                                        {{ $tagihan->count() }}
                                    @endif
                                </h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto"><i
                                    class='bx bxs-archive'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card radius-10 border-start border-0 border-3 border-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Tagihan Belum Lunas</p>
                            <h4 class="my-1 text-danger">
                                @if ($readyToLoad == false)
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                @else
                                    {{ $tagihan->where('status', false)->count() }}
                                @endif
                            </h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto"><i
                                class='bx bxs-x-circle'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
