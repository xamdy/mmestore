</head>
<body>
    <div class="wrap js-check-wrap">
        <ul class="nav nav-tabs">
            <li class="active"><a href="javascript:;">所有房间</a></li>
            <li><a href="<?php echo e(url('admin/room/add')); ?>">添加房间</a></li>
        </ul>
        <form class="well form-inline margin-top-20" method="get" action="<?php echo e(url('admin/room/index')); ?>">
            <?php echo e(csrf_field()); ?>

            房间类型:
            <input type="text" class="form-control js-bootstrap-datetime" name="room_name"
               value=""
               style="width: 140px;" autocomplete="off">&nbsp; &nbsp;
            酒店名称:
            <select class="form-control" name="id" style="width: 140px;">
                <option value='0'>请选择</option>
                <?php $__currentLoopData = $hotel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value='<?php echo e($v->id); ?>'><?php echo e($v->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select> &nbsp;&nbsp;
            <input type="submit" class="btn btn-primary" value="筛选"/>
            <a class="btn btn-danger" href="<?php echo e(url('admin/room/index')); ?>">清空</a>
            
            
            <a class="btn btn-info pull-right" href="<?php echo e(url('admin/room/plugins')); ?>">批量导入</a>
        </form>
        <form class="js-ajax-form" action="" method="post">

            <table class="table table-hover table-bordered table-list">
                <thead>
                    <tr>
                        <th >序号</th>
                        <th >房间编号</th>
                        <th >酒店名称</th>
                        <th >房间类型</th>
                        <th >商品种类</th>
                        <th >商品余量</th>
                        <th >操作</th>
                    </tr>
                </thead>
                <?php $__currentLoopData = $roomlList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <?php echo e($v->id); ?>

                    </td>
                    <td>
                        <?php echo e($v->room_number); ?>

                    </td>
                    <td>
                        <?php echo e($v->name); ?>

                        <br/>
                        <span class="form-required" style="font-size: 12px"><?php echo e($v->name_en); ?></span>
                    </td>
                    <td>
                        <?php echo e($v->room_name); ?>

                    </td>
                    <td>
                        <?php echo e($v->goods_count); ?>

                    </td>
                    <td>
                        <?php echo e($v->goods_num); ?>

                    </td>
                    <td>
                        <a href="<?php echo e(url('admin/room/show',[$v->id])); ?>">查看</a>
                        <a href="<?php echo e(url('admin/room/edit',[$v->id])); ?>" class="js-ajax-delete">编辑</a>
                        <a href="javascript:;" onclick="selectComment(<?php echo e($v->id); ?>)" class="btn btn-default" role="button">补货</a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </table>
            <ul class="pagination"><?php echo e($roomlList->links()); ?></ul>
        </form>
    </div>
</body>
</html>
<script src="<?php echo e(asset('static/themes/admin_simpleboot3/public/assets/js/jquery-1.10.2.min.js')); ?>"></script>
<script src="<?php echo e(asset('static/layer/layer.js')); ?>"></script>
<script>
    function selectComment(id){
        var url  = "<?php echo e(url('admin/room/addGoods')); ?>/"+id;
        layer.open({
          type: 2,
          title: '房间补货',
          shadeClose: true,
          shade: 0.8,
          area: ['50%', '90%'],
          content: url //iframe的url
      }); 


    }
</script>

<?php echo $__env->make('admin/public/header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>