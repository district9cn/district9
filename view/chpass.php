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

        <form id="register" class="form-horizontal" method="post" action="../control/user_chpass.php">
            <fieldset>
                <legend> 修改密码</legend>
                <div class="control-group">
                    <label  class="control-label" for="password"> 原密码</label>
                    <div class="controls">
                        <input  type="password" class="input-xlarge" id="password" name="password" value=""/>
                    </div>
                </div>
                <div class="control-group">
                    <label  class="control-label" for="newpassword"> 新密码</label>
                    <div class="controls">
                        <input  type="password" class="input-xlarge" id="newpassword" name="newpassword" value=""/>
                    </div>
                </div>
                <div class="control-group">
                    <label  class="control-label" for="passwordConfirm"> 确认密码</label>
                    <div class="controls">
                        <input  type="password" class="input-xlarge" id="passwordConfirm" name="passwordConfirm" value=""/>
                    </div>
                </div>
                <div class="form-actions" >
                    <button type="submit" class="btn btn-primary" > 修改</button>
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
