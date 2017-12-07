@extends('layouts.publicbase')

@section('content')

<div class="breadcrumb">
	<div class="container">
		<h2>Kontak</h2>
	</div>
</div>

<section id="userSetting">
	<div class="container">
		<!-- <div class="formAlert alertSuccess">
			<span>Profil berhasil disimpan</span>
			<div class="closeAlert">x</div>
		</div> -->
		<h3>Hallo, {{app()->SSO->Auth()->name}}</h3>
		<div class="leftForm">
			<div class="photoUser">
				<div class="photoPreview" style="background-image:url('{{ app()->SSO->Auth()->foto_profil }}');">
				</div>
				<div class="inputTrigger" onclick="document.getElementById('inputUserImage').click(); return false;"></div>
				<input type="file" name="photo" id="inputUserImage">
			</div>
		</div>
		<div class="rightForm">
			<div class="formGroup">
				<div class="inputTitle">
					Nama Lengkap
				</div>
				<div class="inputText">
					<input type="text" name="name" id="" value="{{app()->SSO->Auth()->name}}">
				</div>
			</div>
			<!-- //withError -->
			<div class="formGroup">
				<div class="inputTitle">
					Alamat Email
				</div>
				<!--//with error <span class="tootTip">alamat email tidak valid</span> -->
				<div class="inputText">
					<input type="text" name="name" id="" value="{{app()->SSO->Auth()->email}}">
				</div>
			</div>
			<div class="formGroup">
				<div class="inputTitle">
					Nomor Telepon
				</div>
				<div class="inputText">
					<input type="text" name="name" id="" value="{{app()->SSO->Auth()->phone_number}}">
				</div>
			</div>
			<h5 class="formSprt">Rubah Sandi</h5>
			<div class="formGroup">
				<div class="inputTitle">
					Sandi Lama
				</div>
				<div class="inputText">
					<input type="password" name="name" id="">
				</div>
			</div>
			<div class="formGroup">
				<div class="inputTitle">
					Sandi Baru
				</div>
				<div class="inputText">
					<input type="password" name="name" id="">
				</div>
			</div>
			<div class="formGroup">
				<div class="inputTitle">
					Ulangi Sandi Baru
				</div>
				<div class="inputText">
					<input type="password" name="name" id="">
				</div>
			</div>
			<button type="sumbit" class="submitUserSet button blue">Kirim</button>
		</div>
	</div>
</section>

@endsection