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
		<div class="rightForm">
			@if ($errors->any())
			    <div class="alert alert-danger">
			        <ul>
			            @foreach ($errors->all() as $error)
			                <li>{{ $error }}</li>
			            @endforeach
			        </ul>
			    </div>
			@endif
			<form id="form" name="form" action="{{route('SHB.complete_data_save')}}" method="post" enctype="multipart/form-data">
				<input type="hidden" name="step" value="step2">
				<div class="formGroup pilih_type radioAskUmkm">
					@php
						$chkTdk = ( 'perorangan' == $user->role ) ? 'checked' : '';
						$frmUClass = ( 'perorangan' == $user->role ) ? ' hidden' : '';
						$chkYa = ( 'checked' == $chkTdk ) ? '' : 'checked'; 
					@endphp

					<div>Anda Pengusaha Umkm?</div>
					<div class="left ">
						<div class="left"><input {{$chkYa}} type="radio" name="type_user" value="ya"></div> <div class="pilih left">Ya</div>
					</div>
					<div class="left">
						<div class="left"><input {{$chkTdk}} type="radio" name="type_user" value="tidak"></div><div class="pilih left">Tidak</div>
					</div>
					<div style="clear: both;"></div>
			</div>

				<div id="form-pengusaha" class="{{$frmUClass}}">
				
					<div class="formGroup">
						<div class="inputTitle">
							Nama Usaha
						</div>
						<div class="inputText">
							<input type="text" name="nama_usaha"  value="{{$user->data->nama_usaha ?? old('nama_usaha') ?? ''}}" placeholder="Nama Usaha">
						</div>
					</div>

					<div class="formGroup">
						<div class="jnsUsaha">
							<div>Jenis Usaha</div>
							<select class="selectJnsUsaha" name="jenis_usaha">
								@foreach ($usaha as $u)
									@if( old('jenis_usaha') == $u  )
										<option selected value="{{$u}}">{{$u}}</option>
									@elseif( isset($user->data->jenis_usaha) && $user->data->jenis_usaha == $u  )
										<option selected value="{{$u}}">{{$u}}</option>
									@else
										<option value="{{$u}}">{{$u}}</option>
									@endif
								@endforeach
							</select>
						</div>
						<div class="tahunBerdiri">
							<div>Tahun Berdiri</div>
							<div class="inputText">
							<input type="text" name="tahun_berdiri" value="{{$user->data->tahun_berdiri ?? old('tahun_berdiri') ??''}}" placeholder="2013">
							</div>
						</div>
						<div style="clear: both;"></div>
					</div>
					<div class="formGroup">
						<div class="infoUsaha">
							Informasi Usaha
						</div> 
						<div id="info_usaha" class="inputText">
							<div class="addInfoWrap">
								<button type="button" class="addInfoTrigger"><i class="fa fa-plus" aria-hidden="true"></i>Tambah Informasi Usaha</button>
								<ul class="infoOption">
									<li id="addInfoWebsite" class="addInfo infoLink {{ (isset(old('info_usaha')['Website']) ? 'hidden' : (isset($user->data->info_usaha->Website) ? 'hidden' : '')) }}">Website</li>
									<li id="addInfoEmail" class="addInfo infoEmail {{ (isset(old('info_usaha')['Email']) ? 'hidden' : (isset($user->data->info_usaha->Email) ? 'hidden' : '')) }}">Email</li>
									<li id="addInfoTelepon" class="addInfo infoPhone {{ (isset(old('info_usaha')['Telepon']) ? 'hidden' : (isset($user->data->info_usaha->Telepon) ? 'hidden' : '')) }}">Telepon</li>
									<li id="addInfoFacebook" class="addInfo infoLink {{ (isset(old('info_usaha')['Facebook']) ? 'hidden' : (isset($user->data->info_usaha->Facebook) ? 'hidden' : '')) }}">Facebook</li>
									<li id="addInfoInstagram" class="addInfo infoLink {{ (isset(old('info_usaha')['Instagram']) ? 'hidden' : (isset($user->data->info_usaha->Instagram) ? 'hidden' : '')) }}">Instagram</li>
									<li id="addInfoGooglePlus" class="addInfo infoLink {{ (isset(old('info_usaha')['GooglePlus']) ? 'hidden' : (isset($user->data->info_usaha->GooglePlus) ? 'hidden' : '')) }}">Google Plus</li>
									<li id="addInfoTwitter" class="addInfo infoLink {{ (isset(old('info_usaha')['Twitter']) ? 'hidden' : (isset($user->data->info_usaha->Twitter) ? 'hidden' : '')) }}">Twitter</li>
								</ul>	
							</div>
							<div class="addedInfo">
								@if(old('info_usaha') || $user->data->info_usaha)
									@if($user->data->info_usaha)
										@php
											$goLoop = $user->data->info_usaha;
										@endphp
									@else
										@php
											$goLoop = old('info_usaha');
										@endphp
									@endif

									@foreach($goLoop as $key => $info)
										<div class="formGroup">
											<div class="inputTitle">
												{{ ($key == 'GooglePlus' ? 'Google Plus' : $key) }} :
											</div>
											<div class="inputText">
												<input type="text" name="info_usaha[{{ $key }}]"  value="{{ $info }}" placeholder="{{ $key }} . . .">
											</div>
											<div id="close{{ $key }}" class="close"><i class="fa fa-times" aria-hidden="true"></i></div>
										</div>
									@endforeach
								@endif
							</div>
						</div>
					</div>

					<div class="formGroup">
						<div class="inputTitle">
							Perkiraan Omzet
						</div>
						<div class="inputText">
							<select class="omzetSelect" name="omzet">
								<option {{ (old('omzet') == '1-5') ? 'selected' : '' }} value="1-5" {{ ( isset($user->data->omzet) && '1-5' == $user->data->omzet ) ? 'selected' : '' }} >1-5 Juta</option>
								<option {{ (old('omzet') == '5-10') ? 'selected' : '' }} value="5-10" {{ ( isset($user->data->omzet) && '5-10' == $user->data->omzet ) ? 'selected' : '' }}>5-10 Juta</option>
								<option {{ (old('omzet') == '10-20') ? 'selected' : '' }} value="10-20" {{ ( isset($user->data->omzet) && '10-20' == $user->data->omzet ) ? 'selected' : '' }}>10-20 Juta</option>
								<option {{ (old('omzet') == '20-50') ? 'selected' : '' }} value="20-50" {{ ( isset($user->data->omzet) && '20-50' == $user->data->omzet ) ? 'selected' : '' }}>20-50 Juta</option>
								<option {{ (old('omzet') == '50+') ? 'selected' : '' }} value="50+" {{ ( isset($user->data->omzet) && '50+' == $user->data->omzet ) ? 'selected' : '' }}> 50+ Juta</option>
							</select>
						</div>
					</div>


				</div>
				<div>KTP</div>
				<input type="file" name="foto_ktp" id="KTPTRIGGER" accept="image/*">
				<div class="previewKTP">
					<img id="PREVIEW" src="{{ asset('images/ktpdefault.jpg') }}">
				</div>
				<button type="sumbit" class="submitUserSet button blue">Simpan</button>
			</form>
		</div>
	</div>
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