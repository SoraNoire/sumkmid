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
                <a itemprop="item" href="{{ url('/mentor') }}"><span itemprop="name">Mentor</span></a> <i class="fa fa-angle-right" aria-hidden="true"></i>
                <meta itemprop="position" content="2" />
            </li>
        </ol>
	</div>
</div>

<div id="mentor-archive">
	<div class="container">
		<div class="row">
			@foreach($var['mentors'] as $mentor)
			<div class="cst-4-col">
			<div class="item">
				<div class="item-image" style="background-image: url('{{$mentor->foto_profil}}');"></div>
				<div class="item-title">
					<a href="{{ route('public_mentor_single',$mentor->username) }}" title="">
						{!! str_limit(html_entity_decode(strip_tags($mentor->name)), 40) !!}
					</a>
					<a href="{{ route('public_mentor_single',$mentor->username) }}" class="sub" title="CEO Anak Mas">{{ $mentor->jabatan }}</a>
				</div>		
			</div>
			</div>
			@endforeach
		</div>
	</div>
</div>
@endsection