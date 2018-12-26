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
  width: 654px !important;
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
  <li class="active"><a href="javascript:;">添加优惠卷</a></li>
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
 <form method="post" class="form-horizontal js-ajax-form margin-top-20" action="{{ url('admin/coupon/add') }}" enctype="multipart/form-data" id="fm">
  {{csrf_field()}}
  <div class="form-horizontal js-ajax-form margin-top-20">

   <div class="form-group">
    <label for="input-user_login"  class="col-sm-2 control-label"><span class="form-required"></span>优惠卷名称：</label>
    <div class="col-md-6 col-sm-10 form-inline">
     <input type="text" class="form-control"  name="coupon_name"   placeholder="优惠卷名称" required >

    </div>
   </div>

   <div class="form-group">
    <label for="input-user_login"  class="col-sm-2 control-label"><span class="form-required"></span></label>
    <div class="col-md-6 col-sm-10 form-inline">
     <input type="text" class="form-control"  name="coupon_name_en"   placeholder="Name of preferential volume" required >

    </div>
   </div>



   <div class="form-group">
    <label for="input-user_login"  class="col-sm-2 control-label"><span class="form-required"></span>优惠卷类型：</label>
    <div class="col-md-6 col-sm-10 form-inline">
     <select class="form-control" name="coupon_type" id="coupon_type" required>
      <option value="">请选择...</option>
      <option value="1">满减</option>
      <option value="2">打折</option>
     </select>
    </div>
   </div>

   <script>
    $(document).on('change', "#coupon_type", function () {
//    $("#coupon_type").change(function () {
        var value = $(":selected").val();
        console.log(value);
        if (value == 2) {
            $('#label').text("请选择打折商品：");
            $.ajax({
                url: "{{url('admin/coupon/checkGoods')}}",
                dataType: 'json',
                type: 'get',
                success: function (res) {
                    if (res.code == 200) {
                        $.each(JSON.parse(res.data), function (k, v) {
                            $("#img_parent").append("<div id='img_son'><input type='checkbox' name='goods_id[]' value='" + v.goods_id + "' ><img width='50px' height='50px' src='" + v.main_img + "'></div>");
                        });
                    }
                }
            });
            $("#discount").html("  <input type=\"text\" class=\"form-control\"  name=\"coupon_money\"   placeholder=\"1-8折区域 填写数字即可\" required>");
//            $("#full").empty();
//            $("#full").siblings('label').empty();

        } else {
            $("#img_parent").empty();
            $('#label').empty();
//            $("#full").html("<input type=\"text\" class=\"form-control\"  name=\"coupon_quota\"   placeholder=\"满减优惠金额\" required >");
//            $("#full").siblings('label').html("<span class=\"form-required\"></span>满减金额：");
//            $("#discount").html("  <input type=\"text\" class=\"form-control\"  name=\"coupon_money\"   placeholder=\"优惠卷面额\" required>");

        }
    })

   </script>

   <div class="form-group">
    <label for="input-user_login"  class="col-sm-2 control-label"><span class="form-required"></span>满减金额：</label>
    <div class="col-md-6 col-sm-10 form-inline" id="full">
     <input type="text" class="form-control"  name="coupon_quota"   placeholder="满减优惠金额" required>
    </div>
   </div>

   <div class="form-group">
    <label for="input-user_login"  class="col-sm-2 control-label"><span class="form-required"></span>优惠卷面额：</label>
    <div class="col-md-6 col-sm-10 form-inline" id="discount">
     <input type="text" class="form-control"  name="coupon_money"   placeholder="优惠卷面额" required>
    </div>
   </div>


  <div class="form-group" id="goods">
    <label for="input-user_login"  class="col-sm-2 control-label" id="label">
    </label>
   <div class="col-md-6 col-sm-10 form-inline">
    <div id="img_parent">

    </div>
   </div>
   </div>

   <div class="form-group">
    <label for="input-user_login"  class="col-sm-2 control-label"><span class="form-required"></span>优惠卷描述：</label>
    <div class="col-md-6 col-sm-10 form-inline">
     <textarea  type="text" class="form-control" style="min-height: 100px;min-width: 200px;max-height: 100px; max-width: 200px;" name="coupon_desc"   placeholder="Please fill in between 5 and 30 words" required>
     </textarea>
    </div>
   </div>

   <div class="form-group">
    <label for="input-user_login"  class="col-sm-2 control-label"><span class="form-required"></span></label>
    <div class="col-md-6 col-sm-10 form-inline">
     <textarea  type="text" class="form-control" style="min-height: 100px;min-width: 200px;max-height: 100px; max-width: 200px;" name="coupon_desc_en"   placeholder="coupon volume description" required>
     </textarea>
    </div>
   </div>

   <div class="form-group">
    <label for="input-user_login"  class="col-sm-2 control-label"><span class="form-required"></span>发放数量：</label>
    <div class="col-md-6 col-sm-10 form-inline">
    <input type="text" class="form-control"  name="send_num"   placeholder="发放数量" required>
    </div>
    </div>
   </div>

   <div class="form-group">
    <label for="input-user_login"  class="col-sm-2 control-label"><span class="form-required"></span>活动开始时间：</label>
    <div class="input-group date form_date col-md-1" data-date="" data-link-field="dtp_input2" >
     <div id="dateinput">
     <input class="form-control" id="datetimeinput" name="vaild_start_time" type="text" value=""
            placeholder="请选择活动开始时间"  readonly>
     </div>
     <span id="date" class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
    </div>
   </div>

   <div class="form-group">
    <label for="input-user_login"  class="col-sm-2 control-label"><span class="form-required"></span>活动结束时间：</label>
    <div class="input-group date form_date col-md-1" data-date="" data-link-field="dtp_input2" >
     <div id="dateinput">
     <input class="form-control"  id="datetimeinput" name="vaild_end_time" type="text" value=""
            placeholder="请选择活动结束时间" readonly >
     </div>
     <span id="date" class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
    </div>
   </div>

   <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
     <button type="submit" class="btn btn-primary js-ajax-submit" id="add">添加</button>
     <button type="button" class="btn btn-primary js-ajax-submit" onclick="history.go(-1);">返回</button>
    </div>
   </div>

 </form>
</div>
</body>
<script src="{{ asset('/date/js/bootstrap-datetimepicker.js')}}" charset = "UTF-8"></script>
<script src="{{ asset('/date/js/bootstrap-datetimepicker.min.js')}}" charset = "UTF-8"></script>
<script src="{{ asset('/date/js/locales/bootstrap-datetimepicker.zh-CN.js')}}" charset = "UTF-8"></script>
<script type="text/javascript" src="{{ asset('/lib/layer/2.4/layer.js') }}"></script>
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


