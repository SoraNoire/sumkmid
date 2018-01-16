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
                <a itemprop="item" href="{{ url('/tentang-kami') }}"><span itemprop="name">Tentang Kami</span></a> <i class="fa fa-angle-right" aria-hidden="true"></i>
                <meta itemprop="position" content="2" />
            </li>
        </ol>
	</div>
</div>

<div id="tentang">
	<div class="container">
		<div class="row">
			<div class="col-12 tentangContent">
				{!! $var['data']->content !!}
			</div>
		</div>
	</div>
</div>
@endsection