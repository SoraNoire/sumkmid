@extends('layouts.publicbase')

@section('content')

<div class="breadcrumb">
	<div class="container">
		<h2>Mentor</h2>
	</div>
</div>

<div id="mentor-list">
	<div class="container">

		@for ($i = 0; $i <=12; $i++)
		<div class="item">
			<div class="item-image" style="background-image: url('/img/Group.png');"></div>
        	<div class="item-title">
        		<a href="#" title="Dito Arifin">Dito Arifin</a>
        		<a href="#" class="sub" title="CEO Anak Mas">CEO Anak Mas</a>
        	</div>		
		</div>
		@endfor

	</div>
</div>
@endsection