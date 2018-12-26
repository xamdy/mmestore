@extends('admin/public/header')
</head>
<body>
	<div class="wrap">
		<ul class="nav nav-tabs">
			<li class="active"><a href="javascript:;">修改密码</a></li>
		</ul>
		<form class="form-horizontal js-ajax-form margin-top-20" onsubmit='return checkForm()' method="post" action="{{url('admin/setting/passpost')}}">
			<div class="form-group">
				<label for="input-old-password" class="col-sm-2 control-label">原始密码</label>
				<div class="col-md-6 col-sm-10">
					<input type="password" class="form-control" id="input-old-password" name="old_password">
				</div>
			</div>
			<div class="form-group">
				<label for="input-password" class="col-sm-2 control-label">新密码</label>
				<div class="col-md-6 col-sm-10">
					<input type="password" class="form-control" id="input-password" name="password">
				</div>
			</div>
			<div class="form-group">
				<label for="input-repassword" class="col-sm-2 control-label">重复新密码</label>
				<div class="col-md-6 col-sm-10">
					<input type="password" class="form-control" id="input-repassword" name="re_password">
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-primary js-ajax-submit">保存</button>
				</div>
			</div>
		</form>
	</div>
	<script src="{{ asset('/static/themes/admin_simpleboot3/public/assets/js/jquery-1.10.2.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('/lib/layer/2.4/layer.js') }}"></script>
	<!-- <script src="{{ asset('/static/static/js/admin.js')}}"></script> -->
	<script type="text/javascript">
	
    function checkForm(){
    	var old_password = $('#input-old-password').val();
    	var password = $('#input-password').val();
    	var repassword = $('#input-repassword').val();
    	if(!old_password ){
    		layer.msg('请输入原始密码',{icon:2,time:2000});return false;
    	}
    	if(!password){
    		layer.msg('请您输入密码',{icon:2,time:2000}); return false;
    	}
    	if(!repassword){
    		layer.msg('请您输入重复新密码',{icon:2,time:2000}); return false;
    	}
    	if(repassword == password){
    		return true;
    	}else{
    		layer.msg('新密码与重复新密码不一致',{icon:2,time:2000}); return false;
    	}
	  	
	}
	</script>
</body>
</html>