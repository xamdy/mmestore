@extends('admin/public/header')

</head>
<body>
<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li><a href="{{url('admin/rbac/index')}}">角色管理</a></li>
        <li><a href="{{url('admin/rbac/roleadd')}}">添加角色</a></li>
        <li class="active"><a>编辑角色</a></li>
    </ul>
    <form class="form-horizontal js-ajax-form margin-top-20" onsubmit='return checkForm()' action="{{url('admin/rbac/editpost')}}" method="post">
        <div class="form-group">
            <label for="input-name" class="col-sm-2 control-label"><span class="form-required">*</span>角色名称</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control" id="input-name" name="name" value="{{$res->name}}">
            </div>
        </div>
        <div class="form-group">
            <label for="input-remark" class="col-sm-2 control-label">角色描述</label>
            <div class="col-md-6 col-sm-10">
                <textarea type="text" class="form-control" id="input-remark" name="remark">{{$res->remark}}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">状态</label>
            <div class="col-md-6 col-sm-10">
                <label class="radio-inline">
                    <?php $active_true_checked=($res->status==1)?"checked":"";?>
                    <input type="radio" name="status" value="1" {{$active_true_checked}}> 开启
                </label>
                <label class="radio-inline">
                    <?php $active_false_checked=($res->status==0)?"checked":""; ?>
                    <input type="radio" name="status" value="0" {{$active_false_checked}}> 禁用 
                </label>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="hidden" name="id" value="{{$res->id}}"/>
                <input type="hidden" class="form-control" value="{{ Session::token() }}" name="_token">
                <button type="submit" class="btn btn-primary js-ajax-submit">保存</button>
                <a class="btn btn-default" href="javascript:history.back(-1);">返回</a>
            </div>
        </div>
    </form>
</div>
   <script src="{{ asset('/fileUpload/js/jquery-2.1.3.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/lib/layer/2.4/layer.js') }}"></script>
    <script type="text/javascript">
        function checkForm(){
        var name = $("input[name='name']").val();
        // var app = $("input[name='user_pass']").val();
       var status = $("input[name='status']:checked").val();
        if(!name){
            layer.msg('请您输入角色名称',{icon:2,time:2000});return false;
        }
        if(!status){
            layer.msg('请您选择状态',{icon:2,time:2000}); return false;
        }
        return true;
    }
    </script>
</body>
</html>