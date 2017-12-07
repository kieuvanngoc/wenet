<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{$page_title}</title>
    <!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700|Lato:400,100,300,700,900' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="{$PG_URL_HOMEPAGE}templates/css/animate.css">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="{$PG_URL_HOMEPAGE}templates/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
</head>

<body>
<div class="container">
    <div class="top">
        <h1 id="title" class="hidden">
            <span id="logo">System <span>CRM</span></span>
        </h1>
    </div>
    <div class="login-box animated fadeInUp">
        <form action='login.php' method='POST' autocomplete='off'>
            <div class="box-header">
                <h2>Log In</h2>
            </div>
            {if $errorTxt}
                <label class="block clearfix">
                    <span class="block input-icon input-icon-right">
                        <div class='error'>{$errorTxt}</div>
                    </span>
                </label>
                <br/>
            {/if}
            <label for="username">Username</label>
            <br/>
            <input type="text" id="username" name="username" placeholder="Tên đăng nhập" />
            <br/>
            <label for="password">Password</label>
            <br/>
            <input type="password" id="password" name="password" placeholder="Mật khẩu" />
            {if $captcha!=false}
                <br/>
                <label class="block clearfix">
                    <span class="block input-icon input-icon-right">
                        {$captcha}
                    </span>
                </label>
            {/if}
            <br/>
            <button type="submit">Sign In</button>
            <br/>
            <a href="#"><p class="small">Forgot your password?</p></a>
            <input type='hidden' name='task' value='dologin'>
            <NOSCRIPT><input type='hidden' name='javascript' value='no'></NOSCRIPT>
        </form>
    </div>
</div>
</body>

<script>
    $(document).ready(function () {
        $('#logo').addClass('animated fadeInDown');
        $("input:text:visible:first").focus();
    });
    $('#username').focus(function() {
        $('label[for="username"]').addClass('selected');
    });
    $('#username').blur(function() {
        $('label[for="username"]').removeClass('selected');
    });
    $('#password').focus(function() {
        $('label[for="password"]').addClass('selected');
    });
    $('#password').blur(function() {
        $('label[for="password"]').removeClass('selected');
    });
</script>
</html>