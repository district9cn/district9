
        <div class="container">

            <!-- 新建docker表单和docker列表 -->
            <form id="connect" class="form-horizontal" action="../control/docker_create.php">
                <fieldset>
                    <legend>docker列表</legend>

                    <?php
                        require_once('../core/init.php');

                        $user = new UserModel();
                        if($user->getLog()) {
                            $userinfo = $user->getData();
                            $docker = new DockerModel();
                            if($docker->find((int)$userinfo['id'])) {
                                $list = $docker->getData();
                            }
                        }
                    ?>
                    <div class="form-actions">
                        <div class="control-group">
                        <label class="control-label">
                            名称
                        </label>
                        <div class="controls">
                            <input  type="text" class="input-xlarge" id="name" name="name"/>
                            <button type="button" class="btn btn-primary" id="create">
                                 新建
                            </button>
                        </div>
                        </div>
                    </div>

                    <table class="table table-hover">
                       <thead>
                          <?php
                            if(!empty($list)) {
                              echo '<tr>';
                              echo   '<th style="width:40%">ID</th>';
                              echo   '<th style="width:40%">名称</th>';
                              echo   '<th style="width:20%">操作</th>';
                              echo '</tr>';
                            }
                          ?>
                       </thead>
                       <tbody>
                          <?php
                            if(!empty($list)) {
                                foreach($list as $doc) {
                                    echo '<tr>';
                                        echo '<td>'.$doc['id'].'</td>';
                                    echo '<td id="name" data-toggle="tooltip" title="单击更改名称，长度最大32位">'.$doc['name'].'</td>';
                                        echo '<td>';
                                            echo '<a class="btn btn-default" href="../control/docker_lunch.php?id='.$doc['id'].'" role="button">启动</a>';
                                            echo '<a class="btn btn-default" href="../control/docker_del.php?id='.$doc['id'].'" role="button">删除</a>';
                                        echo '</td>';
                                    echo '<tr/>';
                                }
                            }
                          ?>
                       </tbody>
                    </table>

                <!-- 点击提交按钮时弹出页面提交中 -->
                <div id="doing" runat="server" style="display:none; Z-INDEX: 12000; LEFT: 0px; WIDTH: 100%; CURSOR: wait; POSITION: absolute; TOP: 0px; HEIGHT: 100%"> 
                    <table width="100%" height="100%"> 
                        <tr align="center" valign="middle"> 
                            <td> 
                                <table style="background-color:#58ACFA; FILTER: Alpha(Opacity=75); WIDTH: 200px; HEIGHT: 100px"> 
                                    <tr align="center" valign="middle"> 
                                        <td>页面提交中...</td> 
                                    </tr> 
                                </table> 
                            </td> 
                        </tr> 
                    </table> 
                </div>

                </fieldset>
            </form>

            <!-- 如果登陆前创建过docker, 弹出提示框是否添加  -->
            <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">  
                <div class="modal-header">  
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>  
                    <h3 id="myModalLabel">提示</h3>  
                </div>  
                <div class="modal-body">  
                    <p></p>  
                </div>  
                <div class="modal-footer">  
                    <button class="btn" data-dismiss="modal" aria-hidden="true">取消</button>  
                    <button id="addDocker" class="btn btn-primary">添加</button>  
                </div>  
            </div>  

        </div>

        <script type="application/javascript" src="../static/javascripts/jquery.min.js"></script>
        <script src="../static/javascripts/bootstrap.min.js"></script>
        <script type='application/javascript'>
            $(document).ready(function() {
                //点击列表中的启动和删除显示处理中提示框
                $('#connect table tr td a').each(function() {
                    $(this).click(function(){
                        $('#doing').show();
                    });
                });

                //创建按钮事件
                $('#create').click(function(ev) {
                    $('.error').removeClass('error');

                    var name = $('input:text#name');
                    if (!name.val()) {
                        name.closest('.control-group')
                            .addClass('error');
                        return false;
                    }

                    $('#doing').show();
                    $('#connect').submit();
                });

                //点击列表中的docker名称，更改名称处理
                $('#connect table tr td:nth-child(2)').each(function() {
                    $(this).click(function(){
                        var tdObj = $(this);  

                        if (tdObj.children("input").length > 0) {  
                            return false;  
                        }  

                        //单击docker名称显示输入框
                        var text = tdObj.html();   
                        tdObj.html("");  
                        var inputObj = $("<input type='text'>").css("border-width","1")  
                            .width("300px")  
                            .css("background-color",tdObj.css("background-color"))  
                            .val(text).appendTo(tdObj);  
                        inputObj.trigger("focus").trigger("select");  

                        inputObj.click(function() {  
                            return false;  
                        });  

                        //键盘按键处理，回车键和esc键
                        inputObj.keyup(function(event){  
                            var keycode = event.which;  
                            if (keycode == 13) {  
                                var inputtext = $(this).val();  
                                if(inputtext.length > 32 || 
                                        inputtext.indexOf("'") != -1 || 
                                        inputtext.indexOf("\"") != -1 ||
                                        inputtext.indexOf("\\") != -1 ||
                                        inputtext.indexOf("&") != -1){
                                    inputObj.css("border-color", "#b94a48");
                                    inputObj.css("box-shadow", "0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 6px #d59392");
                                    inputObj.focus(function(){
                                        inputObj.css("box-shadow", "0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 6px #d59392");
                                    });
                                    inputObj.blur(function(){
                                        inputObj.css("box-shadow", "none");
                                    });
                                    return;
                                }
                                tdObj.html(inputtext);  

                                var id = tdObj.parent().children(0).html();
                                var params = "id="+id+"&name="+inputtext;  
                                var url = "../control/docker_chname.php";

                                $.ajax({  
                                    type: "post",  
                                    url: url,  
                                    dataType: "json",  
                                    data: params,  
                                    success: function(msg){  
                                        if(msg == false) {
                                            tdObj.html(text);  
                                        }
                                    }  
                                });  
                            }  

                            if (keycode == 27) {  
                                tdObj.html(text);  
                            }  
                        });  
                    });
                });

                //判断登陆前是否创建了docker
                var dockername = "<?php echo Session::get(Config::get('session/newdocker')) ?>";
                if(dockername) {
                    var isAdd;
                    var url = "../control/docker_add.php";

                    $('#myModal').modal('show');
                    $('#myModal .modal-body p').html("新创建的docker:"+dockername+"是否添加？");

                    //选择取消或者关闭
                    $('#myModal').on('hide.bs.modal', function () {
                        isAdd = false;
                        var params = "id="+dockername+"&isAdd="+isAdd;  

                        $.ajax({  
                            type: "post",  
                            url: url,  
                            dataType: "json",  
                            data: params,  
                            success: function(msg){  
                            }  
                        });  
                    });

                    //选择确定 
                    $('#addDocker').click(function(ev) {
                        isAdd = true;
                        var params = "id="+dockername+"&isAdd="+isAdd;  

                        $.ajax({  
                            type: "post",  
                            url: url,  
                            dataType: "json",  
                            data: params,  
                            success: function(msg){  
                                location.href = './';
                            }  
                        });  
                        $('#myModal').modal('hide');
                    });

                }
            });
        </script>
