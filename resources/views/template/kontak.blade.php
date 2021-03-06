@extends('layouts.publicbase')

@section('content')

<div class="breadcrumb">
	<div class="container">
		<ol itemscope itemtype="http://schema.org/BreadcrumbList">
            <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a itemprop="item" href="{{ route('public_home') }}"><span itemprop="name">Beranda</span></a> <i class="fa fa-angle-right" aria-hidden="true"></i>
                <meta itemprop="position" content="1" />
            </li>
            <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a itemprop="item" href="{{ url('/kontak') }}"><span itemprop="name">Kontak</span></a>
                <meta itemprop="position" content="2" />
            </li>
        </ol>
	</div>
</div>

<div id="contact">
	<div id="map">
	</div>
	<div class="container">
		<div class="row">
			<div class="col-5 contact-address">
				<h3>Sekretariat Sahabat UMKM</h3>
				<p style="margin: 0">Jln. Kebon Kacang Raya No. 25 Tanah Abang</p>
				<p style="margin: 0">Jakarta Pusat, Indonesia</p>
				<p style="margin-bottom: 14px">Kode Pos 10240</p>
				<p style="margin-bottom: 8px"><span class="icon i-phone"></span>&nbsp; (021) 3917399</p>
				<p><span class="icon i-mail"></span>&nbsp; {{ app()->Meta->get('email_info') }}</p>
			</div>
			<div class="col-7 contact-form">
				<h3>Hubungi Kami</h3>
				@if(session('msg'))
				<p>{{ session('msg') }}</p>
				@else
				<form action="{{ route('sendemailcontact') }}" method="post">
    				<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<table>
						<tr>
							<td>
								<label>Nama</label>
								<input type="text" name="nama" placeholder="Masukkan Nama Anda" required="required">
							</td>
							<td>
								<label>E-mail</label>
								<input type="text" name="email" placeholder="Masukkan Alamat E-mail" required="required">
							</td>
						</tr>
						<tr>
							<td>
								<label>Nama Usaha</label>
								<input type="text" name="nama_usaha" placeholder="Masukkan Nama Usaha">
							</td>
							<td>
								<label>Nomor Kontak</label>
								<input type="text" name="telp" placeholder="Masukkan Nomor Telepon" required="required">
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<label>Subyek</label>
								<input type="text" name="subject" placeholder="Masukkan Subyek" required="required">
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<label>Pesan Anda</label>
								<textarea name="pesan" placeholder="Isi Pesan Anda..." required="required"></textarea>
							</td>
						</tr>
						<tr>
							<td colspan="2" style="padding: 0px;">
								<button type="submit">KIRIM</button>
							</td>
						</tr>
					</table>
                	<!-- 
                	<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="text" name="nama" placeholder="nama">
					<input type="email" name="email" placeholder="email">
					<textarea name="pesan" placeholder="pesan"></textarea>
					<button type="sumbit" class="button blue">Kirim</button> 
					-->
				</form>
				@endif
			</div>
		</div>
	</div>
</div>

<script>function initMap(){var e=-6.193724,n=106.817485,o={lat:e,lng:n},a=new google.maps.Map(document.getElementById("map"),{zoom:16,center:o});new google.maps.Marker({position:o,map:a})}</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAyE7KLJT2rPz-8N2Fi6Ump53yabrvNj5g&callback=initMap" async defer></script>
<script src="https://apis.google.com/js/platform.js" async defer></script>
@endsection