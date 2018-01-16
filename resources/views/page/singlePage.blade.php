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
                <a itemprop="item" href="{{ $var['slug'] }}"><span itemprop="name">{{ $var['page'] }}</span></a> <i class="fa fa-angle-right" aria-hidden="true"></i>
                <meta itemprop="position" content="2" />
            </li>
        </ol>
	</div>
</div>

<div id="singlePage">
	<div class="container">
		<div class="row">
			<div class="col-12 pageContent">
				{!! $var['content'] !!}
			</div>
		</div>
	</div>
</div>
@endsection