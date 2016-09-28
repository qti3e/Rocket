<!doctype html>
<html lang="en" ng-app="rocketApp">
<head>
    <meta charset="utf-8">
    <script type="application/javascript">
        var host        = "{%host | 127.0.0.1}";
        var port        = "{%port | 8085}";
    </script>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/styles.css">
    <script src="assets/js/angular.js"></script>
    <script src="assets/js/angular-animate.js"></script>
    <script src="assets/js/angular-ui-router.js"></script>
    <script src="app/app.js"></script>
    <title ng-bind="$state.current.name + ' - Rocket'">Rocket framework</title>
</head>
<body>
<nav class="navbar navbar-fixed-top navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#" title="{%version | Rocket}">Rocket</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
                <li><a href="#">Link</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#"><i class="glyphicon glyphicon-log-out"></i> Log out</a></li>
            </ul>
        </div>
    </div>
</nav>

<div ui-view class="container slide" style="padding-top: 80px;"></div>
<footer class="navbar navbar-fixed-bottom">
    <hr>
    <div class="navbar-inner">
        <div class="container text-center">
            This app is running under <b>{%version | Rocket}</b>, created by <a href="https://github.com/qti3e" title="QTIƎE" target="_blank">Alireza Ghadimi</a>.
        </div>
    </div>
</footer>
</body>
</html>
