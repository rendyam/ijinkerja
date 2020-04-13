<!DOCTYPE html>
<html>
<head>
	<title>Ijin Kerja</title>
    <style type="text/css">
      .tg  {border-collapse:collapse;border-spacing:0;}
      .tg td{font-family:Arial, sans-serif;font-size:14px;padding:2px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
      .tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:2px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
      .tg .tg-w0yc{font-weight:bold;font-size:11px;font-family:"Times New Roman", Times, serif !important;;border-color:inherit;text-align:left;vertical-align:middle}
      .tg .tg-70n2{font-weight:bold;font-size:11px;font-family:"Times New Roman", Times, serif !important;;border-color:inherit;text-align:center;vertical-align:top}
      .tg .bagian1{font-weight:bold;font-size:11px;font-family:"Times New Roman", Times, serif !important;;border-color:inherit;text-align:left;vertical-align:top}
      .tg .tg-p1nr{font-size:11px;font-family:"Times New Roman", Times, serif !important;;border-color:inherit;text-align:left;vertical-align:top}
      .tg .tg-4msd{font-size:11px;font-family:"Times New Roman", Times, serif !important;;border-color:inherit;text-align:left;vertical-align:top}
      .tg .tg-hl2d{font-size:11px;font-family:"Times New Roman", Times, serif !important;;border-color:inherit;text-align:left;vertical-align:middle}
      .tg .tg-qgz6{font-family:"Times New Roman", Times, serif !important;;border-color:inherit;text-align:left;vertical-align:top}
      .tg .tg-0pky{border-color:inherit;text-align:left;vertical-align:top}
      .tg .tg-nxv2{font-size:16px;font-family:"Times New Roman", Times, serif !important;;border-color:inherit;text-align:center;vertical-align:top}
      .tg .tg-rspx{font-size:11px;font-family:"Times New Roman", Times, serif !important;;border-color:inherit;text-align:left;vertical-align:top}
      .tg .tg-4qi8{font-size:14px;font-family:"Times New Roman", Times, serif !important;;border-color:inherit;text-align:center;vertical-align:top}
      .tg .tg-bxgz{font-size:11px;font-family:"Times New Roman", Times, serif !important;;border-color:inherit;text-align:center;vertical-align:top}
      .tg .tg-lj5e{font-size:12px;font-family:"Times New Roman", Times, serif !important;;border-color:inherit;text-align:center;vertical-align:top}
      .tg .tg-xwok{font-weight:bold;font-size:11px;font-family:"Times New Roman", Times, serif !important;;border-color:inherit;text-align:left;vertical-align:top}
      .tg .extra{font-weight:bold;font-size:11px;font-family:"Times New Roman", Times, serif !important;;border-color:inherit;text-align:left;vertical-align:middle}
      .tg .extra5{font-weight:bold;font-size:11px;font-family:"Times New Roman", Times, serif !important;;border-color:inherit;text-align:left;}
      .verticalTableHeader {
          text-align:center;
          white-space:nowrap;
          g-origin:50% 50%;
          -webkit-transform: rotate(270deg);
          -moz-transform: rotate(270deg);
          -ms-transform: rotate(270deg);
          -o-transform: rotate(270deg);
          transform: rotate(270deg);
          
      }
      .verticalTableHeader p {
          margin:0 -100% ;
          display:inline-block;
      }
      .verticalTableHeader p:before{
          content:'aaa';
          width:0;
          padding-top:110%;/* takes width as reference, + 10% for faking some extra padding */
          display:inline-block;
          vertical-align:middle;
      }
    </style>
</head>
    <body>
    <table class="tg" style="undefined;table-layout: fixed; width: 725px">
<colgroup>
<col style="width: 58px">
<col style="width: 103px">
<col style="width: 92px">
<col style="width: 96px">
<col style="width: 72px">
<col style="width: 122px">
<col style="width: 76px">
<col style="width: 106px">
</colgroup>
  <tr>
    <th class="tg-0pky" colspan="2" rowspan="3"> <center> <img src="{{ asset('startui/img/KBS-logo.png') }}" height="50px" alt=""> </center> </th>
    <th class="tg-nxv2" colspan="4" rowspan="2"><span style="font-weight:bold">FORM</span></th>
    <th class="tg-rspx" colspan="2">No. Dok. : FMPS-DKWS-11-0</th>
  </tr>
  <tr>
    <td class="tg-rspx" colspan="2">Revisi : 01<br></td>
  </tr>
  <tr>
    <td class="tg-4qi8" colspan="4" rowspan="2"><span style="font-weight:bold">LEMBAR IJIN KERJA (LIK)</span></td>
    <td class="tg-rspx" colspan="2">Tgl. Terbit : 15-08-2019<br></td>
  </tr>
  <tr>
    <td class="tg-bxgz" colspan="2">Divisi Kawasan &amp; K3LH<br></td>
    <td class="tg-rspx" colspan="2">Halaman 1 dari 1<br></td>
  </tr>
  <tr>
    <td class="tg-lj5e" colspan="8">Nomor : {{ $ijin_kerja->nomor_lik }} <br></td>
  </tr>
  <tr>
    <td class="tg-hl2d" rowspan="11"><span style="font-weight:bold"><p class="verticalTableHeader">Bagian 1 : Informasi Pekerjaan &nbsp;&nbsp;&nbsp;</p></span><br></td>
    <td class="tg-rspx" colspan="2">Kategori Ijin Kerja<br></td>
    <td class="tg-rspx" colspan="5">{{ $ijin_kerja->kategori}} </td>
  </tr>
  <tr>
    <td class="tg-rspx" colspan="7">Jenis Resiko (Beri tanda "X" pada kotak sesuai dengan jenis resiko)<br></td>
  </tr>
  <tr>
    <td class="tg-rspx" colspan="2">(@if(in_array("Ketinggian", json_decode($ijin_kerja->jenis_resiko))) X @else ( ) @endif) Ketinggian</td>
    <td class="tg-rspx" colspan="2">(@if(in_array("Penggalian", json_decode($ijin_kerja->jenis_resiko))) X @else ( ) @endif) Penggalian</td>
    <td class="tg-rspx" colspan="3">(@if(in_array("Pekerjaan Bawah Air", json_decode($ijin_kerja->jenis_resiko))) X @else ( ) @endif) Pekerjaan di bawah air</td>
  </tr>
  <tr>
    <td class="tg-rspx" colspan="2">(@if(in_array("Kerja Panas", json_decode($ijin_kerja->jenis_resiko))) X @else ( ) @endif) Kerja Panas</td>
    <td class="tg-rspx" colspan="2">(@if(in_array("Area Terbatas", json_decode($ijin_kerja->jenis_resiko))) X @else ( ) @endif) Area Terbatas</td>
    <td class="tg-rspx" colspan="3">@if(count($get_risk_lainnya) > 0) ( X ) Lainnya: {{ $get_risk_lainnya[0] }} @else (( )) Lainnya @endif </td>
  </tr>
  <tr>
    <td class="bagian1" colspan="2" rowspan="3"><br>Izin diberikan kepada</td>
    <td class="tg-rspx" colspan="2">No. PO/PPJ/KONTRAK<br></td>
    <td class="tg-rspx" colspan="3"> {{ json_decode($ijin_kerja->izin_diberikan_kepada)->no_po }} </td>
  </tr>
  <tr>
    <td class="tg-rspx" colspan="2">Perusahaan</td>
    <td class="tg-rspx" colspan="3">{{ json_decode($ijin_kerja->izin_diberikan_kepada)->perusahaan }}</td>
  </tr>
  <tr>
    <td class="tg-rspx" colspan="2">Penanggung jawab<br></td>
    <td class="tg-p1nr">{{json_decode($ijin_kerja->izin_diberikan_kepada)->pic_pemohon}}</td>
    <td class="tg-rspx">No. HP<br></td>
    <td class="tg-p1nr">{{ json_decode($ijin_kerja->izin_diberikan_kepada)->no_hp }}</td>
  </tr>
  <tr>
    <td class="bagian1" colspan="2">Masa Berlaku Tanggal<br></td>
    <td class="tg-p1nr" colspan="5"> {{json_decode($ijin_kerja->masa_berlaku)->mulai}} s/d {{json_decode($ijin_kerja->masa_berlaku)->akhir}}</td>
  </tr>
  <tr>
    <td class="bagian1" colspan="2">Lokasi Pekerjaan<br></td>
    <td class="tg-p1nr" colspan="5">{{ $ijin_kerja->lokasi_pekerjaan}}</td>
  </tr>
  <tr>
    <td class="bagian1" colspan="2" rowspan="2">Uraian Singkat Pekerjaan<br></td>
    <td class="tg-p1nr" colspan="5" rowspan="2">{{ $ijin_kerja->uraian_singkat_pekerjaan }}</td>
  </tr>
  <tr>
  </tr>
  <tr>
    <td class="tg-w0yc" rowspan="13"> <p class="verticalTableHeader">Bagian 2 : Tindakan Pencegahan Kecelakaan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<p> <br></td>
    <td class="tg-xwok" colspan="7">Jenis Bahaya (Beri tanda "X" pada kotak yang sesuai dengan jenis bahaya)<br></td>
  </tr>
  <tr>
    <td class="tg-rspx" colspan="2">(@if(in_array("Spark (Loncatan Api)", json_decode($ijin_kerja->jenis_bahaya))) X @else ( ) @endif) Spark (loncatan api)</td>
    <td class="tg-rspx" colspan="2">(@if(in_array("Gas Berbahaya", json_decode($ijin_kerja->jenis_bahaya))) X @else ( ) @endif) Gas Berbahaya</td>
    <td class="tg-rspx" colspan="3">(@if(in_array("Tertabrak", json_decode($ijin_kerja->jenis_bahaya))) X @else ( ) @endif) Tertabrak</td>
  </tr>
  <tr>
    <td class="tg-rspx" colspan="2">(@if(in_array("Panas", json_decode($ijin_kerja->jenis_bahaya))) X @else ( ) @endif) Panas</td>
    <td class="tg-rspx" colspan="2">(@if(in_array("Electrical Shock", json_decode($ijin_kerja->jenis_bahaya))) X @else ( ) @endif) Electrical Shock</td>
    <td class="tg-rspx" colspan="3">(@if(in_array("Terpotong", json_decode($ijin_kerja->jenis_bahaya))) X @else ( ) @endif) Terpotong</td>
  </tr>
  <tr>
    <td class="tg-rspx" colspan="2">(@if(in_array("Debu", json_decode($ijin_kerja->jenis_bahaya))) X @else ( ) @endif) Debu</td>
    <td class="tg-rspx" colspan="2">(@if(in_array("Kebakaran", json_decode($ijin_kerja->jenis_bahaya))) X @else ( ) @endif) Kebakaran</td>
    <td class="tg-rspx" colspan="3">(@if(in_array("Terjatuh/Tersandung", json_decode($ijin_kerja->jenis_bahaya))) X @else ( ) @endif) Terjatuh/Tersandung</td>
  </tr>
  <tr>
    <td class="tg-rspx" colspan="2">(@if(in_array("Bahan Kimia", json_decode($ijin_kerja->jenis_bahaya))) X @else ( ) @endif) Bahan Kimia</td>
    <td class="tg-rspx" colspan="2">(@if(in_array("Kejatuhan Benda", json_decode($ijin_kerja->jenis_bahaya))) X @else ( ) @endif) Kejatuhan Benda</td>
    <td class="tg-rspx" colspan="3">@if(count($get_danger_lainnya) > 0) ( X ) Lainnya: {{ $get_danger_lainnya[0] }} @else (( )) Lainnya @endif </td>
  </tr>
  <tr>
    <td class="tg-xwok" colspan="7">Alat Pelindung Diri (Beri tanda "X" pada kotak yang sesuai dengan kebutuhan alat pelindung diri)<br></td>
  </tr>
  <tr>
    <td class="tg-rspx" colspan="2">(@if(in_array("Helmet", json_decode($ijin_kerja->apd))) X @else ( ) @endif) Helmet</td>
    <td class="tg-rspx" colspan="2">(@if(in_array("Masker", json_decode($ijin_kerja->apd))) X @else ( ) @endif) Masker</td>
    <td class="tg-rspx" colspan="3">(@if(in_array("Life Jacket & Ring Buoy", json_decode($ijin_kerja->apd))) X @else ( ) @endif) Life Jacket &amp; Ring Buoy</td>
  </tr>
  <tr>
    <td class="tg-rspx" colspan="2">(@if(in_array("Safety Shoes", json_decode($ijin_kerja->apd))) X @else ( ) @endif) Safety Shoes</td>
    <td class="tg-rspx" colspan="2">(@if(in_array("Pelindung Muka", json_decode($ijin_kerja->apd))) X @else ( ) @endif) Pelindung Muka</td>
    <td class="tg-rspx" colspan="3">(@if(in_array("Alat Bantu Pernapasan", json_decode($ijin_kerja->apd))) X @else ( ) @endif) Alat Bantu Pernapasan (SCBA)</td>
  </tr>
  <tr>
    <td class="tg-rspx" colspan="2">(@if(in_array("Sarung Tangan", json_decode($ijin_kerja->apd))) X @else ( ) @endif) Sarung Tangan</td>
    <td class="tg-rspx" colspan="2">(@if(in_array("Pelindung Telinga", json_decode($ijin_kerja->apd))) X @else ( ) @endif) Pelindung Telinga</td>
    <td class="tg-rspx" colspan="3">@if(count($get_se_lainnya) > 0) ( X ) Lainnya: {{ $get_se_lainnya[0] }} @else (( )) Lainnya @endif </td>
  </tr>
  <tr>
    <td class="tg-rspx" colspan="2">(@if(in_array("Kacamata Safety", json_decode($ijin_kerja->apd))) X @else ( ) @endif) Kacamata Safety</td>
    <td class="tg-rspx" colspan="2">(@if(in_array("Full Bodyharness", json_decode($ijin_kerja->apd))) X @else ( ) @endif) Full Bodyharness</td>
    <td class="tg-rspx" colspan="3"></td>
  </tr>
  <tr>
    <td class="tg-rspx" colspan="7">Catatan Safety Officer<br></td>
  </tr>
  <tr>
    <td class="tg-4msd" colspan="7" rowspan="2">{{ $ijin_kerja->catatan_safety_officer }}<br><br></td>
  </tr>
  <tr>
  </tr>
  <tr>
    <td class="tg-hl2d" rowspan="7"><span style="font-weight:bold"><p class="verticalTableHeader">Bagian 3 : &nbsp;&nbsp;&nbsp;&nbsp; <br> Dokumen Pendukung &nbsp;&nbsp;&nbsp;&nbsp;</p></span><br></td>
    <td class="tg-xwok" colspan="7">Dokumen Pendukung<br></td>
  </tr>
  <tr>
    <td class="tg-rspx" colspan="4">(@if(in_array("JSA", json_decode($ijin_kerja->list_dokumen))) X @else ( ) @endif) JSA/HIRA</td>
    <td class="tg-rspx" colspan="3">(@if(in_array("Daftar Peralatan", json_decode($ijin_kerja->list_dokumen))) X @else ( ) @endif) Daftar Peralatan<br></td>
  </tr>
  <tr>
    <td class="tg-rspx" colspan="4">(@if(in_array("Sertifikat Peralatan A2B dan SIO Operator", json_decode($ijin_kerja->list_dokumen))) X @else ( ) @endif) Sertifikat Peralatan A2B dan SIO Operator<br></td>
    <td class="tg-rspx" colspan="3">(@if(in_array("Daftar Pekerja", json_decode($ijin_kerja->list_dokumen))) X @else ( ) @endif) Daftar Pekerja<br></td>
  </tr>
  <tr>
    <td class="tg-rspx" colspan="4">(@if(in_array("PO/PPJ/KONTRAK/Memo Dinas", json_decode($ijin_kerja->list_dokumen))) X @else ( ) @endif) PO/PPJ/KONTRAK/Memo Dinas<br></td>
    <td class="tg-rspx" colspan="3">@if(count($get_dokumen_lainnya) > 0) ( X ) Lainnya: {{ $get_dokumen_lainnya[0] }} @else (( )) Lainnya @endif </td>
  </tr>
  <tr>
    <td class="tg-bxgz" colspan="3" rowspan="3"> <img src="data:image/png;base64, {!! $qrcode !!}"><br> (Pemohon/Penanggung Jawab)</td>
    <td class="tg-bxgz" colspan="2" rowspan="3"><img src="data:image/png;base64, {!! $qrcode !!}"><br> (Safety Officer)</td>
    <td class="tg-bxgz" colspan="2" rowspan="3"><img src="data:image/png;base64, {!! $qrcode !!}"><br> (Kadis K3LH)</td>
  </tr>
  <tr>
  </tr>
  <tr>
  </tr>
  <tr>
    <td class="extra" rowspan="4"> <div class="verticalTableHeader">Bagian 4 : &nbsp;&nbsp;&nbsp; <br> Perpanjangan &nbsp;&nbsp;&nbsp; <br> Ijin Kerja &nbsp;&nbsp;&nbsp; </div> <br></td>
    <td class="tg-rspx" colspan="7">Perpanjangan Ijin Kerja<br></td>
  </tr>
  <tr>
    <td class="tg-hl2d" colspan="7" rowspan="3">Dari Tanggal:<br></td>
  </tr>
  <tr>
  </tr>
  <tr>
  </tr>
  <tr>
    <td class="extra5" rowspan="6"> <p class="verticalTableHeader"> Bagian 5 : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br> Penutupan Ijin Kerja &nbsp;&nbsp;&nbsp;&nbsp; </p><br></td>
    <td class="tg-xwok" colspan="3">Penutupan Ijin Kerja<br></td>
    <td class="tg-bxgz" colspan="2">Safety Officer<br></td>
    <td class="tg-70n2" colspan="2" rowspan="6">Catatan Lainnya<br></td>
  </tr>
  <tr>
    <td class="tg-rspx" colspan="3">Kondisi Setelah Bekerja Dilaksanakan<br></td>
    <td class="tg-rspx" colspan="2">Tanggal &amp; Jam<br></td>
  </tr>
  <tr>
    <td class="tg-rspx" colspan="3">( ) Jumlah Personil Lengkap<br></td>
    <td class="tg-rspx" colspan="2" rowspan="3"></td>
  </tr>
  <tr>
    <td class="tg-rspx" colspan="3">( ) Seluruh Peralatan Dirapikan<br></td>
  </tr>
  <tr>
    <td class="tg-rspx" colspan="3">( ) Kebersihan</td>
  </tr>
  <tr>
    <td class="tg-rspx" colspan="3">( ) Lainnya</td>
    <td class="tg-rspx" colspan="2">Nama :</td>
  </tr>
</table>
    </body>
</html>