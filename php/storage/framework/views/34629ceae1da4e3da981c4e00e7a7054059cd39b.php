</head>
<body>

<!-- 1.添加meta csrf_token -->
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li class="active"><a href="<?php echo e(url('admin/member/index')); ?>">用户列表</a></li>
    </ul>

    <form class="well form-inline margin-top-20" method="post" action="<?php echo e(url('admin/member/index')); ?>">
        <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">

        手机号:
        <input type="text" class="form-control" name="tel" style="width: 120px;" value="" placeholder="请输入手机号">&nbsp;&nbsp;&nbsp;&nbsp;

        语种：
        <select name="cate_id" class="form-control">
            <option value="0">所有语言</option>
            <option  value="1">中文</option>
            <option  value="2">英文</option>
        </select>


        <input type="submit" class="btn btn-primary" value="筛选" />
        <a class="btn btn-danger" href="<?php echo e(url('admin/member/index')); ?>">清空</a>
    </form>

    <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th width="100">用户id</th>
            <th>用户昵称</th>
            <th>性别</th>
            <th>手机号</th>
            <th>语言</th>
            <th>注册时间</th>
            <th width="130">购买记录</th>
        </tr>
        </thead>
        <tbody>

        <?php $__currentLoopData = $res; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($vo->user_id); ?></td>
                <td><?php echo e($vo->name); ?></td>

                <td>
                    <?php if($vo->sex==1): ?>
                        男
                    <?php else: ?>
                        女
                    <?php endif; ?>
                </td>

                <td><?php echo e($vo->tel); ?></td>
                <td>
                    <?php if($vo->languages==1): ?>
                        中文
                    <?php else: ?>
                        English
                    <?php endif; ?>
                </td>
                <td><?php echo e(date('Y-m-d H:i:s',$vo->register_time)); ?></td>
                <td>

                    <a href="<?php echo e(url("admin/order/index?user_id=$vo->user_id")); ?>">详情</a>
                </td>


            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <?php echo e($res->links()); ?>

    

    <div class="badge" style="width:100%;text-align:center;font-size:18px;margin-bottom:70px;" >
        共<?php echo e($res->total()); ?>条数据
    </div>

    
</div>
<script src="<?php echo e(asset('/static/themes/admin_simpleboot3/public/assets/js/jquery-1.10.2.min.js')); ?>"></script>
<script src="<?php echo e(asset('/static/static/js/admin.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('/lib/layer/2.4/layer.js')); ?>"></script>

<script type="text/javascript">

    //     2.设置全局ajax选项
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var _token = $('meta[name="csrf-token"]').attr('content');



</script>
</body>
</html>
<?php echo $__env->make('admin/public/header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>