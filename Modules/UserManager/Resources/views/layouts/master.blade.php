<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="">

    <title>{{ config('app.name', 'Laravel') }} @yield('title')</title>

    <!-- Bootstrap core CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
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
        @if (!Auth::guest())
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="{{ url('/admin') }}">Project name</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
              <ul class="nav navbar-nav navbar-right">
                <!--<li><a href="{{ url('/blog') }}">Dashboard</a></li>-->
                
                <li><a href="{{ url('/logout') }}">Logout</a></li>
              </ul>
            </div>
        @endif
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            @role('admin')
            <li class="{{ ($page_meta_title ?? '') == 'Posts' ? 'active' : ''}}"><a href="{{ route('users.index') }}">Users</a></li>
            <li class="{{ ($page_meta_title ?? '') == 'Category' ? 'active' : ''}}"><a href="{{ route('roles.index') }}">Roles</a></li>
            <li class="{{ ($page_meta_title ?? '') == 'Category' ? 'active' : ''}}"><a href="{{ route('permissions.index') }}">Permissions</a></li>
            <li class="{{ ($page_meta_title ?? '') == 'Category' ? 'active' : ''}}"><a href="{{ URL::to('admin/mentors') }}">Mentor</a></li>
            @endrole
            @role(['mentor'])
                <li class="active"><a href="{{ URL::to('/mentors') }}">Mentor</a></li>
            @endrole
          </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <div class="container-fluid">
                <div class="row">
                    @if(Session::has('flash_message'))
                        <div class="container">      
                            <div class="alert alert-success"><em> {!! session('flash_message') !!}</em>
                            </div>
                        </div>
                    @endif 

                    <div class="row">
                        <div class="col-md-12">              
                            @include ('usermanager::errors.list') {{-- Including error file --}}
                        </div>
                    </div>
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

