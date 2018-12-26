@extends('admin/public/header')
</head>
<link rel="stylesheet" href="{{ asset('/date/css/bootstrap-datetimepicker.css')}}">
<link rel="stylesheet" href="{{ asset('/date/css/bootstrap-datetimepicker.min.css')}}">
<script src="{{ asset('/js/jquery-1.8.3.min.js')}}"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
 .form-control{
  width: 70% !important;
 }
 #img_son{
  width: 50px;
  float: left;
  margin-right:40px;
  margin-bottom: 8px;
 }
 #img_parent{
  width: 500px;
 }
 #date{
  float: left;
  width: 46px;
  height: 34px;
  background-color: #ffffff;
 }
 #dateinput{
  width: 645px;
  margin-left: 15px;
 }
#datetimeinput{
 background-color: #ffffff;
}
</style>
<body>
<div class="wrap">
 <ul class="nav nav-tabs">
  <li ><a href="{{ url('admin/coupon/list') }}">优惠券列表</a></li>
  <li class="active"><a href="javascript:;">优惠卷编辑</a></li>
 </ul>
 @if (count($errors) > 0)
  <div class="alert alert-danger">
   <ul>
    @foreach ($errors->all() as $error)
     <li>{{ $error }}</li>
    @endforeach
   </ul>
  </div>
 @endif
 <form method="post" class="form-horizontal js-ajax-form margin-top-20" action="{{ url('admin/coupon/editAdd') }}" id="fm">
  {{csrf_field()}}
  <input type="hidden" id="coupon_id" name="coupon_id" value="{{$list->coupon_id}}">
  <div class="form-horizontal js-ajax-form margin-top-20">

   <div class="form-group">
    <label for="input-user_login"  class="col-sm-2 control-label"><span class="form-required"></span>优惠卷名称：</label>
    <div class="col-md-6 col-sm-10 form-inline">
     <input type="text" class="form-control"  name="coupon_name"   value="{{$list->coupon_name}}"  required >
    </div>
   </div>




   <div class="form-group">
    <label for="input-user_login"  class="col-sm-2 control-label"><span class="form-required"></span>优惠卷类型：</label>
    <div class="col-md-6 col-sm-10 form-inline">
     <select class="form-control" name="coupon_type" id="coupon_type" required>
      <option  value="1" @if($list->coupon_type == 1) selected @endif>满减优惠</option>
      <option  value="2" @if($list->coupon_type  == 2) selected @endif>商品专属</option>
     </select>
    </div>
   </div>

   @if($list->coupon_type==2)
  <div class="form-group" id="goods">
    <label for="input-user_login"  class="col-sm-2 control-label" id="label">
    </label>
   <div class="col-md-6 col-sm-10 form-inline">
    <div id="img_parent">
    @foreach($data['goods'] as $k=>$v)
      <div id="img_son">
      <input type="checkbox" name="goods_id[]" value="{{$v->goods_id}}"
             @foreach($data['goods_id'] as $k=>$va) @if($va==$v->goods_id) checked @endif   @endforeach  >
     <img width='50px' height='50px' src='{{ URL::asset($v->main_img) }}'>
      </div>
     @endforeach

    </div>
   </div>
   </div>
@endif

   @if($list->coupon_type==1)
    <div class="form-group">
     <label for="input-user_login"  class="col-sm-2 control-label"><span class="form-required"></span>满减金额：</label>
     <div class="col-md-6 col-sm-10 form-inline">
      <input type="text" class="form-control"  name="coupon_quota"  value="{{$list->coupon_quota}}"  required>
     </div>
    </div>
   @endif






   @if($list->coupon_type==1)
    <div class="form-group">
     <label for="input-user_login"  class="col-sm-2 control-label"><span class="form-required"></span>优惠卷面额：</label>
     <div class="col-md-6 col-sm-10 form-inline">
      <input type="text" class="form-control"  name="coupon_money"  value="{{$list->coupon_money}}"  required>
     </div>
    </div>
   @else
    <div class="form-group">
     <label for="input-user_login"  class="col-sm-2 control-label"><span class="form-required"></span>优惠卷面额：</label>
     <div class="col-md-6 col-sm-10 form-inline">

      <select class="form-control" name="coupon_money"  required>

      @for($i=1;$i<=8;$i++)
        <option  value="{{$i}}" @if(substr($list->coupon_money,0,-3) == $i ) selected @endif>{{'优惠'.$i.'折'}}</option>
      {{--<input type="text" class="form-control"  name="coupon_money"  value="{{substr($list->coupon_money,0,-3)}}"  required>--}}
       @endfor
      </select>

     </div>
    </div>
   @endif


   <div class="form-group">
    <label for="input-user_login"  class="col-sm-2 control-label"><span class="form-required"></span>优惠卷描述：</label>
    <div class="col-md-6 col-sm-10 form-inline">
     <textarea  type="text" class="form-control" style="min-height: 100px;min-width: 200px;max-height: 100px; max-width: 200px;" name="coupon_desc" required>
   {{$list->coupon_desc}}  </textarea>
    </div>
   </div>

   <div class="form-group">
    <label for="input-user_login"  class="col-sm-2 control-label"><span class="form-required"></span>发放数量：</label>
    <div class="col-md-6 col-sm-10 form-inline">
    <input type="text" class="form-control"  name="send_num" value="{{$list->send_num}}"   required>
    </div>
    </div>
   </div>



   <div class="form-group">
    <label for="input-user_login"  class="col-sm-2 control-label"><span class="form-required"></span>活动开始时间：</label>
    <div class="input-group date form_date col-md-1" data-date="" data-link-field="dtp_input2" >
     <div id="dateinput">
     <input class="form-control" id="datetimeinput" name="vaild_start_time" type="text"
          value="{{date('Y-m-d H:i:s',$list->vaild_start_time)}}">
     </div>
     <span id="date" class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
    </div>
   </div>

   <div class="form-group">
    <label for="input-user_login"  class="col-sm-2 control-label"><span class="form-required"></span>活动结束时间：</label>
    <div class="input-group date form_date col-md-1" data-date="" data-link-field="dtp_input2" >
     <div id="dateinput">
     <input class="form-control"  id="datetimeinput" name="vaild_end_time" type="text"
             value="{{date('Y-m-d H:i:s',$list->vaild_end_time)}}"  >
     </div>
     <span id="date" class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
    </div>
   </div>

   <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
     <button type="submit" class="btn btn-primary js-ajax-submit" id="add">修改</button>
     <button type="button" class="btn btn-primary js-ajax-submit" onclick="history.go(-1);">返回</button>
    </div>
   </div>

 </form>
</div>
</body>
<script src="{{ asset('/date/js/bootstrap-datetimepicker.js')}}" charset = "UTF-8"></script>
<script src="{{ asset('/date/js/bootstrap-datetimepicker.min.js')}}" charset = "UTF-8"></script>
<script src="{{ asset('/date/js/locales/bootstrap-datetimepicker.zh-CN.js')}}" charset = "UTF-8"></script>
<script type="text/javascript" src="{{ asset('/lib/layer/2.4/layer.js') }}"></script>s
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


