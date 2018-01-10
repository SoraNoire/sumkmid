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
					<input type="hidden" name="email" id="" value="{{$user->email}}">
					<input type="hidden" name="nama"  value="{{$user->name}}">
					<input type="file" name="photo" id="inputUserImage" accept="image/x-png,image/gif,image/jpeg">
					<input type="submit" style="display:none;">
				</form>
			</div>
		</div>
		<div class="rightForm "">
			<form action="{{route('user-setting.detail.go-update')}}" method="post" enctype="multipart/form-data">
			{{ csrf_field() }}
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
							<select id="tahun_lahir" class="dropdown3" name="tahun_lahir">
								<option value="">Tahun</option>
								@for( $i=(date('Y'));$i>=(date('Y')-60);$i-- ) 
									@if( $i == explode('/',(app()->OAuth::Auth()->data->tanggal_lahir ?? '0/0/0'))[0] )
										<option selected value="{{$i}}" >{{$i}}</option>
									@else
										<option value="{{$i}}" >{{$i}}</option>
									@endif
								@endfor
							</select>
							
							<select class="dropdown3" name="bulan_lahir" onchange="aturTanggal(this)">
								<option value="">Bulan</option>
								@for($i=1;$i<=12;$i++)
									@if( $i == explode('/',(app()->OAuth::Auth()->data->tanggal_lahir ?? '0/0/0'))[1] )
										<option selected value="{{$i}}" >{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
									@else
										<option value="{{$i}}" >{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
									@endif
								@endfor
							</select>

							<select id="tanggal_lahir" class="dropdown3" name="tanggal_lahir">
								<option value="">Tanggal</option>
								@for($i=1;$i<=31;$i++)
									@if( $i == explode('/',(app()->OAuth::Auth()->data->tanggal_lahir ?? '0/0/0'))[2] )
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
				<div class="formGroup pilih_type radioAskUmkm">
					@php
						$chkTdk = ( 'perorangan' == $user->role ) ? 'checked' : '';
						$frmUClass = ( 'perorangan' == $user->role ) ? ' hidden' : '';
						$chkYa = ( 'checked' == $chkTdk ) ? '' : 'checked'; 
					@endphp
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
						<div class="jnsUsaha">
							<div>Jenis Usaha</div>
							<select class="selectJnsUsaha" name="jenis_usaha">
								@foreach ($usaha as $u)
									@if( isset($user->data->jenis_usaha) && $user->data->jenis_usaha == $u  )
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
							<input type="text" name="lama_berdiri" value="{{$user->data->lama_berdiri??''}}" placeholder="2013">
							</div>
						</div>
						<div style="clear: both;"></div>
					</div>

					<div class="formGroup">
						<div class="infoUsaha">
							Informasi Usaha
							<span id="add_info" title="Tambah info usaha">+</span>
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

											<span id="delete_info" onclick="deleteInfo(this)" title="Tambah info usaha">-</span>

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
										<span id="delete_info" onclick="deleteInfo(this)" title="Tambah info usaha">-</span>
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
							<select class="omzetSelect" name="omzet">
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

				<div style="margin-top: 20px;" class="completion3">
				<div class="formGroup">
					<div class="inputTitle">
						Apakah anda sudah memiliki usaha menetap bidang UMKM?
					</div>
					<div>
						@php
							$ya = "Spesifik / Sudah tetap";
							$tidak = "Berganti-ganti / Belum Tetap";
							$chkYa = '';
							$chkTidak = 'checked';
							if(isset($user->data->usaha_tetap) && $ya == $user->data->usaha_tetap){$chkYa = 'checked';$chkTidak = '';}
						@endphp
						<div>
							<div class="left"><input {{$chkYa}} type="radio" name="usaha_tetap" value="Spesifik / Sudah tetap"></div>
							<div class="pilih answers">{{$ya}}</div>
						</div>
						<div style="clear: both;"></div>
						<div>
							<div class="left">
								<input {{$chkTidak}} type="radio" name="usaha_tetap" value="Berganti-ganti / Belum Tetap">
							</div>
							<div class="pilih answers">{{$tidak}}</div>
							<div style="clear: both;"></div>
						</div>
					</div>
				</div>

				<div class="formGroup">
					<div class="inputTitle">
						Izin Usaha?
					</div>
					<div>
						@php
							$ya = "Lengkap sesuai kebutuhan bisnis ( SIUP, TDP, NPWP, DLL)";
							$tidak = "Belum Lengkap";
							$chkYa = '';
							$chkTidak = 'checked';
							if(isset($user->data->kelengkapan_dokumen) && $ya == $user->data->kelengkapan_dokumen){$chkYa = 'checked';$chkTidak = '';}
						@endphp
						

						<div>
							<div class="left">
								<input {{$chkYa}} type="radio" name="kelengkapan_dokumen" value="{{$ya}}">
							</div>
							<div class="pilih answers">{{$ya}}</div>
						</div>
						<div style="clear: both;"></div>
						<div>
							<div class="left">
								<input {{$chkTidak}} type="radio" name="kelengkapan_dokumen" value="{{$tidak}}">
							</div>
							<div class="pilih answers">{{$tidak}}</div>
							<div style="clear: both;"></div>
						</div>
					</div>
				</div>

				<div class="formGroup">
					<div class="inputTitle">
						Asset tempat usaha
					</div>
					<div>
						@php
							$ya = "Tetap (Milik sendiri)";
							$tidak = "Belum tetap (kontrak, sewa, dll)";
							$chkYa = '';
							$chkTidak = 'checked';
							if(isset($user->data->tempat_usaha) && $ya == $user->data->tempat_usaha){$chkYa = 'checked';$chkTidak = '';}
						@endphp
						

						<div>
							<div class="left">
								<input {{$chkYa}} type="radio" name="tempat_usaha" value="{{$ya}}">
							</div>
							<div class="pilih answers">{{$ya}}</div>
						</div>
						<div style="clear: both;"></div>
						<div>
							<div class="left">
								<input {{$chkTidak}} type="radio" name="tempat_usaha" value="{{$tidak}}">
							</div>
							<div class="pilih answers">{{$tidak}}</div>
							<div style="clear: both;"></div>
						</div>

					</div>
				</div>

				<div class="formGroup">
					<div class="inputTitle">
						Administrasi Keuangan
					</div>
					<div>
						@php
							$ya = "Sudah teratur ( terpisah dengan keuangan pribadi / keluarga )";
							$tidak = "Belum teratur ( masih bercampur dengan keuangan pribadi / keluarga )";
							$chkYa = '';
							$chkTidak = 'checked';
							if(isset($user->data->adm_keuangan) && $ya == $user->data->adm_keuangan){$chkYa = 'checked';$chkTidak = '';}
						@endphp
						

						<div>
							<div class="left">
								<input {{$chkYa}} type="radio" name="adm_keuangan" value="{{$ya}}">
							</div>
							<div class="pilih answers">{{$ya}}</div>
						</div>
						<div style="clear: both;"></div>
						<div>
							<div class="left">
								<input {{$chkTidak}} type="radio" name="adm_keuangan" value="{{$tidak}}">
							</div>
							<div class="pilih answers">{{$tidak}}</div>
							<div style="clear: both;"></div>
						</div>

					</div>
				</div>

				<div class="formGroup">
					<div class="inputTitle">
						Akses perbankan
					</div>
					<div>
						@php
							$ya = "Sudah ada akses ke perbankan / sudah pernah melakukan pinjaman ( berupa apapun bentuknya ) ke bank";
							$tidak = "Belum ada akses ke perbankan / belum pernah melakukan pinjaman ( berupa apapun bentuknya ) ke bank";
							$chkYa = '';
							$chkTidak = 'checked';
							if(isset($user->data->akses_perbankan) && $ya == $user->data->akses_perbankan){$chkYa = 'checked';$chkTidak = '';}
						@endphp

						
						<div>
							<div class="left">
								<input {{$chkYa}} type="radio" name="akses_perbankan" value="{{$ya}}">
							</div>
							<div class="pilih answers">{{$ya}}</div>
						</div>
						<div style="clear: both;"></div>
						<div>
							<div class="left">
								<input {{$chkTidak}} type="radio" name="akses_perbankan" value="{{$tidak}}">
							</div>
							<div class="pilih answers">{{$tidak}}</div>
							<div style="clear: both;"></div>
						</div>
					</div>
				</div>
				</div>
				<button type="sumbit" class="submitUserSet button blue">Simpan dan Lanjutkan</button>
			</form>
		</div>
	</div>
</section>
<script type="text/javascript">
	
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
</script>

@endsection