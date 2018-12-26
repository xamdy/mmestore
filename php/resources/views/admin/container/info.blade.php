@extends('admin/public/header')
</head>

<body>
<div class="wrap">
    <ul class="nav nav-tabs">
        <li><a href="{{url('admin/container/index')}}">体验店列表</a></li>
        <li class="active"><a href="{{url('admin/container/info')}}">查看体验店</a></li>
    </ul>
	<form method="post" class="form-horizontal js-ajax-form margin-top-20" id="form" action="#">

		<div class="form-group">
			<label for="input-user_login" class="col-sm-2 control-label">体验店编号</label>
			<div class="col-md-2 col-sm-10">
                {{$res['info']->container_number}}
			</div>
		</div>

        <div class="form-group">
            <label  class="col-sm-2 control-label">所属酒店</label>
            <div class="col-md-2 col-sm-10">
                {{$res['info']->hotel}}
            </div>
        </div>

        <div class="form-group">
            <label  class="col-sm-2 control-label">所属房间</label>
            <div class="col-md-2 col-sm-10">
                {{$res['info']->room}}
            </div>
        </div>

		<div class="form-group">
			<label  class="col-sm-2 control-label">体验店状态</label>
			<div class="col-md-2 col-sm-10">
				@if($res['info']->status == 1)
                    使用中
                    @elseif($res['info']->status == 2)
                    维修中
                    @else
                    废弃
				@endif
			</div>
		</div>

        <div class="form-group">
            <label  class="col-sm-2 control-label">保修提醒次数</label>
            <div class="col-md-2 col-sm-10">
                {{$res['info']->error_num}}
            </div>
        </div>

		<div class="form-group">
			<label  class="col-sm-2 control-label">体验店二维码</label>
			<div class="col-md-2 col-sm-10">
				<img src="{{ asset($res['info']->img) }}" alt="" width="120px" height="120px">
			</div>
		</div>

		<div class="form-group">

			<label  class="col-sm-2 control-label">商品信息</label>
			<div class="col-md-6 col-sm-10">

				<table class="table table-hover table-bordered">
					<thead>
					<tr>
						<th>条形码</th>
						<th>商品名称</th>
						<th>售价</th>
						<th>优先级</th>
					</tr>
					</thead>
					<tbody id="add">
					@foreach($res['goods'] as $vo)
						<tr id=goods_{{$vo->barcode}}>
							<td>{{$vo->barcode}}</td>
							<td>{{$vo->goods_name}}</td>
							<td>{{$vo->present_price}}</td>
							<td>{{$vo->level}}</td>

						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<a class="btn btn-default" href="#"  onclick="back();">返回</a>
			</div>
		</div>

	</form>

</div>

</body>
</html>

<script src="{{ asset('/js/jquery-1.8.3.min.js')}}"></script>

<script>

    // 返回上一步骤
    function back() {
        return history.go(-1);
    }

</script>