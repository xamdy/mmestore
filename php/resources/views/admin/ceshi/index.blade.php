@extends('admin/public/header')
</head>
<body>
    <div class="wrap js-check-wrap">
        <ul class="nav nav-tabs">
            <li class="active"><a href="javascript:;">设备列表</a></li>
            <li ><a href="{{ url('admin/ceshi/index') }}">设备添加</a></li>
        </ul>
        <form class="js-ajax-form" action="" method="post">

            <table class="table table-hover table-bordered table-list">
                <thead>
                    <tr>
                        <th >序号</th>
                        <th >硬件编号</th>
                        <th >添加时间</th>
                        <th >操作</th>
                    </tr>
                </thead>
                @foreach($list as $k => $v)
                <tr>
                    <td>
                        {{ $v['id'] }}
                    </td>
                    <td>
                        {{ $v['number'] }}
                    </td>
                    <td>
                        {{ isset($v['time']) ? date('Y-m-d H:i:s',$v['time']) : 0 }}
                    </td>
                    <td>
                        <a href="{{url('admin/ceshi/opend',array('id'=>$v['number']))}}">开锁</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="{{url('admin/ceshi/close',array('id'=>$v['number']))}}">关闭灯带</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="{{url('admin/ceshi/del',array('id'=>$v['id']))}}" class="js-ajax-delete">删除</a>
                    </td>
                </tr>
                @endforeach
            </table>
        </form>
    </div>
</body>
</html>
<script src="{{ asset('static/themes/admin_simpleboot3/public/assets/js/jquery-1.10.2.min.js')}}"></script>
<script src="{{ asset('static/layer/layer.js')}}"></script>
<script>
    function selectComment(id){
        var url  = "{{ url('admin/room/addGoods') }}/"+id;
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
