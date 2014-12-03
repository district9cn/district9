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

            if (Session::exists(Config::get('session/sucess'))) {
                echo '<div class="alert alert-success">';
                $sucess = Session::get(Config::get('session/sucess'));
                echo Config::get('sucess/'.$sucess);
                echo '</div>';
                Session::delete(Config::get('session/sucess'));
            }

            if (Session::exists(Config::get('session/error'))) {
                echo '<div class="alert alert-error">';
                $error = Session::get(Config::get('session/error'));
                echo Config::get('error/'.$error);
                echo '</div>';
                Session::delete(Config::get('session/error'));
            }

            $user = new UserModel();
            if($user->getLog()) {
                require('user.php');
            } else {
                require('home.php');
            }

            require('tail.php')
        ?>
	</body>
</html>
