@extends('layouts.publicbase')

@section('content')

<div class="breadcrumb">
	<div class="container">
		<h2>Kontak</h2>
	</div>
</div>

<section id="userSetting">
	<div class="container">
<<<<<<< HEAD
=======
		<div class="formAlert alertSuccess">
			<span>Profil berhasil disimpan</span>
		</div>
>>>>>>> eef18f9b3ea150b02f09ebe8312b3f1a12c57af6
		<h3>Hallo, Syarief Navi</h3>
		<div class="leftForm">
			<div class="photoUser">
				<div class="photoPreview">
					<img src="{{ asset('img/userImage.png') }}" alt="user-image">
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
					<input type="text" name="name" id="">
				</div>
			</div>
			<div class="formGroup withError">
				<div class="inputTitle">
					Alamat Email
				</div>
				<span class="tootTip">alamat email tidak valid</span>
				<div class="inputText">
					<input type="text" name="name" id="">
				</div>
			</div>
			<div class="formGroup">
				<div class="inputTitle">
					Nomor Telepon
				</div>
				<div class="inputText">
					<input type="text" name="name" id="">
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