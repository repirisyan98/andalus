<!--sidebar wrapper -->
<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <h1>
                <i class="bx bx-water"></i>
                </h3>
        </div>
        <div>
            <h4 class="logo-text">CLUSTER ANDALUS</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li> <a href="{{ route('home') }}">
                <div class="parent-icon"><i class='bx bx-home-circle'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>
        @can('admin')
            <li> <a href="{{ route('admin.biaya_admin') }}">
                    <div class="parent-icon"><i class='bx bx-dollar'></i>
                    </div>
                    <div class="menu-title">Biaya Admin</div>
                </a>
            </li>
            <li> <a href="{{ route('admin.data_pelanggan') }}">
                    <div class="parent-icon"><i class='bx bx-group'></i>
                    </div>
                    <div class="menu-title">Data Pelanggan</div>
                </a>
            </li>
            <li> <a href="{{ route('admin.tagihan') }}">
                    <div class="parent-icon"><i class='bx bx-archive'></i>
                    </div>
                    <div class="menu-title">Tagihan</div>
                </a>
            </li>
            <li> <a href="{{ route('admin.pencatatan_kas') }}">
                    <div class="parent-icon"><i class='bx bx-book'></i>
                    </div>
                    <div class="menu-title">Pencatatan Kas</div>
                </a>
            </li>
        @endcan
        @can('user')
            <li> <a href="{{ route('user.tagihan') }}">
                    <div class="parent-icon"><i class='bx bx-archive'></i>
                    </div>
                    <div class="menu-title">Tagihan</div>
                </a>
            </li>
        @endcan
    </ul>
    <!--end navigation-->
</div>
<!--end sidebar wrapper -->
