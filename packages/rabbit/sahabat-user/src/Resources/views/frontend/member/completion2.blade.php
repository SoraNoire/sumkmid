@extends('layouts.publicbase')

@section('content')
<div class="breadcrumb">
	<div class="container">
		<h2><a href="{{ route('public_home') }}">Beranda</a> <i class="fa fa-angle-right" aria-hidden="true"></i>Pendaftaran <i class="fa fa-angle-right" aria-hidden="true"></i>Data Usaha</h2>
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
				<div class="photoPreview" style="background-image:url('{{ $user->foto_profil ?? asset('images/admin.png') }}');">
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
		<div class="rightForm ">




			<form id="form" name="form" action="{{route('SHB.complete_data_save')}}" method="post" enctype="multipart/form-data">
			
				<div class="formGroup">
					
					@php
						$chkTdk = ( 'perorangan' == $user->role ) ? 'checked' : '';
						$frmUClass = ( 'perorangan' == $user->role ) ? ' hidden' : '';
						$chkYa = ( 'checked' == $chkTdk ) ? '' : 'checked'; 
					@endphp

					<div>Anda Pengusaha Umkm?</div>
					<input {{$chkYa}} type="radio" name="type_user" value="ya">Ya
					<input {{$chkTdk}} type="radio" name="type_user" value="tidak">Tidak
					<div style="clear: both;"></div>
			</div>

				<div id="form-pengusaha" class="{{$frmUClass}}">
				
					<div class="formGroup">
						<div class="inputTitle">
							Nama Usaha
						</div>
						<div class="inputText">
							<input type="text" name="nama_usaha"  value="{{$user->data->nama_usaha ?? ''}}" placeholder="Nama Usaha">
						</div>
					</div>

					<div class="formGroup">
						<div style="width: 48%;float: left;">
							<div>Jenis Usaha</div>
							<select name="jenis_usaha">
								@foreach ($usaha as $u)
									<option value="{{$u}}">{{$u}}</option>
								@endforeach
							</select>
						</div>
						<div style="width: 48%;float: left;">
							<div>Tahun Berdiri</div>
							<input type="text" name="lama_berdiri" placeholder="2013">
						</div>
						<div style="clear: both;"></div>
					</div>

					<div class="formGroup">
						<div>
							<div style="width: 48%;float: left;">
								<div>Website</div>
								<select name="informasi_usaha[1]">
									<option value=NULL>Pilih</option>
									<option value="website" > Website</option>
								</select>
							</div>
							<div style="width: 48%;float: left;">
								<input type="text" name="info['website']">
							</div>
							<div style="clear: both;"></div>
						</div>
					</div>

					<div class="formGroup">
						<div class="inputTitle">
							Perkiraan Omzet
						</div>
						<div class="inputText">
							<select name="omzet">
								<option value=NULL>Pilih</option>
								<option value="1-5">1-5 Juta</option>
								<option value="5-10">5-10 Juta</option>
								<option value="10-20">10-20 Juta</option>
								<option value="20-50">20-50 Juta</option>
								<option value="50+"> 50+ Juta</option>
							</select>
						</div>
					</div>


				</div>
				<div>KTP</div>
				<input type="file" name="foto_ktp">

				<button type="sumbit" class="submitUserSet button blue">Simpan</button>
			</form>
		</div>
	</div>
	<style type="text/css">
		.hidden{
			display: none;
		}
	</style>
	<script type="text/javascript">
		var radios = document.forms["form"].elements["type_user"];
		var pengusaha = document.getElementById('form-pengusaha');
		for(radio in radios) {
			radios[radio].onclick = function() {
		        if('tidak' == this.value){pengusaha.className += ' hidden';}else{pengusaha.classList.remove("hidden");}
		    }
		}

		document.querySelector('#form').addEventListener('submit', function(e) {
		    e.preventDefault();
		    var fp = document.querySelector('#form-pengusaha');
		    if( -1 !== fp.className.indexOf('hidden') )
		    {
		    	fp.innerHTML = '';
		    }
		    this.submit();
		});
	</script>
</section>

@endsection