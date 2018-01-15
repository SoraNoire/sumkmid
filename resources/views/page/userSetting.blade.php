@extends('layouts.publicbase')

@section('content')
<div class="breadcrumb">
	<div class="container">
		<h2><a href="{{ route('public_home') }}">Beranda</a> <i class="fa fa-angle-right" aria-hidden="true"></i>Profil</h2>
	</div>
</div>
<section id="userSetting">
	<div class="container">
	@if(session('success') == 'true')
		<div class="formAlert alertSuccess">
			<span>Profil berhasil disimpan</span>
			<div class="closeAlert">x</div>
		</div>
	@endif
		<h3>Hallo, {{$var['user']->name}}</h3>
		<div class="leftForm">
			<div class="photoUser">
				<div class="photoPreview" style="background-image:url('{{ $var['user']->foto_profil ?? asset('images/admin.png') }}');">
				</div>
				<div class="inputTrigger" onclick="document.getElementById('inputUserImage').click(); return false;"></div>
				<form id="upldimageuser" action="{{ route('user_update_profile_pict') }}" accept="image/*" enctype="multipart/form-data" method="post">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="file" name="photo" id="inputUserImage" accept="image/x-png,image/gif,image/jpeg">
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
					<input type="text" name="nama"  value="{{$var['user']->name}}">
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
					<input type="email" name="email" value="{{$var['user']->email}}">
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
					<input type="password" name="old_password">
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
					<input type="password" name="new_password" >
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
					<input type="password" name="password_confirmation">
				</div>
			</div>
			<button type="sumbit" class="submitUserSet button blue">Kirim</button>
			</form>
			@if( isset( $var['user']->data ) )
			<div class="editDetail">
				<h3>Edit Detail</h3>
				<small>Edit Detail Data Diri Anda (Tanggal Lahir, Alamat, Telepon, dll)</small>
				<a href="{{ route('user_setting.detail',1) }}"><button class="goToEditDetail button">Menuju ke Laman</button></a>	
			</div>
			@endif
		</div>
	</div>
</section>

@endsection