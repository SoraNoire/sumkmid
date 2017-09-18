@extends('blog::layouts.front')
@include('blog::pages.blog.sidebar')
@section('content')

    <div class="swiper-container">
        <div class="parallax-bg" data-swiper-parallax="-23%"></div>
        <div class="swiper-wrapper">
            <div class="swiper-slide" style="background-image:url({{ asset('public/img/img/tes.jpg')}})">
                <div class="title" data-swiper-parallax="-100">Slide 1</div>
                <div class="subtitle" data-swiper-parallax="-200">Subtitle</div>
                <div class="text" data-swiper-parallax="-300">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam dictum mattis velit, sit amet faucibus felis iaculis nec. Nulla laoreet justo vitae porttitor porttitor. Suspendisse in sem justo. Integer laoreet magna nec elit suscipit, ac laoreet nibh euismod. Aliquam hendrerit lorem at elit facilisis rutrum. Ut at ullamcorper velit. Nulla ligula nisi, imperdiet ut lacinia nec, tincidunt ut libero. Aenean feugiat non eros quis feugiat.</p>
                </div>
            </div>
            <div class="swiper-slide" style="background-image:url({{ asset('public/img/img/tes.jpg')}})">
                <div class="title" data-swiper-parallax="-100">Slide 2</div>
                <div class="subtitle" data-swiper-parallax="-200">Subtitle</div>
                <div class="text" data-swiper-parallax="-300">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam dictum mattis velit, sit amet faucibus felis iaculis nec. Nulla laoreet justo vitae porttitor porttitor. Suspendisse in sem justo. Integer laoreet magna nec elit suscipit, ac laoreet nibh euismod. Aliquam hendrerit lorem at elit facilisis rutrum. Ut at ullamcorper velit. Nulla ligula nisi, imperdiet ut lacinia nec, tincidunt ut libero. Aenean feugiat non eros quis feugiat.</p>
                </div>
            </div>
            <div class="swiper-slide" style="background-image:url({{ asset('public/img/img/tes.jpg')}})">
                <div class="title" data-swiper-parallax="-100">Slide 3</div>
                <div class="subtitle" data-swiper-parallax="-200">Subtitle</div>
                <div class="text" data-swiper-parallax="-300">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam dictum mattis velit, sit amet faucibus felis iaculis nec. Nulla laoreet justo vitae porttitor porttitor. Suspendisse in sem justo. Integer laoreet magna nec elit suscipit, ac laoreet nibh euismod. Aliquam hendrerit lorem at elit facilisis rutrum. Ut at ullamcorper velit. Nulla ligula nisi, imperdiet ut lacinia nec, tincidunt ut libero. Aenean feugiat non eros quis feugiat.</p>
                </div>
            </div>
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination swiper-pagination-white"></div>
        <!-- Add Navigation -->
        <div class="swiper-button-prev swiper-button-white"></div>
        <div class="swiper-button-next swiper-button-white"></div>
    </div>

<div class="container">
    <div class="content">
        <div class="row">
            <div class="kol8">
                 <h4>Post</h4>
                <div class="post">
                    <span class="date">September 14, 2017</span>
                    <span class="edit"><a href="">EDIT</a></span>
                    <h3 class="title-post"><a href="#">SPOTLITE</a></h3>
                    <a href="#" style="text-decoration: none;"><img src="{{ asset('public/img/img/tes.jpg')}}"></a>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                    </p>
                </div>
                <div class="post">
                    <span class="date">September 14, 2017</span>
                    <span class="edit"><a href="">EDIT</a></span>
                    <h3 class="title-post"><a href="#">SPOTLITE</a></h3>
                    <a href="#" style="text-decoration: none;"><img src="{{ asset('public/img/img/tes.jpg')}}"></a>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                    </p>
                </div>
                <div class="post">
                    <span class="date">September 14, 2017</span>
                    <span class="edit"><a href="">EDIT</a></span>
                    <h3 class="title-post"><a href="#">SPOTLITE</a></h3>
                    <a href="#" style="text-decoration: none;"><img src="{{ asset('public/img/img/tes.jpg')}}"></a>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                    </p>
                </div>

                    <div class="cst-3-col">
                    <a href=""><img src="{{ asset('public/img/img/tes.jpg')}}"></a>
                    <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </span>
                    </div>
                    <div class="cst-3-col">
                    <a href=""><img src="{{ asset('public/img/img/tes.jpg')}}"></a>
                    <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </span>
                    </div>
                    <div class="cst-3-col">
                    <a href=""><img src="{{ asset('public/img/img/tes.jpg')}}"></a>
                    <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </span>
                    </div>

            </div>
            <div class="kol4 sidebar">

                

                @yield('sidebar')

            </div>
        </div>
    </div>
</div>


@stop
