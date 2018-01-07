<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="item-id" content="{{ $item_id ?? 0 }}">
    <meta name="category-id" content="{{ $category_id ?? 0 }}">
    @php 
        $can = [];
        if (in_array('read', app()->OAuth::can())) {
            $can[] = "1";
        }
        if (in_array('write', app()->OAuth::can())) {
            $can[] = "2";
        }
        if (in_array('edit', app()->OAuth::can())) {
            $can[] = "3";
        }
        if (in_array('delete', app()->OAuth::can())) {
            $can[] = "4";
        }

        $mediaCan = [];
        if ( in_array('read', app()->OAuth::can('panel.media')) ) {
            $mediaCan[] = "1";
        }
        if ( in_array('write', app()->OAuth::can('panel.media')) ) {
            $mediaCan[] = "2";
        }
        if ( in_array('edit', app()->OAuth::can('panel.media')) ) {
            $mediaCan[] = "3";
        }
        if ( in_array('delete', app()->OAuth::can('panel.media')) ) {
            $mediaCan[] = "4";
        }
    @endphp
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
        can = "{{ implode(',', $can) }}".split(',');
        mediaCan = "{{ implode(',', $mediaCan) }}".split(',');
    </script>
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="">

    <title>Blog - {{ $page_meta_title ?? ''}}</title>

    <link href="{{ asset('css/app.css') }}?v=1.0.1" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/font-awesome.css') }}?v=1.0.0">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey={{ $tinymceApiKey ?? '' }}"></script>
  </head>

  <body id="{{ $body_id ?? ''}}">

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="{{ url('/') }}" style="margin-top: -5px;">
            <img src="{{ asset('/img/logo.png') }}" style="max-width: 70%;">
          </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li>
              <a href="{{ url('/admin/blog') }}">Dashboard</a>
            </li>
            @if( in_array('read', app()->OAuth::can('panel.event')) )
            <li class="mobile-admin-nav {{ ($page_meta_title ?? '') == 'Events' ? 'active' : ''}}">
              <a href="{{ url('admin/blog/event') }}">Event</a>
            </li>
            @endif
            @if( in_array('read', app()->OAuth::can('panel.gallery')) )
            <li class="mobile-admin-nav {{ ($page_meta_title ?? '') == 'Gallery' ? 'active' : ''}}">
              <a href="{{ url('admin/blog/gallery/') }}">Gallery</a>
            </li>
            @endif
            @if( in_array('read', app()->OAuth::can('panel.category')) )
            <li class="mobile-admin-nav {{ ($page_meta_title ?? '') == 'Categories' ? 'active' : ''}}">
              <a href="{{ route('panel.category__index') }}">Categories</a>
            </li>
            @endif
            @if( in_array('read', app()->OAuth::can('panel.tag')) )
            <li class="mobile-admin-nav {{ ($page_meta_title ?? '') == 'Tags' ? 'active' : ''}}">
              <a href="{{ route('panel.tag__index') }}">Tags</a>
            </li>
            @endif
            @if( in_array('read', app()->OAuth::can('panel.media')) )
            <li class="mobile-admin-nav {{ ($page_meta_title ?? '') == 'Media' ? 'active' : ''}}">
              <a href="{{ url('admin/blog/media') }}">Media</a>
            </li>
            @endif
            @if( in_array('read', app()->OAuth::can('panel.trash')) )
            <li class="mobile-admin-nav {{ ($page_meta_title ?? '') == 'Trash' ? 'active' : ''}}">
              <a href="{{ route('panel.post.trash__index') }}">Trash</a>
            </li>
            @endif
            @if( in_array('read', app()->OAuth::can('panel.setting.site')) )
            <li class="mobile-admin-nav {{ ($page_meta_title ?? '') == 'Site Setting' ? 'active' : ''}}">
              <a href="{{ route('panel.setting.site__index') }}">Site Setting</a>
            </li>
            @endif
            @if( in_array('read', app()->OAuth::can('panel.slider')) )
            <li class="mobile-admin-nav {{ ($page_meta_title ?? '') == 'Slider' ? 'active' : ''}}">
              <a href="{{ route('panel.slider__index') }}">Slider</a>
            </li>
            @endif

            <li>
                <a href="{{URL::to('/logout')}}">
                    Logout
                </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">

            @if( in_array('read', app()->OAuth::can('panel.event')) )
            <li class="{{ ($page_meta_title ?? '') == 'Events' ? 'active' : ''}}">
              <a href="{{ url('admin/blog/event') }}">Event</a>
            </li>
            @endif
            @if( in_array('read', app()->OAuth::can('panel.gallery')) )
            <li class="{{ ($page_meta_title ?? '') == 'Gallery' ? 'active' : ''}}">
              <a href="{{ url('admin/blog/gallery/') }}">Gallery</a>
            </li>
            @endif
            @if( in_array('read', app()->OAuth::can('panel.category')) )
            <li class="{{ ($page_meta_title ?? '') == 'Categories' ? 'active' : ''}}">
              <a href="{{ route('panel.category__index') }}">Categories</a>
            </li>
            @endif
            @if( in_array('read', app()->OAuth::can('panel.tag')) )
            <li class="{{ ($page_meta_title ?? '') == 'Tags' ? 'active' : ''}}">
              <a href="{{ route('panel.tag__index') }}">Tags</a>
            </li>
            @endif
            @if( in_array('read', app()->OAuth::can('panel.media')) )
            <li class="{{ ($page_meta_title ?? '') == 'Media' ? 'active' : ''}}">
              <a href="{{ url('admin/blog/media') }}">Media</a>
            </li>
            @endif
            @if( in_array('read', app()->OAuth::can('panel.trash')) )
            <li class="{{ ($page_meta_title ?? '') == 'Trash' ? 'active' : ''}}">
              <a href="{{ route('panel.post.trash__index') }}">Trash</a>
            </li>
            @endif
            @if( in_array('read', app()->OAuth::can('panel.setting.site')) )
            <li class="{{ ($page_meta_title ?? '') == 'Site Setting' ? 'active' : ''}}">
              <a href="{{ route('panel.setting.site__index') }}">Site Setting</a>
            </li>
            @endif
            @if( in_array('read', app()->OAuth::can('panel.slider')) )
            <li class="{{ ($page_meta_title ?? '') == 'Slider' ? 'active' : ''}}">
              <a href="{{ route('panel.slider__index') }}">Slider</a>
            </li>
            @endif
          </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <div class="container-fluid">
                <div class="row">
                    @if(session('msg'))
                    <div class="alert alert-{{ session('status') }} alert-dismissable " role="alert">
                        <span type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </span>
                        {{ session('msg') }}
                    </div>
                    @endif
                    
                    @yield('content')
                </div>
            </div>
        </div>
      </div>
    </div>

    @yield('modal')

    <script src="{{ asset('js/index.js') }}?v=1.0.32" type="text/javascript"></script>
  </body>
</html>
