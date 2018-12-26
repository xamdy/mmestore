@extends('admin/public/header')
</head>
<body>

<style>


    .layui-layer{
        position: fixed;
        top: 40% !important;
        left: 50%;
    }

</style>

<!-- 1.添加meta csrf_token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li class="active"><a href="{{url('admin/goods/goodsList')}}">商品列表</a></li>
        <li><a href="{{url('admin/goods/add')}}">商品添加</a></li>
    </ul>

    <form class="well form-inline margin-top-20" method="get" action="{{url('admin/goods/goodsList')}}">
        商品名称:
        <input type="text" class="form-control" name="goods_name"
        @if(isset($res->goods_name))
               value="{{$res->goods_name}}"
               @endif
               style="width: 150px;" value="" placeholder="请输入商品名称">&nbsp;&nbsp;&nbsp;&nbsp;

        条形码:
        <input type="text" class="form-control" name="barcode"
        @if(isset($res->barcode))
               value="{{$res->barcode}}"
               @endif
               style="width: 120px;" value="" placeholder="请输入条形码">

        &nbsp;&nbsp;&nbsp;&nbsp;
        状态：
        <select name="status" class="form-control">
            <option value="0">全部商品状态</option>
            <option  value="1">上架</option>
            <option  value="2">下架</option>
        </select>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

        分类：
        <select name="cate_id" class="form-control">
            <option value="0">全部分类</option>
            @foreach($category as $key => $value)
                <option  value="{{ $value['id'] }}">{{ $value['name'] }}</option>
            @endforeach
        </select>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;


        <input type="submit" class="btn btn-primary" value="搜索" />
        <a class="btn btn-danger" href="{{url('admin/goods/goodsList')}}">清空</a>
    </form>

    <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th width="50">ID</th>
            <th>商品图片</th>
            <th>商品名称</th>
            <th>分类</th>
            <th>条形码</th>
            <th>原价</th>
            <th>现价</th>
            <th>库存量</th>
            <th>总销量</th>
            <th>状态</th>
            <th width="130">	操作</th>
        </tr>
        </thead>
        <tbody>

        @foreach($res as $vo)
            <tr>
                <td>{{$vo->goods_id}}</td>
                <td><img width="80px" height="80px" src="{{ URL::asset($vo->main_img) }}"></td>
                <td>{{$vo->goods_name}}</td>
                <td>{{$vo->name}}</td>
                <td>{{$vo->barcode}}</td>
                <td>{{$vo->original_price}}</td>
                <td>{{$vo->present_price}}</td>
                <td>{{$vo->inventory}}</td>
                <td>{{$vo->sold}}</td>
                <td>
                    @if($vo->status==1)
                        上架
                    @else
                       下架
                    @endif
                </td>

                <td>
                    {{--<font color="#cccccc">详情</font>--}}
                    {{--<font color="#cccccc">添加库存</font><br><br>--}}

                    <a href='{{url("admin/goods/details",array("id"=>$vo->goods_id))}}'>详情</a> &nbsp;&nbsp;&nbsp;&nbsp;
                    <a onclick="changeSum({{ $vo->goods_id }});">添加库存</a> <br><br>


                    <a onclick="deletes({{ $vo->goods_id }});">删除</a>&nbsp;&nbsp;&nbsp;&nbsp;
                    {{--<a onclick="addLoss({{ $vo->goods_id }});">添加损耗</a><br><br>--}}

                @if($vo->status==1)
                        <a onclick="upGoods({{ $vo->goods_id }});">下架</a>
                    @else
                        <a href='{{url("admin/goods/edit",array("id"=>$vo->goods_id))}}'>编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;<br><br>
                        <a onclick="upGoods({{ $vo->goods_id }});">上架</a>
                    @endif

                </td>

            </tr>
        @endforeach
        </tbody>
    </table>

    {{--{{ $res->links() }}--}}
    {!! $res->appends(array('goods_name'=>$res->goods_name,'barcode'=>$res->barcode))->render() !!}

    <div class="badge" style="width:100%;text-align:center;font-size:18px;margin-bottom:70px;" >
        共{{ $res->total() }}条数据
    </div>

    {{--{!! $res->appends(array('user_login'=>$res->user_login,'user_email'=>$res->user_email))->render() !!}--}}
</div>
<script src="{{ asset('/static/themes/admin_simpleboot3/public/assets/js/jquery-1.10.2.min.js')}}"></script>
<script src="{{ asset('/static/static/js/admin.js')}}"></script>
<script type="text/javascript" src="{{ asset('/lib/layer/2.4/layer.js') }}"></script>

<script type="text/javascript">

//     2.设置全局ajax选项
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var _token = $('meta[name="csrf-token"]').attr('content');


    // 删除
    function deletes(id) {
        layer.confirm('确定要删除该商品吗？',function(){
            layer.close();
            $.ajax({
                url:"{{ url('admin/goods/del') }}",
                type:"POST",
                dataType:"json",
                data:{"id":id,_token:_token},
                success:function(res){
                    if (res.code == 1) {
                        layer.msg(res.msg,{
                            time:2000
                        });
                        setTimeout(function(){
                            window.location.reload();
                        });
                    }else  {
                        layer.msg(res.msg,{
                            time:2000
                        });
                    }
                }
            });
        });
    }

    // 添加损耗
    function addLoss(id){
        layer.open({
            type: 1 //Page层类型
            //,area: ['500px', '300px']
            ,btn:["确定","取消"]
            ,title: '添加损耗'
            ,skin: 'layui-layer-prompt'
            ,content: "<div class=''><input type='text' class='layui-layer-input' value='' placeholder='请输入损耗数量'><textarea class='layui-layer-input' style='width:230px;margin-top:5px;' placeholder='请输入损耗原因、损耗地点等信息'></textarea></div>"
            ,yes: function(index, layero){
                //按钮【按钮一】的回调
                var num = $(layero).find("input[type='text']").val();
                var why = $(layero).find("textarea").val();

            }
        });
    }


    //添加库存
    function changeSum(id){
        layer.prompt({
            formType:1,
            title: '请输入添加数量',
            area: ['200px', '200px'] //自定义文本域宽高
        }, function(value, index, elem){
            layer.close(index);
            $.ajax({
                url:"{{ url('admin/goods/addInventory') }}",
                type:"POST",
                async:true,
                dataType:"json",
                data:{"id":id, "value":value,_token:_token},
                success:function(res){

                    layer.msg(res.msg,{
                        time:10000
                    });
                    setTimeout(function(){
                        window.location.reload();
                    });
                }
            })
        });
    }

    // 上架下架
    function upGoods(id) {
        layer.confirm('确定要上架或者下架该商品吗？',function(){
            layer.close();
            $.ajax({
                url:"{{ url('admin/goods/upGoods') }}",
                type:"POST",
                dataType:"json",
                data:{"id":id,_token:_token},
                success:function(res){
                    if (res.code == 1) {
                        layer.msg(res.msg,{
                            time:2000
                        });
                        setTimeout(function(){
                            window.location.reload();
                        });
                    }else  {
                        layer.msg(res.msg,{
                            time:2000
                        });
                    }
                }
            });
        });
    }

    //删除
    $('.js-ajax-delete').click(function(){
        var url = $(this).attr('href');
        if(confirm('你确定要删除吗？')){
            window.location=url;
        }
        return false;
    })
</script>
</body>
</html>