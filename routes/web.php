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
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('setcookie', function(){
    Session::setId($_GET['id']);
    Session::start();
    return 'Cookie created';
  });
  
Route::resource('ijin-kerja', 'IjinKerjaController')->names([
    'index' => 'indexIjinKerja',
    'create' => 'createIjinKerja',
    'store' => 'simpanIjinKerja'
]);
Route::group(["prefix" => "/ijin-kerja"], function(){
    Route::get('/lihat/{id}', 'IjinKerjaController@showIjinKerjaDiajukan')->name('showIjinKerjaDiajukan');
    Route::post('/reject/{id}', 'IjinKerjaController@rejectIjinKerja')->name('rejectIjinKerja');
    Route::get('/buat-ijin-kerja/{id}', 'IjinKerjaController@createIjinKerja')->name('createIjinKerja'); //buat ijin kerja setelah dokumen sudah lengkap
    Route::post('/kirim-ijin-kerja/{id}', 'IjinKerjaController@sendIjinKerja')->name('sendIjinKerja'); //kirim Ijin Kerja untuk ditandatangan Pemohon
    Route::post('/send-to-kadis/{id}', 'IjinKerjaController@sendToKadis')->name('sendToKadis'); //kirim Ijin Kerja untuk ditandatangan Kadis
    Route::post('/publish-ijin-kerja/{id}', 'IjinKerjaController@publishIjinKerja')->name('publishIjinKerja'); //kirim Ijin Kerja untuk ditandatangan Pemohon
});

Route::get('/index', 'IjinKerjaController@indexPemohon')->name('indexPemohon');
Route::get('/upload', 'IjinKerjaController@uploadDokumenPendukung')->name('uploadDokumenPendukung');
Route::post('/upload', 'IjinKerjaController@uploadingDokumen')->name('uploadingDokumen');
Route::get('/lihat/{id}', 'IjinKerjaController@showIjinKerjaPemohon')->name('showIjinKerjaPemohon');
Route::post('/update-uploaded/{id}', 'IjinKerjaController@updateUploadedDok')->name('updateUploadedDok');
Route::post('/send-to-so/{id}', 'IjinKerjaController@sendToSo')->name('sendToSo'); //kirim Ijin Kerja untuk ditandatangan Safety Officer
Route::get('/download-ijin-kerja/{id}', 'IjinKerjaController@download')->name('downloadIjinKerja');
