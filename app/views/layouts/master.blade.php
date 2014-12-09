<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    
    @section('PlaceHolderMetaTitle')
    <title>@yield('PlaceHolderTitle', 'Configurely') | Configurely</title>
    @show
    
	{{ HTML::script('js/lib/jquery-1.11.1.min.js') }}
    
	{{ HTML::style('css/lib/bootstrap-3.2.0.min.css') }}
	{{ HTML::style('css/lib/bootstrap-theme.min.css') }}
	{{ HTML::style('css/app.css') }}
    
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	
    @yield('PlaceHolderAdditionalPageHead')
  </head>
  <body>
    
	@section('PlaceHolderNavBar')
    <div class="navbar navbar-default navbar-static-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="http://configurely.com">Configurely</a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><a href="/about">What is this?</a></li>
                    <li><a href="/about/api">API Documentation</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    @if(Auth::check())
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->email; }} <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="/logout">Log out</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Manage Applications</a></li>
                        </ul>
                    </li>
                    @else
                    <li><a href="/login">Log in</a></li>
                    <li><a href="/signup">Sign up</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    @show
    
	@if(Session::get('flash_message'))
    <div class="alert alert-{{ Session::get('flash_severity') }}" role="alert">{{ Session::get('flash_message') }}</div>
	@endif
    
	@section('PlaceHolderMain')
    <div class="container">
   
		@yield('PlaceHolderMainForm')
        
    </div>
	@show
    
	{{ HTML::script('js/lib/bootstrap-3.2.0.min.js') }}
	{{ HTML::script('js/app.js') }}
    @yield('PlaceHolderAdditionalScript')
    
  </body>
</html>