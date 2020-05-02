<html>
<head>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Oswald:700' rel='stylesheet' type='text/css'>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <script src="js/jquery-1.9.0.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/uservoice.js"></script>
    @yield('head')
</head>

<body class="blue-red">
<nav class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            @section('navbar-header')
                <a class="navbar-brand" href="http://makeadiff.in/madapp">MADApp</a>
            @show
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse-1">
            <ul class="nav navbar-nav">
                @section('navbar-links')
                    <li><a href="telephone-internet">Monthly</a></li>
                    {{-- <li><a href="travel">Travel</a></li> --}}
                    <li><a href="status">Status</a></li>
                @show
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class=""><a href="{{URL::to('/logout')}}">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>
@yield('body')

</body>
</html>