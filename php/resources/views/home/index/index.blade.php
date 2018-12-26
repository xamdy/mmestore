<body style="background-color:white;">
<br/><br/><br/><br/><br/>

<div class="col-xs-6 col-md-1">
</div>

<div class="col-xs-12 col-sm-6 col-md-6" style="margin-top:5%;" >
	<!-- 日志表格 -->
	<div class="bs-example" data-example-id="hoverable-table" >
		<table class="table">
			<thead>
				<tr>
				  <th></th>
				  <th>id</th>
				  <th>内容</th>
				  <th>操作</th>
				</tr>
			</thead>
			<tbody>
				@forelse( $data as $key=>$log )
					<tr>
						<td></td>
						<td>{{ $log->id }}</td>
						<td>{{ $log->name }}</td>
						<td><a href="{{ url('home/index/destroy', [$log->id]) }}">删除</a></td>
						<td><a href="{{ url('home/index/update', [$log->id,$log->name]) }}">编辑</a></td>
					</tr>
				@empty
					<tr>
						<td scope="row">暂无数据</td>
					</tr>
				@endforelse
			</tbody>
		</table>
		<form action="{{url('home/index/notify')}}" method="post">
			{{csrf_field()}}
			<input type="text" name="name" id="name" value="" />
			<input type="submit" value="添加"/>
		</form>
	</div>
{{--<div class="col-xs-12 col-sm-6 col-md-6" style="margin-top:5%;" >--}}
	{{--<!-- 日志表格 -->--}}
	{{--<div class="bs-example" data-example-id="hoverable-table" >--}}
		{{--<table class="table">--}}
			{{--<thead>--}}
				{{--<tr>--}}
				  {{--<th></th>--}}
				  {{--<th>操作者</th>--}}
				  {{--<th>操作说明</th>--}}
				  {{--<th>操作时间</th>--}}
				{{--</tr>--}}
			{{--</thead>--}}
			{{--<tbody>--}}
				{{--@forelse( $log_info as $key=>$log )--}}
					{{--<tr>--}}
						{{--<td></td>--}}
						{{--<td>{{ $log->name }}</td>--}}
						{{--<td>{{ $log->content }}</td>--}}
						{{--<td>{{ $log->time }}</td>--}}
					{{--</tr>--}}
				{{--@empty--}}
					{{--<tr>--}}
						{{--<td scope="row">暂无数据</td>--}}
					{{--</tr>--}}
				{{--@endforelse--}}
			{{--</tbody>--}}
		{{--</table>--}}
	{{--</div>--}}
{{--</div>--}}


{{--<div>--}}
	{{--<tr>--}}
		{{--<th></th>--}}
		{{--<th>充值金额：</th>--}}
		{{--<th><input type="text" id="yuan">元</th>--}}
	{{--</tr>--}}

	{{--<br>--}}
	{{--<tr>--}}
		{{--<button  class="zhi">支付宝</button>--}}
		{{--<button  class="wei">微信</button>--}}
	{{--</tr>--}}
{{--</div>--}}

<!-- 1.添加meta csrf_token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src=" {{ asset('/js/jquery-1.8.3.min.js') }} "></script>

<script type="text/javascript" >

	<!-- 2. 设置全局ajax选项 -->
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	var _token = $('meta[name="csrf-token"]').attr('content');

	$(".zhi").click(function () {
		$.ajax({
			url: "{{ url('home/index/weixin') }}",
			type: 'POST',
			dataType: 'json',
			data:{
				price: $("#yuan").val(),
				order_sn:'123456',
				_token: _token,
			},
			success:function(json){
				alert(json);
			}
		})
	})
	
</script>

