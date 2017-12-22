@extends('blog::layouts.front')
@include('blog::pages.blog.sidebar')
@section('content')
<div class="container">
    <div class="content">
        <div class="row">
            <div class="kol8">
                <h3 class="breadcrumb">
                  <ol>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Berita</a></li>
                    <li><a href="#">{{ $post->title }}</a></li>
                  </ol>
                </h3>
                <article class="post">
                    <h1>{{ $post->title }}</h1>
                    <span class="date">{{ $post->published_at }}</span>
                    <span class="edit"><a href="">EDIT</a></span>
                    <div class="fimg-single-post">
                      <img src="{{ asset('public/img/img/image-alignment-580x300.jpg')}}">
                      <div class="fimg-caption">
                        <p>Duis numquam rutrum nostrum. Inventore fermentum? Convallis pede auctor dis autem magna? Incididunt sint! Totam in, elit tempor exercitationem placerat.</p>
                      </div>
                    </div>
                    <div class="content-read">
                        {!! $post->body !!}
                    </div>
                    <hr>
                    <div class="author">
                      <span>Author</span>
                      <span class="author-name">Syarief Navi</span>
                    </div>
                    <div class="post-tags">
                      <ul>
                        <li><a href="#">tag1</a></li>
                        <li><a href="#">tag2</a></li>
                        <li><a href="#">tag3</a></li>
                        <li><a href="#">tag4</a></li>
                      </ul>
                    </div>
                </article>
                <div class="post-comments">
                  <h3>Comments</h3>
                  <ul>
                    <li class="comment-1">
                      <div class="comment-image">
                        <img src="{{asset('public/img/foto-komen/Finn.jpg')}}" alt="">
                      </div>
                      <div class="comment-content">
                        <span class="comment-name">Syarief Navi</span>
                        <div class="comment-value">
                          <p>Incidunt anim consequat sociosqu dictum montes esse malesuada? Dolor libero. Voluptate laboris! Quos per voluptates aliquet habitasse culpa, lobortis ratione.</p>
                        </div>
                      </div>
                      <ul>
                        <li class="comment-2">
                          <div class="comment-image">
                            <img src="{{asset('public/img/foto-komen/Finn.jpg')}}" alt="">
                          </div>
                          <div class="comment-content">
                            <span class="comment-name">Syarief Navi</span>
                            <div class="comment-value">
                              <p>Incidunt anim consequat sociosqu dictum montes esse malesuada? Dolor libero. Voluptate laboris! Quos per voluptates aliquet habitasse culpa, lobortis ratione.</p>
                            </div>
                          </div>
                          <ul>
                            <li class="comment-2">
                              <div class="comment-image">
                                <img src="{{asset('public/img/foto-komen/Finn.jpg')}}" alt="">
                              </div>
                              <div class="comment-content">
                                <span class="comment-name">Syarief Navi</span>
                                <div class="comment-value">
                                  <p>Incidunt anim consequat sociosqu dictum montes esse malesuada? Dolor libero. Voluptate laboris! Quos per voluptates aliquet habitasse culpa, lobortis ratione.</p>
                                </div>
                              </div>
                            </li>
                          </ul>
                        </li>
                      </ul>
                    </li>
                    <li class="comment-1">
                      <div class="comment-image">
                        <img src="{{asset('public/img/foto-komen/Finn.jpg')}}" alt="">
                      </div>
                      <div class="comment-content">
                        <span class="comment-name">Syarief Navi</span>
                        <div class="comment-value">
                          <p>Incidunt anim consequat sociosqu dictum montes esse malesuada? Dolor libero. Voluptate laboris! Quos per voluptates aliquet habitasse culpa, lobortis ratione.</p>
                        </div>
                      </div>
                    </li>
                    <li class="comment-1">
                      <div class="comment-image">
                        <img src="{{asset('public/img/foto-komen/Finn.jpg')}}" alt="">
                      </div>
                      <div class="comment-content">
                        <span class="comment-name">Syarief Navi</span>
                        <div class="comment-value">
                          <p>Incidunt anim consequat sociosqu dictum montes esse malesuada? Dolor libero. Voluptate laboris! Quos per voluptates aliquet habitasse culpa, lobortis ratione.</p>
                        </div>
                      </div>
                    </li>
                    <li class="comment-1">
                      <div class="comment-image">
                        <img src="{{asset('public/img/foto-komen/Finn.jpg')}}" alt="">
                      </div>
                      <div class="comment-content">
                        <span class="comment-name">Syarief Navi</span>
                        <div class="comment-value">
                          <p>Incidunt anim consequat sociosqu dictum montes esse malesuada? Dolor libero. Voluptate laboris! Quos per voluptates aliquet habitasse culpa, lobortis ratione.</p>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
            </div>
            <div class="kol4 sidebar">
                @yield('sidebar')
            </div>
        </div>
    </div>
</div>


@stop
