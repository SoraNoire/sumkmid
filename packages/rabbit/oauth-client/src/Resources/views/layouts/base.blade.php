<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Administrator</title>
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo-ico.png') }}">
    <title>{{ config('app.name', 'Laravel') }} - Administrator</title>
    <link href="{{ asset('css/app.css') }}?v=1.0.1" rel="stylesheet">
    
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  </head>
  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="{{ route('panel.dashboard') }}" style="margin-top: -5px;">
            <img src="{{URL::to('/img/logo.png')}}" style="max-width:70%;"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="{{ route('panel.dashboard') }}">Dashboard</a></li>
            @if( app()->OAuth->Auth() && in_array(app()->OAuth->Auth()->role,['admin']) )
            <li class="mobile-admin-nav {{( ($page == 'Role Management' ? 'active' : '') )}}">
                <a href="{{route('OA.dashboard')}}">
                    Role Management
                </a>
            </li>

            <li class="mobile-admin-nav {{( ($page == 'Module Management' ? 'active' : '') )}}">
                <a href="{{route('OA.modules')}}">
                    Module Management
                </a>
            </li>
            @endif

            <li><a href="{{ url('/logout') }}">Logout</a>
            </li>

          </ul>
        </div>
      </div>
    </nav>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
                    <li class="{{( ($page == 'dashboard' ? 'active' : '') )}}">
                        <a href="{{ route('panel.dashboard') }}">
                            Dashboard
                        </a>
                    </li>
                    @if( app()->OAuth->Auth() && in_array(app()->OAuth->Auth()->role,['admin']) )

                    <li class="{{( ($page == 'Role Management' ? 'active' : '') )}}">
                        <a href="{{route('OA.dashboard')}}">
                            Role Management
                        </a>
                    </li>

                    <li class="{{( ($page == 'Module Management' ? 'active' : '') )}}">
                        <a href="{{route('OA.modules')}}">
                            Module Management
                        </a>
                    </li>

                    @endif
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="{{ asset('js/index.js') }}?v=1.0.5" type="text/javascript"></script>
    @if (session('msg'))
    <script type="text/javascript">$(document).ready(function(){notif.showNotification("top","right",'{{session("msg")}}','{{session("status")}}')});</script>
    @endif

    @stack('script')

  </body>
</html>
