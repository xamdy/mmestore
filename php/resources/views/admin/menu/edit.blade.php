@extends('admin/public/header')

	<div class="wrap">
		<ul class="nav nav-tabs">
			<li ><a href="{{url('admin/menu/index')}}">后台菜单</a></li>
       		<li class="active"><a href="javascript:;">编辑菜单</a></li>
        	<li><a href="{{url('admin/menu/lists')}}">所有菜单</a></li>
		</ul>
		<div class="alert alert-warning" style="margin: 0 0 5px 0;">
				 请在开发人员指导下进行以上操作！
			</div>
		<form method="post" class="form-horizontal js-ajax-form margin-top-20" action="{{url('admin/menu/doedit')}}" onsubmit='return checkForm()'>
			<div class="form-group">
				<label for="input-parent_id" class="col-sm-2 control-label"><span class="form-required">*</span>上级</label>
				<div class="col-md-6 col-sm-10">
					<select class="form-control" name="parent_id" id="input-parent_id">
						<option value="0">作为一级菜单</option>{{!! $selectCategory !!}}
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="input-name" class="col-sm-2 control-label"><span class="form-required">*</span>名称</label>
				<div class="col-md-6 col-sm-10">
					<input type="text" class="form-control" id="input-name" name="name" value="{{$res->name}}">
				</div>
			</div>
			<div class="form-group">
				<label for="input-app" class="col-sm-2 control-label"><span class="form-required">*</span>应用</label>
				<div class="col-md-6 col-sm-10">
					<input type="text" class="form-control" id="input-app" name="app" value="{{$res->app}}">
				</div>
			</div>
			<div class="form-group">
				<label for="input-controller" class="col-sm-2 control-label"><span class="form-required">*</span>控制器</label>
				<div class="col-md-6 col-sm-10">
					<input type="text" class="form-control" id="input-controller" name="controller" value="{{$res->controller}}">
				</div>
			</div>
			<div class="form-group">
				<label for="input-action" class="col-sm-2 control-label"><span class="form-required">*</span>方法</label>
				<div class="col-md-6 col-sm-10">
					<input type="text" class="form-control" id="input-action" name="action" value="{{$res->action}}">
				</div>
			</div>
			<div class="form-group">
				<label for="input-param" class="col-sm-2 control-label">参数</label>
				<div class="col-md-6 col-sm-10">
					<input type="text" class="form-control" id="input-param" name="param" value="{{$res->param}}">
					<p class="help-block">例:id=3&amp;p=3</p>
				</div>
			</div>
			<div class="form-group">
				<label for="input-icon" class="col-sm-2 control-label">图标</label>
				<div class="col-md-6 col-sm-10">
					<input type="text" class="form-control" id="input-icon" name="icon" value="{{$res->icon}}">
					<p class="help-block">
						<a href="http://www.thinkcmf.com/font/font_awesome/icons.html" target="_blank">选择图标</a> 不带前缀fa-，如fa-user => user
					</p>
				</div>
			</div>
			<div class="form-group">
				<label for="input-remark" class="col-sm-2 control-label">备注</label>
				<div class="col-md-6 col-sm-10">
					<textarea class="form-control" id="input-remark" name="remark">{{$res->remark}}</textarea>
				</div>
			</div>
			<div class="form-group">
				<label for="input-status" class="col-sm-2 control-label">状态</label>
				<div class="col-md-6 col-sm-10" id="input-status">
					<select class="form-control" name="status">
						<option value="1" @if($res->status===1) selected @endif >在左侧菜单显示</option>
						<option value="0" @if($res->status===0) selected @endif >在左侧菜单隐藏</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="input-type" class="col-sm-2 control-label">类型</label>
				<div class="col-md-6 col-sm-10">
					<select class="form-control" name="type" id="input-type">
						<option value="1" @if($res->type===1) selected @endif >有界面可访问菜单</option>
						<option value="2" @if($res->type===2) selected @endif >无界面可访问菜单</option>
						<option value="0" @if($res->type===0) selected @endif >只作为菜单</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<input type="hidden" class="form-control" value="{{ Session::token() }}" name="_token">
					<input type="hidden" class="form-control" value="{{ $res->id }}" name="id">
					<button type="submit" class="btn btn-primary js-ajax-submit">提交</button>
				</div>
			</div>
		</form>
	</div>
		<script src="{{ asset('/fileUpload/js/jquery-2.1.3.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/lib/layer/2.4/layer.js') }}"></script>
	<script type="text/javascript">
	    function checkForm(){
        var name = $("input[name='name']").val();
        var app = $("input[name='app']").val();
        var controller = $("input[name='controller']").val();
        var action = $("input[name='action']").val();
   
        if(!name){
            layer.msg('请您输入名称',{icon:2,time:2000});return false;
        }
        if(!app){
            layer.msg('请您输入应用',{icon:2,time:2000}); return false;
        }
        if(!controller){
            layer.msg('请您输入控制器',{icon:2,time:2000}); return false;
        }
        if(!action){
            layer.msg('请您输入方法',{icon:2,time:2000}); return false;
        }
        return true;
    }
    </script>
</body>
</html>