<html>
    <head>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/custom.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Oswald:700' rel='stylesheet' type='text/css'>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <script src="js/jquery-1.9.0.js"></script>
        <script src="js/bootstrap.min.js"></script>
        @yield('head')
    </head>

    <body style="background-image:url(img/background.jpg)">
        <nav class="navbar navbar-default navbar-static-top" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                @section('navbar-header')
                <a class="navbar-brand" href="http://localhost/makeadiff.in/home/makeadiff/public_html/madapp/index.php/common_dashboard/dashboard_view">MADApp</a>
                @show
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse-1">
                <ul class="nav navbar-nav">
                    @section('navbar-links')
                    <li><a href="telephone-internet">Monthly</a></li>
                    <li><a href="travel">Travel</a></li>
                    <li><a href="status">Status</a></li>
                    @show
                </ul>

            </div>
        </nav>
        @yield('body')

    </body>
</html>