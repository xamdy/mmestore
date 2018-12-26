
<!DOCTYPE html>


<!-- 1.添加meta csrf_token -->
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<html lang="en" class="no-js">
    <head>

        <meta charset="utf-8">
        <title>后台管理</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <!-- CSS -->
        <link href="<?php echo e(asset('/static/static/login/reset.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('/static/static/login/supersized.css')); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo e(asset('/static/static/login/style.css')); ?>" rel="stylesheet">
        <style type="text/css">
            body{
                background-image:url(<?php echo e(asset('/static/static/3.png')); ?>);
            }
        </style>
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>

    <body>

        <div class="page-container">
            <h1>后&nbsp;&nbsp;&nbsp;台&nbsp;&nbsp;&nbsp;管&nbsp;&nbsp;&nbsp;理</h1>
            <form id="form" method="post">
                <input type="text" name="admin_name" class="username" id="admin_name" placeholder="帐号">
                <input type="password" name="admin_password" id="admin_password" class="password" placeholder="密码">

                <button type="button" id="submits">登&nbsp;&nbsp;&nbsp;录</button>
           
            </form>
        </div>

        <!-- Javascript -->
        <script>
        var img1 = "<?php echo e(asset('/static/static/1.png')); ?>";
        var img2 = "<?php echo e(asset('/static/static/2.png')); ?>";
        var img3 = "<?php echo e(asset('/static/static/3.png')); ?>";
        </script>
        <script src="<?php echo e(asset('/static/themes/admin_simpleboot3/public/assets/js/jquery-1.10.2.min.js')); ?>"></script>
        <script src="<?php echo e(asset('/static/static/login/supersized.3.2.7.min.js')); ?>"></script>
        <script src="<?php echo e(asset('/static/static/login/supersized-init.js')); ?>"></script>
        <script src="<?php echo e(asset('/static/static/login/scripts.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo e(asset('/lib/layer/2.4/layer.js')); ?>"></script>
        <script>

            //     2.设置全局ajax选项
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var _token = $('meta[name="csrf-token"]').attr('content');
 
    $(document).ready(function() {
    $(document).keyup(function(event) {
      if(event.keyCode ==13){
                var $u = $("#admin_name").val();
                var $p = $("#admin_password").val();
                if(!$u || !$p)
                {
                    layer.msg('用户名或密码不能为空');
                }else
                {
                 $.ajax({
                    url: "<?php echo e(asset('admin/logins/doLogin')); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        'admin_name': $('#admin_name').val(),
                        'admin_password': $('#admin_password').val(),
                        '_token': _token

                    },
                    success: function(json) {

                        if(json.code == "1")
                        {
                             layer.msg(json.msg);
                            window.location.href="<?php echo e(asset('admin/index/index')); ?>";
                        }
                        else
                        {
                            layer.msg(json.msg);
                        }
                    }
                })

                }
        }
    });
    //敲击回车键调用登录方法
    function downLogin(){
        if(window.event.keyCode == 13){
            document.all('submits').click();
        }
    }
    $("#submits").click(function() {
        var $u = $("#admin_name").val();
        var $p = $("#admin_password").val();
        if(!$u || !$p)
        {
            layer.msg('用户名或密码不能为空');
        }else{
           $.ajax({
                    url: "<?php echo e(asset('admin/logins/doLogin')); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        'admin_name': $('#admin_name').val(),
                        'admin_password': $('#admin_password').val(),
                        '_token': _token
                    },
                    success: function(json) {
                        if(json.code == "1")
                        {
                            layer.msg(json.msg);
                            window.location.href="<?php echo e(asset('admin/index/index')); ?>";
                        }else
                        {
                            layer.msg(json.msg);
                        }
                    }
                })
        }
    });
});
</script>
    </body>

</html>

