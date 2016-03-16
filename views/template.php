<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="views/img/favicon.ico">
        <title>EverWinter MU Online - Private Server</title>

        <!-- Bootstrap core CSS -->
        <link href="views/css/bootstrap.min.css" rel="stylesheet">
        <link href="views/css/bootstrap-formhelpers.min.css" rel="stylesheet">
        <link href="views/css/template.css" rel="stylesheet">

        <script src="views/js/jquery.min.js"></script>
        <script src="views/js/jquery-clock.js"></script>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </head>

    <body>

        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.php"><img src="views/img/everwinter-logo-dark-bg.png" class="img-responsive" alt="EverWinter" id="logo"></a>
                </div>
                <div id="navbar" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li <?php echo ('main' === $module) ? 'class="active"' : '' ?>><a href="index.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a></li>
                        <li <?php echo ('register' === $module) ? 'class="active"' : '' ?>><a href="?module=register">Sign Up</a></li>
                        <li <?php echo ('download' === $module) ? 'class="active"' : '' ?>><a href="?module=download">Download</a></li>
                        <li <?php echo ('ranking' === $module) ? 'class="active"' : '' ?>><a href="?module=ranking">Ranking</a></li>
                        <!--<li><a href="http://forum.everwintermu.com" title="Message Board" target="_blank">Message Board</a></li>-->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">More <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="http://forum.everwintermu.com" title="Message Board" target="_blank">Message Board</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="?module=server_information">Server Information</a></li>
                            </ul>
                        </li>
                    </ul>
                    <?php if (!empty($accountId)) {
                        echo '<div class="navbar-right">
                                <ul class="nav navbar-nav">
                                    <li><div id="loggedInAs">Logged in as: <b>'. $accountId .'</b></div></li>
                                    <li ' , ('account' === $module) ? 'class="active"' : '' , '><a href="?module=account" title="Your Account"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></a></li>
                                    <li><a href="?module=account&action=logout" title="Log Out"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></a></li>
                                </ul>
                            </div>';
                    } else {
                    ?>
                    <form class="navbar-form navbar-right navbar-input-group" action="?module=account" method="post">
                        <div class="form-group">
                            <input type="text" placeholder="User Name" name="user" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <input type="password" placeholder="Password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span></button>
                    </form>
                    <?php 
                    }
                    ?>
                </div><!--/.nav-collapse -->
            </div>
        </nav>
        <div id="everwinterLogo"></div> 
        <div class="container">
                <div id="header">
                        <!--MAIN-->
                </div>
                <div id="content">
                    <?php include($module . MODULE_FILE_EXTENSION); ?>
                </div>
        </div><!-- /.container -->

        <footer class="footer">
            <div class="container">
                <p class="text-muted text-center">EverWinter MU Online &copy; 2016. All right reserved.</p>
            </div>
        </footer>

        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="views/js/bootstrap.min.js"></script>
        <script src="views/js/bootstrap-formhelpers.min.js"></script>
        <script src="views/js/custom.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="views/js/ie10-viewport-bug-workaround.js"></script>
    </body>
</html>
