<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    if (auth()->user()) {
        return redirect('home');
    }
    return view('auth.login');
    // return view('home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'admin'], function () {
    Route::get('/', 'AdminController@index')->name('admin.home');
    Route::get('/login', 'AuthAdmin\LoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'AuthAdmin\LoginController@login')->name('admin.login.submit');
    Route::get('/ijin-kerja', 'Admin\IjinKerjaAdminController@index')->name('indexIjinKerjaAdmin');
    Route::post('/logout', 'AuthAdmin\LoginController@logout')->name('logoutAdmin');

    Route::group(["prefix" => "/ijin-kerja"], function () {
        Route::get('/lihat/{id}', 'Admin\IjinKerjaAdminController@showIjinKerjaDiajukan')->name('showIjinKerjaDiajukanAdmin');
        Route::post('/reject/{id}', 'Admin\IjinKerjaAdminController@rejectIjinKerja')->name('rejectIjinKerja');
        Route::get('/buat-ijin-kerja/{id}', 'Admin\IjinKerjaAdminController@createIjinKerja')->name('createIjinKerja'); //buat ijin kerja setelah dokumen sudah lengkap
        Route::post('/kirim-ijin-kerja/{id}', 'Admin\IjinKerjaAdminController@sendIjinKerja')->name('sendIjinKerja'); //kirim Ijin Kerja untuk ditandatangan Pemohon
        Route::post('/send-to-kadis/{id}', 'Admin\IjinKerjaAdminController@sendToKadis')->name('sendToKadis'); //kirim Ijin Kerja untuk ditandatangan Kadis
        Route::get('/download-ijin-kerja/{id}', 'Admin\IjinKerjaAdminController@download')->name('downloadIjinKerjaAdmin');
    });

    Route::group(["prefix" => "/laporan"], function () {
        Route::get('', 'Admin\IjinKerjaAdminController@indexLaporan')->name('indexLaporan');
        Route::get('/list-data', 'Admin\IjinKerjaAdminController@listDataLaporan')->name('listDataLaporan');
    });
});

Route::group(['auth' => ['web']], function () {
    Route::get('autologin', function () {
        $user = $_GET['id'];
        // dd($user);
        Auth::login($user);
        // Auth::loginUsingId($user, true);
        return redirect()->intended('/kbs');
    });
});

Route::get('/index', 'IjinKerjaController@indexPemohon')->name('indexPemohon');
Route::get('/upload', 'IjinKerjaController@uploadDokumenPendukung')->name('uploadDokumenPendukung');
Route::get('/get-documents', 'IjinKerjaController@getDocuments')->name('getDocuments');
Route::post('/upload', 'IjinKerjaController@uploadingDokumen')->name('uploadingDokumen');
Route::get('/lihat/{id}', 'IjinKerjaController@showIjinKerjaPemohon')->name('showIjinKerjaPemohon');
Route::post('/update-uploaded/{id}', 'IjinKerjaController@updateUploadedDok')->name('updateUploadedDok');
Route::post('/send-to-so/{id}', 'IjinKerjaController@sendToSo')->name('sendToSo'); //kirim Ijin Kerja untuk ditandatangan Safety Officer
Route::get('/download-ijin-kerja/{id}', 'IjinKerjaController@download')->name('downloadIjinKerja');
Route::get('/download-contoh-dokumen', 'IjinKerjaController@downloadContohDokumen')->name('downloadContohDokumen');
Route::get('/safety-induction', 'IjinKerjaController@safetyInduction')->name('safetyInduction');
Route::post('/logout', 'Auth\LoginController@logoutUser')->name('logoutUser');

// Ijin Masuk Controllers untuk vendor
Route::group(['prefix' => 'ijinmasuk'], function () {
    Route::get('/', 'IjinKerjaController@indexIjinMasuk')->name('indexIjinMasuk');
    Route::get('/create', 'IjinKerjaController@createIjinMasuk')->name('createIjinMasuk');
    Route::post('/store', 'IjinKerjaController@storeIjinMasuk')->name('storeIjinMasuk');
    Route::get('/edit', 'IjinKerjaController@editIjinMasuk')->name('editIjinMasuk');
    Route::get('/view/{id}', 'IjinKerjaController@viewIjinMasuk')->name('viewIjinMasuk');
    Route::get('/submit/{id}', 'IjinKerjaController@submitIjinMasuk')->name('submitIjinMasuk');
    Route::post('/update', 'IjinKerjaController@updateIjinMasuk')->name('updateIjinMasuk');
    Route::get('/delete', 'IjinKerjaController@deleteIjinMasuk')->name('deleteIjinMasuk');
    Route::get('/get-user-docs/{id}', 'IjinKerjaController@getUserDocs')->name('getUserDocs');
});

