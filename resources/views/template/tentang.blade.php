@extends('layouts.publicbase')

@section('content')

<div class="breadcrumb">
	<div class="container">
		<h2><a href="{{ route('public_home') }}">Beranda</a> <i class="fa fa-angle-right" aria-hidden="true"></i>Tentang Kami</h2>
	</div>
</div>

<div id="tentangKami">
	<div class="container">
		<div class="row">
			<div class="col-7 tentangDesc">
				<p>Seiring dengan berkembangnya jumlah pelaku industri UMKM di Indonesia, turut memicu perkembangan terbentuknya komunitas UMKM. Namun, persoalan klasik seputar pembinaan dan pengembangan usaha masih tetap melekat bagi para pelaku UMKM Indonesia karena masih banyak kebutuhan dan masalah mereka yang belum terakomodir dan tercapai solusinya. Karena jika dikembangkan secara terarah dan terstuktur, UMKM dapat menjadi pilar ekonomi bangsa Indonesia.</p>
				<p>Dengan latar belakang itulah komunitas Sahabat UMKM dibentuk sebagai komunitas yang mempertemukan antara Pelaku UMKM dan Profesional Kewirausahaan. Dengan prinsip komunikasi positif dan saling membangun, Sahabat UMKM menjadi sarana penyampaian ilmu dan wadah interaksi bagi para pelaku usaha untuk saling berkontribusi, menyampaikan pemikiran, dan berbagi pengalaman dalam </p>
			</div>
		</div>
		<div class="row visiMisi">
			<div class="col-6 visi">
				<h3>VISI</h3>
				<p>Mewujudkan UMKM Indonesia yang profesional dan berdaya saing di pasar lokal maupun internasional, sebagai pilar ekonomi bangsa Indonesia.</p>
			</div>
			<div class="col-6 misi">
				<h3>MISI</h3>
				<ul>
					<li>Menjadi wadah sosialisasi, komunikasi, dan berbagi ilmu antara para pelaku UMKM Indonesia.</li>
					<li>Menjembatani pelaku UMKM Indonesia dengan pihak pemerintah dan swasta yang memiliki program bantuan dan pembinaan UMKM.</li>
					<li>Membawa UMKM Indonesia ke pasar digital.</li>
				</ul>
			</div>
		</div>
	</div>
</div>

@endsection