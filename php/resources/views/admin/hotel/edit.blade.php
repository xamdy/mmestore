@extends('admin/public/header')
</head>
<meta name="csrf-token" content="{{ csrf_token() }}">
<body>
    <div class="wrap">
        <ul class="nav nav-tabs">
            <li><a href="{{ url('admin/hotel/index') }}">酒店列表</a></li>
            <li class="active"><a href="javascript:;">酒店编辑</a></li>
        </ul>
        <form method="post" class="form-horizontal js-ajax-form margin-top-20" action="{{ url('admin/hotel/update') }}" enctype="multipart/form-data">
            {{csrf_field()}}
            <input type="hidden" name="id" value="{{$result->id}}">
            <div class="form-group">
                <label for="input-user_login" class="col-sm-2 control-label"><span class="form-required">*</span>酒店名称：（中文）</label>
                <div class="col-md-6 col-sm-10">
                    <input type="text" class="form-control" id="input-user_login" name="name" maxlength='16' required placeholder="请输入酒店中文名称" value="{{ $result->name }}">
                </div>
            </div>

            <div class="form-group">
                <label for="input-user_login" class="col-sm-2 control-label"><span class="form-required">*</span>酒店名称：（英文）</label>
                <div class="col-md-6 col-sm-10">
                    <input type="text" class="form-control" id="input-user_login" name="name_en" required maxlength='50' placeholder="Please enter the hotel English name" value="{{ $result->name_en }}">
                </div>
            </div>



            <div class="form-group">
                <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>酒店地址：（中文）</label>
                <div class="col-md-6 col-sm-10">
                    <input type="text" class="form-control" id="input-user_email" name="address" maxlength='50' required required placeholder="请输入酒店地址" value="{{ $result->address }}">
                </div>
            </div>

            <div class="form-group">
                <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>酒店地址：（英文）</label>
                <div class="col-md-6 col-sm-10">
                    <input type="text" class="form-control" id="input-user_email" maxlength='100' required name="address_en" required placeholder="Please enter the hotel address" value="{{ $result->address_en }}">
                </div>
            </div>

            <div class="form-group">
                <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>酒店电话</label>
                <div class="col-md-6 col-sm-10">
                    <input type="text" class="form-control" id="input-user_email" maxlength='50' required name="front_phone" value="{{ $result->front_phone }}">
                </div>
            </div>
            @foreach($result->tags as $v)
            <div class="form-group clo">
                <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>酒店经理：</label>
                <div class="col-md-6 col-sm-10 form-inline">
                    <input type="hidden"  name="hid[]" value="{{$v->hid}}">
                    <input type="hidden" class="form-control" required maxlength='8' name="jlname[]" placeholder="请输入经理姓名"value="{{$v->name}}">
                    <input type="text" class="form-control" required maxlength='15' name="tel[]" placeholder="请输入经理电话"value="{{$v->tel}}">
                    <input type="text" class="form-control" required maxlength='15' name="number[]" placeholder="账号"value="{{$v->number}}">
                    <input type="text" class="form-control" required maxlength='15' name="password[]" placeholder="密码"value="{{$v->password}}">
                    <a class="btn btn-default add-spec" href="#" role="button" >添加</a>
                    <a class="btn del btn-default " onclick="del({{$v->hid}})" href="javascript:;" role="button" >删除</a>
                </div>
            </div>
            @endforeach
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">  
                    <button type="submit" class="btn btn-primary js-ajax-submit">提交</button>
                    <button type="button" class="btn btn-primary js-ajax-submit" onclick="history.go(-1);">返回</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
<script src="{{ asset('static/themes/admin_simpleboot3/public/assets/js/jquery-1.10.2.min.js')}}"></script>
<script src="{{ asset('static/layer/layer.js')}}"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var _token = $('meta[name="csrf-token"]').attr('content');
        //删除数据库数据
        function del(id) {
             layer.confirm('确定删除账号么？', {
                    btn: ['确定','取消'] //按钮
                }, function(){
                    $.ajax({
                url: '{{url('admin/hotel/delmag')}}',
                type: 'POST',
                dataType: 'json',
                data: {id: id,_token:_token},
                success: function (json) {
                if(json== 1){
                    layer.msg('删除成功!', {icon: 6, time: 2000});
                    window.location.reload();
                    }else{
                        layer.msg('删除失败', {icon: 5, time: 2000});
                    }
                }
            })
                });
            
        }
        // 添加删除克隆元素
        $(function(){
            $('.add-spec').on('click',function(event){
//          获取当前点击的添加按钮父级的tr对象
            var aa = $('.clo').length;
            if(aa <5){
                var parentTr=$(this).parents().parents('.clo');
                var cloneTr=parentTr.clone();
                cloneTr.find('.add-spec').remove();//删除添加按钮
    //          改变按钮class
                cloneTr.find('.del').attr('onclick','javascript:;').addClass('remove-spec');
                parentTr.after(cloneTr);
                $('.remove-spec').on('click',function(){
                   //              删除当前点击的按钮的父级的tr节点
                    $(this).parents().parents('.clo').remove();
                });
            }else{
                layer.msg('只允许添加五个经理账号', {icon: 5, time: 2000});
            }
            
            })
        })
    </script>
