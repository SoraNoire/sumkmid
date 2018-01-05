@extends('layouts.publicbase')

@section('content')
<div class="breadcrumb">
	<div class="container">
		<h2><a href="{{ route('public_home') }}">Beranda</a> <i class="fa fa-angle-right" aria-hidden="true"></i>Pendaftaran <i class="fa fa-angle-right" aria-hidden="true"></i>Data Diri</h2>
	</div>
</div>
<?php
?>
<section id="userSetting">
	<div class="container">
	@if(session('success') == 'true')
		<div class="formAlert alertSuccess">
			<span>Profil berhasil disimpan</span>
			<div class="closeAlert">x</div>
		</div>
	@endif
		
		<div class="leftForm">
			<div class="photoUser">
				<div class="photoPreview" style="background-image:url('{{ $user->avatar ?? asset('images/admin.png') }}');">
				</div>
				<div class="inputTrigger" onclick="document.getElementById('inputUserImage').click(); return false;"></div>
				<form id="upldimageuser" action="{{ route('user_update_profile_pict') }}" accept="image/*" enctype="multipart/form-data" method="post">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="email" id="" value="{{$user->email}}">
					<input type="hidden" name="nama"  value="{{$user->name}}">
					<input type="file" name="photo" id="inputUserImage" accept="image/x-png,image/gif,image/jpeg">
					<input type="submit" style="display:none;">
				</form>
			</div>
		</div>
		<div class="rightForm "">
			<form action="{{route('SHB.complete_data_save')}}" method="post" enctype="multipart/form-data">
			
			<div class="formGroup ttl">
				<div class="tl">
					<div>
						Tempat Lahir
					</div>
					<div class="inputText">
						<input name="kota_lahir" value="{{ app()->OAuth::Auth()->data->kota_lahir ?? '' }}" />
					</div>
				</div>

				<div class="tgll">
					<div> Tanggal Lahir </div>
						<select id="tahun_lahir" class="dropdown3" name="tahun_lahir">
							<option>Tahun</option>
							@for( $i=(date('Y'));$i>=(date('Y')-60);$i-- ) 
								@if( $i == explode('/',(app()->OAuth::Auth()->data->tanggal_lahir ?? '0/0/0'))[0] )
									<option selected value="{{$i}}" >{{$i}}</option>
								@else
									<option value="{{$i}}" >{{$i}}</option>
								@endif
							@endfor
						</select>
						
						<select class="dropdown3" name="bulan_lahir" onchange="aturTanggal(this)">
							<option>Bulan</option>
							@for($i=1;$i<=12;$i++)
								@if( $i == explode('/',(app()->OAuth::Auth()->data->tanggal_lahir ?? '0/0/0'))[1] )
									<option selected value="{{$i}}" >{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
								@else
									<option value="{{$i}}" >{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
								@endif
							@endfor
						</select>

						<select id="tanggal_lahir" class="dropdown3" name="tanggal_lahir">
							<option>Tanggal</option>
							@for($i=1;$i<=31;$i++)
								@if( $i == explode('/',(app()->OAuth::Auth()->data->tanggal_lahir ?? '0/0/0'))[2] )
									<option selected value="{{$i}}" >{{$i}}</option>
								@else
									<option value="{{$i}}" >{{$i}}</option>
								@endif
							@endfor
						</select>
						
				</div>

				
				<div style="clear: both;"></div>
			</div>
				<div class="formGroup pilihkotaprov">
					<div class="inputTitle">
						Provinsi Dan Kota
					</div>
					<div class="inputText">
						<select id="pilihProvinsi" name="provinsi">
							<option class="" value="">Pilih Provinsi</option>
							<option value="Aceh">Aceh</option>
							<option value="Bali">Bali</option>
							<option value="Bangka Belitung">Bangka Belitung</option>
							<option value="Banten">Banten</option>
							<option value="Bengkulu">Bengkulu</option>
							<option value="Gorontalo">Gorontalo</option>
							<option value="Jakarta">Jakarta</option>
							<option value="Jambi">Jambi</option>
							<option value="Jawa Barat">Jawa Barat</option>
							<option value="Jawa Tengah">Jawa Tengah</option>
							<option value="Jawa Timur">Jawa Timur</option>
							<option value="Kalimantan Barat">Kalimantan Barat</option>
							<option value="Kalimantan Selatan">Kalimantan Selatan</option>
							<option value="Kalimantan Tengah">Kalimantan Tengah</option>
							<option value="Kalimantan Timur">Kalimantan Timur</option>
							<option value="Kalimantan Utara">Kalimantan Utara</option>
							<option value="Kepulauan Riau">Kepulauan Riau</option>
							<option value="Lampung">Lampung</option>
							<option value="Maluku Utara">Maluku Utara</option>
							<option value="Maluku">Maluku</option>
							<option value="Nusa Tenggara Barat">Nusa Tenggara Barat</option>
							<option value="Nusa Tenggara Timur">Nusa Tenggara Timur</option>
							<option value="Papua Barat">Papua Barat</option>
							<option value="Papua">Papua</option>
							<option value="Riau">Riau</option>
							<option value="Sulawesi Selatan">Sulawesi Selatan</option>
							<option value="Sulawesi Tengah">Sulawesi Tengah</option>
							<option value="Sulawesi Tenggara">Sulawesi Tenggara</option>
							<option value="Sulawesi Utara">Sulawesi Utara</option>
							<option value="Sumatera Barat">Sumatera Barat</option>
							<option value="Sumatera Selatan">Sumatera Selatan</option>
							<option value="Sumatera Utara">Sumatera Utara</option>
							<option value="Yogyakarta">Yogyakarta</option>
						</select>
					</div>
					<div class="inputText">
						<select name="kota" id="pilihKota">
							<option value="pilihkota">Pilih Kota</option>
							<option class="defkota Aceh" value="Banda Aceh">Banda Aceh</option>
							<option class="defkota Aceh" value="Langsa">Langsa</option>
							<option class="defkota Aceh" value="Lhokseumawe">Lhokseumawe</option>
							<option class="defkota Aceh" value="Meulaboh">Meulaboh</option>
							<option class="defkota Aceh" value="Sabang">Sabang</option>
							<option class="defkota Aceh" value="Subulussalam">Subulussalam</option>
							<option class="defkota Bali" value="Denpasar">Denpasar</option>
							<option class="defkota Bangka-Belitung" value="Pangkalpinang">Pangkalpinang</option>
							<option class="defkota Banten" value="Cilegon">Cilegon</option>
							<option class="defkota Banten" value="Serang">Serang</option>
							<option class="defkota Banten" value="Tangerang Selatan">Tangerang Selatan</option>
							<option class="defkota Banten" value="Tangerang">Tangerang</option>
							<option class="defkota Bengkulu" value="Bengkulu">Bengkulu</option>
							<option class="defkota Gorontalo" value="Gorontalo">Gorontalo</option>
							<option class="defkota Jakarta" value="Jakarta Barat">Jakarta Barat</option>
							<option class="defkota Jakarta" value="Jakarta Pusat">Jakarta Pusat</option>
							<option class="defkota Jakarta" value="Jakarta Selatan">Jakarta Selatan</option>
							<option class="defkota Jakarta" value="Jakarta Timur">Jakarta Timur</option>
							<option class="defkota Jakarta" value="Jakarta Utara">Jakarta Utara</option>
							<option class="defkota Jambi" value="Sungai Penuh">Sungai Penuh</option>
							<option class="defkota Jambi" value="Jambi">Jambi</option>
							<option class="defkota Jawa-Barat" value="Bandung">Bandung</option>
							<option class="defkota Jawa-Barat" value="Bekasi">Bekasi</option>
							<option class="defkota Jawa-Barat" value="Bogor">Bogor</option>
							<option class="defkota Jawa-Barat" value="Cimahi">Cimahi</option>
							<option class="defkota Jawa-Barat" value="Cirebon">Cirebon</option>
							<option class="defkota Jawa-Barat" value="Depok">Depok</option>
							<option class="defkota Jawa-Barat" value="Sukabumi">Sukabumi</option>
							<option class="defkota Jawa-Barat" value="Tasikmalaya">Tasikmalaya</option>
							<option class="defkota Jawa-Barat" value="Banjar">Banjar</option>
							<option class="defkota Jawa-Tengah" value="Magelang">Magelang</option>
							<option class="defkota Jawa-Tengah" value="Pekalongan">Pekalongan</option>
							<option class="defkota Jawa-Tengah" value="Purwokerto">Purwokerto</option>
							<option class="defkota Jawa-Tengah" value="Salatiga">Salatiga</option>
							<option class="defkota Jawa-Tengah" value="Semarang">Semarang</option>
							<option class="defkota Jawa-Tengah" value="Surakarta">Surakarta</option>
							<option class="defkota Jawa-Tengah" value="Tegal">Tegal</option>
							<option class="defkota Jawa-Timur" value="Batu">Batu</option>
							<option class="defkota Jawa-Timur" value="Blitar">Blitar</option>
							<option class="defkota Jawa-Timur" value="Kediri">Kediri</option>
							<option class="defkota Jawa-Timur" value="Madiun">Madiun</option>
							<option class="defkota Jawa-Timur" value="Malang">Malang</option>
							<option class="defkota Jawa-Timur" value="Mojokerto">Mojokerto</option>
							<option class="defkota Jawa-Timur" value="Pasuruan">Pasuruan</option>
							<option class="defkota Jawa-Timur" value="Probolinggo">Probolinggo</option>
							<option class="defkota Jawa-Timur" value="Surabaya">Surabaya</option>
							<option class="defkota Kalimantan-Barat" value="Pontianak">Pontianak</option>
							<option class="defkota Kalimantan-Barat" value="Singkawang">Singkawang</option>
							<option class="defkota Kalimantan-Selatan" value="Banjarbaru">Banjarbaru</option>
							<option class="defkota Kalimantan-Selatan" value="Banjarmasin">Banjarmasin</option>
							<option class="defkota Kalimantan-Tengah" value="Palangkaraya">Palangkaraya</option>
							<option class="defkota Kalimantan-Timur" value="Balikpapan">Balikpapan</option>
							<option class="defkota Kalimantan-Timur" value="Bontang">Bontang</option>
							<option class="defkota Kalimantan-Timur" value="Samarinda">Samarinda</option>
							<option class="defkota Kalimantan-Utara" value="Tarakan">Tarakan</option>
							<option class="defkota Kepulauan-Riau" value="Batam">Batam</option>
							<option class="defkota Kepulauan-Riau" value="Tanjungpinang">Tanjungpinang</option>
							<option class="defkota Lampung" value="Bandar Lampung">Bandar Lampung</option>
							<option class="defkota Lampung" value="Metro">Metro</option>
							<option class="defkota Maluku-Utara" value="Ternate">Ternate</option>
							<option class="defkota Maluku-Utara" value="Tidore Kepulauan">Tidore Kepulauan</option>
							<option class="defkota Maluku" value="Ambon">Ambon</option>
							<option class="defkota Maluku" value="Tual">Tual</option>
							<option class="defkota Nusa-Tenggara-Barat" value="Bima">Bima</option>
							<option class="defkota Nusa-Tenggara-Barat" value="Mataram">Mataram</option>
							<option class="defkota Nusa-Tenggara-Timur" value="Kupang">Kupang</option>
							<option class="defkota Papua-Barat" value="Sorong">Sorong</option>
							<option class="defkota Papua" value="Jayapura">Jayapura</option>
							<option class="defkota Riau" value="Dumai">Dumai</option>
							<option class="defkota Riau" value="Pekanbaru">Pekanbaru</option>
							<option class="defkota Sulawesi-Selatan" value="Makassar">Makassar</option>
							<option class="defkota Sulawesi-Selatan" value="Palopo">Palopo</option>
							<option class="defkota Sulawesi-Selatan" value="Parepare">Parepare</option>
							<option class="defkota Sulawesi-Tengah" value="Palu">Palu</option>
							<option class="defkota Sulawesi-Tenggara" value="Bau-Bau">Bau-Bau</option>
							<option class="defkota Sulawesi-Tenggara" value="Kendari">Kendari</option>
							<option class="defkota Sulawesi-Utara" value="Bitung">Bitung</option>
							<option class="defkota Sulawesi-Utara" value="Kotamobagu">Kotamobagu</option>
							<option class="defkota Sulawesi-Utara" value="Manado">Manado</option>
							<option class="defkota Sulawesi-Utara" value="Tomohon">Tomohon</option>
							<option class="defkota Sumatera-Barat" value="Bukittinggi">Bukittinggi</option>
							<option class="defkota Sumatera-Barat" value="Padang">Padang</option>
							<option class="defkota Sumatera-Barat" value="Padangpanjang">Padangpanjang</option>
							<option class="defkota Sumatera-Barat" value="Pariaman">Pariaman</option>
							<option class="defkota Sumatera-Barat" value="Payakumbuh">Payakumbuh</option>
							<option class="defkota Sumatera-Barat" value="Sawahlunto">Sawahlunto</option>
							<option class="defkota Sumatera-Barat" value="Solok">Solok</option>
							<option class="defkota Sumatera-Selatan" value="Lubuklinggau">Lubuklinggau</option>
							<option class="defkota Sumatera-Selatan" value="Pagaralam">Pagaralam</option>
							<option class="defkota Sumatera-Selatan" value="Palembang">Palembang</option>
							<option class="defkota Sumatera-Selatan" value="Prabumulih">Prabumulih</option>
							<option class="defkota Sumatera-Utara" value="Binjai">Binjai</option>
							<option class="defkota Sumatera-Utara" value="Medan">Medan</option>
							<option class="defkota Sumatera-Utara" value="Padang Sidempuan">Padang Sidempuan</option>
							<option class="defkota Sumatera-Utara" value="Pematangsiantar">Pematangsiantar</option>
							<option class="defkota Sumatera-Utara" value="Sibolga">Sibolga</option>
							<option class="defkota Sumatera-Utara" value="Tanjungbalai">Tanjungbalai</option>
							<option class="defkota Sumatera-Utara" value="Tebingtinggi">Tebingtinggi</option>
							<option class="defkota Yogyakarta" value="Yogyakarta">Yogyakarta</option>
						</select>
					</div>
				</div>
				<div class="formGroup">
					<div class="inputTitle">
						Alamat Lengkap
					</div>
					<div class="inputText inputAlamat">
						<textarea name="alamat" placeholder="Alamat Lengkap">{{$user->data->alamat ?? ''}}</textarea>
					</div>
				</div>

				<div class="formGroup">
					<div class="inputTitle">
						No Telp
					</div>
					<div class="inputText">
						<input type="text" name="telepon"  value="{{$user->data->telepon ?? ''}}" placeholder="+62..">
					</div>
				</div>

				<button type="sumbit" class="submitUserSet button blue">Selanjutnya</button>
			</form>
		</div>
	</div>
</section>
<script type="text/javascript">
	
	function aturTanggal(val=false)
	{
		var tahun = document.getElementById('tahun_lahir').value;
		if(tahun == 'Tahun'){
			alert('Pilih Tahun Terlebih Dahulu');
		}
		var bulan = val.value;
		var date = new Date(tahun, bulan , 0);
		var jumlah_hari = date.getDate();
		var _el = '';
		for ( j=1;j<=jumlah_hari;j++ )
		{
			_el += ' <option value="'+pad(j)+'"> ' + pad(j) + '</option>'; 
		}
		document.getElementById('tanggal_lahir').innerHTML = _el;
	}
	function pad(n) {
	    return (n < 10) ? ("0" + n) : n;
	}
</script>

@endsection