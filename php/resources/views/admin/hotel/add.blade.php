@extends('admin/public/header')
</head>
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    #eprovinceName,#ecityName {
        width: 248px;
        float: left;
        margin-right: 10px;
        margin-bottom: 10px;
    }
    #edistrictName{
        width: 248px;
        float: left;
        color: #fff3cc;;
        margin-bottom: 10px;
     }
    #area{
        width: 900px;
    }
</style>
<body>
    <div class="wrap">
        <ul class="nav nav-tabs">
            <li><a href="{{ url('admin/hotel/index') }}">酒店列表</a></li>
            <li class="active"><a href="{{ url('admin/hotel/add') }}">酒店添加</a></li>
        </ul>
        <form method="post" class="form-horizontal js-ajax-form margin-top-20" action="{{ url('admin/hotel/store') }}" enctype="multipart/form-data" id="fm">
            {{csrf_field()}}
            <div class="form-group">
                <label for="input-user_login" class="col-sm-2 control-label"><span class="form-required">*</span>酒店名称：（中文）</label>
                <div class="col-md-6 col-sm-10">
                    <input type="text" class="form-control"  name="name" maxlength='16' required placeholder="请输入酒店中文名称">
                </div>
            </div>

            <div class="form-group">
                <label for="input-user_login" class="col-sm-2 control-label"><span class="form-required">*</span>酒店名称：（英文）</label>
                <div class="col-md-6 col-sm-10">
                    <input type="text" class="form-control"  name="name_en"  maxlength='50' required placeholder="Please enter the hotel English name">
                </div>
            </div>



            <div class="form-group">
                <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>酒店地址：（中文）</label>
                <div class="col-md-6 col-sm-10">
                    <div data-toggle="distpicker" id="area" >
                            <select class="form-control" id="eprovinceName" data-province="---- 所在省 ----" name="provinceName"></select>
                            <select class="form-control" id="ecityName" data-city="---- 所在市 ----" name="cityName"></select>
                            <select class="form-control" id="edistrictName" data-district="---- 所在区 ----" name="districtName"></select>
                    </div>
                    <input type="text" class="form-control" id="address" name="address" required placeholder="请输入详细地址">
                </div>
            </div>

            <div class="form-group">
                <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>酒店地址：（英文）</label>
                <div class="col-md-6 col-sm-10">
                    <input type="text" class="form-control"  name="address_en" required placeholder="Please enter the hotel address">
                </div>
            </div>

            <div class="form-group">
                <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>酒店电话：</label>
                <div class="col-md-6 col-sm-10">
                    <input type="text" class="form-control"  name="front_phone" placeholder="请输入前台/办公电话" required>
                </div>
            </div>

            <div class="form-group clo">
                <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>酒店经理：</label>
                <div class="col-md-6 col-sm-10 form-inline">
                    <input type="hidden" class="form-control" required maxlength='8' name="jlname[]" placeholder="请输入经理姓名" value="经理姓名">
                    <input type="text" class="form-control" required maxlength='15' name="tel[]" placeholder="请输入经理电话">
                    <input type="text" class="form-control" required maxlength='15' name="number[]" placeholder="账号">
                    <input type="password" class="form-control" required maxlength='15' name="password[]" placeholder="密码">
                    <a class="btn btn-default add-spec" href="#" role="button" >添加</a>
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
</html>
<script src="{{ asset('static/themes/admin_simpleboot3/public/assets/js/jquery-1.10.2.min.js')}}"></script>
<script src="{{ asset('static/layer/layer.js')}}"></script>
<script src="{{ asset('distpicker/src/distpicker.data.js')}}"></script>
<script src="{{ asset('distpicker/src/distpicker.js')}}"></script>
    <!--点击克隆-->
    <script type="text/javascript">
        $(function(){
            $('.add-spec').on('click',function(event){
//          获取当前点击的添加按钮父级的tr对象
            var aa = $('.clo').length;
            if(aa <5){
                var parentTr=$(this).parents().parents('.clo');
                var cloneTr=parentTr.clone();
    //          改变按钮class
                cloneTr.find('.add-spec').removeClass('add-spec').addClass('remove-spec').html('删除');
                cloneTr.find('.form-control').val('');
                parentTr.after(cloneTr);
                $('.remove-spec').on('click',function(){
    //              删除当前点击的按钮的父级的tr节点
                    $(this).parents().parents('.clo').remove();
                })
            }else{
                layer.msg('只允许添加五个经理账号', {icon: 5, time: 2000});
            }
            
            })
        });
//        $('#target').distpicker({
//            eprovinceName: '---- 所在省 ----',
//            ecityName: '---- 所在市 ----',
//            edistrictName: '---- 所在区 ----'
//        });
//        $("#area").click(function(){
//            var province = $("#eprovinceName").find("option:selected").text('---- 所在省 ----');
        $("#eprovinceName").find("option[text='-- 省 --']").attr("selected",true);
//            var city = $("#ecityName").find("option:selected").text();
//            var district = $("#edistrictName").find("option:selected").text();
//        });
    </script>
    

