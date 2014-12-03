		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<a class="brand" href="./">District9</a>
				<div class="nav-collapse">
					<ul class="nav">
						<li class="active"><a href="./">首页</a></li>
                        <?php 
                            require_once('../core/init.php');

                            $user = new UserModel();
                            if($user->getLog()) {
                                $info = $user->getData();
                                echo '<li><a href="./chpass.php">' . $info['name'] . '</a></li>';
                                echo '<li><a href="../control/user_logout.php">登出</a></li>';
                            } else {
                            
                                echo '<li><a href="./login.php">登入</a></li>';
                                echo '<li><a href="./register.php"> 注册</a></li>';
                            }
                        ?>
					</ul>
				</div>
				</div>
			</div>
		</div>
