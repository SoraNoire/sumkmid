<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="UTF-8">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- csrf key -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sahabat UMKM</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('img/logo-ico.png') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}?v=1.1.0">
</head>
<body>
	<!-- header -->
    <header>
        <section class="headerTop">
            <div class="container">
                <div class="the-row">
                    <div class="col-3 mainLogo">
                        <a href="{{ route('public_home') }}"><img src="{{ asset('img/logo.png') }}" alt="sahabat-umkm-logo"></a>
                    </div>
                    <nav class="col-9 topNav">
                        <div class="navWrapper">
	                        <div class="burgerBtn"></div>
	                        <ul>
                                <li>
	                                <a href="{{ route('public_event') }}">
	                                    Event
	                                </a>
	                            </li>
                                <li>
	                                <a href="{{ route('public_mentor') }}">
	                                    Mentor
	                                </a>
	                            </li>
                                <li>
	                                <a href="{{ route('public_galeri') }}">
	                                    Galeri
	                                </a>
	                            </li>
                                <li>
	                                <a href="#">
	                                    Tanya Jawab
	                                </a>
	                            </li>
                                <li>
	                                <a href="{{ route('public_kontak') }}">
	                                    Kontak
	                                </a>
                                </li>
                               @if(app()->OAuth->Auth())
                                <li class="userNavSetting">
                                    <span>{{app()->OAuth->Auth()->name}}</span>
                                    <div class="goToProfile">
                                        <span id="profileTrigger" class="icon i-cog"></span>
                                    </div>
                                    <ul>
                                        @if('admin' == app()->OAuth->Auth()->role)
                                        <li><a href="{{route('panel.dashboard')}}">Dashboard</a></li>
                                        @endif
                                        <li><a href="{{route('user_setting')}}">Edit Profile</a></li>
                                        <li><a href="{{route('OA.logout')}}">Logout</a></li>
                                    </ul>
                                </li>
                                @else
                                <li class="loginButton">
                                    <a href="{{ route('OA.register') }}" class="button">
                                        Daftar
                                    </a>
                                </li>
                                @endif
	                        </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </section>
    </header>
    <!-- end of header -->

 	@yield('content')

 	<!-- footer -->
	<section id="newsletter" class="blue-bg">
        <div class="container">
        	<span>Gabung bersama SahabatUMKM.id dan dapatkan ribuan benefit GRATIS!!!</span>
        	<form class="newsletter-form">
        		<input type="email" name="email_subscribe" placeholder="Subscribe our newsletter" required="required">
            	<button id="submit_newsletter">daftar</button>
        	</form>
        </div>
	</section>

 	<footer>
 		<div class="container">
 			<div class="footer-logo">
 				<a href="#">
 					<img src="{{ asset('img/footer-logo.png') }}" alt="footer-logo-sahabat-umkm">
 				</a>
 			</div>
 			<div class="footer-socmed">
 				<ul>
                    <li><a target="_blank" href="#" style="background-image: url('/img/home-facebook-logo.svg');"></a></li>
                    <li><a target="_blank" href="#" style="background-image: url('/img/home-twitter-logo.svg');"></a></li>
                    <li><a target="_blank" href="#" style="background-image: url('/img/home-youtube-logo.svg');"></a></li>
                    <li><a target="_blank" href="#" style="background-image: url('/img/home-instagram-logo.svg');"></a></li>
 				</ul>
 			</div>
 			<div class="footer-nav">
 				<ul>
                    <li>
                        <a href="{{ route('public_event') }}">
                            Event
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('public_mentor') }}">
                            Mentor
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('public_galeri') }}">
                            Galeri
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            Tanya Jawab
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('public_kontak') }}">
                            Kontak
                        </a>
                    </li>
                </ul>
 			</div>
            <span class="copyright">&copy;2017 SahabatUMKM.id, All Right Reserved Worldwide</span>
 		</div>
    </footer>

</body>
</html>