@extends('admin/public/header')
</head>
<body>
    <div class="wrap js-check-wrap">
        <ul class="nav nav-tabs">
            <li class="active"><a href="javascript:;">所有房间</a></li>
            <li><a href="{{ url('admin/room/add') }}">添加房间</a></li>
        </ul>
        <form class="well form-inline margin-top-20" method="get" action="{{ url('admin/room/index') }}">
            {{csrf_field()}}
            房间类型:
            <input type="text" class="form-control js-bootstrap-datetime" name="room_name"
               value=""
               style="width: 140px;" autocomplete="off">&nbsp; &nbsp;
            酒店名称:
            <select class="form-control" name="id" style="width: 140px;">
                <option value='0'>请选择</option>
                @foreach($hotel as $k => $v)
                <option value='{{ $v->id }}'>{{ $v->name}}</option>
                @endforeach
            </select> &nbsp;&nbsp;
            <input type="submit" class="btn btn-primary" value="筛选"/>
            <a class="btn btn-danger" href="{{ url('admin/room/index') }}">清空</a>
            
            
            <a class="btn btn-info pull-right" href="{{ url('admin/room/plugins') }}">批量导入</a>
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
                @foreach($roomlList as $k => $v)
                <tr>
                    <td>
                        {{ $v->id }}
                    </td>
                    <td>
                        {{ $v->room_number }}
                    </td>
                    <td>
                        {{ $v->name}}
                        <br/>
                        <span class="form-required" style="font-size: 12px">{{ $v->name_en }}</span>
                    </td>
                    <td>
                        {{ $v->room_name }}
                    </td>
                    <td>
                        {{ $v->goods_count}}
                    </td>
                    <td>
                        {{ $v->goods_num}}
                    </td>
                    <td>
                        <a href="{{ url('admin/room/show',[$v->id]) }}">查看</a>
                        <a href="{{ url('admin/room/edit',[$v->id]) }}" class="js-ajax-delete">编辑</a>
                        <a href="javascript:;" onclick="selectComment({{$v->id}})" class="btn btn-default" role="button">补货</a>
                    </td>
                </tr>
                @endforeach
            </table>
            <ul class="pagination">{{ $roomlList->links() }}</ul>
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
