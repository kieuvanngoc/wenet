<?php /* Smarty version 2.6.26, created on 2017-12-05 13:13:45
         compiled from login.tpl */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo $this->_tpl_vars['page_title']; ?>
</title>
    <!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700|Lato:400,100,300,700,900' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?php echo $this->_tpl_vars['PG_URL_HOMEPAGE']; ?>
templates/css/animate.css">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="<?php echo $this->_tpl_vars['PG_URL_HOMEPAGE']; ?>
templates/css/style.css">
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
            <?php if ($this->_tpl_vars['errorTxt']): ?>
                <label class="block clearfix">
                    <span class="block input-icon input-icon-right">
                        <div class='error'><?php echo $this->_tpl_vars['errorTxt']; ?>
</div>
                    </span>
                </label>
                <br/>
            <?php endif; ?>
            <label for="username">Username</label>
            <br/>
            <input type="text" id="username" name="username" placeholder="Tên đăng nhập" />
            <br/>
            <label for="password">Password</label>
            <br/>
            <input type="password" id="password" name="password" placeholder="Mật khẩu" />
            <?php if ($this->_tpl_vars['captcha'] != false): ?>
                <br/>
                <label class="block clearfix">
                    <span class="block input-icon input-icon-right">
                        <?php echo $this->_tpl_vars['captcha']; ?>

                    </span>
                </label>
            <?php endif; ?>
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
    $(document).ready(function () );
    $('#username').focus(function() );
    $('#username').blur(function() );
    $('#password').focus(function() );
    $('#password').blur(function() );
</script>
</html>