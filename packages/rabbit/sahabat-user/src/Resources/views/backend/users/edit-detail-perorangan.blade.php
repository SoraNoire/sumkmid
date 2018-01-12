@extends('blog::layouts.master')

@section('content')
<div class="col-md-12">
	<div class="card">
	    <div class="card-header" data-background-color="green">
	        <h3 class="title">Edit Module</h3>
	    </div>
	    <div class="card-content">

	    	@if( Session::has('message') )
	    		<div class="col-sm-12 alert">
	    			{!! session('message') !!}
	    		</div>
	    	@endif
	        <form method="post" action="{{ route('panel.user__update',$user->id) }}" accept-charset="UTF-8">
	        <input type="hidden" name="_token" value="{{ csrf_token() }}">
	            <div class="row">
	                	<div class="col-md-6 col-sm-12">
			<div class="form-group label-floating">
				<label class="control-label">Nama Lengkap</label>
				<input type="text" class="form-control" name="name" disabled value="{{$user->name??''}}" />
			</div>
			<div class="formGroup ttl">
				<div class="tl">
					<div>
						Tempat Lahir
					</div>
					<div class="inputText">
						<input name="kota_lahir" value="{{ $user->data->kota_lahir ?? '' }}" />
					</div>
				</div>

				<div class="tgll">
					<div> Tanggal Lahir </div>
						<select id="tahun_lahir" class="dropdown3" name="tahun_lahir">
							<option>Tahun</option>
							@for( $i=(date('Y'));$i>=(date('Y')-60);$i-- ) 
								@if( $i == explode('/',($user->data->tanggal_lahir ?? '0/0/0'))[0] )
									<option selected value="{{$i}}" >{{$i}}</option>
								@else
									<option value="{{$i}}" >{{$i}}</option>
								@endif
							@endfor
						</select>
						
						<select class="dropdown3" name="bulan_lahir" onchange="aturTanggal(this)">
							<option>Bulan</option>
							@for($i=1;$i<=12;$i++)
								@if( $i == explode('/',($user->data->tanggal_lahir ?? '0/0/0'))[1] )
									<option selected value="{{$i}}" >{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
								@else
									<option value="{{$i}}" >{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
								@endif
							@endfor
						</select>

						<select id="tanggal_lahir" class="dropdown3" name="tanggal_lahir">
							<option>Tanggal</option>
							@for($i=1;$i<=31;$i++)
								@if( $i == explode('/',($user->data->tanggal_lahir ?? '0/0/0'))[2] )
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
							<option {{ (isset($user->data->provinsi) && $user->data->provinsi == $provinsi->nama_provinsi) ? 'selected' : '' }} id="provinsi{{ $provinsi->id }}" value="{{ $provinsi->nama_provinsi }}">{{ $provinsi->nama_provinsi }}</option>
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
				<div class="formGroup">
					@if($user->data->foto_ktp)
						@php
						$path = storage_path('cr/ktp/'.$user->data->foto_ktp);
						$ktp = file_get_contents($path);
						$type = pathinfo($path, PATHINFO_EXTENSION);
						$data = file_get_contents($path);
						$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
						@endphp
						<input type="hidden" name="foto_ktp_db" value="{{$user->data->foto_ktp}}">
					@endif
					<div>KTP</div>
					<img src="{{$base64}}" height="150" width="300">
					<input type="file" name="foto_ktp">
				</div>
					
				<button type="sumbit" class="submitUserSet button blue">Simpan</button>
			</form>
						</div>  
	            </div>
	            <div class="clearfix"></div>  
	    </div>
	</div>

</div>
<div class="col-sm-12">
	
</div>


@endsection


