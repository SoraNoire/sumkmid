@extends('layouts.publicbase')

@section('content')

<div class="breadcrumb">
	<div class="container">
		<h2>Mentor</h2>
	</div>
</div>

<div id="mentor-archive">
	<div class="container">
		<div class="row">
		@for ($i = 0; $i < 12; $i++)
		<div class="cst-4-col">
		<div class="item">
			<div class="item-image" style="background-image: url('/img/Group.png');"></div>
        	<div class="item-title">
        		<a href="#" title="Dito Arifin">
        			{!! str_limit(html_entity_decode(strip_tags('Bagas Dicko Alfahmi Haha Hihi Huhu Heho Haha Hihi Huhu Heho')), 40) !!}
        		</a>
        		<a href="#" class="sub" title="CEO Anak Mas">CEO Anak Mas</a>
        	</div>		
		</div>
		</div>
		@endfor
		</div>
	</div>
</div>
@endsection