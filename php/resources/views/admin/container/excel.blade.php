@extends('admin.public.header')
</head>
<body>
    <div class="wrap js-check-wrap">
        <ul class="nav nav-tabs">
            <li ><a href="{{url('admin/container/index')}}">体验店列表</a></li>
            <li><a href="{{url('admin/container/add')}}">添加体验店</a></li>
            <li class="active"><a href="javascript:void(0)">批量添加</a></li>
        </ul>

            <form action="{{ url('admin/excel/more') }}" method="post"  enctype="multipart/form-data" class="form-horizontal js-ajax-form margin-top-20">
                {{csrf_field()}}

                <div class="form-group">
                    <label for="input-user_email" class="col-sm-2 control-label"></label>
                    <div class="table-actions">
                        <a class="btn btn-success" href="{{ url('admin/excel/excel') }}">导出Excel模板</a>
                    </div>
                </div>


                <div class="form-group">
                    <label for="input-user_email" class="col-sm-2 control-label"></label>
                    <div class="table-actions">
                        <input type="file" class="" name="cover" required>
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
<script src="{{ asset('static/layer/layer.js')}}"></script>
