@extends('admin/public/header')
</head>
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .form-control{
        width: 70% !important;
    }
</style>
<body>
    <div class="wrap">
        <ul class="nav nav-tabs">
            <li><a href="{{ url('admin/room/index') }}">房间列表</a></li>
            <li class="active"><a href="javascript:;">房间编辑</a></li>
        </ul>
        <form method="post" class="form-horizontal js-ajax-form margin-top-20" action="{{ url('admin/room/update',[$result->id]) }}" id="fm">
            {{csrf_field()}}
            <div class="form-group">
                <label for="input-user_login" class="col-sm-2 control-label"><span class="form-required">*</span>房间类型：</label>
                <div class="col-md-6 col-sm-10 form-inline">
                    <input type="text" class="form-control" id="input-user_login" name="room_name" maxlength='16'  value="{{ $result->room_name }}">
                </div>
            </div>
            <div class="form-group">
                <label for="input-user_login" class="col-sm-2 control-label"><span class="form-required">*</span>房间编号：</label>
                <div class="col-md-6 col-sm-10">
                    <p class="form-control-static">{{ $result->room_number }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>酒店名称：</label>
                <div class="col-md-6 col-sm-10">
                    <p class="form-control-static">{{ $result->name }}</p>
                    <input type="hidden" value="{{ $result->h_id}}" name="hotel_id">
                </div>
            </div>
            <div class="form-group">
                <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>体验店编号</label>
                <div class="col-md-6 col-sm-10 form-inline">
                    
                    <input type="text" class="form-control" id="input-user_login" name="container_id" maxlength='16'  value="{{ $result->container_number }}" placeholder="请输入体验店编号">
                    
                </div>
                </div>
            </div>
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
<script src="{{ asset('static/themes/admin_simpleboot3/public/assets/js/jquery.validate.min.js')}}"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var _token = $('meta[name="csrf-token"]').attr('content');
    $("#fm").validate({
        rules:{
            room_name:{
                required: true
            },
            container_id: {
                required: true,
                remote: {
                    url: "{{ url('admin/room/queryy') }}",
                    type: "post",
                    dataType: "json",
                    data: {
                        _token:_token
                    },
                dataFilter: function (data) {　　　　//判断控制器返回的内容
                    if (data == "true") {
                        return true;
                    }
                    else {
                        return false;
                    }
                }
            }
        }
    },
    messages:{
        room_name:{
          required: "请输入房间名称"
        },
        container_id:{
            required: "请输入体验店编号",
            remote:"体验店不存在或已绑定"
        }
  },　　　　//这个地方要注意，修改去控制器验证的事件。
    onsubmit: true
});
</script>

