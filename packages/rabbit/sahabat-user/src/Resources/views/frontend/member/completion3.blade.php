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

			<form action="{{route('SHB.complete_data_save')}}" method="post" enctype="multipart/form-data">
			

				<div class="formGroup">
					<div class="inputTitle">
						Mengapa anda ingin bergabung dengan sahabat umkm?
					</div>
					<div class="inputText">
						<textarea type="text" name="kuisioner_mengapa">{{$user->data->kuisioner_mengapa ?? ''}}</textarea>
					</div>
				</div>

				<div class="formGroup">
					<div class="inputTitle">
						Apa yang anda harapkan setelah menjadi member umkm?
					</div>
					<div class="inputText">
						<textarea type="text" name="kuisioner_harapan">{{$user->data->kuisioner_harapan ?? ''}}</textarea>
					</div>
				</div>

				<div class="formGroup">
					<div class="inputTitle">
						<input type="checkbox" name="tos_terima" {{ (isset($user->data->tos_terima) && 1==$user->data->tos_terima) ? 'checked' : ''}} >
						Terima
					</div>
				</div>
				<button type="sumbit" class="submitUserSet button blue">Simpan</button>
			</form>
		</div>
	</div>
</section>

@endsection