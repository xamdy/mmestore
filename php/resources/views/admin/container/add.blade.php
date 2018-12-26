@extends('admin/public/header')
</head>
<!-- 1.添加meta csrf_token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
	#check_warning ,#span{
		float: left;
		color: red;
		margin-top: 8px;
	}
</style>
<body>
<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li><a href="{{url('admin/container/index')}}">体验店列表</a></li>
        <li class="active"><a href="{{url('admin/container/add')}}">添加体验店</a></li>
        <li><a href="{{url('admin/container/excel')}}">批量添加</a></li>
    </ul>
		<form method="post" class="form-horizontal js-ajax-form margin-top-20" id="form" action="{{url('admin/container/add')}}">
			<input type="hidden" name="_token" value="{{csrf_token()}}">
			<div class="form-group">
				<label for="input-user_login" class="col-sm-2 control-label">体验店编号</label>
				<div class="col-md-2 col-sm-10">
					{{--鼠标离开事件检测体验店代码是否已经存在，js中根据id--}}
					<input type="text" class="form-control" pattern="[0-9]" value="" id="container_number" name="container_number">
					{{--消息提示框--}}
					{{--<div  class="form-required" >--}}
					{{--</div>--}}
				</div>
				<span id="check_warning"> </span>
			</div>
			<div class="form-group">
				<label for="input-user_login" class="col-sm-2 control-label">体验店锁编号</label>
				<div class="col-md-2 col-sm-10">
					<input type="text" class="form-control" pattern="[0-9]" value="" id="code" name="code">
				</div>
				<span id="span"> </span>
			</div>
			<div class="form-group">
				<label  class="col-sm-2 control-label">体验店状态</label>
				<div class="col-md-2 col-sm-10">
					<select class="form-control" name="status" style="height: 37px">
						<option value="1">使用中</option>
						<option value="2">维修中</option>
						<option value="3">废弃</option>
					</select>
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
							<th>操作</th>
						</tr>
						</thead>
						<tbody id="add">
							<tr>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td><a id='add_goods' onclick='add_goods()' ><i class='fa form-required fa-plus-circle normal'></i></a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<a class="btn btn-default" href="#"  onclick="add()">添加体验店</a>
				</div>
			</div>

		</form>

	</div>

	<script src="{{ asset('/static/themes/admin_simpleboot3/public/assets/js/jquery-1.10.2.min.js')}}"></script>

	<script type="text/javascript" src="{{ asset('/lib/layer/2.4/layer.js') }}"></script>

