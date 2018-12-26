@extends('admin/public/header')
</head>
<body>
	<div class="wrap js-check-wrap">
		<ul class="nav nav-tabs">
			<li class="active"><a href="{{url('admin/user/index')}}">管理员</a></li>
			<li><a href="{{url('admin/user/add')}}">管理员添加</a></li>
		</ul>
        <form class="well form-inline margin-top-20" method="get" action="{{url('admin/user/index')}}">
            用户名:
            <input type="text" class="form-control" name="user_login"
             @if(isset($res->user_login))
             value="{{$res->user_login}}" 
             @endif
             style="width: 120px;" value="" placeholder="请输入用户名">
            邮箱:
            <input type="text" class="form-control" name="user_email" 
@if(isset($res->user_login))
            value="{{$res->user_email}}"
@endif
             style="width: 120px;" value="" placeholder="请输入邮箱">
            <input type="submit" class="btn btn-primary" value="搜索" />
            <a class="btn btn-danger" href="{{url('admin/user/index')}}">清空</a>
        </form>
		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th width="50">ID</th>
					<th>用户名</th>
					<th>最后登录IP</th>
					<th>最后登录时间</th>
					<th>邮箱</th>
					<th>	状态</th>
					<th width="130">	操作</th>
				</tr>
			</thead>
			<tbody>
				<!-- <php>$user_statuses=array("0"=>lang('USER_STATUS_BLOCKED'),"1"=>lang('USER_STATUS_ACTIVATED'),"2"=>lang('USER_STATUS_UNVERIFIED'));</php> -->
				@foreach($res as $vo)
				<tr>
					<td>{{$vo->id}}</td>
					<td>
					
					{{$vo->user_login}}
					
					</td>
					<td>{{$vo->last_login_ip}}</td>
					<td>
						<if condition="$vo['last_login_time'] eq 0">
						@if($vo->last_login_time==0) 
						@else
							{{date('Y-m-d H:i:s',$vo->last_login_time)}}
						@endif
					</td>
					<td>{{$vo->user_email}}</td>
					<td>@if($vo->user_status==1) 正常 @else 拉黑 @endif </td>
					<td>
						@if($vo->id==1)
							<font color="#cccccc">编辑</font>  <font color="#cccccc">删除</font>
							
								<!-- <font color="#cccccc">拉黑</font> -->
							
						@else


							<a href='{{url("admin/user/edit",array("id"=>$vo->id))}}'>编辑</a>
							<a class="js-ajax-delete"  href="{{url('admin/user/delete',array('id'=>$vo->id))}}">删除</a>
							<a class="js-ajax-black"  href="{{url('admin/user/black',array('id'=>$vo->id))}}">拉黑</a>
							<!-- <a href="{{url('admin/user/ban',array('id'=>$vo->id))}}" class="js-ajax-dialog-btn" data-msg="">拉黑</a>
							
							<a href="{{url('adin/user/cancelban',array('id'=>$vo->id))}}" class="js-ajax-dialog-btn" data-msg="">44</a> -->
						@endif
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		<!-- <div class="pagination">{{$res->links()}}</div> -->
		{!! $res->appends(array('user_login'=>$res->user_login,'user_email'=>$res->user_email))->render() !!}
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
        // 拉黑
        $('.js-ajax-black').click(function(){
                var url = $(this).attr('href');
               if(confirm('你确定要拉黑吗？')){
                            window.location=url;
                }
                return false;
        })
    </script>
</body>
</html>