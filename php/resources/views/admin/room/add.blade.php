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
            <li class="active"><a href="{{ url('admin/room/add') }}">房间添加</a></li>
        </ul>
        <form method="post" class="form-horizontal js-ajax-form margin-top-20" action="{{ url('admin/room/store') }}" enctype="multipart/form-data" id="fm">
            {{csrf_field()}}
            <div class="form-group">
                <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>选择酒店：</label>
                <div class="col-md-6 col-sm-10 form-inline">
                    <select class="form-control" name="hotel_id" style="width: 164px;height: 37px;"  id="hotel_id">
                        <option value='0'>请选择</option>
                        @foreach($hotel as $k => $v)
                        <option value='{{ $v->id }}'>{{ $v->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group ">
                <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>体验店编号：</label>
                <div class="col-md-6 col-sm-10 form-inline">
                    <select class="form-control" name="container_id" style="width: 164px;height: 37px;"  id="container_id">
                        <option value='0'>请选择</option>
                        @foreach($container as $k => $v)
                            <option value='{{ $v->id }}'>{{ $v->container_number}}</option>
                        @endforeach
                    </select>
               </div>
           </div>
           <div class="form-group">
            <label for="input-user_login"  class="col-sm-2 control-label"><span class="form-required">*</span>房间类型：</label>
            <div class="col-md-6 col-sm-10 form-inline">
                <input type="text" class="form-control"  name="room_name" maxlength='10'  placeholder="请输入房间类型">
            </div>
        </div>

        <div class="form-group">
            <label for="input-user_login" class="col-sm-2 control-label"><span class="form-required">*</span>房间编号：</label>
            <div class="col-md-6 col-sm-10 form-inline">
                <input type="text" class="form-control"  name="room_number"  maxlength='10' placeholder="请输入房间编号">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary js-ajax-submit" >添加</button>
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
            hotel_id:{
                min:1
            },
            room_name:{
                required: true
            },
            room_number:{
                required: true
            },
            container_id:{
                min:1
            },
            {{--container_id: {--}}
                {{--remote: {--}}
                    {{--url: "{{ url('admin/room/queryy') }}",--}}
                    {{--type: "post",--}}
                    {{--dataType: "json",--}}
                    {{--data: {--}}
                        {{--_token:_token--}}
                    {{--},--}}
                {{--dataFilter: function (data) {　　　　//判断控制器返回的内容--}}
                    {{--if (data == "true") {--}}
                        {{--return true;--}}
                    {{--}--}}
                    {{--else {--}}
                        {{--return false;--}}
                    {{--}--}}
                {{--}--}}
            {{--}--}}
        {{--}--}}
    },
    messages:{
        hotel_id:{
          min: "请选择酒店"
        },
        room_name:{
          required: "请输入房间名称"
        },
        room_number:{
          required: "请输入房间编号"
        },
        container_id:{
            min:"请选择体验店编号"
        }
  },　　　　//这个地方要注意，修改去控制器验证的事件。
    onsubmit: true
});
</script>



