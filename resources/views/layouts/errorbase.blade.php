<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- csrf key -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sahabat UMKM</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('images/fav.png') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link href="//cdn-images.mailchimp.com/embedcode/classic-10_7.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}?v=1.1.61">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />

    @if(isset($fb_pixel))
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
    @endif

    @if(isset($analytic))
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
    @endif
</head>
<body>
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
                                <li><a href="{{ route('public_tentang') }}">Tentang</a></li>
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
                                <li class="{{ ($var['page'] ?? '' == 'galeri' ? 'active' : '') }}">
                                    <a href="{{ route('public_gallery') }}">
                                        Galeri
                                    </a>
                                </li>
<!--                            <li class="{{ ($var['page'] ?? '' == 'Ijin Usaha' ? 'active' : '') }}">
                                    <a href="#">
                                        Forum
                                    </a>
                                </li> -->
                                <li class="{{ ($var['page'] ?? '' == 'Kontak' ? 'active' : '') }}">
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
            <form class="newsletter-form" method="get" action="{{ route('public_newsletter') }}">
                <input type="email" name="email" placeholder="Subscribe our newsletter">
                <button type="submit">daftar</button>
            </form>
        </div>
    </section>

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
                    <h5>OUR OFFICE</h5>
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
                                {{ config('app.email_info') }}
                            </p>
                        </li>

                    </ul>
                </div>
                <div class="col-4 stayInTouch">
                    <h5>STAY IN TOUCH</h5>
                    <div class="footerSubForm">
                        <form action="{{ route('public_newsletter') }}" method="get">
                            <input type="email" name="email" placeholder="Subscribe our newsletter" required="required">
                            <button id="submit_newsletter"><i class="fa fa-location-arrow" aria-hidden="true"></i></button>
                        </form>
                    </div>
                    <ul>
                        <li><a target="_blank" href="{{ $link_fb ?? '#' }}"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                        <li><a target="_blank" href="{{ $link_tw ?? '#' }}"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                        <li><a target="_blank" href="{{ $link_in ?? '#' }}"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                        <li><a target="_blank" href="{{ $link_ig ?? '#' }}"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                        <li><a target="_blank" href="{{ $link_gplus ?? '#' }}"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                        <li><a target="_blank" href="{{ $link_yt ?? '#' }}"><i class="fa fa-youtube-play" aria-hidden="true"></i></a></li>
                    </ul>
                    <span class="copyText">Copyright &copy; 2017 - Sahabat UMKM</span>
                </div>
            </div>
        </div>
    </footer>
    <div class="trnsOverlay"></div>
    <div class="whiteOverlay"></div>
    <!-- end of footer -->
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="{{ asset('js/home.js') }}?v=1.1.21"></script>


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