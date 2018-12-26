@extends('admin/public/header')
</head>
<style>
    table th{
        text-align: center;
    }
</style>
<body>
<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li class="active"><a href="javascript:;">轮播图列表</a></li>
        <li><a href="{{ url('admin/sowingmap/ImgAdd') }}">添加轮播图</a></li>
    </ul>
    <form class="well form-inline margin-top-20" method="get" action="{{ url('admin/sowingmap/Imglist') }}">
        {{csrf_field()}}
        图片名称:&nbsp;
        <input type="text" class="form-control js-bootstrap-datetime" name="img_name" placeholder="请输入图片名称"  value="{{$data->img_name}}">
        <input type="submit" class="btn btn-primary" value="筛选"/>
        <a class="btn btn-danger" href="{{ url('admin/sowingmap/Imglist') }}">清空</a>
    </form>
    <form class="js-ajax-form" action="" method="post">

        <table class="table table-hover table-bordered table-list" style="text-align: center">
            <thead>
            <tr>
                <th width="90">图片序号</th>
                <th width="90">图片名称</th>
                <th width="90">图片展示</th>
                <th width="100">图片状态</th>
                <th width="90">操作</th>
            </tr>
            </thead>
            @foreach($data as $k => $v)
                <tr trdata="data-{{$v->id}}">
                    <td>
                        {{ $v->id }}
                    </td>
                    <td>
                        {{ $v->img_name }}
                    </td>
                    <td><img width="80px" height="80px" src="{{ URL::asset($v->img_url) }}"></td>
                    <td>
                        @if($v->img_status == 1)
                            启用
                        @else
                            未启用
                        @endif
                    </td>
                    <td >
                        <a href="{{ url('admin/sowingmap/details',[$v->id]) }}">查看</a>
                        <a href="{{ url('admin/sowingmap/edit',[$v->id]) }}" class="js-ajax-delete">编辑</a>
                        <a onclick="deletes({{ $v->id }})" class="js-ajax-delete">删除</a>
                    </td>
                </tr>
            @endforeach

        </table>
        <ul class="pagination">{{$data->appends(array('img_name'=>$data->img_name))->render() }}</ul>
    </form>
</div>
</body>
<script src="{{ asset('/static/themes/admin_simpleboot3/public/assets/js/jquery-1.10.2.min.js')}}"></script>
<script src="{{ asset('/static/static/js/admin.js')}}"></script>
<script type="text/javascript" src="{{ asset('/lib/layer/2.4/layer.js') }}"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var _token = $('meta[name="csrf-token"]').attr('content');
    function deletes(id) {
        layer.confirm('确定要删除该商品吗？',function(){
            layer.close();
            $.ajax({
                url:"{{ url('admin/sowingmap/del') }}",
                type:"GET",
                dataType:"json",
                data:{"id":id},
                success:function(res){
                    if (res.code == 1) {
                        layer.msg(res.msg,{
                            time:2000
                        });
                        $("tr[trdata='data-"+id+"']").remove();
                    }else  {
                        layer.msg(res.msg,{
                            time:2000
                        });
                    }
                }
            });
        });
    }
</script>
</html>