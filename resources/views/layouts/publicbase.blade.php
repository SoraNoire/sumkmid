<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="UTF-8">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- csrf key -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ app()->Meta->get('meta_title') }}</title>
    
    {!! app()->Meta->print_meta() !!}

    <link rel="icon" type="image/x-icon" href="{{ asset('images/fav.png') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link href="//cdn-images.mailchimp.com/embedcode/classic-10_7.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}?v=1.1.8">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.css') }}?v=1.0.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />

    @if(app()->Meta->get('fb_pixel') != '')
      <!-- Facebook Pixel Code -->
      <script>
      !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
      n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
      document,'script','//connect.facebook.net/en_US/fbevents.js');
      // Insert Your Facebook Pixel ID below. 
      fbq('init', '{{ app()->Meta->get('fb_pixel') }}');
      fbq('track', 'PageView');
      </script>
      <!-- Insert Your Facebook Pixel ID below. --> 
      <noscript><img height="1" width="1" style="display:none"
      src="https://www.facebook.com/tr?id={{ app()->Meta->get('fb_pixel') }}&amp;ev=PageView&amp;noscript=1"
      /></noscript>
      <!-- End Facebook Pixel Code -->
    @endif

    @if(app()->Meta->get('gtm') != '')
     <!-- Google Tag Manager --> <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start': new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0], j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src= 'https://www.googletagmanager.com/gtm.js? id='+i+dl;f.parentNode.insertBefore(j,f); })(window,document,'script','dataLayer','{{ app()->Meta->get('gtm') }}');</script> <!-- End Google Tag Manager -->
    @endif
    {!! PublicHelper::printSchema($var['page']) !!}
</head>
<body>
    @if(app()->Meta->get('gtm') != '')
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ app()->Meta->get('gtm') }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    @endif
	<!-- header -->
    <header>
        <section class="headerTop">
            <div class="container">
                <div class="the-row">
                    <div class="col-3 mainLogo">
                        <a href="{{ route('public_home') }}"><img src="{{ asset('img/icon1color.png') }}" alt="sahabat-umkm-logo"></a>
                    </div>
                    <nav class="col-9 topNav">
                        <div class="navWrapper">
	                        <div class="burgerBtn"></div>
	                        <ul>
                                
                                {!!  app()->Meta->get('top_menu') !!}

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
                                        <li><a href="{{route('user_setting')}}">Edit Profil</a></li>
                                        <li><a href="{{route('OA.logout')}}">Keluar</a></li>
                                    </ul>
                                </li>
                                @else
	                            <li class="loginButton">
	                                <a href="https://goo.gl/forms/iTe48cYNuizOwnu42" target="_blank" class="button">
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


 	<footer>
        <div class="container">
            <div class="the-row">
                <div class="col-4 footerItems">
                    <div class="footerLogo">
                        <img src="{{ asset('img/footer-logo.png') }}" alt="logo-sahabat-UMKM">
                    </div>
                    <p class="desc">
                        {{ app()->Meta->get('footer_desc') }}
                    </p>
                </div>
                <div class="col-4 contactItems">
                    <h5>Sekretariat Sahabat UMKM</h5>
                    <ul>
                        <li>
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                            <p>
                                Jalan Kebon Kacang Raya no. 25<br>
                                Tanah Abang, Jakarta Pusat
                            </p>
                        </li>
                        <li>
                            <i class="fa fa-phone" aria-hidden="true"></i>
                            <p>
                                (+62) 21 3917399
                            </p>
                        </li>
                        <li>
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                            <p>
                                <a href="{{ 'mailto:'.app()->Meta->get('email_info') }}">{{ app()->Meta->get('email_info') }}</a>
                            </p>
                        </li>

                    </ul>
                </div>
                <div class="col-4 stayInTouch">
                    <h5>Tetap Terhubung dengan Kami</h5>
                    <div class="footerSubForm">
                        <form action="{{ route('public_newsletter') }}" method="get">
                            <input type="email" name="email" placeholder="Berlangganan Surel" required="required">
                            <button id="submit_newsletter"><i class="fa fa-location-arrow" aria-hidden="true"></i></button>
                        </form>
                    </div>
                    <ul>
                        <li><a target="_blank" href="{{ app()->Meta->get('link_fb') ?? '#' }}"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                        <li><a target="_blank" href="{{ app()->Meta->get('link_tw') ?? '#' }}"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                        <li><a target="_blank" href="{{ app()->Meta->get('link_in') ?? '#' }}"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                        <li><a target="_blank" href="{{ app()->Meta->get('link_ig') ?? '#' }}"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                        <li><a target="_blank" href="{{ app()->Meta->get('link_gplus') ?? '#' }}"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                        <li><a target="_blank" href="{{ app()->Meta->get('link_yt') ?? '#' }}"><i class="fa fa-youtube-play" aria-hidden="true"></i></a></li>
                    </ul>
                    <span class="copyText">Hak Cipta &copy; 2017 - Sahabat UMKM</span>
                </div>
            </div>
        </div>
    </footer>
    <div class="trnsOverlay"></div>
    <div class="whiteOverlay"></div>
    <!-- end of footer -->
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="{{ asset('js/home.js') }}?v=1.1.22"></script>


    @if (session()->has('swal'))
      <?php 
      $msg = session('swal');
      ?>
      <script type="text/javascript">
        swal({
         title: '<?=$msg->status?>',
         text: '<?=$msg->message?>',
         type: '<?=$msg->status?>',
         confirmButtonText: 'OK'
       });
     </script>
    @endif

</body>
</html>