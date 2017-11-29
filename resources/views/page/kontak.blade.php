@extends('layouts.publicbase')

@section('content')

<div class="breadcrumb">
	<div class="container">
		<h2>Mentor</h2>
	</div>
</div>

<div id="contact">
	<div id="map"></div>
	<div class="container">
		<div class="the-row">
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
				<form action="" method="post">
					<input type="text" name="nama" placeholder="nama">
					<input type="email" name="email" placeholder="email">
					<textarea name="pesan" placeholder="pesan"></textarea>
					<button type="sumbit" class="button blue">Kirim</button>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection