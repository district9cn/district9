<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html>
	<head>
		<meta charset="utf-8"/>
        <title> District9</title>
		<link rel='stylesheet' href='../static/stylesheets/bootstrap.css' />
		<style type="text/css">
			body {
				padding-top: 60px;
				padding-bottom: 40px;
			}
		</style>
		<link href="../static/stylesheets/bootstrap-responsive.css" rel="stylesheet">
	</head>
	
	<body>
        <?php
            require('header.php');
            require_once('../core/init.php');

            if (Session::exists(Config::get('session/userinfo'))) {
                $userinfo = unserialize(Session::get(Config::get('session/userinfo')));
                Session::delete(Config::get('session/userinfo'));
            }

            if (Session::exists(Config::get('session/error'))) {
                $validate = new Validation();
                
                echo '<div class="alert alert-error">';
                $error = Session::get(Config::get('session/error'));
                echo $validate->toErrors($error);
                echo '</div>';

                Session::delete(Config::get('session/error'));
            }
        ?>

        <div class="container">
            <!-- Connection form -->

        <form id="register" class="form-horizontal" method="post" action="../control/user_reg.php">
            <fieldset>
                <legend> 用户注册</legend>
                <div class="control-group">
                    <label  class="control-label" for="username"> 用户名</label>
                    <div class="controls">
                    <input  type="text" class="input-xlarge" id="username" name="username" 
                        value='<?php if (isset($userinfo)) echo $userinfo['username']?>'/>
                    <p class="help-block"> 你的账户名称，用于登录和显示。长度为3到20之间。</p>
                    </div>
                </div>
                <div class="control-group">
                    <label  class="control-label" for="password"> 口令</label>
                    <div class="controls">
                        <input  type="password" class="input-xlarge" id="password" name="password" value=""/>
                        <p class="help-block"> 密码长度为6到20之间。</p>
                    </div>
                </div>
                <div class="control-group">
                    <label  class="control-label" for="passwordConfirm"> 重复输入口令</label>
                    <div class="controls">
                        <input  type="password" class="input-xlarge" id="passwordConfirm" name="passwordConfirm" value=""/>
                    </div>
                </div>
                <div class="control-group">
                    <label  class="control-label" for="mail"> 邮箱</label>
                    <div class="controls">
                    <input  type="text" class="input-xlarge" id="mail" name="mail" 
                        value='<?php if(isset($userinfo)) echo $userinfo['mail']?>'/>
                    </div>
                </div>
                <div class="form-actions" >
                    <button type="submit" class="btn btn-primary" > 注册</button>
                </div>
                <input type='hidden' name='token' value='<?php echo Token::create();?>'>
            </fieldset>
        </form>

        </div>

        <?php
            require('tail.php')
        ?>

        <script type="application/javascript" src="../static/javascripts/jquery.min.js">
        </script>
	</body>
</html>
