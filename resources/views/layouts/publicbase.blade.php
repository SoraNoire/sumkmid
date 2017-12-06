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
	                            <li class="{{ ($var['page'] ?? '' == 'Event' ? 'active' : '') }}">
	                                <a href="{{ route('public_event') }}">
	                                    Event
	                                </a>
	                            </li>
	                            <li class="{{ ($var['page'] ?? '' == 'mentor' ? 'active' : '') }}">
	                                <a href="{{ route('public_mentor') }}">
	                                    Mentor
	                                </a>
	                            </li>
	                            <li class="{{ ($var['page'] ?? '' == 'video' ? 'active' : '') }}">
	                                <a href="{{ route('public_video') }}">
	                                    Video
	                                </a>
	                            </li>
	                            <li class="{{ ($var['page'] ?? '' == 'Ijin Usaha' ? 'active' : '') }}">
	                                <a href="#">
	                                    Tanya Jawab
	                                </a>
	                            </li>
	                            <li class="{{ ($var['page'] ?? '' == 'Kontak' ? 'active' : '') }}">
	                                <a href="{{ route('public_kontak') }}">
	                                    Kontak
	                                </a>
	                            </li>
	                            <li class="loginButton">
	                                <a href="{{ route('login') }}" class="button">
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
                    <li><a target="_blank" href="#" style="background-image: url('/img/home-facebook-logo.svg');"></a></li>
                    <li><a target="_blank" href="#" style="background-image: url('/img/home-twitter-logo.svg');"></a></li>
                    <li><a target="_blank" href="#" style="background-image: url('/img/home-youtube-logo.svg');"></a></li>
                    <li><a target="_blank" href="#" style="background-image: url('/img/home-instagram-logo.svg');"></a></li>
 				</ul>
 			</div>
 			<div class="footer-nav">
 				<ul>
                    <li class="{{ ($var['page'] ?? '' == 'Event' ? 'active' : '') }}">
                        <a href="{{ route('public_event') }}">
                            Event
                        </a>
                    </li>
                    <li class="{{ ($var['page'] ?? '' == 'mentor' ? 'active' : '') }}">
                        <a href="{{ route('public_mentor') }}">
                            Mentor
                        </a>
                    </li>
                    <li class="{{ ($var['page'] ?? '' == 'video' ? 'active' : '') }}">
                        <a href="{{ route('public_video') }}">
                            Video
                        </a>
                    </li>
                    <li class="{{ ($var['page'] ?? '' == 'Ijin Usaha' ? 'active' : '') }}">
                        <a href="#">
                            Tanya Jawab
                        </a>
                    </li>
                    <li class="{{ ($var['page'] ?? '' == 'Kontak' ? 'active' : '') }}">
                        <a href="{{ route('public_kontak') }}">
                            Kontak
                        </a>
                    </li>
                </ul>
 			</div>
            <span class="copyright">&copy;2017 SahabatUMKM.id, All Right Reserved Worldwide</span>
 		</div>
    </footer>
    <div class="trnsOverlay"></div>
    <div class="whiteOverlay"></div>
    <!-- end of footer -->
    <!-- <script
        src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="{{asset('js/jscroll.js')}}"></script>
    @if($var['page'] == 'Event')
    <script>
        //event
        $(function() {
        $('.infinite-scroll').jscroll({
            autoTrigger: true,
            loadingHtml: '<div class="scroller-status loading"> <div class="event the-row"> <div class="col-3"></div> <div class="event-timeline"> <div class="event-indicator"></div> </div> <div class="col-9 infinity-scroll-message"> <div class="loadingItems"> <span class="infinite-scroll-request"><img src="/img/infinity-load.svg"> Memuat Event</span> </div> </div> </div> </div>',
            padding: 0,
            nextSelector: '.pagination li.active + li a',
            contentSelector: 'div.infinite-scroll',
            callback: function() {
                $('ul.pagination').remove();
                $('.scroller-status').addClass('end-of-page');
            }
            });
            });
    </script>
    @endif
    @if($var['page'] == 'Video')
    <script>
        //video
        $(function(){
        $('.infinite-scroll').jscroll({
            autoTrigger: true,
            loadingHtml: '<div class="loadingVideo atEnd"> <span class="infinite-scroll-request"><img src="/img/infinity-load.svg"></span> <span class="infinite-scroll-last">Loading Video...</span> <span class="end-text">Tidak Ada Video Lagi</span> </div>',
            padding: 0,
            nextSelector: '.pagination li.active + li a',
            contentSelector: 'div.infinite-scroll',
            callback: function() {
                $('ul.pagination').remove();
                $('.atEnd').show();
            }
        });
        });
    </script>
    @endif
    <script src="{{ asset('js/home.js') }}?v=1.1.0"></script>

</body>
</html>