@extends('admin/public/header')
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
    <form class="well form-inline margin-top-20" method="get" action="{{ url('admin/lock/list') }}">
        {{csrf_field()}}
        体验店编号:&nbsp;
        <select name="container_id" class="form-control" id="container">
            <option value="">请选择...</option>
            @foreach($con as $key=>$value)
                <option  value="{{$value->id}}" @if($value->id == $data->container_id) selected @endif>{{$value->container_number}}</option>
            @endforeach
        </select>
        房间号:
        <select name="room_id" class="form-control" id="room">
            <option value="">请选择...</option>
            @foreach($room as $key=>$value)
                <option  value="{{$value->id}}"  @if($value->id == $data->room_id) selected @endif>{{$value->room_number}}</option>
            @endforeach
        </select>
        酒店名称：
        <select name="hotel_id" class="form-control" id="hotel">
            <option value="">请选择...</option>
            @foreach($hotel as $key=>$value)
            <option  value="{{$value->id}}"  @if($value->id == $data->hotel_id) selected @endif>{{$value->name}}</option>
                @endforeach
        </select>
        开锁时间:
        <input type="text" lass="form-control" style="width:120px;height:32px;" onClick="WdatePicker({readOnly:true,maxDate:'%y-%M-%d'})" id="start_time"  name="start_time"
               @if(isset($data->start_time))
               value="{{$data->start_time}}"
                @endif
        >

        -<input type="text" lass="form-control" style="width:120px;height:32px;" onClick="WdatePicker({readOnly:true,maxDate:'%y-%M-%d'})" id="end_time"   name="end_time"
                @if(isset($data->end_time))
                value="{{$data->end_time}}"
                @endif
        >

        <input type="submit" class="btn btn-primary" value="筛选"/>
        <a class="btn btn-danger" href="{{ url('admin/lock/list') }}">清空</a>
        <a class="btn btn-info pull-right" href="{{ url('admin/ceshi/closeLock') }}">查看关锁数据</a>
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
            @foreach($data as $k => $v)
            <tr>
                <td>{{json_decode($v->name)}}</td>
                <td>{{$v->tel}}</td>
                <td>{{$v->container_number}}</td>
                <td>{{$v->hotel_name}}</td>
                <td>{{$v->room_name}}</td>
                <td>{{$v->room_number}}</td>
                <td>
                    @if($v->status == 0)
                        开锁成功
                    @else
                        开锁失败
                    @endif
                </td>
                <td>{{date("Y-m-d H:i:s",$v->create_time)}}</td>
                <td >
                    <a href="{{ url('admin/lock/details',[$v->id]) }}">查看</a>
                </td>
            </tr>
            @endforeach
        </table>
        <ul class="pagination">
        {{$data->appends(array('start_time'=>$data->start_time,'end_time'=>$data->end_time,'room_id'=>$data->room_id,'hotel_id'=>$data->hotel_id,'container_id'=>$data->container_id))->render() }}
        </ul>
    </form>
</div>
</body>
<script src="{{ asset('/static/themes/admin_simpleboot3/public/assets/js/jquery-1.10.2.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('/lib/layer/2.4/layer.js') }}"></script>
<script type="text/javascript" src="{{ asset('/static/static/js/My97DatePicker/4.8/WdatePicker.js')}}"></script>
<script>
    $(document).on('change','#container',function () {
        var id=$("#container").val();
        $.ajax({
            url:"{{ url('admin/lock/serach') }}",
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