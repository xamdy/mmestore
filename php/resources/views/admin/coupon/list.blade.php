@extends('admin/public/header')
</head>
<style>
    table th{
        text-align: center;
    }
    a{
        margin-left: 5px;
    }
    .form_date{
        height: 33px;
        border-bottom: 1px solid #CBCBCB;
    }
</style>
<link rel="stylesheet" href="{{ asset('/date/css/bootstrap-datetimepicker.css')}}">
<link rel="stylesheet" href="{{ asset('/date/css/bootstrap-datetimepicker.min.css')}}">
<body>
<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li class="active"><a href="javascript:;">优惠券列表</a></li>
        <li><a href="{{ url('admin/coupon/add') }}">添加优惠卷</a></li>
    </ul>
    <form class="well form-inline margin-top-20" method="get" action="{{ url('admin/coupon/list') }}">
        优惠卷类型：
        <select  class="form-control"  name="type" >
            <option  value="0"  @if($data->type == 0) selected @endif>全部</option>
            <option  value="1" @if($data->type == 1) selected @endif>满减优惠</option>
            <option  value="2" @if($data->type  == 2) selected @endif>商品专属</option>
        </select>
        优惠卷名称：
        <input type="text" class="form-control js-bootstrap-datetime" name="coupon_name" value="{{$data->coupon_name}}" placeholder="输入优惠卷关键字..."/>

                活动开始时间：

                    <input class="form_date" name="vaild_start_time" type="text"  placeholder="请选择活动开始时间"
                           @if(isset($data->vaild_start_time))
                           value="{{$data->vaild_start_time}}"
                           @endif
                           readonly> -
                活动结束时间：
                    <input class="form_date" name="vaild_end_time" type="text"  placeholder="请选择活动结束时间"
                           @if(isset($data->vaild_end_time))
                           value="{{$data->vaild_end_time}}"
                           @endif
                           readonly>
    <input type="submit" class="btn btn-primary" value="筛选"/>
        <a class="btn btn-danger" href="{{ url('admin/coupon/list') }}">清空</a>
    </form>
    <form class="js-ajax-form" action="" method="post">
        <table class="table table-hover table-bordered table-list" style="text-align: center">
            <thead>
            <tr>
                <th width="100">优惠卷名称</th>
                <th width="100">优惠卷数量</th>
                <th width="100">优惠卷类型</th>
                <th width="100">优惠卷金额</th>
                <th width="85">领取数量</th>
                <th width="85">优惠商品</th>
                <th width="80">活动开始时间</th>
                <th width="80">活动结束时间</th>
                <th width="90">操作</th>
            </tr>
            </thead>
            @foreach($data as $itme)
                <tr id="data-{{$itme->coupon_id}}" class="center">
                    <td>{{$itme->coupon_name}}</td>
                    <td>{{$itme->send_num}}</td>
                    <td>

                        @if ($itme->coupon_type==1)
                            满减
                        @else
                            打折
                        @endif

                    </td>
                    <td>
                        @if ($itme->coupon_type==1)
                            {{$itme->coupon_money}}
                        @else
                            {{substr($itme->coupon_money,0,-3)}}折
                        @endif


                       </td>
                    <td>
                        @if ($itme->receive_num=='')
                            0
                        @else
                            {{$itme->receive_num}}
                        @endif

                    </td>

                    <td>
                        @if ($itme->goods_id=='')
                            全场通用
                            @else
                            专属商品
                        @endif
                    </td>
                    <td>{{date("Y-m-d H:i",$itme->vaild_start_time)}}</td>
                    <td>{{date("Y-m-d H:i",$itme->vaild_end_time)}}</td>
                    <td class="center">
                        <a href="{{ url('admin/coupon/details',[$itme->coupon_id]) }}" title="查看" class="glyphicon glyphicon-th-list"></a>
                        <a href="{{ url('admin/coupon/edit',[$itme->coupon_id]) }}" title="编辑" class="glyphicon glyphicon-edit"></a>
                        <a href="javascript:void(0)" title="删除" onclick="deletes({{ $itme->coupon_id }})" class="glyphicon glyphicon-trash"></a>
                    </td>
                </tr>
            @endforeach

        </table>
        <ul class="pagination">{{$data->appends(array('type'=>$data->type,'coupon_name'=>$data->coupon_name,'vaild_start_time'=>$data->vaild_start_time,'vaild_end_time'=>$data->vaild_end_time))->render()}}</ul>
    </form>
</div>
</body>
<script src="{{ asset('/static/themes/admin_simpleboot3/public/assets/js/jquery-1.10.2.min.js')}}"></script>
<script src="{{ asset('/date/js/bootstrap-datetimepicker.js')}}" charset = "UTF-8"></script>
<script src="{{ asset('/date/js/bootstrap-datetimepicker.min.js')}}" charset = "UTF-8"></script>
<script src="{{ asset('/date/js/locales/bootstrap-datetimepicker.zh-CN.js')}}" charset = "UTF-8"></script>
<script src="{{ asset('/static/static/js/admin.js')}}"></script>
<script type="text/javascript" src="{{ asset('/lib/layer/2.4/layer.js') }}"></script>
<script type="text/javascript">
        $('.form_date').datetimepicker({
            language:  'zh-CN',
            format: "yyyy-mm-dd hh:ii:ss",
            autoclose: true,
            todayBtn: true,
            pickerPosition: "bottom-left"

        });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var _token = $('meta[name="csrf-token"]').attr('content');
    function deletes(id) {
        layer.confirm('确定要删除该商品吗？',function(){
            layer.close();
            $.ajax({
                url:"{{ url('admin/coupon/del') }}",
                type:"GET",
                dataType:"json",
                data:{"id":id},
                success:function(res){
                    if (res.code == 200) {
                        layer.msg(res.msg,{
                            time:2000
                        });
                        $("#data-"+id).remove();
                    }else  {
                        layer.msg(res.msg,{
                            time:2000
                        });
                    }
                }
            });
        });
    }
</script>
</html>