Route::group(['prefix' => 'kbs'], function () {
    Route::get('/', 'KbsController@index')->name('kbs.home');
    Route::get('/login', 'AuthKbs\LoginController@showLoginForm')->name('kbs.login');
    Route::post('/login', 'AuthKbs\LoginController@login')->name('kbs.login.submit');
    Route::get('/ijin-kerja', 'Kbs\IjinKerjaAdminController@index')->name('indexIjinKerjaKbs');
    Route::get('/lihat/{id}', 'Kbs\IjinKerjaAdminController@showIjinKerjaDiajukan')->name('showIjinKerjaDiajukanKbs');
    Route::post('/publish-ijin-kerja/{id}', 'Kbs\IjinKerjaAdminController@publishIjinKerja')->name('publishIjinKerjaKbs'); //Kadis tandatangan dan publish Ijin Kerja untuk Pemohon
    Route::post('/reject/{id}', 'Kbs\IjinKerjaAdminController@rejectIjinKerja')->name('rejectIjinKerjaKbs');

    Route::get('/download-ijin-kerja/{id}', 'Kbs\IjinKerjaAdminController@download')->name('downloadIjinKerjaKbs');


    //Ijin masuk Controllers untuk KADISKAM KBS dan Admin KBS (Rendy)
    Route::group(["prefix" => "/ijin-masuk"], function () {
        Route::get('/', 'Kbs\IjinKerjaAdminController@indexIjinMasukKbs')->name('indexIjinMasukKbs');
        Route::get('/index-call-center', 'Kbs\IjinKerjaAdminController@indexIjinMasukCC')->name('indexIjinMasukCC');

        Route::get('/view/{id}', 'Kbs\IjinKerjaAdminController@viewIjinMasukKbs')->name('viewIjinMasukKbs');
        Route::get('/view-call-center/{id}', 'Kbs\IjinKerjaAdminController@viewIjinMasukCC')->name('viewIjinMasukCC');

        Route::post('/update', 'Kbs\IjinKerjaAdminController@updateIjinMasukKbs')->name('updateIjinMasukKbs');
        Route::post('/update-call-center', 'Kbs\IjinKerjaAdminController@updateIjinMasukCC')->name('updateIjinMasukCC');
    });

    Route::group(["prefix" => "/master-data"], function () {
        Route::group(["prefix" => "/vendor"], function () {
            Route::get('/', 'Kbs\IjinKerjaAdminController@indexVendor')->name('indexVendor');
            Route::get('/edit', 'Kbs\IjinKerjaAdminController@editVendor')->name('editVendor');
            Route::get('/update', 'Kbs\IjinKerjaAdminController@updateVendor')->name('updateVendor');
        });

        Route::group(["prefix" => "/tipe-vendor"], function () {
            Route::get('/', 'Kbs\IjinKerjaAdminController@indexTipeVendor')->name('indexTipeVendor');
            Route::get('/create', 'Kbs\IjinKerjaAdminController@createTipeVendor')->name('createTipeVendor');
            Route::get('/edit', 'Kbs\IjinKerjaAdminController@editTipeVendor')->name('editTipeVendor');
            Route::get('/update', 'Kbs\IjinKerjaAdminController@updateTipeVendor')->name('updateTipeVendor');
            Route::get('/delete', 'Kbs\IjinKerjaAdminController@deleteTipeVendor')->name('deleteTipeVendor');
        });

        Route::group(["prefix" => "/tipe-dokumen"], function () {
            Route::get('/', 'Kbs\IjinKerjaAdminController@indexTipeDokumen')->name('indexTipeDokumen');
            Route::get('/create', 'Kbs\IjinKerjaAdminController@createTipeDokumen')->name('createTipeDokumen');
            Route::get('/edit', 'Kbs\IjinKerjaAdminController@editTipeDokumen')->name('editTipeDokumen');
            Route::get('/update', 'Kbs\IjinKerjaAdminController@updateTipeDokumen')->name('updateTipeDokumen');
            Route::get('/delete', 'Kbs\IjinKerjaAdminController@deleteTipeDokumen')->name('deleteTipeDokumen');
        });
    });

    Route::post('/logout', 'AuthKbs\LoginController@logout')->name('logoutKbs');
});
