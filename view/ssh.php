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
            require('header.php')
        ?>

        <div class="container">

            <!--  连接信息表单，没使用  -->
            <form id="connect" class="form-horizontal">
                <fieldset>
                    <legend>Connect to a remote SSH server</legend>

                    <div class="control-group">
                        <label class="control-label">
                            Destination
                        </label>
                        <div class="controls">
                            <input type="text" id="username"
                                class="input-small"
                                placeholder="root" />
                            <div class="input-prepend">
                                <span class="add-on">@</span><input
                                    type="text"
                                    id="hostname"
                                    class="input-large"
                                    placeholder="localhost" />
                                <span class="add-on">port</span><input
                                    type="text"
                                    id="portnumber"
                                    class="input-small"
                                    value=22 />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Authentication method
                        </label>

                        <div class="controls">
                            <label class="radio">
                                <input type="radio" name="authentication_method"
                                    value="password" checked />
                                    Password
                            </label>

                            <label class="radio">
                                <input type="radio" name="authentication_method"
                                    value="private_key" />
                                    Private Key
                            </label>
                        </div>
                    </div>

                    <div class="control-group" id="password_authentication">
                        <label class="control-label">
                            Password
                        </label>
                        <div class="controls">
                            <input type="password" id="password"
                                class="input-large" />
                        </div>
                    </div>

                    <div id="private_key_authentication">
                        <div class="control-group">
                            <label class="control-label">
                                Private Key
                            </label>
                            <div class="controls">
                                <textarea id="private_key" rows="6"
                                    class="input-xxlarge"></textarea>
                                <p class="help-block">
                                    Copy &amp; Paste your SSH private from
                                    <code>~/.ssh/id_rsa</code> or
                                    <code>~/.ssh/id_dsa</code>
                                </p>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">
                                Key Passphrase
                            </label>
                            <div class="controls">
                                <input type="password" id="key_passphrase"
                                    class="input-large" />
                                <p class="help-block">
                                    Enter your private key passphrase if it
                                    is encrypted. Leave empty otherwise.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Command
                        </label>
                        <div class="controls">
                            <input type="text" id="command" class="input-large" />
                            <p class="help-block">
                                Enter command to be executed or
                                empty for interactive.
                            </p>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            Connect
                        </button>
                    </div>

                </fieldset>
            </form>

            <!--  ssh窗口  -->
            <div id="term">
            </div>

        </div>

        <?php
            require('tail.php')
        ?>

        <script type="application/javascript" src="../static/javascripts/jquery.min.js">
        </script>
        <script type="application/javascript" src="../static/javascripts/term.js">
        </script>
        <script type="application/javascript" src="../static/javascripts/wssh.js">
        </script>
        <script type="application/javascript">
            //打开一个终端并对输入进行判断，如果输入exit命令则退出并重定向到主页面
            function openTerminal(options) {
                var cmd='';
                var client = new WSSHClient();
                var term = new Terminal(80, 24, function(key) {
                    if(key == '\r') {
                        if(cmd == 'exit') {
                            location.href='../view/';
                        } else {
                            cmd = '';
                        }
                    } else if(key == '\x08' || key == '\x7f') {
                        cmd = cmd.substring(0, cmd.length - 1);
                    } else {
                        cmd += key;
                    }

                    client.send(key);
                });
                term.open();
                $('.terminal').detach().appendTo('#term');
                term.resize(80, 24);
                term.write('Connecting...');
                client.connect($.extend(options, {
                    onError: function(error) {
                        term.write('Error: ' + error + '\r\n');
                    },
                    onConnect: function() {
                        // Erase our connecting message
                        term.write('\r');
                    },
                    onClose: function() {
                        term.write('\r\nConnection Reset By Peer');
                    },
                    onData: function(data) {
                        term.write(data);
                    }
                }));
            }
        </script>

        <script type='application/javascript'>
            $(document).ready(function() {
                //进行ssh登陆到docker
                var options = {
                    username: '<?php echo Config::get('docker/username') ?>',
                    password:'<?php echo Config::get('docker/password') ?>',
                    hostname: '<?php echo Session::get(Config::get('session/server')) ?>',
                    port: '<?php echo Session::get(Config::get('session/port')) ?>',
                    command: '',
                    authentication_method: 'password'
                };

                //alert(options.toSource());
                $('#connect').hide();
                $('#ssh').show();
                openTerminal(options);

                return;

                /**
                 * 以下无效。。。。。。。
                 */
                $('#ssh').hide();
                $('#private_key_authentication', '#connect').hide();

                $('input:radio[value=private_key]', '#connect').click(
                    function() {
                        $('#password_authentication').hide();
                        $('#private_key_authentication').show();
                    }
                );

                $('input:radio[value=password]', '#connect').click(
                    function() {
                        $('#password_authentication').show();
                        $('#private_key_authentication').hide();
                    }
                );

                $('#connect').submit(function(ev) {
                    ev.preventDefault();

                    function validate(fields) {
                        var success = true;
                        fields.forEach(function(field) {
                            if (!field.val()) {
                                field.closest('.control-group')
                                    .addClass('error');
                                success = false;
                            }
                        });
                        return success;
                    }

                    // Clear errors
                    $('.error').removeClass('error');

                    var username = $('input:text#username');
                    var hostname = $('input:text#hostname');
                    var portnumber = $('input:text#portnumber');
                    var command = $('input:text#command');

                    var authentication = $(
                        'input[name=authentication_method]:checked',
                        '#connect').val();
                    var options = {
                        username: username.val(),
                        hostname: hostname.val(),
                        command: command.val(),
                        authentication_method: authentication
                    };

                    var port = parseInt(portnumber.val())
                    if (port > 0 && port < 65535) {
                        $.extend(options, {port: port});
                    } else {
                        $.extend(options, {port: 22});
                    }

                    if (authentication == 'password') {
                        var password = $('input:password#password');
                        if (!validate([username, hostname, password]))
                            return false;
                        $.extend(options, {password: password.val()});
                    } else if (authentication == 'private_key') {
                        var private_key = $('textarea#private_key');
                        if (!validate([username, hostname, private_key]))
                            return false;
                        $.extend(options, {private_key: private_key.val()});
                        var key_passphrase = $('input:password#key_passphrase');
                        if (key_passphrase.val()) {
                            $.extend(options,
                                {key_passphrase: key_passphrase.val()});
                        }
                    }

                    $('#connect').hide();
                    $('#ssh').show();
                    openTerminal(options);
                });
            });
        </script>
    </body>
</html>
