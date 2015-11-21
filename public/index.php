<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="favicon.ico?v=1"/>

    <title>Telegram CLI Wrapper | Proof of Concept</title>    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css" integrity="sha384-aUGj/X2zp5rLCbBxumKTCw2Z50WgIr1vs/PFN4praOTvYXWlVyh2UtNUU0KAUhAX" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>
    <script src="js/common.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        body {
            padding-top: 40px;
            padding-bottom: 40px;
        }
    </style>
</head>

<body>

<div id="container" class="container">

    <div id="alert-pos"></div>

    <form class="form-signin" id="main-form">
        <h2 class="form-signin-heading">telegram-cli-wrapper</h2>
        <h2 class="form-signin-heading">Proof of Concept</h2>
        <label for="phone" class="sr-only">Phone number with country code</label>
        <input type="text" id="phone" class="form-control" placeholder="Phone number with country code" required autofocus>
        <label for="inputPassword" class="sr-only">Code</label>
        <input type="password" id="inputPassword" class="form-control" placeholder="Code">
        <div class="checkbox">
            <label>
                <input id="i-am-new" type="checkbox" value="remember-me"> I am new
            </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block ajax-no" id="button" type="submit">Access</button>
    </form>


</div> <!-- /container -->

<div class="container-fluid">
    <div class="row">
        <p class="text-center">
            <img src="img/ajax-loader.gif" class="ajax-yes" id="loader">
        </p>
    </div>
</div>

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="js/ie10-viewport-bug-workaround.js"></script>

</body>

<script>

    $(function(){

        $("#i-am-new").change( function() {
            if ($(this).is(':checked')) {
                $("#inputPassword").hide();
                $("#button").html("Register");
            }else{
                $("#inputPassword").show();
                $("#button").html("Access");
            }
        });

        $("#main-form").submit(function(e){

            e.preventDefault();
            console.log("submitting");

            if ($("#i-am-new").is(':checked')) {
                $.ajax({
                    url: 'register.php',
                    method: 'POST',
                    data: {
                        phone: $("#phone").val()
                    },
                    success: function(data){
                        if (data.success){
                            alert('success', 'code sent through telegram to phone '+$("#phone").val());
                        }else{
                            alert('warning', data.reason);
                        }
                    },
                    error: function(){
                        alert('danger', 'something wrong with the server happened');
                    }
                })
            }else{

                var code = $("#inputPassword").val();
                if (!code) {
                    alert('warning', 'you have to write your access code to enter');
                    return false;
                }

                $.ajax({
                    url: 'login.php',
                    method: 'POST',
                    data: {
                        phone: $("#phone").val(),
                        code: code
                    },
                    success: function(data){
                        if (data.success){
                            alert('success', 'login successful');
                            window.location = "area.php";
                        }else{
                            alert('warning', data.reason);
                        }
                    },
                    error: function(){
                        alert('danger', 'something wrong with the server happened');
                    }
                });

            }


            return false;

        });

    });
</script>
</html>