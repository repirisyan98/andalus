<?php

use App\Models\Kas;
use App\Models\TagihanPelanggan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['register' => false, 'password.reset' => false,]);

Route::redirect('/', 'login');

Route::middleware('auth')->group(function () {

    Route::get('home', function () {
        return view('home');
    })->name('home');

    Route::get('profile', function () {
        return view('profile');
    })->name('profile');
    Route::get('pengaturan', function () {
        return view('pengaturan');
    })->name('pengaturan');

    Route::group(['prefix' => 'admin', 'middleware' => 'can:admin'], function () {
        Route::get('biaya-admin', function () {
            return view('admin.biaya_admin');
        })->name('admin.biaya_admin');

        Route::get('data-pelanggan', function () {
            return view('admin.data_pelanggan');
        })->name('admin.data_pelanggan');

        Route::get('{id}/detail-tagihan-pelanggan', function ($id) {
            return view('admin.detail_tagihan_pelanggan', ['id' => $id]);
        })->name('admin.detail_tagihan_pelanggan');

        Route::get('pencatatan-kas', function () {
            return view('admin.pencatatan_kas');
        })->name('admin.pencatatan_kas');

        Route::get('print-kas/{periode?}', function ($periode = null) {
            if ($periode != null) {
                $periode = date_create($periode);
            }
            $data = Kas::when($periode != null, function ($query) use ($periode) {
                return $query->whereMonth('tanggal', date_format($periode, 'm'))->whereYear('tanggal', date_format($periode, 'Y'));
            })->get();
            $pdf = Pdf::loadView('admin.print_kas', ['data' => $data, 'periode' => $periode != null ? date_format($periode, 'M Y') : '']);
            return $pdf->stream();
        })->name('admin.print_kas');

        Route::get('print-tagihan/{filter_status}/{filter_tanggal?}', function ($filter_status, $filter_tanggal = null) {
            if ($filter_tanggal != null) {
                $filter_tanggal = date_create($filter_tanggal);
            }
            $data = TagihanPelanggan::with('user')->when($filter_status != '2', function ($query) use ($filter_status) {
                return $query->where('status', $filter_status);
            })->when($filter_tanggal != null, function ($query) use ($filter_tanggal) {
                return $query->whereMonth('tanggal', date_format($filter_tanggal, 'm'))->whereYear('tanggal', date_format($filter_tanggal, 'Y'));
            })->get();
            $pdf = Pdf::loadView('admin.print_tagihan', ['data' => $data, 'filter_tanggal' => $filter_tanggal != null ? date_format($filter_tanggal, 'M Y') : '', 'filter_status' => $filter_status]);
            return $pdf->setPaper('A4', 'landscape')->stream();
        })->name('admin.print_tagihan');

        Route::get('detail-print-tagihan/{filter_status}/{user_id}/{filter_tanggal?}', function ($filter_status, $user_id, $filter_tanggal = null) {
            if ($filter_tanggal != null) {
                $filter_tanggal = date_create($filter_tanggal);
            }
            $data = TagihanPelanggan::with('user')->when($filter_status != '2', function ($query) use ($filter_status) {
                return $query->where('status', $filter_status);
            })->when($filter_tanggal != null, function ($query) use ($filter_tanggal) {
                return $query->whereMonth('tanggal', date_format($filter_tanggal, 'm'))->whereYear('tanggal', date_format($filter_tanggal, 'Y'));
            })->where('user_id', $user_id)->get();

            $user = User::select('username', 'name')->where('id', $user_id)->first();
            $pdf = Pdf::loadView('admin.detail_print_tagihan', ['data' => $data, 'filter_tanggal' => $filter_tanggal != null ? date_format($filter_tanggal, 'M Y') : '', 'filter_status' => $filter_status, 'user' => $user]);
            return $pdf->setPaper('A4', 'landscape')->stream();
        })->name('admin.detail_print_tagihan');

        Route::get('tagihan', function () {
            return view('admin.tagihan');
        })->name('admin.tagihan');
    });

    Route::group(['prefix' => 'user', 'middleware' => 'can:user'], function () {
        Route::get('tagihan', function () {
            return view('user.tagihan');
        })->name('user.tagihan');

        Route::get('print-tagihan/{filter_status}/{filter_tanggal?}', function ($filter_status, $filter_tanggal = null) {
            if ($filter_tanggal != null) {
                $filter_tanggal = date_create($filter_tanggal);
            }
            $data = TagihanPelanggan::when($filter_status != '2', function ($query) use ($filter_status) {
                return $query->where('status', $filter_status);
            })->when($filter_tanggal != null, function ($query) use ($filter_tanggal) {
                return $query->whereMonth('tanggal', date_format($filter_tanggal, 'm'))->whereYear('tanggal', date_format($filter_tanggal, 'Y'));
            })->where('user_id', auth()->user()->id)->get();
            $pdf = Pdf::loadView('user.print_tagihan', ['data' => $data, 'filter_tanggal' => $filter_tanggal != null ? date_format($filter_tanggal, 'M Y') : '', 'filter_status' => $filter_status]);
            return $pdf->setPaper('A4', 'landscape')->stream();
        })->name('user.print_tagihan');
    });
});