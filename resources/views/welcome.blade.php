<!DOCTYPE html>
<html lang="fr" ng-app="cooplivre">
    <head>
        <title>Livre - Coop ETS</title>
        <link href="https://fonts.googleapis.com/css?family=Lato:100,400,500,700" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=RobotoDraft:300,400,500,700,400italic">
        <link rel="stylesheet" type="text/css" href="{{asset('css/main.css')}}">
        <script src="{{asset('js/all.js')}}"></script>
        <script src="https://checkout.stripe.com/checkout.js"></script>
        <meta name="viewport" content="width=device-width, user-scalable=no">
    </head>
    <body>
        <div coop-header></div>
        <div class="content">
            <div ng-view class="wrap"></div>
        </div>
        <div class="footer">

        </div>
    </body>
</html>
