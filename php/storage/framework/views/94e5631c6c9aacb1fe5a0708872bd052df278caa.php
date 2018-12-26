</head>
<style>
    table th{
        text-align: center;
    }
</style>
<body>
<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li class="active"><a href="javascript:;">数据列表</a></li>
    </ul>
    <form class="well form-inline margin-top-20" method="get" action="<?php echo e(url('admin/lock/list')); ?>">
        <?php echo e(csrf_field()); ?>

        体验店编号:&nbsp;
        <select name="container_id" class="form-control" id="container">
            <option value="">请选择...</option>
            <?php $__currentLoopData = $con; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option  value="<?php echo e($value->id); ?>" <?php if($value->id == $data->container_id): ?> selected <?php endif; ?>><?php echo e($value->container_number); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        房间号:
        <select name="room_id" class="form-control" id="room">
            <option value="">请选择...</option>
            <?php $__currentLoopData = $room; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option  value="<?php echo e($value->id); ?>"  <?php if($value->id == $data->room_id): ?> selected <?php endif; ?>><?php echo e($value->room_number); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        酒店名称：
        <select name="hotel_id" class="form-control" id="hotel">
            <option value="">请选择...</option>
            <?php $__currentLoopData = $hotel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option  value="<?php echo e($value->id); ?>"  <?php if($value->id == $data->hotel_id): ?> selected <?php endif; ?>><?php echo e($value->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        开锁时间:
        <input type="text" lass="form-control" style="width:120px;height:32px;" onClick="WdatePicker({readOnly:true,maxDate:'%y-%M-%d'})" id="start_time"  name="start_time"
               <?php if(isset($data->start_time)): ?>
               value="<?php echo e($data->start_time); ?>"
                <?php endif; ?>
        >

        -<input type="text" lass="form-control" style="width:120px;height:32px;" onClick="WdatePicker({readOnly:true,maxDate:'%y-%M-%d'})" id="end_time"   name="end_time"
                <?php if(isset($data->end_time)): ?>
                value="<?php echo e($data->end_time); ?>"
                <?php endif; ?>
        >

        <input type="submit" class="btn btn-primary" value="筛选"/>
        <a class="btn btn-danger" href="<?php echo e(url('admin/lock/list')); ?>">清空</a>
        <a class="btn btn-info pull-right" href="<?php echo e(url('admin/ceshi/closeLock')); ?>">查看关锁数据</a>
    </form>
    <form class="js-ajax-form" action="" method="post">

        <table class="table table-hover table-bordered table-list" style="text-align: center">
            <thead>
            <tr>
                <th width="100">开锁人姓名</th>
                <th width="80">开锁人手机号</th>
                <th width="100">体验店编号</th>
                <th width="60">酒店名称</th>
                <th width="100">房间类型</th>
                <th width="90">房间编号</th>
                <th width="90">开锁状态</th>
                <th width="90">开锁时间</th>
                <th width="90">操作</th>
            </tr>
            </thead>
            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e(json_decode($v->name)); ?></td>
                <td><?php echo e($v->tel); ?></td>
                <td><?php echo e($v->container_number); ?></td>
                <td><?php echo e($v->hotel_name); ?></td>
                <td><?php echo e($v->room_name); ?></td>
                <td><?php echo e($v->room_number); ?></td>
                <td>
                    <?php if($v->status == 0): ?>
                        开锁成功
                    <?php else: ?>
                        开锁失败
                    <?php endif; ?>
                </td>
                <td><?php echo e(date("Y-m-d H:i:s",$v->create_time)); ?></td>
                <td >
                    <a href="<?php echo e(url('admin/lock/details',[$v->id])); ?>">查看</a>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </table>
        <ul class="pagination">
        <?php echo e($data->appends(array('start_time'=>$data->start_time,'end_time'=>$data->end_time,'room_id'=>$data->room_id,'hotel_id'=>$data->hotel_id,'container_id'=>$data->container_id))->render()); ?>

        </ul>
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