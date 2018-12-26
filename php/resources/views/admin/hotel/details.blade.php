@extends('admin/public/header')
</head>
<style>
    .unique {
        width: 800px;
        height: auto;
        overflow: hidden;
        zoom: 1;
        margin-left: 283px;
    }
    .unique_input {
        float: left;
        width: 40% !important;
    }
    .unique_input:nth-of-type(1) {
        margin-right: 1%;
    }
</style>
<body>
    <div class="wrap">
        <ul class="nav nav-tabs">
            <li><a href="{{ url('admin/hotel/index') }}">酒店列表</a></li>
            <li class="active"><a href="javascript:;">酒店详情</a></li>
        </ul>
        <form method="post" class="form-horizontal js-ajax-form margin-top-20" action="" >
            {{csrf_field()}}
            <div class="form-group">
                <label for="input-user_login" class="col-sm-2 control-label">酒店名称：（中文）</label>
                <div class="col-md-6 col-sm-10">
                    <input type="text" class="form-control" id="input-user_login" name="name" maxlength='16' required placeholder="请输入酒店中文名称" value="{{ $result->name }}">
                </div>
            </div>

            <div class="form-group">
                <label for="input-user_login" class="col-sm-2 control-label">酒店名称：（英文）</label>
                <div class="col-md-6 col-sm-10">
                    <input type="text" class="form-control" id="input-user_login" name="name_en" required maxlength='50' placeholder="Please enter the hotel English name" value="{{ $result->name_en }}">
                </div>
            </div>



            <div class="form-group">
                <label for="input-user_email" class="col-sm-2 control-label">酒店地址：（中文）</label>
                <div class="col-md-6 col-sm-10">
                    <input type="text" class="form-control" id="input-user_email" name="address" required placeholder="请输入酒店地址" value="{{ $result->address }}">
                </div>
            </div>

            <div class="form-group">
                <label for="input-user_email" class="col-sm-2 control-label">酒店地址：（英文）</label>
                <div class="col-md-6 col-sm-10">
                    <input type="text" class="form-control" id="input-user_email" name="address_en" required placeholder="Please enter the hotel address" value="{{ $result->address_en }}">
                </div>
            </div>

            <div class="form-group">
                <label for="input-user_email" class="col-sm-2 control-label">酒店电话</label>
                <div class="col-md-6 col-sm-10">
                    <input type="text" class="form-control" id="input-user_email" name="front_phone" value="{{ $result->front_phone }}">
                </div>
            </div>
            @foreach($result->tags as $v)
            <div class="form-group clo">
                <label for="input-user_email" class="col-sm-2 control-label">酒店经理：</label>
                <div class="col-md-6 col-sm-10 form-inline">
                    <input type="hidden" class="form-control"  value="{{$v->name}}">
                    <input type="text" class="form-control" value="{{$v->tel}}">
                    <input type="text" class="form-control" value="{{$v->number}}">
                    <input type="text" class="form-control" value="{{$v->password}}">
                </div>
            </div>
            @endforeach
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
{{--                     <a href="{{ url('admin/hotel/edit',[$result->id]) }}" class="btn btn-primary js-ajax-submit">编辑</a> --}}
                     <button type="button" class="btn btn-primary js-ajax-submit" onclick="history.go(-1);">返回</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>

       

