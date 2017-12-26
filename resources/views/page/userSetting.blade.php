@extends('layouts.publicbase')

@section('content')
<div class="breadcrumb">
	<div class="container">
		<h2><a href="{{ route('public_home') }}">Home</a> <i class="fa fa-angle-right" aria-hidden="true"></i>Profil</h2>
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
		<h3>Hallo, {{app()->OAuth->Auth()->name}}</h3>
		<div class="leftForm">
			<div class="photoUser">
				<div class="photoPreview" style="background-image:url('{{ app()->OAuth->Auth()->foto_profil }}');">
				</div>
				<div class="inputTrigger" onclick="document.getElementById('inputUserImage').click(); return false;"></div>
				<form id="upldimageuser" action="{{ route('user_update_profile_pict') }}" accept="image/*" enctype="multipart/form-data" method="post">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="email" id="" value="{{app()->OAuth->Auth()->email}}">
					<input type="hidden" name="nama"  value="{{app()->OAuth->Auth()->name}}">
					<input type="file" name="photo" id="inputUserImage">
					<input type="submit" style="display:none;">
				</form>
			</div>
		</div>
		<div class="rightForm ">
			<form action="{{ route('user_setting_save') }}" method="post">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="formGroup {{ (session('warnName') == 'name') ? 'withError' : '' }}">
				<div class="inputTitle">
					Nama Lengkap
				</div>
				@if(session('warnName') == 'name')
					<span class="tootTip">{{session('warnMsg')}}</span>
				@endif
				<div class="inputText">
					<input type="text" name="nama"  value="{{app()->OAuth->Auth()->name}}">
				</div>
			</div>
			<!-- //withError -->
			<div class="formGroup {{ (session('warnName') == 'email') ? 'withError' : '' }}">
				<div class="inputTitle">
					Alamat Email
				</div>
				@if(session('warnName') == 'email')
					<span class="tootTip">{{session('warnMsg')}}</span>
				@endif
				<!--//with error <span class="tootTip">alamat email tidak valid</span> -->
				<div class="inputText">
					<input type="email" name="email" id="" value="{{app()->OAuth->Auth()->email}}">
				</div>
			</div>
			<div class="formGroup {{ (session('warnName') == 'nomorTelepon') ? 'withError' : '' }}">
				<div class="inputTitle">
					Nomor Telepon
				</div>
				@if(session('warnName') == 'nomorTelepon')
					<span class="tootTip">{{session('warnMsg')}}</span>
				@endif
				<div class="inputText">
					<input type="text" name="nomorTelepon" id="" value="{{app()->OAuth->Auth()->phone_number}}">
				</div>
			</div>
			<h5 class="formSprt">Rubah Sandi</h5>
			<div class="formGroup {{ (session('warnName') == 'oldPass') ? 'withError' : '' }}">
				<div class="inputTitle">
					Sandi Lama
				</div>
				@if(session('warnName') == 'oldPass')
					<span class="tootTip">{{session('warnMsg')}}</span>
				@endif
				<div class="inputText">
					<input type="password" name="old_password" id="">
				</div>
			</div>
			<div class="formGroup {{ (session('warnName') == 'newPass') ? 'withError' : '' }}">
				<div class="inputTitle">
					Sandi Baru
				</div>
				@if(session('warnName') == 'newPass')
					<span class="tootTip">{{session('warnMsg')}}</span>
				@endif
				<div class="inputText">
					<input type="password" name="new_password" id="">
				</div>
			</div>
			<div class="formGroup {{ (session('warnName') == 'password_confirmation') ? 'withError' : '' }}">
				<div class="inputTitle">
					Ulangi Sandi Baru
				</div>
				@if(session('warnName') == 'password_confirmation')
					<span class="tootTip">{{session('warnMsg')}}</span>
				@endif
				<div class="inputText">
					<input type="password" name="password_confirmation" id="">
				</div>
			</div>
			<button type="sumbit" class="submitUserSet button blue">Kirim</button>
			</form>
		</div>
	</div>
</section>

@endsection