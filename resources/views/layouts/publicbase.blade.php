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
    <link rel="stylesheet" href="{{ asset('css/index.css') }}?v=1.0.0">
</head>
<body>
	<!-- header -->
    <header>
        <section class="headerTop">
            <div class="container">
                <div class="the-row">
                    <div class="col-3 mainLogo">
                        <a href="{{ route('home') }}"><img src="{{ asset('img/logo.png') }}" alt="sahabat-umkm-logo"></a>
                    </div>
                    <nav class="col-9 topNav">
                        <div class="navWrapper">
	                        <span class="toggleMenu">MENU</span>
	                        <ul>
	                            <li class="{{ ($var['page'] == 'Event' ? 'active' : '') }}">
	                                <a href="{{ route('event') }}">
	                                    Event
	                                </a>
	                            </li>
	                            <li class="{{ ($var['page'] == 'mentor' ? 'active' : '') }}">
	                                <a href="{{ route('mentor') }}">
	                                    Mentor
	                                </a>
	                            </li>
	                            <li class="{{ ($var['page'] == 'video' ? 'active' : '') }}">
	                                <a href="{{ route('video') }}">
	                                    Video
	                                </a>
	                            </li>
	                            <li class="{{ ($var['page'] == 'Forum' ? 'active' : '') }}">
	                                <a href="#">
	                                    Forum
	                                </a>
	                            </li>
	                            <li class="{{ ($var['page'] == 'Ijin Usaha' ? 'active' : '') }}">
	                                <a href="#">
	                                    Ijin Usaha & Sertifikasi
	                                </a>
	                            </li>
	                            <li class="{{ ($var['page'] == 'Kontak' ? 'active' : '') }}">
	                                <a href="{{ route('kontak') }}">
	                                    Kontak
	                                </a>
	                            </li>
	                            <li class="loginButton">
	                                <a href="#" class="button">
	                                    Masuk
	                                </a>
	                            </li>
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
 					<li><a target="_blank" href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                    <li><a target="_blank" href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                    <li><a target="_blank" href="#"><i class="fa fa-youtube-play" aria-hidden="true"></i></a></li>
                    <li><a target="_blank" href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
 				</ul>
 			</div>
 			<div class="footer-nav">
 				<ul>
                    <li class="{{ ($var['page'] == 'Event' ? 'active' : '') }}">
                        <a href="{{ route('event') }}">
                            Event
                        </a>
                    </li>
                    <li class="{{ ($var['page'] == 'mentor' ? 'active' : '') }}">
                        <a href="{{ route('mentor') }}">
                            Mentor
                        </a>
                    </li>
                    <li class="{{ ($var['page'] == 'video' ? 'active' : '') }}">
                        <a href="{{ route('video') }}">
                            Video
                        </a>
                    </li>
                    <li class="{{ ($var['page'] == 'Forum' ? 'active' : '') }}">
                        <a href="#">
                            Forum
                        </a>
                    </li>
                    <li class="{{ ($var['page'] == 'Ijin Usaha' ? 'active' : '') }}">
                        <a href="#">
                            Ijin Usaha & Sertifikasi
                        </a>
                    </li>
                    <li class="{{ ($var['page'] == 'Kontak' ? 'active' : '') }}">
                        <a href="{{ route('kontak') }}">
                            Kontak
                        </a>
                    </li>
                </ul>
 			</div>
            <span class="copyright">&copy;2017 SahabatUMKM.id, All Right Reserved Worldwide</span>
 		</div>
    </footer>
    <!-- end of footer -->
    <script
        src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>
    <script src="{{ asset('js/home.js') }}?v=1.0.0"></script>

</body>
</html>