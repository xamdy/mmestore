@extends('admin/public/header')

</head>
<body>
	<div class="wrap js-check-wrap">
		<ul class="nav nav-tabs">
			
			<li ><a href="{{url('admin/menu/index')}}">后台菜单</a></li>
			<li><a href="{{url('admin/menu/add/0/1')}}">添加菜单</a></li>
			<li class="active"><a href="javacript:;">所有菜单</a></li>
		</ul>
		<form class="form-horizontal js-ajax-form" action="{:url('menu/listorders')}" method="post">
		
			<div class="alert alert-warning" style="margin: 0 0 5px 0;">
				 请在开发人员指导下进行以上操作！
			</div>
			<table class="table table-hover table-bordered table-list">
				<thead>
					<tr>
						<th width="50">ID</th>
						<th>菜单英文名称</th>
						<th width="50">状态</th>
						<th width="90">管理操作</th>
					</tr>
				</thead>
				@foreach($res as $vo)
				<tr>
					<td>{{$vo->id}}</td>
					<td>{{$vo->name}}:{{$vo->app}}/{{$vo->controller}}/{{$vo->action}}</td>
					<td>
				
					@if($vo->status===1)
					显示
					@else
					隐藏
					@endif
					</td>
					<td>
						<a href="{{url('admin/menu/edit',array('mid'=>$vo->id,'type'=>1))}}">编辑</a>
						<a class="js-ajax-delete" href="{{url('admin/menu/delete',array('id'=>$vo->id,'type'=>1))}}">删除</a>
					</td>
				</tr>
				@endforeach
				<tfoot>
					<tr>
						<th width="50">ID</th>
						<th>菜单英文名称</th>
						<th width="40">状态</th>
						<th width="80">管理操作</th>
					</tr>
				</tfoot>
			</table>
				{{ $res->links() }}
			
		</form>
	</div>
	<script src="{{ asset('/static/static/js/admin.js')}}"></script>
</body>
</html>