
        <div class="container">
            <!-- Connection form -->

            <form id="connect" class="form-horizontal" action="../control/docker_lunch.php">
                <fieldset>
                    <legend>启动一个docker</legend>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            启动
                        </button>
                    </div>

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

        </div>

        <script type="application/javascript" src="../static/javascripts/jquery.min.js">
        </script>
        <script type='application/javascript'>
            $(document).ready(function() {
                $('#connect').submit(function(ev) {
                    $('#doing').show();
                });
            });
        </script>
