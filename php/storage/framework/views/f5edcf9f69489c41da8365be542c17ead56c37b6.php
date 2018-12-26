<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <!-- Set render engine for 360 browser -->
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- HTML5 shim for IE8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->
     <link href="<?php echo e(asset('/static/themes/admin_simpleboot3/public/assets/themes/flatadmin/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo e(asset('/static/themes/admin_simpleboot3/public/assets/simpleboot3/css/simplebootadmin.css')); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo e(asset('/static/static/font-awesome/css/font-awesome.min.css')); ?>" rel="stylesheet" type="text/css">
   
    <!--[if lt IE 9]>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        form .input-order {
            margin-bottom: 0px;
            padding: 0 2px;
            width: 42px;
            font-size: 12px;
        }

        form .input-order:focus {
            outline: none;
        }

        .table-actions {
            margin-top: 5px;
            margin-bottom: 5px;
            padding: 0px;
        }

        .table-list {
            margin-bottom: 0px;
        }

        .form-required {
            color: red;
        }
    </style>
    <script type="text/javascript">
        //全局变量
        var GV = {
            ROOT: "__ROOT__/",
            WEB_ROOT: "__WEB_ROOT__/",
            JS_ROOT: "static/js/",
            APP: '{$Request.module}'/*当前应用名*/
        };
    </script>
    <script src="<?php echo e(asset('/static/themes/admin_simpleboot3/public/assets/js/jquery-1.10.2.min.js')); ?>"></script>
    <script src="<?php echo e(asset('/static/themes/admin_simpleboot3/public/assets/js/bootstrap.min.js')); ?>"></script>
<!--     <script src="<?php echo e(url('/static/static/js/wind.js')); ?>"></script>
    <script>
        Wind.css('/static/static/js/artDialog/artDialog.js');
        Wind.css('/static/static/js/layer/skin/default/layer.css');
        $(function () {
            $("[data-toggle='tooltip']").tooltip();
            $("li.dropdown").hover(function () {
                $(this).addClass("open");
            }, function () {
                $(this).removeClass("open");
            });
        });

        
    </script>
    <if condition="APP_DEBUG">
        <style>
            #think_page_trace_open {
                z-index: 9999;
            }
        </style>
    </if> -->