<!DOCTYPE html>
<html ng-app="bookApp">
    <head>
        <title></title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/main.css">
    </head>
    <body>
        <nav class="navbar navbar-default" role="navigation">
            <div class="container">
              <!-- Brand and toggle get grouped for better mobile display -->
              <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Wriiite</a>
              </div>

              <!-- Collect the nav links, forms, and other content for toggling -->
              <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav">
                  <li active-link="active"><a href="#">Books</a></li>
                  <li active-link="active"><a href="#/users/">Users</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                  <li><a href="#">Login</a></li>
                  <li><a href="#">Signup</a></li>
                </ul>
              </div><!-- /.navbar-collapse -->
            </div>
        </nav>
        <div class="container repeat-item" ng-view="">

        </div>
        <!-- ANGULAR + RESSOURCES FOR ANGULAR -->
        <script type="text/javascript" src="libs/angular.min.js"></script>
        <script type="text/javascript" src="libs/angular-ressource.min.js"></script>
        <script type="text/javascript" src="libs/angular-animate.min.js"></script>
        <script type="text/javascript" src="libs/angular-route.min.js"></script>

        <!-- LIBS -->
        <script type="text/javascript" src="libs/jquery.min.js"></script>

        <!-- APP LOGIC -->
        <script type="text/javascript" src="app/app.js"></script>
        <script src="app/controllers/controllers.js"></script>
        <script src="app/factories/factories.js"></script>
        <script src="app/directives/directives.js"></script>
        <script src="app/filters/filters.js"></script>

    </body>
</html>