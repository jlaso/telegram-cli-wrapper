<?php

session_start();
if (!isset($_SESSION['user'])) {
    header("location: index.php");
    die;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="favicon.ico?v=1"/>

    <title>Telegram CLI Wrapper | Proof of Concept</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"
          integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ=="
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css"
          integrity="sha384-aUGj/X2zp5rLCbBxumKTCw2Z50WgIr1vs/PFN4praOTvYXWlVyh2UtNUU0KAUhAX" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"
            integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ=="
            crossorigin="anonymous"></script>
    <script src="js/common.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">
                <img alt="Brand" src="img/telegram-lo.png" height="30">
            </a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="active">
                    <a href="#">
                        Telegram CLI wrapper
                        <small>Welcome to the user area</small>
                    </a>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
</nav>

<ul class="nav nav-tabs" id="main-nav-bar">
    <li role="presentation" class="active"><a href="#" action="home.php" class="action">Home</a></li>
    <li role="presentation"><a href="#" action="profile.php" class="action">Profile</a></li>
    <li role="presentation"><a href="#" action="messages.php" class="action">Messages</a></li>
</ul>

<div id="container" class="container">

    <div id="alert-pos"></div>

    <div id="content"></div>

    <div id="home">
        <form class="form-signin" id="main-form">
            <label for="text" class="sr-only">Text to send</label>
            <input type="text" id="text" class="form-control" placeholder="Text to send" required autofocus>
            <button class="btn btn-lg btn-primary btn-block ajax-no" id="send-button" type="submit">Send</button>
            <button class="btn btn-lg btn-info btn-block ajax-no" id="check-button">Check received</button>
        </form>

        <p class="btn btn-default btn-lg active">For now the system is not running telegram always. <br>
            So, you need to come here and click on "Check received" after you send something by telegram to me.</p>
    </div>

</div>
<!-- /container -->

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

    var me = "<?php echo $_SESSION['user']; ?>";

    function drawMessages(messages) {
        var content = '<table class="table table-stripped table-bordered">' +
            '<thead><tr><td>Date</td><td>Message</td></tr></thead><tbody>';
        for (var i = 0; i < messages.length; i++) {
            var message = messages[i];
            content += '<tr class="' + (message.from == me ? "success" : "") + '">' +
                '<td>' + message.date + '</td>' +
                '<td class="text-' + (message.from == me ? "left" : "right") + '">' + message.text + '</td>' +
                '</tr>';
        }
        content += '</tbody></table>';
        $("#content").html(content);
    }
    function drawProfile(user) {
        var content = '<table class="table table-stripped table-bordered">' +
            '<thead><tr><td>Field</td><td>Value</td></tr></thead><tbody>' +
            '<tr><td>id</td><td>' + user.id + '</td>' +
            '<tr><td>flags</td><td>' + user.flags + '</td>' +
            '<tr><td>phone</td><td>' + user.phone + '</td>' +
            '<tr><td>last name</td><td>' + user.last_name + '</td>' +
            '<tr><td>first name</td><td>' + user.first_name + '</td>' +
            '<tr><td>print name</td><td>' + user.print_name + '</td>' +
            '</tr>' +
            '</tbody></table>';
        $("#content").html(content);
    }

    $(function () {

        $("#home").show();
        $("#content").hide();

        $("#send-button").click(function(e){
            e.preventDefault();

            var text = $("#text").val();
            if (!text) {
                alert('warning', 'text is required');
                return false;
            }

            $.ajax({
                url: 'send.php',
                method: 'POST',
                data: {
                    text: text
                },
                success: function(data){
                    if (data.success) {
                        alert('success', 'message sent successfully, check your telegram app');
                    } else {
                        alert('warning', data.reason);
                    }
                },
                error: function () {
                    alert('danger', 'something wrong with the server happened');
                }

            });

            return false;
        });

        $("#check-button").click(function(e){
            e.preventDefault();

            $.ajax({
                url: 'check.php',
                method: 'POST',
                data: {},
                success: function(data){
                    if (data.success) {
                        alert('success', 'message receive and processed, please check you telegram app');
                    } else {
                        alert('warning', data.reason);
                    }
                },
                error: function () {
                    alert('danger', 'something wrong with the server happened');
                }

            });

            return false;
        });

        $(".action").click(function (e) {
            e.preventDefault();
            if (inAjax) return;

            var action = $(this).attr("action");

            $("#main-nav-bar li").removeClass('active');
            $(this).parent().addClass("active");
            if (action == "home.php") {
                $("#content").hide();
                $("#home").show();
                return;
            }

            $("#home").hide();
            $("#content").show().html("");


            $.ajax({
                url: action,
                method: 'POST',
                data: {},
                success: function (data) {
                    if (data.success) {
                        switch (action) {
                            case "messages.php":
                                drawMessages(data.messages);
                                break;
                            case "profile.php":
                                drawProfile(data.user);
                                break;
                        }
                    } else {
                        alert('warning', data.reason);
                    }
                },
                error: function () {
                    alert('danger', 'something wrong with the server happened');
                }
            });

            return false;
        });

    });
</script>
</html>