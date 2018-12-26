@extends('admin/public/header')
</head>
<!-- 1.添加meta csrf_token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<body>
	<div class="wrap">

        <ul class="nav nav-tabs">
            <li><a href="{{url('admin/container/index')}}">体验店列表</a></li>
            <li class="active"><a href="{{url('admin/container/edit')}}">编辑体验店</a></li>
        </ul>

		<form method="post" class="form-horizontal js-ajax-form margin-top-20" id="form" action="{{url('admin/container/edit')}}">
			<input type="hidden" name="_token" value="{{csrf_token()}}">
			<div class="form-group">
				<label for="input-user_login" class="col-sm-2 control-label">体验店编号</label>
				<div class="col-md-2 col-sm-10">
					<input type="text" class="form-control" readonly value="{{$res['info']->container_number}}" id="container_number" name="container_number">
					<input type="hidden" class="form-control"  value="{{$res['info']->id}}" id="id" name="id">
				</div>
			</div>
			<div class="form-group">
				<label for="input-user_login" class="col-sm-2 control-label">体验店锁编号</label>
				<div class="col-md-2 col-sm-10">
					<input type="text" class="form-control"  value="{{$res['info']->lock_code}}" onBlur="update_code()" id="code" name="code">
					<input type="hidden" class="form-control" readonly  value="{{$res['info']->lock_code}}" id="lock_code" >
				</div>
			</div>
			<div class="form-group">
				<label  class="col-sm-2 control-label">体验店状态</label>
				<input type="hidden" class="form-control" readonly  value="{{$res['info']->status}}" id="status_bk" >
				<div class="col-md-2 col-sm-10">
					<select class="form-control" name="status" id="status" onchange="update_status()">
						<option value="1"  {{$res['info']->status== '1' ? 'selected'  :''}} >使用中</option>
						<option value="2"  {{$res['info']->status== '2' ? 'selected'  :''}} >维修中</option>
						<option value="3"  {{$res['info']->status== '3' ? 'selected'  :''}} >废弃</option>
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
						@foreach($res['goods'] as $vo)
							<tr id="goods_{{$vo->barcode}}">
								<td> <input type='hidden' class='lzt' value="{{$vo->barcode}}" id="" name="goods[{{$vo->goods_id}}][barcode]">
									  <input type='hidden'  value="{{$vo->goods_id}}" name="goods[{{$vo->goods_id}}][goods_id]">
									<input type='hidden'  value="{{$vo->inventory_status}}" id="inventory_status_{{$vo->barcode}}" >
										{{$vo->barcode}}
								</td>
								<td>{{$vo->goods_name}}</td>
								<td>{{$vo->present_price}}</td>
								<td><input type='text' style='width: 30px;' value='{{$vo->level}}'  name="goods[{{$vo->goods_id}}][level]">

								</td>
								<td><a href='#' onclick='del_goods({{$vo->barcode}})' ><i class='fa fa-minus-circle normal'></i></a></td>
							</tr>
						@endforeach
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
					<a class="btn btn-default" href="#"  onclick="add()">保存修改</a>
                    <button type="button" class="btn btn-default" onclick="back()">返回</button>
				</div>
			</div>

		</form>

	</div>

	<script src="{{ asset('/static/themes/admin_simpleboot3/public/assets/js/jquery-1.10.2.min.js')}}"></script>

	<script type="text/javascript" src="{{ asset('/lib/layer/2.4/layer.js') }}"></script>

<script>

    function back() {
        return history.go(-1);
    }

	$.ajaxSetup({

		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	var _token = $('meta[name="csrf-token"]').attr('content');

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
				var c_id =	$('#id').val();
				var reg = /^[0-9]+.?[0-9,/]*$/;
				//正则验证输入信息是否正确
				if (reg.exec(num)) {
					//提交输入信息并返回data数据
					var url = "{{url('admin/container/checkGoods')}}";
					$.ajax({
						type:"post",
						url:url,
						data:{'num':num,'c_id':c_id,_token:_token},
						dataType:"json",
						success:function(data){
							console.log(data);
							//判断返回状态
							if(data.code== 1){
								$('#error_num').html('&nbsp;&nbsp;以上条形码不存在或已下架');
							}else if(data.code== 2){
								$('#error_num').html('&nbsp;&nbsp;条形码'+data['msg']+'不存在或已下架');
							}else if(data.code== 4){
								$('#error_num').html('&nbsp;&nbsp;以上部分条形码已存在');
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
					$('#error_num').html('&nbsp;&nbsp;格式错误');
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
		var inventory_status = $('#inventory_status_'+barcode ).val();
		if(inventory_status == '3' ){
			layer.msg('商品已锁定不饿能删除',{icon: 2});
			return false;
		}else{
			$('#goods_'+barcode).remove();
		}

	}

	//体验店信息验证后提交
	function add(){

		var barcode = $('.lzt').val();
		if(!barcode){
			layer.msg('请添加体验店商品');
			return false;
		}
		$("#form").submit();

	}

	//体验店信息验证后提交
	function update_status(){

		var status = $('#status').val();

		if(status == 3 ){
			//询问框
			layer.confirm(' 此修改解除与房间的绑定且过程不可逆！', {icon: 2, title:'您确定把状态改为废弃吗？',
				btn: ['确定','取消'] //按钮
			}, function(){
				update_do(1);
			}, function(){
				//取消当前选中
				var va2 = $('#status').val();
				//$("#status option:eq(va2)").attr('selected',false);
				//$("#status").find("option[value='va2']").attr('selected',false);
				//恢复之前选中
				//var va1 = $('#status_bk').val();
				$("#status option:eq(va1)").attr('selected',false);
				//$("#status").find("option[value='va1']").attr("selected",true);
			});
		}else{
			update_do(1);
		}
	}

	//体验店信息验证后提交
	function update_code(){
		//询问框
		layer.confirm('确定要修改吗？', {
			btn: ['确定','取消'] //按钮
		}, function(){
			update_do(2);
		}, function(){
			//恢复之前数值
			var value = $('#lock_code').val();
			$('#code').val(value);
		});

	}
	function update_do(status){

		var id = $('#id').val();
		if(status == 1){
			var column = 'status';
			var value = $('#status').val();
		}else{
			var column = 'lock_code';
			var value = $('#code').val();
		}

		var url = "{{url('admin/container/updateStatus')}}";
		$.ajax({
			type:"post",
			url:url,
			data:{'id':id,'column':column,'value':value,_token:_token},
			dataType:"json",
			success:function(data) {
				//console.log(data);
				if (data) {
					if(status == 1){
						//$('#status_bk').val(value);
					}else{
						$('#code').val(value);
					}
					layer.msg('修改成功',{icon: 1});
				}else{
					layer.msg('修改失败',{icon: 2});
				}
			}
		})

	}
</script>
</body>
</html>