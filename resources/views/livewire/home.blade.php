<div wire:init='loadPosts'>
    {{-- Be like water. --}}
    @if (auth()->user()->role == '1')

        <hr>
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
            <div class="col">
                <a href="{{route('admin.pencatatan_kas')}}">
                    <div class="card radius-10 border-start border-0 border-3 border-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Saldo</p>
                                    <h4 class="my-1 text-success">
                                        @if ($readyToLoad == false)
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span>
                                        @else
                                            Rp. {{ number_format($saldo, 0, ',', '.') }}
                                        @endif
                                    </h4>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i
                                        class='bx bxs-dollar-circle'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{route('admin.data_pelanggan')}}">
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
                </a>
            </div>
            <div class="col">
               <a href="{{route('admin.tagihan','1')}}">
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
               </a>
            </div>
            <div class="col">
                <a href="{{route('admin.tagihan','0')}}">
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
                </a>
            </div>
            <div class="col">
                <a href="{{route('admin.tagihan')}}">
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
                </a>
            </div>
        </div>
    @else
    @php
    $lama_tunggakan = 0;
    $total_tunggakan = 0;
@endphp
        @if ($readyToLoad == true)
            @foreach ($tunggakan as $item)
                @php
                    $total_tunggakan += $item->tarif * ($item->meteran_akhir - $item->meteran_awal) + $item->biaya_admin;
                    $lama_tunggakan++;
                @endphp
                {{-- @php
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
                @endif --}}
            @endforeach
        @endif
        <hr>
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
            {{-- <div class="col">
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
            </div> --}}
            <a href="{{route('user.tagihan')}}">
                <div class="card radius-10 border-start border-0 border-3 border-danger">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Tunggakan</p>
                                <h4 class="my-1 text-danger">
                                    @if ($readyToLoad == false)
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                    @else
                                        Rp. {{number_format($total_tunggakan,0,',','.')}}
                                    @endif
                                </h4>
                                    <small>
                                        Lama Tunggakan : {{ $lama_tunggakan }} Bulan
                                    </small>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto"><i
                                    class='bx bxs-x-circle'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endif
</div>
