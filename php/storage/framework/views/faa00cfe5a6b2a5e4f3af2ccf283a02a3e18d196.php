</head>
<!-- 1.添加meta csrf_token -->
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<body>
	<div class="wrap js-check-wrap">
		<ul class="nav nav-tabs">
			<li class="active"><a href="<?php echo e(url('admin/container/index')); ?>">体验店列表</a></li>
			<li><a href="<?php echo e(url('admin/container/add')); ?>">添加体验店</a></li>
			<li><a href="<?php echo e(url('admin/container/excel')); ?>">批量添加</a></li>
		</ul>
        <form class="well form-inline margin-top-20" method="post" action="<?php echo e(url('admin/container/index')); ?>">

            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
            体验店编号:
            <input type="text" class="form-control" name="container_number"
             style="width: 120px;" value="" placeholder="请输入体验店">

			酒店名称:
			<select class="form-control" name="h_id" id="h_id" >
				<option value="" selected >请选择酒店</option>
				<?php $__currentLoopData = $hotel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option  value="<?php echo e($v1['id']); ?>"   ><?php echo e($v1['name']); ?></option>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</select>

            状态:
			<select class="form-control" name="status">
				<option value="">全  部</option>
				<option value="1">使用中</option>
				<option value="2">维修中</option>
				<option value="3">废弃</option>
			</select>
            <input type="submit" class="btn btn-primary" value="搜索" />

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
				<?php $__currentLoopData = $contList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr>
					<td><?php echo e($vo['container_number']); ?></td>
					<td><?php echo e($vo['hotel_name']); ?></td>
					<td><?php echo e($vo['room_name']); ?></td>
					<td><?php echo e($vo['z_num']); ?></td>
					<td><?php echo e($vo['num']); ?></td>
					<?php if($vo['status'] == 2): ?>
						<td>维修中</td>
					<?php elseif($vo['status'] == 1): ?>
						<td>使用中</td>
					<?php else: ?>
						<td>废弃</td>
					<?php endif; ?>
					<td>
						<?php if($vo['error_num'] == 0): ?>
							<?php echo e($vo['error_num']); ?>

						<?php else: ?>
							<a  href="#" onclick="select_err(<?php echo e($vo['id']); ?>)" ><?php echo e($vo['error_num']); ?></a>
						<?php endif; ?>
					</td>
					<td>
                        <?php if($vo['status'] == 3): ?>
                            <?php else: ?>
                            <a  href="<?php echo e(asset($vo['img'])); ?>" download="<?php echo e($vo['container_number']); ?>"><img src="">下载</a>
                            <?php endif; ?>
                    </td>

                    
                        
                    


					<td> <a  href='<?php echo e(url("admin/container/edit?id=$vo[id]")); ?>'>编辑</a>
						<a  href="<?php echo e(url('admin/container/info',array('id'=>$vo['id'],'error_num'=>$vo['error_num'],'hotel'=>$vo['hotel_name'],'room'=>$vo['room_name']))); ?>">查看</a>
					</td>
				</tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</tbody>
		</table>

	<div class="pagination"><?php echo e($res->links()); ?></div>
	</div>
	<script src="<?php echo e(asset('/static/themes/admin_simpleboot3/public/assets/js/jquery-1.10.2.min.js')); ?>"></script>
	<script src="<?php echo e(asset('/static/static/js/admin.js')); ?>"></script>
	<script type="text/javascript" src="<?php echo e(asset('/lib/layer/2.4/layer.js')); ?>"></script>
	<script type="text/javascript" src="<?php echo e(asset('/static/static/js/My97DatePicker/4.8/WdatePicker.js')); ?>"></script>
	<script src="<?php echo e(asset('/js/jquery-1.8.3.min.js')); ?>"></script>
	<script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var _token = $('meta[name="csrf-token"]').attr('content');

		//查询报修次数
		function  select_err (id){
			var url = "<?php echo e(url('admin/container/selectErr')); ?>";
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
<?php echo $__env->make('admin/public/header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>