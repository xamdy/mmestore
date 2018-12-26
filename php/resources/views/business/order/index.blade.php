@extends('business/public/header')
</head>
<!-- 1.添加meta csrf_token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<body>
    <div class="wrap js-check-wrap">
        <ul class="nav nav-tabs">
            <li class="active"><a href="{{url('business/order/index')}}">订单列表</a></li>
            
        </ul>
        <form class="well form-inline margin-top-20" method="post" action="{{url('business/order/index')}}">

            <input type="hidden" name="_token" value="{{csrf_token()}}">
            订单编号:
            <input type="text" class="form-control" name="order_number"
             style="width: 120px;" value="" placeholder="请输入订单号">
            房间号:
            <select class="form-control" name="r_id" id="r_id" >
                <option value="" selected id = 'room_option'>请选择房间号</option>
                @foreach($room as $v)
                <option value="{{$v->id}}" name="r_id">{{$v->room_number}}</option>
                @endforeach
            </select>
            手机号:
            <input type="text" class="form-control" name="user_tel"
                   style="width: 120px;" value="" placeholder="请输入手机号">

            下单时间:
            <input type="text" lass="form-control" style="width:120px;height:32px;" onClick="WdatePicker({readOnly:true,maxDate:'%y-%M-%d'})" id="start_time"  name="start_time">
            -<input type="text" lass="form-control" style="width:120px;height:32px;" onClick="WdatePicker({readOnly:true,maxDate:'%y-%M-%d'})" id="end_time"   name="end_time"  > 
            订单状态:
            <select class="form-control" name="status">
                <option value="">全  部</option>
                <option value="2">已完成</option>
                <option value="1">待支付</option>
                <option value="3">已取消</option>
            </select>
            <input type="submit" class="btn btn-primary" value="搜索" />
            <a class="btn btn-info pull-right" href="{{ url('business/order/export',array('where'=>$where)) }}">导出订单</a>
        </form>
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    {{--<th width="50">ID</th>--}}
                    <th>序号</th>
                    <th>订单编号</th>
                    <th>房间号</th>
                    <th>手机号码</th>
                    <th>订单金额</th>
                    <th>订单状态</th>
                    <th>下单时间</th>
                    <th width="130">    操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($res as $vo)
                <tr>
                    <td>{{$vo->order_id}}</td>
                    <td>{{$vo->order_number}}</td>
                    <td>{{$vo->order_address}}</td>
                    <td>{{$vo->tel}}</td>
                    <td>{{$vo->real_amount}}</td>
                    @if($vo->status == 2)
                        <td>已完成</td>
                    @elseif($vo->status == 1)
                        <td>待支付</td>
                    @else
                        <td>已取消</td>
                    @endif

                    <td>{{date('Y-m-d H:i',$vo->creat_time)}}</td>
                    <td> <a  href="{{url('business/order/orderInfo',array('order_id'=>$vo->order_id))}}">查看详情</a></td>

                </tr>
                @endforeach
            </tbody>
        </table>

    <div class="pagination">{{$res->links()}}</div>
    </div>
    <script src="{{ asset('/static/themes/admin_simpleboot3/public/assets/js/jquery-1.10.2.min.js')}}"></script>
    <script src="{{ asset('/static/static/js/admin.js')}}"></script>
    <script type="text/javascript" src="{{ asset('/lib/layer/2.4/layer.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/static/static/js/My97DatePicker/4.8/WdatePicker.js')}}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var _token = $('meta[name="csrf-token"]').attr('content');

        //二级联动根据酒店id 查询并返回房间信息
        $("#h_id").change(function(){
            var id=$("#h_id").val();

            var url = "{{url('admin/order/selectRoom')}}";
            $.ajax({
                type:"post",
                url:url,
                data:{'hotel_id':id,_token:_token},
                dataType:"json",
                success:function(data){

                    var  option1='<option id="clear_room" value="';
                    var  option2='"   >';
                    var  option3='</option>';
                    //清除原有标签
                    $("#clear_room").remove();
                    //循环添加标签
                    $.each(data,function(i,t){
                        $("#room_option").after(option1+t.id+option2+t.room_number+option3);
                    })
                }

            })
        });

    </script>
</body>
</html>