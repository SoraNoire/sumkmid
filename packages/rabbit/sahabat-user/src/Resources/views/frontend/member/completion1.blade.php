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
					<div>
						<div class="">
							Kota
						</div>
						<div class="">
							<input name="kota_lahir" value="{{ app()->OAuth::Auth()->data->kota_lahir ?? '' }}" />
						</div>
					</div>

					<div>
						<div class="">
							Tahun
						</div>
						<div class="">
							<select class="" name="tahun_lahir">
								@for( $i=(date('Y')-60);$i<=(date('Y'));$i++ ) 
									@if( $i == explode('/',(app()->OAuth::Auth()->data->tanggal_lahir ?? '0/0/0'))[0] )
										<option selected value="{{$i}}" >{{$i}}</option>
									@else
										<option value="{{$i}}" >{{$i}}</option>
									@endif
								@endfor
							</select>
						</div>
					</div>
					<div>
						<div class="">
							Bulan
						</div>
						<div class="">
							<select class="" name="bulan_lahir">
								@for($i=1;$i<=12;$i++)
									@if( $i == explode('/',(app()->OAuth::Auth()->data->tanggal_lahir ?? '0/0/0'))[1] )
										<option selected value="{{$i}}" >{{$i}}</option>
									@else
										<option value="{{$i}}" >{{$i}}</option>
									@endif
								@endfor
							</select>
						</div>
					</div>
					<div>
						<div class="">
							Tanggal
						</div>
						<div class="">
							<select class="" name="tanggal_lahir">
								@for($i=1;$i<=31;$i++)
									@if( $i == explode('/',(app()->OAuth::Auth()->data->tanggal_lahir ?? '0/0/0'))[2] )
										<option selected value="{{$i}}" >{{$i}}</option>
									@else
										<option value="{{$i}}" >{{$i}}</option>
									@endif
								@endfor
							</select>
						</div>
					</div>
					<div style="clear: both;"></div>
			</div>

				<div class="formGroup">
					<div class="inputTitle">
						Alamat Lengkap
					</div>
					<div class="inputText">
						<input type="text" name="alamat"  value="{{$user->data->alamat ?? ''}}" placeholder="Alamat Lengkap">
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

				<button type="sumbit" class="submitUserSet button blue">Simpan</button>
			</form>
		</div>
	</div>
</section>

@endsection