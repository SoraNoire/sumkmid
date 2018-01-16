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

<section id="userSetting">
	<div class="container">
		<!-- <div class="formAlert alertSuccess">
			<span>Profil berhasil disimpan</span>
			<div class="closeAlert">x</div>
		</div> -->
		<div class="leftForm">
			<div class="photoUser">
				<div class="photoPreview" style="background-image:url('{{$var['mentors']->avatar}}');">
				</div>
			</div>
		</div>
		<div class="rightForm" id="singleMentor">
            <h3>
                {{$var['mentors']->name}}
                <small>{{$var['mentors']->jabatan}}</small>
            </h3>
            <p>{{$var['mentors']->description}}</p>
            <ul>
                @if($var['mentors']->medsos_fb)
                <li><a target="_blank" href="{{$var['mentors']->medsos_fb}}"><span class="icon i-fb"></span></a></li>
                @endif
                @if($var['mentors']->medsos_twitter)
                <li><a target="_blank" href="{{$var['mentors']->medsos_twitter}}"><span class="icon i-tw"></span></a></li>
                @endif
                @if($var['mentors']->medsos_linkedin)
                <li><a target="_blank" href="{{$var['mentors']->medsos_linkedin}}"><span class="icon i-linkedin"></span></a></li>
                @endif
                @if($var['mentors']->medsos_ig)
                <li><a target="_blank" href="{{$var['mentors']->medsos_ig}}"><span class="icon i-ig"></span></a></li>
                @endif
                @if($var['mentors']->medsos_googleplus)
                <li><a target="_blank" href="{{$var['mentors']->medsos_googleplus}}"><span class="icon i-gp"></span></a></li>
                @endif
            </ul>
		</div>
</section>

@endsection