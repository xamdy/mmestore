@extends('admin/public/header')
</head>
<body>
	<div class="wrap">
		<ul class="nav nav-tabs">
			<li><a href="{{url('admin/user/index')}}">管理员</a></li>
			<li class="active"><a href="{{url('admin/user/add')}}">管理员添加</a></li>
		</ul>
		<form method="post" class="form-horizontal js-ajax-form margin-top-20" action="{{url('admin/user/addpost')}}" onsubmit='return checkForm()'>
			<div class="form-group">
				<label for="input-user_login" class="col-sm-2 control-label"><span class="form-required">*</span>用户名</label>
				<div class="col-md-6 col-sm-10">
					<input type="text" class="form-control" id="input-user_login" name="user_login">
				</div>
			</div>
			<div class="form-group">
				<label for="input-user_pass" class="col-sm-2 control-label"><span class="form-required">*</span>密码</label>
				<div class="col-md-6 col-sm-10">
					<input type="text" class="form-control" id="input-user_pass" name="user_pass" placeholder="******">
				</div>
			</div>
			<div class="form-group">
				<label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>邮箱</label>
				<div class="col-md-6 col-sm-10">
					<input type="text" class="form-control" id="input-user_email" name="user_email">
				</div>
			</div>
			<div class="form-group">
				<label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>角色</label>
				<div class="col-md-6 col-sm-10">
					@foreach($role as $vo)
						<label class="checkbox-inline">
							<input value="{{$vo->id}}" type="checkbox" name="role_id[]" >

							{{$vo->name}}
						</label>
					@endforeach
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<input type="hidden" class="form-control" value="{{ Session::token() }}" name="_token">
					<button type="submit" class="btn btn-primary js-ajax-submit">添加</button>
				</div>
			</div>
		</form>
	</div>
	<script src="{{ asset('/fileUpload/js/jquery-2.1.3.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/lib/layer/2.4/layer.js') }}"></script>
	<script type="text/javascript">
	    function checkForm(){
        var name = $("input[name='user_login']").val();
        var app = $("input[name='user_pass']").val();
       
        if(!name){
            layer.msg('请您输入用户名',{icon:2,time:2000});return false;
        }
        if(!app){
            layer.msg('请您输入密码',{icon:2,time:2000}); return false;
        }
        return true;
    }
    </script>
</body>
</html>