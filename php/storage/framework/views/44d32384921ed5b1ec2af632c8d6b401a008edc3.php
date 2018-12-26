</head>
<style>
    table th{
        text-align: center;
    }
</style>
<body>
<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li class="active"><a href="javascript:;">关锁列表</a></li>
    </ul>
    <form class="well form-inline margin-top-20" method="get">
      <span>只能查看当天关锁数据</span>
        <a   class="btn btn-info pull-right" style="margin-top: -5px" id="downloads" href="<?php echo e(url('admin/ceshi/export',array('data'=>json_encode($data)))); ?>" >导出</a>
    </form>
    <form class="js-ajax-form" action="" method="post">
        <table class="table table-hover table-bordered table-list" style="text-align: center">
            <thead>
            <tr>
                <th width="100">关锁人姓名</th>
                <th width="100">关锁人手机号</th>
                <th width="100">体验店编号</th>
                <th width="60">酒店名称</th>
                <th width="100">房间类型</th>
                <th width="90">房间编号</th>
                <th width="90">关锁状态</th>
                <th width="90">关锁时间</th>
            </tr>
            </thead>
            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>

                    <td>
                        <?php if($v['user_name']=='@'): ?>
                            <?php echo e($v['user_name']); ?>

                        <?php else: ?>
                            <?php echo e(json_decode($v['user_name'])); ?>

                        <?php endif; ?>
                    </td>
                    <td><?php echo e($v['user_tel']); ?></td>
                    <td><?php echo e($v['container_number']); ?></td>
                    <td><?php echo e($v['hotel_name']); ?>

                        <br/>
                        <span class="form-required" style="font-size: 12px"><?php echo e($v['hotel_name_en']); ?></span>
                    </td>
                    <td><?php echo e($v['room_name']); ?></td>
                    <td><?php echo e($v['room_number']); ?></td>
                    <td>
                        <?php if(substr($v['lock'],-1) == 1): ?>
                            关锁成功
                        <?php else: ?>
                            关锁失败
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($v['lock_time']); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </table>
    </form>
</div>
</body>
<script src="<?php echo e(asset('/static/themes/admin_simpleboot3/public/assets/js/jquery-1.10.2.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('/lib/layer/2.4/layer.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('/static/static/js/My97DatePicker/4.8/WdatePicker.js')); ?>"></script>
<script>
    $(document).on('change','#container',function () {
        var id=$("#container").val();
        $.ajax({
            url:"<?php echo e(url('admin/lock/serach')); ?>",
            type:"GET",
            dataType:"json",
            data:{"id":id},
            success:function(res){
                $("#room").empty();
                $("#hotel").empty();
                var option="<option value=''>请选择...</option>";
                $("#room").append(option);
                $("#room").append("<option value='"+res.room.id+"'>"+res.room.room_number+"</option>");
                $("#hotel").append(option);
                $("#hotel").append("<option value='"+res.hotel.id+"'>"+res.hotel.name+"</option>");
            }
        });
    })
</script>
</html>
<?php echo $__env->make('admin/public/header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>