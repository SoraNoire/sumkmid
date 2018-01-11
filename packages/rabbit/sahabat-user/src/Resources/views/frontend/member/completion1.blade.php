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
					<input type="file" name="photo" id="inputUserImage" accept="image/x-png,image/gif,image/jpeg">
					<input type="submit" style="display:none;">
				</form>
			</div>
		</div>
		<div class="rightForm "">
			<form action="{{route('SHB.complete_data_save')}}" method="post" enctype="multipart/form-data">
				<input type="hidden" name="step" value="step1">
			@if ($errors->any())
			    <div class="alert alert-danger">
			        <ul>
			            @foreach ($errors->all() as $error)
			                <li>{{ $error }}</li>
			            @endforeach
			        </ul>
			    </div>
			@endif
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
						
						<select id="tanggal_lahir" class="dropdown3" name="tanggal_lahir">
							<option>Tanggal</option>
							@for($i=1;$i<=31;$i++)
							@php
								if($i < 10){
									$io = '0'.$i;
								}else{
									$io = $i;
								}
							@endphp
								@if( $i == explode('/',(app()->OAuth::Auth()->data->tanggal_lahir ?? '0/0/0'))[2] )
									<option selected value="{{$io}}" >{{$io}}</option>
								@else
									<option value="{{$io}}" >{{$io}}</option>
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
							@foreach( $alamat['provinsi'] as $provinsi)
							<option {{ (isset($user->data->provinsi) ? ($user->data->provinsi == $provinsi->nama_provinsi ? 'selected' : '') : '') }} id="provinsi{{ $provinsi->id }}" value="{{ $provinsi->nama_provinsi }}">{{ $provinsi->nama_provinsi }}</option>
							@endforeach
						</select>
					</div>
					<div class="inputText">
						<select name="kota" id="pilihKota">
							<option value="pilihkota">Pilih Kota</option>
							@foreach($alamat['kota'] as $kota)
							<option {{ (isset($user->data->kota) ? ($user->data->kota == $kota->nama ? 'selected' : '') : '') }}  class="defkota provinsi{{ $kota->id_provinsi }}" value="{{ $kota->nama }}">{{ $kota->nama }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="clearfix"></div>
				<br>
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
<!-- <script type="text/javascript">
	
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
</script> -->

@endsection