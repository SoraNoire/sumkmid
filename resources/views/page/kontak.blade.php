@extends('layouts.publicbase')

@section('content')

<div class="breadcrumb">
	<div class="container">
		<h2>Mentor</h2>
	</div>
</div>

<div id="contact">
	<div id="map">
	</div>
	<div class="container">
		<div class="row">
			<div class="col-6 contact-address">
				<h3>Head Office</h3>
				<p style="margin: 0">Jln. Kebon Kacang Raya No. 25 Tanah Abang</p>
				<p style="margin: 0">Jakarta Pusat, Indonesia</p>
				<p style="margin-bottom: 14px">Kode Pos 10240</p>
				<p style="margin-bottom: 8px"><img src="/img/contact-telephone-icon.svg">&nbsp; (021) 3917399</p>
				<p><img src="/img/contact-mail-icon.svg">&nbsp; info@mdirect.id</p>
			</div>
			<div class="col-6 contact-form">
				<h3>Leave a message</h3>
				@if(session('msg'))
				<p>{{ session('msg') }}</p>
				@else
				<form action="{{ route('sendemailcontact') }}" method="post">
                	<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="text" name="nama" placeholder="nama">
					<input type="email" name="email" placeholder="email">
					<textarea name="pesan" placeholder="pesan"></textarea>
					<button type="sumbit" class="button blue">Kirim</button>
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