<script>

	$.ajaxSetup({

		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	var _token = $('meta[name="csrf-token"]').attr('content');

	//鼠标离开事件 查询数据库是否存在
	$("#container_number").mouseleave(function(){
		//清空提示消息
		$('#check_warning').html('');
		//获取编号
		var number = $("#container_number").val();
		console.log(number);
		var url = "{{url('admin/container/checkNumber')}}";
		// 异步提交验证
		$.ajax({
			type:"post",
			url:url,
			data:{'container_number':number,'status':1,_token:_token},
			dataType:"json",
			success:function(data){
			    console.log(data);
				if(data == 2){
					//清空输入框原有编号
					$('#container_number').val('');
					var text = '体验店编号:'+number+' 已存在';
					//追加新的信息
					$('#check_warning').html(text);
				}

			}

		})
	});


	$("#code").mouseleave(function(){
		//清空提示消息
		$('#span').html('');
		//获取编号
		var lock_code = $("#code").val();
		var url = "{{url('admin/container/checkNumber')}}";
		// 异步提交验证
		$.ajax({
			type:"post",
			url:url,
			data:{'lock_code':lock_code,'status':2,_token:_token},
			dataType:"json",
			success:function(data){
				if(data == 2){
					//清空输入框原有编号
					$('#code').val('');
					var text = '体验锁编号:'+lock_code+' 已存在';
					//追加新的信息
					$('#span').html(text);
				}

			}

		})
	});

	//批量添加商品
	function add_goods(){
		//弹框html编辑
		var textdiv ="<div id='textdiv'>"+
						//	输入框
						"<textarea id = 'textarea' class = 'layui-layer-input' style='height: 100px;width: 307.75px;'></textarea>"+
						// ajax请求返回信息展示
						"<p id='error_num' class='form-required'></p>"+
					"</div>";

		layer.open({
			type: 1,
			title: '输入商品条形码，多条请以 / 分割',
			content: textdiv
			,btn: ['提交', '取消']
			,yes: function(index, layero){
				//按钮【提交】的回调
				var num =	$('#textarea').val();
				var reg = /^[0-9]+.?[0-9,/]*$/;
				//正则验证输入信息是否正确
				if (reg.exec(num)) {
					//提交输入信息并返回data数据
					var url = "{{url('admin/container/checkGoods')}}";
					$.ajax({
						type:"post",
						url:url,
						data:{'num':num,_token:_token},
						dataType:"json",
						success:function(data){
							console.log(data);
							//判断返回状态
							if(data.code== 1){
								$('#error_num').html('&nbsp;&nbsp;以上条形码不存在或已下架');
							}else if(data.code== 2){
								$('#error_num').html('&nbsp;&nbsp;条形码'+data['msg']+'不存在或已下架');
							}else if(data.code== 3){
								var aHtml = '' ;
								//循环返回数据,组装标签
								$.each(data.msg, function(i, item){
									var existence = $('#barcode_'+item.barcode).val();
									//判断商品条形码是否存在，如果不存在追加标签
									if(!existence){
										//组装form提交返回的商品数组信息
										// *注 html中可以根据input标签name的命名把表单数据组装成多维数组返回给后台。例如：name=goods[goods_id]['barcode']
										var goods1 = "goods["+item.goods_id+"][barcode]";
										var goods2 = "goods["+item.goods_id+"][goods_id]";
										var goods3 = "goods["+item.goods_id+"][level]";
										//编辑追加内容
										aHtml+="<tr id=goods_"+item.barcode+">"+
													"<td> <input type='hidden' class='lzt' value="+item.barcode+" id=barcode_"+item.barcode+" name="+goods1+">"+
													"<input type='hidden'  value="+item.goods_id+" name="+goods2+">"+item.barcode+"</td>"+
													"<td>"+item.goods_name+"</td>"+
													"<td>"+item.present_price+"</td>"+
													"<td><input type='text' style='width: 30px;' value=''  name="+goods3+"></td>"+
													"<td><a href='#' onclick='del_goods("+item.barcode+")' ><i class='fa fa-minus-circle normal'></i></a></td>"+
												"</tr>";
									}
								});
								//向add上方追加html标签，追加方式有很多可以参照相关jquery文档。
								$('#add').prepend(aHtml);
								layer.close(index);
							}
						}

					})
				}else{
					$('#error_num').html('&nbsp;&nbsp;* 请输入商品条形码');
				}
			}
			,btn2: function(index, layero){
				//按钮【取消】的回调
				//return false //开启该代码可禁止点击该按钮关闭
			}
			,cancel: function(){
				//右上角关闭回调
				//return false 开启该代码可禁止点击该按钮关闭
			}
		});

	};
	//删除商品
	function del_goods(barcode){
		$('#goods_'+barcode).remove();
	}

	//体验店信息验证后提交
	function add(){
		var container_number = $('#container_number').val();

		if(!container_number){
			layer.msg('请填写体验店编号');
			return false;
		}
        var code=$("#code").val();
        if(!code){
            layer.msg('请填写体验锁编号');
            return false;
        }
		var barcode = $('.lzt').val();

		if(!barcode){
			layer.msg('请添加体验店商品');
			return false;
		}

		$("#form").submit();

	}
</script>
</body>
</html>