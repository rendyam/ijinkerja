@extends('home')

@section('menu_name') Safety Induction dan peraturan di PT Krakatau Bandar Samudera @endsection
@section('title') Safety Induction dan peraturan di PT Krakatau Bandar Samudera @endsection

@push('list-ijin-kerja-css')
    <style>
        .text-block a {
            text-decoration: none;
            color: #fff !important;
            border-bottom: solid 1px rgba(0,130,198,.3);
        }
    </style>
@endpush

@section('content')
    <div class="page-content">
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="box-typical box-typical-padding documentation">
                    <header class="documentation-header">
                        <h2>@yield('menu_name')</h2>
                        <!-- <p class="lead color-blue-grey">Harap diperhatikan bagi seluruh vendor yang akan bekerja di area Krakatau International Port</p> -->
                    </header>

                    <div class="text-block text-block-typical">
                        <h3>Perhatian </h3>
                        <p>Sebelum meng-upload dokumen, harap baca dan pahami Safety Induction di PT Krakatau Bandar Samudera di bawah ini.</p>
                        <p>Pastikan Anda memahami Safety Induction dan centang pada bagian bawah untuk dapat meng-upload dokumen</p>
                        <table class="table table-hover">
                            <tr><td>1.</td> <td>Wajib menggunakan APD seperti Helm safety dan Sepatu safety pada Restricted Area (Area Terbatas) seperti ; Area Dermaga PT KBS, Gudang terbuka dan gudang tertutup PT KBS, Workshop PT KBS, Area kerja Project PT KBS</td></tr>
                            <tr><td>2.</td> <td>Pekerjaan terkait Limbah B3 wajib melampirkan data di foto dan / atau Scan (Dokumen Asli) pengelolaan Limbah B3 (Penghasil / Pengangkut / Pemanfaat):
                                <br>- Memakai Masker
                                <br>- Menjaga Jarak
                                <br>- Mencuci tangan dengan air dan sabun atau handsanitizer
                                <br>- Menghindari kerumunan
                                <br>- Mengurangi Mobilitas
                            </td></tr>
                            <tr><td>3.</td> <td>Patuhi batas kecepatan di area PT KBS,
                                <br>- Batas kecepatan truck dan alat berat maksimal 20 km/jam
                                <br>- Batas kecepatan roda 4 dan roda 2 maksimal 40 km/jam
                            </td></tr>
                            <tr><td>4.</td> <td>Seluruh kendaraan wajib menyalakan lampu utama di area PT KBS</td></tr>
                            <tr><td>5.</td> <td>Perhatikan Rambu-rambu, dan selalu perhatikan sekeliling</td></tr>
                            <tr><td>6.</td> <td>Kendaraan roda 2 dilarang untuk masuk Restricted Area (Area Terbatas) seperti ; Area Dermaga PT KBS, Gudang terbuka dan gudang tertutup PT KBS, Workshop PT KBS, Area kerja Project PT KBS</td></tr>
                            <tr><td>7.</td> <td>Seluruh pekerja dilarang untuk melakukan pungutan terhadap pengguna jasa atau lainnya di area PT KBS</td></tr>
                            <tr><td>8.</td> <td>Seluruh pekerja diinstruksikan untuk melengkapi persyaratan Ijin kerja dan ijin masuk PT KBS</td></tr>
                            <tr><td>9.</td> <td>Wajib menggunakan Body harness saat bekerja di area ketinggian</td></tr>
                            <tr><td>10.</td> <td>Berhati-hati saat naik-turun tangga dan gunakan hand rail</td></tr>
                            <tr><td>11.</td> <td>Periksa kembali alat kerja pastikan aman sebelum memulai pekerjaan</td></tr>
                            <tr><td>12.</td> <td>Waspada terjepit saat bekerja, gunakan sarung tangan</td></tr>
                            <tr><td>13.</td> <td>Waspada kejatuhan benda dari atas dan selalu perhatikan sekeliling.</td></tr>
                            <tr><td>14.</td> <td>Dilarang duduk dan berdiri di pinggir Dermaga</td></tr>
                            <tr><td>15.</td> <td>Tidak beristirahat di lintasan crane atau di area kerja</td></tr>
                            <tr><td>16.</td> <td>Pekerja di pinggir dermaga dan pekerjaan diatas permukaan air wajib menggunakan lifejacket</td></tr>
                            <tr><td>17.</td> <td>Gunakan APAR (alat pemadam api ringan) yang ada di sekitar area kerja untuk memadamkan kebakaran tingkat ringan, hubungi tim K3LH, Pemadam atau security terdekat untuk penanganan kebakaran lebih lanjut</td></tr>
                            <tr><td>18.</td> <td>Jika terjadi bencana alam seperti (Gempa bumi, Badai, Tsunami) dan mendengar sirine panjang yang berulang maka Ikuti arahan petugas (security / K3LH) menuju area titik berkumpul terdekat dan segera evakuasi ke area yang lebih aman sesuai arahan petugas.</td></tr>
                            <tr><td>19.</td> <td>Jika anda menemui situasi kerja yang tidak aman, hentikan pekerjaan dan segera hubungi Tim K3LH atau security terdekat.</td></tr>
                            <tr><td>20.</td> <td>Perusahaan atau personil yang mengabaikan dan melanggar himbauan ini akan diberikan sanksi tegas yang berlaku, salah satunya diberhentikan, blacklist dan dikeluarkan dari area PT KBS.</td></tr>
                        </table>
                        <!-- <h4>Lorem Ipsum</h4> -->
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <div class="pull-right">
                                <div class="checkbox">
                                    <input type="checkbox" id="check-1">
                                    <label for="check-1">Saya memahami dan akan mematuhi peraturan</label>
                                    <a class="button btn btn-inline btn-success disabled" href="{{ route('uploadDokumenPendukung') }}">Buat Ijin Kerja</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--.box-typical-->
            </div><!--.col-xl-6-->
            
        </div><!--.row-->
    </div><!--.container-fluid-->

@endsection

@push('list-ijin-kerja-js')
    
    <script>
		$( "input" ).on( "change", function() {

            var $input = $( this );

            if($input.prop('checked')==true) {

                $("a").removeClass("disabled");
            }
            if($input.prop('checked')==false) {

                $("a").addClass("disabled");
            }
        }).trigger( "change" );
	</script>
@endpush