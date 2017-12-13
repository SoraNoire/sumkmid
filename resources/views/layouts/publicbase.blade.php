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
    <link href="//cdn-images.mailchimp.com/embedcode/classic-10_7.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}?v=1.1.2">

    @if($fb_pixel != '')
      <!-- Facebook Pixel Code -->
      <script>
      !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
      n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
      document,'script','//connect.facebook.net/en_US/fbevents.js');
      // Insert Your Facebook Pixel ID below. 
      fbq('init', '{{ $fb_pixel }}');
      fbq('track', 'PageView');
      </script>
      <!-- Insert Your Facebook Pixel ID below. --> 
      <noscript><img height="1" width="1" style="display:none"
      src="https://www.facebook.com/tr?id={{ $fb_pixel }}&amp;ev=PageView&amp;noscript=1"
      /></noscript>
      <!-- End Facebook Pixel Code -->
    @endif

    @if($analytic != '')
      <!-- Google Analytic Code -->
      <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', '{{ $analytic }}', 'auto');
        ga('send', 'pageview');

      </script>
      <!-- End Google Analytic Code -->
    @endif
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
                                @if(app()->SSO->Auth())
                                <li class="userNavSetting">
                                    <span>{{app()->SSO->Auth()->name}}</span>
                                    <div class="goToProfile">
                                        <img id="profileTrigger" src="{{ asset('img/invalid-name.svg') }}" alt="">
                                    </div>
                                    <ul>
                                        <li><a href="{{route('user_setting')}}">Edit Profile</a></li>
                                        <li><a href="{{route('logout')}}">Logout</a></li>
                                    </ul>
                                </li>
                                @else
	                            <li class="loginButton">
	                                <a href="{{ route('ssologin') }}" class="button">
	                                    Masuk
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
        	<form class="newsletter-form" method="get" action="{{ route('public_newsletter') }}">
        		<input type="email" name="email" placeholder="Subscribe our newsletter">
            	<button type="submit">daftar</button>
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
                    <li><a target="_blank" href="{{ $link_fb }}" style="background-image: url('/img/home-facebook-logo.svg');"></a></li>
                    <li><a target="_blank" href="{{ $link_tw }}" style="background-image: url('/img/home-twitter-logo.svg');"></a></li>
                    <li><a target="_blank" href="{{ $link_yt }}" style="background-image: url('/img/home-youtube-logo.svg');"></a></li>
                    <li><a target="_blank" href="{{ $link_ig }}" style="background-image: url('/img/home-instagram-logo.svg');"></a></li>
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
            loadingHtml: '<div class="loadingVideo atEnd"> <span class="infinite-scroll-request"><img src="/img/infinity-load.svg"></span> <span class="infinite-scroll-last">Loading Video...</span></div>',
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
    @if($var['page'] == 'Mentor')
    <script>
        //Mentor
        $(function(){
        $('.infinite-scroll').jscroll({
            autoTrigger: true,
            loadingHtml: '<div class="loadingVideo"> <span class="infinite-scroll-request"><img src="/img/infinity-load.svg"></span> <span class="infinite-scroll-last">Memuat Mentor...</span> </div>',
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
    <script src="{{ asset('js/home.js') }}?v=1.1.1"></script>

</body>
</html>