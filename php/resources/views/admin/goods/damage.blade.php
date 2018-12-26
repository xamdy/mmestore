@extends('admin/public/header')
</head>
<link rel="stylesheet" href="{{ asset('/date/css/bootstrap-datetimepicker.css')}}">
<link rel="stylesheet" href="{{ asset('/date/css/bootstrap-datetimepicker.min.css')}}">
<style>
    .form_date{
        height: 33px;
        border-bottom: 1px solid #CBCBCB;
    }
</style>
<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li class="active"><a href="{{url('admin/goods/goodsList')}}">货损列表</a></li>
    </ul>

    <form class="well form-inline margin-top-20" method="get" action="{{url('admin/goods/damagelist')}}">
        商品名称:
        <input type="text" class="form-control" name="goods_name"
        @if(isset($data->goods_name))
               value="{{$data->goods_name}}"
               @endif
               style="width: 150px;" value="" placeholder="请输入商品名称">&nbsp;&nbsp;&nbsp;&nbsp;
        状态：
        <select name="damage_type" class="form-control">
            <option value="">请选择...</option>
            <option  value="1" @if(1 == $data->damage_type) selected @endif>损坏</option>
            <option  value="2" @if(2 == $data->damage_type) selected @endif>过期</option>
            <option  value="3" @if(3 == $data->damage_type) selected @endif>丢失</option>
            <option  value="4" @if(4 == $data->damage_type) selected @endif>其他</option>
        </select>

        体验店编号：
        <select name="c_id" class="form-control">
            <option value="">请选择...</option>
            @foreach($container as $key => $value)
                <option  value="{{ $value['id'] }}" @if($value['id'] == $data->c_id) selected @endif>{{ $value['container_number'] }}</option>
            @endforeach
        </select>

        酒店名称：
        <select name="h_id" class="form-control">
            <option value="">请选择...</option>
            @foreach($hotel as $key => $value)
                <option  value="{{ $value['id'] }}" @if($value['id'] == $data->h_id) selected @endif>{{ $value['name'] }}</option>
            @endforeach
        </select>

        货损时间：

        <input class="form_date" name="start_time" type="text"
               @if(isset($data->start_time))
               value="{{$data->start_time}}"
               @endif
               readonly> -
        <input class="form_date" name="end_time" type="text"
               @if(isset($data->end_time))
               value="{{$data->end_time}}"
               @endif
               readonly>
        &nbsp;
        <input type="submit" class="btn btn-primary" value="搜索" />
        <a class="btn btn-danger" href="{{url('admin/goods/damagelist')}}">清空</a>
        <a  class="btn btn-primary" id="downloads" href="{{url('admin/goods/Goodsxsls',array('keyword'=>json_encode($data->keyword)))}}" >导出</a>
    </form>

    <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th>商品名称</th>
            <th>所属酒店</th>
            <th>所属房间</th>
            <th>所属体验店</th>
            <th>货损状态</th>
            <th>货损时间</th>
        </tr>
        </thead>
        <tbody>

        @foreach($data as $vo)
            <tr>
              <td>{{$vo->goods_name}}</td>
              <td>{{$vo->name}}</td>
              <td>{{$vo->room_number}}</td>
              <td>{{$vo->container_number}}</td>
              <td>
                  @if($vo->damage_type==1)
                      损坏
                      @elseif($vo->damage_type==2)
                  过期
                  @elseif($vo->damage_type==3)
                  丢失
                      @else
                  其他
                      @endif
              </td>
              <td>{{date("Y-m-d H:i:s",$vo->damage_time)}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{$data->render()}}
    <div class="badge" style="width:100%;text-align:center;font-size:18px;margin-bottom:70px;" >
        共{{ $data->total() }}条数据
    </div>
</div>
<script src="{{ asset('/static/themes/admin_simpleboot3/public/assets/js/jquery-1.10.2.min.js')}}"></script>
<script src="{{ asset('/static/static/js/admin.js')}}"></script>
<script type="text/javascript" src="{{ asset('/lib/layer/2.4/layer.js') }}"></script>
<script src="{{ asset('/date/js/bootstrap-datetimepicker.js')}}" charset = "UTF-8"></script>
<script src="{{ asset('/date/js/bootstrap-datetimepicker.min.js')}}" charset = "UTF-8"></script>
<script src="{{ asset('/date/js/locales/bootstrap-datetimepicker.zh-CN.js')}}" charset = "UTF-8"></script>
<script type="text/javascript">
    $('.form_date').datetimepicker({
        language:  'zh-CN',
        format: "yyyy-mm-dd hh:ii:ss",
        autoclose: true,
        todayBtn: true,
        pickerPosition: "bottom-left"

    });
    </script>
</html>