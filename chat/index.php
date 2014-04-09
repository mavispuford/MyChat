<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MyChat</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles -->
    <link href="css/jumbotron-narrow.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <!--<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>-->
      <!--<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>-->
    <!--[endif]-->
  </head>
  <body>
    <div class="container">
        <div class="header">
            <ul class="nav nav-pills pull-right">
                <li class="active"><a href="#">Home</a></li>
                <li><a href="#">About</a></li>
            </ul>
            <h3 class="text-muted">MyChat</h3>
        </div>

        <div class="jumbotron">
<!--            <h1>Jumbotron heading</h1>-->
            <p>Message 1</p>
            <p>Message 2</p>
            <p>Message 3</p>
            <p>Message 4</p>
            <p>Message 5</p>
            <!--            <p class="lead">Cras justo odio, dapibus ac facilisis in, egestas eget quam. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>-->
<!--            <p><a class="btn btn-lg btn-success" href="#" role="button">Sign up today</a></p>-->
<!--            --><?php //require 'mychat.php' ?>
        </div>

        <div class="row">

            <form ng-submit="addMessage()">
                <div class="col-lg-8">
                    <input type="text" class="form-control" ng-model="contents" placeholder="Message" />
                </div>
                <div class="col-lg-4">
                    <input type="text" class="form-control" ng-model="username" placeholder="Anonymous" />
                </div>
                <div class="col-lg-12">
                    <input class="btn btn-primary pull-right" style="margin: 4px;" type="submit" value="Submit" id="btn-chat" />
                </div>
            </form>
<!--                <h4>Subheading</h4>-->
<!--                <p>Donec id elit non mi porta gravida at eget metus. Maecenas faucibus mollis interdum.</p>-->

        </div>

        <div class="footer">
            <p>&copy; Blake Ross 2014</p>
        </div>

    </div> <!-- /container -->
	
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>