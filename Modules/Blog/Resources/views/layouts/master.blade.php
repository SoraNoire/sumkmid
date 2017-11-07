<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="item-id" content="{{ $item_id ?? 0 }}">
    <meta name="category-id" content="{{ $category_id ?? 0 }}">
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="">

    <title>Blog - {{ $page_meta_title ?? ''}}</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('css/app.css') }}?v=1.0.0" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
    <script src="https://use.fontawesome.com/0c0c4bc012.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>
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
          <a class="navbar-brand" href="{{ url('/blog') }}">Project name</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="{{ url('/blog') }}">Dashboard</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li class="{{ ($page_meta_title ?? '') == 'Events' ? 'active' : ''}}"><a href="{{ url('admin/blog/event') }}">Event</a></li>
            <li class="{{ ($page_meta_title ?? '') == 'Event Category' ? 'active' : ''}}"><a href="{{ url('admin/blog/event/category') }}">Event Category</a></li>
            <li class="{{ ($page_meta_title ?? '') == 'Page' ? 'active' : ''}}"><a href="{{ url('admin/blog/pages') }}">Pages</a></li>
            <li class="{{ ($page_meta_title ?? '') == 'Posts' ? 'active' : ''}}"><a href="{{ url('admin/blog/posts') }}">Post</a></li>
            <li class="{{ ($page_meta_title ?? '') == 'Post Category' ? 'active' : ''}}"><a href="{{ url('admin/blog/category') }}">Post Category</a></li>
            <li class="{{ ($page_meta_title ?? '') == 'Tag' ? 'active' : ''}}"><a href="{{ url('admin/blog/tag') }}">Post Tag</a></li>
            <li class="{{ ($page_meta_title ?? '') == 'Videos' ? 'active' : ''}}"><a href="{{ url('admin/blog/video/') }}">Video</a></li>
            <li class="{{ ($page_meta_title ?? '') == 'Video Category' ? 'active' : ''}}"><a href="{{ url('admin/blog/video/category') }}">Video Category</a></li>
            <li class="{{ ($page_meta_title ?? '') == 'Video Tag' ? 'active' : ''}}"><a href="{{ url('admin/blog/video/tag') }}">Video Tag</a></li>
            <li class="{{ ($page_meta_title ?? '') == 'Gallery' ? 'active' : ''}}"><a href="{{ url('admin/blog/gallery/') }}">Gallery</a></li>
            <li class="{{ ($page_meta_title ?? '') == 'Gallery Category' ? 'active' : ''}}"><a href="{{ url('admin/blog/gallery/category') }}">Gallery Category</a></li>
            <li class="{{ ($page_meta_title ?? '') == 'Gallery Tag' ? 'active' : ''}}"><a href="{{ url('admin/blog/gallery/tag') }}">Gallery Tag</a></li>
            <li class="{{ ($page_meta_title ?? '') == 'Media' ? 'active' : ''}}"><a href="{{ url('admin/blog/media') }}">Media</a></li>
            <li class="{{ ($page_meta_title ?? '') == 'Menu' ? 'active' : ''}}"><a href="{{ url('admin/blog/menu') }}">Menu</a></li>
          </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <div class="container-fluid">
                <div class="row">
                    @yield('content')
                </div>
            </div>
        </div>
      </div>
    </div>

    @yield('modal')

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="{{ asset('js/index.js') }}?v=1.0.1" type="text/javascript"></script>
   <!--  
    <script src="{{ asset('Modules/Blog/Assets/js/jquery-3.1.0.min.js') }}" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('Modules/Blog/Assets/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('Modules/Blog/Assets/js/custom.js') }}"></script> -->
  </body>
</html>
