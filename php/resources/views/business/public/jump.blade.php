<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <title>后台管理</title>
    <meta http-equiv="X-UA-Compatible" content="chrome=1,IE=edge"/>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta name="robots" content="noindex,nofollow">
    <!-- HTML5 shim for IE8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->
    <link href="{{ asset('/static/themes/admin_simpleboot3/public/assets/themes/flatadmin/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/static/static/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <!--[if lt IE 9]>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div id="applyFor" style="text-align: center; width: 500px; margin: 100px auto;font-size: 20px;">
{{$message}},将在<span class="loginTime" style="color: red">{{$jumpTime}}
</span>秒后
<a href="{{url($url)}}">跳转</a>
页面</div>
<script src="{{ asset('/static/themes/admin_simpleboot3/public/assets/js/jquery-1.10.2.min.js')}}"></script>
<script type="text/javascript">
$(function(){
	var url = "{{url($url)}}"
    var checkUrl = "{{url('')}}";
    // if(url==checkUrl){
    //   url='';
    // }
    // return false;
	var loginTime = parseInt($('.loginTime').text());
	var time = setInterval(function(){
	loginTime = loginTime-1;
	$('.loginTime').text(loginTime);
		if(loginTime==0){
		clearInterval(time);
        if(url==checkUrl){
            parent.location.reload();  
        }else{
            window.location.href=url;
        }
		
		}
	},1000);
})
</script>
</body>
</html>

