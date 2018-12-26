@extends('admin/public/header')
</head>
<!-- 1.添加meta csrf_token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<body>
	<div class="wrap js-check-wrap">
		<ul class="nav nav-tabs">
			<li class="active"><a href="{{url('admin/container/index')}}">体验店列表</a></li>
			<li><a href="{{url('admin/container/add')}}">添加体验店</a></li>
			<li><a href="{{url('admin/container/excel')}}">批量添加</a></li>
		</ul>
        <form class="well form-inline margin-top-20" method="post" action="{{url('admin/container/index')}}">

            <input type="hidden" name="_token" value="{{csrf_token()}}">
            体验店编号:
            <input type="text" class="form-control" name="container_number"
             style="width: 120px;" value="" placeholder="请输入体验店">

			酒店名称:
			<select class="form-control" name="h_id" id="h_id" >
				<option value="" selected >请选择酒店</option>
				@foreach($hotel as $v1)
					<option  value="{{$v1['id']}}"   >{{$v1['name']}}</option>
				@endforeach
			</select>

            状态:
			<select class="form-control" name="status">
				<option value="">全  部</option>
				<option value="1">使用中</option>
				<option value="2">维修中</option>
				<option value="3">废弃</option>
			</select>
            <input type="submit" class="btn btn-primary" value="搜索" />
{{--			<a class="btn btn-primary"  style="text-align: right;"  href="{{url('admin/container/add')}}">添加体验店</a>--}}
        </form>

		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th>体验店编号</th>
					<th>所属酒店</th>
					<th>所属房间</th>
					<th>商品种类</th>
					<th>剩余库存</th>
					<th>状态</th>
					<th>报修提醒</th>
					<th>二维码</th>
					<th width="130">	操作</th>
				</tr>
			</thead>
			<tbody>
				@foreach($contList as $vo)
				<tr>
					<td>{{$vo['container_number']}}</td>
					<td>{{$vo['hotel_name']}}</td>
					<td>{{$vo['room_name']}}</td>
					<td>{{$vo['z_num']}}</td>
					<td>{{$vo['num']}}</td>
					@if($vo['status'] == 2)
						<td>维修中</td>
					@elseif($vo['status'] == 1)
						<td>使用中</td>
					@else
						<td>废弃</td>
					@endif
					<td>
						@if($vo['error_num'] == 0)
							{{$vo['error_num']}}
						@else
							<a  href="#" onclick="select_err({{$vo['id']}})" >{{$vo['error_num']}}</a>
						@endif
					</td>
					<td>
                        @if($vo['status'] == 3)
                            @else
                            <a  href="{{ asset($vo['img']) }}" download="{{$vo['container_number']}}"><img src="">下载</a>
                            @endif
                    </td>

                    {{--<a href="https://www.baidu.com">--}}
                        {{--<img src="http://avatar.csdn.net/2/0/3/1_lsm135.jpg">--}}
                    {{--</a>--}}


					<td> <a  href='{{url("admin/container/edit?id=$vo[id]")}}'>编辑</a>
						<a  href="{{url('admin/container/info',array('id'=>$vo['id'],'error_num'=>$vo['error_num'],'hotel'=>$vo['hotel_name'],'room'=>$vo['room_name']))}}">查看</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>

	<div class="pagination">{{$res->links()}}</div>
	</div>
	<script src="{{ asset('/static/themes/admin_simpleboot3/public/assets/js/jquery-1.10.2.min.js')}}"></script>
	<script src="{{ asset('/static/static/js/admin.js')}}"></script>
	<script type="text/javascript" src="{{ asset('/lib/layer/2.4/layer.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/static/static/js/My97DatePicker/4.8/WdatePicker.js')}}"></script>
	<script src="{{ asset('/js/jquery-1.8.3.min.js')}}"></script>
	<script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var _token = $('meta[name="csrf-token"]').attr('content');

		//查询报修次数
		function  select_err (id){
			var url = "{{url('admin/container/selectErr')}}";
			$.ajax({
				type:"post",
				url:url,
				data:{'id':id,_token:_token},
				dataType:"json",
				success:function(data){
					console.log(data);
					//页面层-自定义
					var aHtml = '' ;
					$.each(data, function(i, item){
						aHtml+='&nbsp;&nbsp;&nbsp;&nbsp;'+item['tel']+'&nbsp;&nbsp;&nbsp;&nbsp;'+item['create_time']+'<br/>';
					});
					layer.open({
						type: 1,
						title: '报修信息',
						closeBtn: 0,
						shadeClose: true,
						area: ['250px', '150px'],
						skin: 'yourclass',
						content: aHtml
					});
				}

			})
		};

	</script>
</body>
</html>