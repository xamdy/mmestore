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
            <li><a href="{{ url('admin/ceshi/lists') }}">设备列表</a></li>
            <li class="active"><a href="{{ url('admin/ceshi/index') }}">设备添加</a></li>
        </ul>
        <form method="post" class="form-horizontal js-ajax-form margin-top-20" action="{{ url('admin/ceshi/addPost') }}" enctype="multipart/form-data" id="fm">
            {{csrf_field()}}
            <div class="form-group ">
                <label for="input-user_email" class="col-sm-2 control-label">硬件编号：</label>
                <div class="col-md-6 col-sm-10 form-inline">
                   <input type="text" class="form-control number"   name="number"  placeholder="请输入硬件编号12位数字" required>
               </div>
           </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary js-ajax-submit" >添加</button>
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




</script>



