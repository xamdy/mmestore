@extends('admin/public/header')
</head>
<body>
	<div class="wrap js-check-wrap">
		<ul class="nav nav-tabs">
			<li class="active"><a href="{{url('admin/rbac/index')}}">角色管理</a></li>
			<li><a href="{{url('admin/rbac/roleadd')}}">添加角色</a></li>
		</ul>
		<form action="{:url('Rbac/listorders')}" method="post" class="margin-top-20">
			<table class="table table-hover table-bordered">
				<thead>
					<tr>
						<th width="40">ID</th>
						<th align="left">角色名称</th>
						<th align="left">角色描述</th>
						<th width="60" align="left">状态</th>
						<th width="160"> 	操作</th>
					</tr>
				</thead>
				<tbody>
					@foreach($res as $vo)
					<tr>
						<td>{{$vo->id}}</td>
						<td>{{$vo->name}}</td>
						<td>{{$vo->remark}}</td>
						<td>
							@if($vo->status==1)
								<font color="red">√</font>
							@else
								<font color="red">╳</font>
							@endif
						</td>
						<td>
							@if($vo->id==1)
								<font color="#cccccc">权限设置</font>  <!-- <a href="javascript:openIframeDialog('{:url('rbac/member',array('id'=>$vo['id']))}','成员管理');">成员管理</a> | -->
								<font color="#cccccc">编辑</font>  <font color="#cccccc">删除</font>
							@else
								<a href="{{url('admin/rbac/auths',array('id'=>$vo->id)) }}">权限设置</a>
								<!-- <a href="javascript:openIframeDialog('{:url('rbac/member',array('id'=>$vo['id']))}','成员管理');">成员管理</a>| -->
								<a href="{{url('admin/rbac/edit',array('id'=>$vo->id))}}">编辑</a>
								<a class="js-ajax-delete" href="{{url('admin/rbac/delete',array('id'=>$vo->id))}}">删除</a>
							@endif
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</form>
	</div>
	<script src="{{ asset('/static/themes/admin_simpleboot3/public/assets/js/jquery-1.10.2.min.js')}}"></script>
	<script src="{{ asset('/static/static/js/admin.js')}}"></script>
	<script type="text/javascript" src="{{ asset('/lib/layer/2.4/layer.js') }}"></script>

	<script type="text/javascript">
	     //删除
        $('.js-ajax-delete').click(function(){
                var url = $(this).attr('href');
               if(confirm('你确定要删除吗？')){
                            window.location=url;
                }
                return false;
        })
    </script>
</body>
</html>