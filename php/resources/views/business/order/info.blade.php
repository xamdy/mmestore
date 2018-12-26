@extends('admin/public/header')
</head>
<body>
	<div class="wrap">
		<form method="post" class="form-horizontal js-ajax-form margin-top-20" action="">
			<div class="form-group">
				<label for="input-user_login" class="col-sm-2 control-label">订单编号</label>
				<div class="col-md-2 col-sm-10">
					<input type="text" class="form-control"  readonly value="{{$info->order_number}}">
				</div>
			</div>
			<div class="form-group">
				<label  class="col-sm-2 control-label">订单状态</label>
				<div class="col-md-2 col-sm-10">
					@if($info->status == 2)
						<input type="text" class="form-control" readonly value="已完成" >
					@elseif($info->status == 1)
						<input type="text" class="form-control" readonly value="待支付" >
					@else
						<input type="text" class="form-control" readonly value="已取消" >
					@endif
				</div>
			</div>
			<div class="form-group">
				<label  class="col-sm-2 control-label">订单来源</label>
				<div class="col-md-2 col-sm-10">
					<input type="text" class="form-control" readonly value="{{$info->order_address}}">
				</div>
			</div>
			<div class="form-group">
				<label  class="col-sm-2 control-label">手机号</label>
				<div class="col-md-2 col-sm-10">
					<input type="text" class="form-control" readonly value="{{$info->order_address}}">
				</div>
			</div>

            <div class="form-group">
                <label  class="col-sm-2 control-label">用户昵称</label>
                <div class="col-md-2 col-sm-10">
                    <input type="text" class="form-control" readonly value="{{$info->name}}">
                </div>
            </div>

            <div class="form-group">
                <label  class="col-sm-2 control-label">头像</label>
                <div class="col-md-2 col-sm-10">
                    <img src="{{$info->img}}" alt=""/>
                </div>
            </div>

			<div class="form-group">
				<label  class="col-sm-2 control-label">订单金额</label>
				<div class="col-md-2 col-sm-10">
					<input type="text" class="form-control" readonly value="{{$info->order_amount}}">
				</div>
			</div>
			<div class="form-group">
				<label  class="col-sm-2 control-label">支付金额</label>
				<div class="col-md-2 col-sm-10">
					<input type="text" class="form-control" readonly value="{{$info->real_amount}}">
				</div>
			</div>
			<div class="form-group">
				<label  class="col-sm-2 control-label">下单时间</label>
				<div class="col-md-2 col-sm-10">
					<input type="text" class="form-control" readonly value="{{date('Y-m-d H:i',$info->creat_time)}}">
				</div>
			</div>

			<div class="form-group">

					<label  class="col-sm-2 control-label">商品信息</label>
				<div class="col-md-6 col-sm-10">
					{{--<table class="table table-hover table-bordered">--}}
					<table class="table table-hover table-bordered">
						<thead>
						<tr>
							<th>商品名称</th>
							<th>条形码</th>
							<th>购买数量</th>
							<th>单价</th>
						</tr>
						</thead>
						<tbody>
						@foreach($list as $vo)
							<tr>
								<td>{{$vo->goods_name}}</td>
								<td>{{$vo->barcode}}</td>
								<td>{{$vo->num}}</td>
								<td>{{$vo->goods_price}}</td>

							</tr>
						@endforeach
						</tbody>
					</table>
				</div>
			</div>

		</form>

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<a class="btn btn-default" href="javascript:history.back(-1);">返回</a>
			</div>
		</div>
	</div>
	<script src="{{ asset('/static/static/js/admin.js')}}"></script>
</body>
</html>