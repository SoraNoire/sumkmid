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
		
		<div class="leftForm" style="width: 20%;">
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
		<div class="rightForm" style="width: 75%;">




			<form id="form" name="form" action="{{route('SHB.complete_data_save')}}" method="post" enctype="multipart/form-data">
			
				<div class="formGroup pilih_type">
					
					@php
						$chkTdk = ( 'perorangan' == $user->role ) ? 'checked' : '';
						$frmUClass = ( 'perorangan' == $user->role ) ? ' hidden' : '';
						$chkYa = ( 'checked' == $chkTdk ) ? '' : 'checked'; 
					@endphp

					<div>Anda Pengusaha Umkm?</div>
					<div class="left">
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
							<input type="text" name="nama_usaha"  value="{{$user->data->nama_usaha ?? ''}}" placeholder="Nama Usaha">
						</div>
					</div>

					<div class="formGroup">
						<div style="width: 48%;float: left;">
							<div>Jenis Usaha</div>
							<select name="jenis_usaha">
								@foreach ($usaha as $u)
									@if( isset($user->data->jenis_usaha) && $user->data->jenis_usaha == $u  )
										<option selected value="{{$u}}">{{$u}}</option>
									@else
										<option value="{{$u}}">{{$u}}</option>
									@endif
								@endforeach
							</select>
						</div>
						<div style="width: 48%;float: left;">
							<div>Tahun Berdiri</div>
							<div class="inputText">
							<input type="text" name="lama_berdiri" value="{{$user->data->lama_berdiri??''}}" placeholder="2013">
							</div>
						</div>
						<div style="clear: both;"></div>
					</div>

					<div class="formGroup">
						<div style="margin: 5px 0;">
							Informasi Usaha
							<span id="add_info" title="Tambah info usaha" style="cursor: pointer;color: #fff;padding: 2px 8px 4px 8px;border-radius: 10px;background: limegreen;">+</span>
						</div> 
						<div id="info_usaha">

							@php
								$info_usaha = false;
								if( isset($user->data->informasi_usaha) ){
									$info_usaha = json_decode($user->data->informasi_usaha);
								}
								$i = 1;
							@endphp

							@if( $info_usaha )
								@foreach($info_usaha as $key => $iu)
									<div class="info_usaha__item_parent">
										<div class="info_usaha__item">

											<span id="delete_info" onclick="deleteInfo(this)" title="Tambah info usaha" style="cursor: pointer;color: #fff;padding: 1px 8px 2px 8px;border-radius: 23px;background: red;">-</span>

											<select  onchange="triggerInfoName(this)" class="info_usaha__select" id="select-{{$i}}" name="informasi_usaha[{{$i}}]">
												<option {{('website'==$key)?'selected':''}} value="website" > Website</option>
												<option {{('facebook'==$key)?'selected':''}} value="facebook" > Facebook</option>
												<option {{('gplus'==$key)?'selected':''}} value="gplus" > Google+</option>
												<option {{('instagram'==$key)?'selected':''}} value="instagram" > Instagram</option>
												<option {{('twitter'==$key)?'selected':''}} value="twitter" > Twitter</option>
												<option {{('email'==$key)?'selected':''}} value="email" > Email</option>
												<option {{('telepon'==$key)?'selected':''}} value="telepon" > Telepon</option>
											</select>
										</div>
										<div class="info_usaha__item inputText info_usaha__clear">
											<input id="info-{{$i}}" value="{{$iu}}" type="text" name="info[{{$key}}]">
										</div>
										<div class="clear"></div>
									</div>
									@php $i++ @endphp
								@endforeach
							@else
								<div class="info_usaha__item_parent">
									<div class="info_usaha__item">

										<span id="delete_info" onclick="deleteInfo(this)" title="Tambah info usaha" style="cursor: pointer;color: #fff;padding: 1px 8px 2px 8px;border-radius: 23px;background: red;">-</span>

										<select  onchange="triggerInfoName(this)" class="info_usaha__select" id="select-1" name="informasi_usaha[1]">
											<option value=NULL>Pilih</option>
											<option value="website" > Website</option>
											<option value="facebook" > Facebook</option>
											<option value="gplus" > Google+</option>
											<option value="instagram" > Instagram</option>
											<option value="twitter" > Twitter</option>
											<option value="email" > Email</option>
											<option value="telepon" > Telepon</option>
										</select>
									</div>
									<div class="info_usaha__item inputText info_usaha__clear">
										<input id="info-1" type="text" name="info[null]">
									</div>
									<div class="clear"></div>
								</div>
							@endif

						</div>
					</div>

					<div class="formGroup">
						<div class="inputTitle">
							Perkiraan Omzet
						</div>
						<div class="inputText">
							<select name="omzet">
								<option value="1-5" {{ ( isset($user->data->omzet) && '1-5' == $user->data->omzet ) ? 'selected' : '' }} >1-5 Juta</option>
								<option value="5-10" {{ ( isset($user->data->omzet) && '5-10' == $user->data->omzet ) ? 'selected' : '' }}>5-10 Juta</option>
								<option value="10-20" {{ ( isset($user->data->omzet) && '10-20' == $user->data->omzet ) ? 'selected' : '' }}>10-20 Juta</option>
								<option value="20-50" {{ ( isset($user->data->omzet) && '20-50' == $user->data->omzet ) ? 'selected' : '' }}>20-50 Juta</option>
								<option value="50+" {{ ( isset($user->data->omzet) && '50+' == $user->data->omzet ) ? 'selected' : '' }}> 50+ Juta</option>
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
		.info_usaha__item
		{
			width: 48%;
			float: left;
		}
		.info_usaha__item_parent
		{
			background-color: rgba(25,67,222,.1);
			padding: 5px 3px;
		}
		.clear
		{
			clear: both;
		}
		.left
		{
			float: left;
		}
		.tanggal_lahir{
			margin: 0px 5px;
		}
		select{
			padding: 4px;
			width: 90%;
			margin: 2px 0;
		}
		input[type="radio"] {
		    padding: 0;
		    margin: 3px;
		    width: 20px;
		    height: 40px;
		}
		.pilih_type .pilih{
			padding: 13px 4px;
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

		var ai = document.getElementById('add_info');
		ai.onclick = function()
		{
			var s = document.getElementsByClassName('info_usaha__select');
	        var oldInfo = document.getElementById('info_usaha').innerHTML;
	        var d = document.createElement('div');
	        d.className += "info_usaha__item_parent";
	        d.innerHTML = addInfo(s.length+1);
	        document.getElementById('info_usaha').appendChild(d);
	    }

	    function triggerInfoName(el)
	    {
	    	var id = el.id.split('-')[1];
	    	var infoinputTarget = document.getElementById('info-'+id);
	    	infoinputTarget.name = "info["+el.value+"]";
	    }

	    function deleteInfo(el)
	    {
	    	var _el = el.parentElement.parentElement.outerHTML = '';
	    	console.log(_el);
	    }

	    function addInfo(id=2)
	    {
	    	var _html = `<div class="info_usaha__item">
	    						<span id="delete_info" onclick="deleteInfo(this)" title="Tambah info usaha" style="cursor: pointer;color: #fff;padding: 1px 8px 2px 8px;border-radius: 23px;background: red;">-</span>
								<select onchange="triggerInfoName(this)" class="info_usaha__select" id="select-`+id+`" name="informasi_usaha[`+id+`]">
									<option value=NULL>Pilih</option>
									<option value="website" > Website</option>
									<option value="facebook" > Facebook</option>
									<option value="gplus" > Google+</option>
									<option value="instagram" > Instagram</option>
									<option value="twitter" > Twitter</option>
									<option value="email" > Email</option>
									<option value="telepon" > Telepon</option>
								</select>
							</div>
							<div class="info_usaha__item inputText info_usaha__clear">
								<input id="info-`+id+`" type="text" name="info[null]">
							</div>
							<div class="clear">
						</div>`;
			return _html;
	    }

	</script>
</section>

@endsection