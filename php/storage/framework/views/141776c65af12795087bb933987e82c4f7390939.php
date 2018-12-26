</head>

<body>
<div class="wrap">
    <ul class="nav nav-tabs">
        <li><a href="<?php echo e(url('admin/container/index')); ?>">体验店列表</a></li>
        <li class="active"><a href="<?php echo e(url('admin/container/info')); ?>">查看体验店</a></li>
    </ul>
	<form method="post" class="form-horizontal js-ajax-form margin-top-20" id="form" action="#">

		<div class="form-group">
			<label for="input-user_login" class="col-sm-2 control-label">体验店编号</label>
			<div class="col-md-2 col-sm-10">
                <?php echo e($res['info']->container_number); ?>

			</div>
		</div>

        <div class="form-group">
            <label  class="col-sm-2 control-label">所属酒店</label>
            <div class="col-md-2 col-sm-10">
                <?php echo e($res['info']->hotel); ?>

            </div>
        </div>

        <div class="form-group">
            <label  class="col-sm-2 control-label">所属房间</label>
            <div class="col-md-2 col-sm-10">
                <?php echo e($res['info']->room); ?>

            </div>
        </div>

		<div class="form-group">
			<label  class="col-sm-2 control-label">体验店状态</label>
			<div class="col-md-2 col-sm-10">
				<?php if($res['info']->status == 1): ?>
                    使用中
                    <?php elseif($res['info']->status == 2): ?>
                    维修中
                    <?php else: ?>
                    废弃
				<?php endif; ?>
			</div>
		</div>

        <div class="form-group">
            <label  class="col-sm-2 control-label">保修提醒次数</label>
            <div class="col-md-2 col-sm-10">
                <?php echo e($res['info']->error_num); ?>

            </div>
        </div>

		<div class="form-group">
			<label  class="col-sm-2 control-label">体验店二维码</label>
			<div class="col-md-2 col-sm-10">
				<img src="<?php echo e(asset($res['info']->img)); ?>" alt="" width="120px" height="120px">
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
					<?php $__currentLoopData = $res['goods']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr id=goods_<?php echo e($vo->barcode); ?>>
							<td><?php echo e($vo->barcode); ?></td>
							<td><?php echo e($vo->goods_name); ?></td>
							<td><?php echo e($vo->present_price); ?></td>
							<td><?php echo e($vo->level); ?></td>

						</tr>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

<script src="<?php echo e(asset('/js/jquery-1.8.3.min.js')); ?>"></script>

<script>

    // 返回上一步骤
    function back() {
        return history.go(-1);
    }

</script>
<?php echo $__env->make('admin/public/header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>