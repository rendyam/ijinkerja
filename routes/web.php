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
    if(auth()->user()){
        return redirect('home');
    }
    return view('auth.login');
    // return view('home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'admin'], function(){
    Route::get('/', 'AdminController@index')->name('admin.home');
    Route::get('/login', 'AuthAdmin\LoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'AuthAdmin\LoginController@login')->name('admin.login.submit');
    Route::get('/ijin-kerja', 'Admin\IjinKerjaAdminController@index')->name('indexIjinKerjaAdmin');
    Route::get('/logout', 'AuthAdmin\LoginController@logoutAdmin')->name('logout');

    Route::group(["prefix" => "/ijin-kerja"], function(){
        Route::get('/lihat/{id}', 'Admin\IjinKerjaAdminController@showIjinKerjaDiajukan')->name('showIjinKerjaDiajukanAdmin');
        Route::post('/reject/{id}', 'Admin\IjinKerjaAdminController@rejectIjinKerja')->name('rejectIjinKerja');
        Route::get('/buat-ijin-kerja/{id}', 'Admin\IjinKerjaAdminController@createIjinKerja')->name('createIjinKerja'); //buat ijin kerja setelah dokumen sudah lengkap
        Route::post('/kirim-ijin-kerja/{id}', 'Admin\IjinKerjaAdminController@sendIjinKerja')->name('sendIjinKerja'); //kirim Ijin Kerja untuk ditandatangan Pemohon
        Route::post('/send-to-kadis/{id}', 'Admin\IjinKerjaAdminController@sendToKadis')->name('sendToKadis'); //kirim Ijin Kerja untuk ditandatangan Kadis
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
Route::post('/upload', 'IjinKerjaController@uploadingDokumen')->name('uploadingDokumen');
Route::get('/lihat/{id}', 'IjinKerjaController@showIjinKerjaPemohon')->name('showIjinKerjaPemohon');
Route::post('/update-uploaded/{id}', 'IjinKerjaController@updateUploadedDok')->name('updateUploadedDok');
Route::post('/send-to-so/{id}', 'IjinKerjaController@sendToSo')->name('sendToSo'); //kirim Ijin Kerja untuk ditandatangan Safety Officer
Route::get('/download-ijin-kerja/{id}', 'IjinKerjaController@download')->name('downloadIjinKerja');
Route::get('/logout', 'Auth\LoginController@logoutUser')->name('logout');

Route::group(['prefix' => 'kbs'], function(){
    Route::get('/', 'KbsController@index')->name('kbs.home');
    Route::get('/login', 'AuthKbs\LoginController@showLoginForm')->name('kbs.login');
    Route::post('/login', 'AuthKbs\LoginController@login')->name('kbs.login.submit');
    Route::get('/ijin-kerja', 'Kbs\IjinKerjaAdminController@index')->name('indexIjinKerjaKbs');
    Route::get('/lihat/{id}', 'Kbs\IjinKerjaAdminController@showIjinKerjaDiajukan')->name('showIjinKerjaDiajukanKbs');
    Route::post('/publish-ijin-kerja/{id}', 'Kbs\IjinKerjaAdminController@publishIjinKerja')->name('publishIjinKerjaKbs'); //Kadis tandatangan dan publish Ijin Kerja untuk Pemohon
});