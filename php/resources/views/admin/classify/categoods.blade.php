@extends('admin/public/header')
</head>

<!-- 1.添加meta csrf_token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<body>
<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li><a href="{{url('admin/classify/index')}}">分类管理</a></li>
        <li class="active"><a href="{{url('admin/classify/add')}}">分类商品列表</a></li>
    </ul>

    <p style="font-size: 25px;color: red">所属分类： {{ $cate_name->name }}</p>
    <input id="cate_id" type="hidden" value="{{ $cate_name->id }}"/>

    <p style="float:right;font-size: 20px"><a id='add_goods' onclick='add_goods()' ><i class='fa form-required fa-plus-circle normal'>添加商品</i></a></p>


    <table class="table table-hover table-bordered">
        <thead>
        <tr style="content: '50';">
            <th width="50">序号</th>
            <th>图片</th>
            <th>名称</th>
            <th>条形码</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($res as $vo)
            <tr>
                <td> {{$vo->goods_id }}</td>
                <td><img width="80px" height="80px" src="{{ URL::asset($vo->main_img) }}"></td>

                <td> {{$vo->goods_name}} </td>

                <td>{{$vo->barcode}}</td>

                <td>
                    <a href="javascript:;" onclick="del({{$vo->goods_id}})" item="{{'admin/classify/remove/',array('id'=>$vo->goods_id)}}" >移除</a>&nbsp;&nbsp;
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="pagination">{{$res->links()}}</div>

    <div class="badge" style="width:100%;text-align:center;font-size:18px;margin-bottom:70px;" >
        共{{ $res->total() }}条数据
    </div>

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

    function del(url){
        var urls = "{{'/admin/classify/remove/'}}"+url;
        layer.confirm('您确定要移除该分类下的商品吗？',function(){
            window.location.href=urls;
        })
    }


    //鼠标离开事件 查询数据库是否存在
    function add() {
        var num =	$('#textarea').val();
        var reg = /^[0-9]+.?[0-9,/]*$/;
        //正则验证输入信息是否正确
        if (reg.exec(num)) {
            var url = "{{url('admin/classify/checkGoods')}}";
            $.ajax({
                type:"post",
                url:url,
                data:{'num':num,_token:_token},
                dataType:"json",
                success:function(data){
                    console.log(data);
                    //判断返回状态
                    if(data.code== 1){
                        $('#error_status').val('');
                        $('#error_num').html('&nbsp;&nbsp;以上条形码不存在');
                    }else if(data.code== 2){
                        $('#error_status').val('');
                        $('#error_num').html('&nbsp;&nbsp;商品条形码'+data['msg']+'不存在');
                    }else if(data.code== 3){
                        $('#error_status').val('1');
                        $('#error_num').html('验证通过');
                    }
                }
            })
        }else {
            $('#error_status').val('');
            $('#error_num').html('&nbsp;&nbsp;格式错误');
        }

    }



    //批量添加商品
    function add_goods(){
        //弹框html编辑
        var textdiv ="<div id='textdiv'>"+
                    //	输入框
                "<textarea id = 'textarea' onmouseout  ='add();' class = 'layui-layer-input' style='height: 100px;width: 307.75px;'>例：6666/0000/8888,填写完后请将鼠标移到框外任意处来判断是否存在该商品</textarea>"+
                    // ajax请求返回信息展示
                "<p id='error_num' class='form-required'></p>"+
                "<input id='error_status' type='hidden' value=''>"+
                "</div>";

        layer.open({
            type: 1,
            title: '输入商品条形码，多条请以 / 分割',
            content: textdiv
            ,btn: ['提交', '取消']
            ,yes: function(index, layero){
                //按钮【提交】的回调
                var num =	$('#textarea').val();
                var cate_id =	$('#cate_id').val();
                var error_status =	$('#error_status').val();
                //正则验证输入信息是否正确
                if (error_status) {
                    //提交输入信息并返回data数据
                    var url = "{{url('admin/classify/updateGoodsCate')}}";
                    $.ajax({
                        type:"post",
                        url:url,
                        data:{'num':num,_token:_token,'cate_id':cate_id},
                        dataType:"json",
                        success:function(data){
                            console.log(data);
                            //判断返回状态
                            if(data.code== 1){
                                layer.msg('成功',{icon:6,time:2000});
                                window.location.reload();
                                layer.close();
                            }else if(data.code == 2 ) {
                                layer.msg('商品已经在该分类下',{icon:5,time:2000});
//                                window.location.reload();
//                                layer.close();
                            }else if(data.code == 3) {
                                layer.msg(data.msg,{icon:5,time:2000});
                            }
                        }
                    })
                }else{
                    $('#error_num').html('&nbsp;&nbsp;请填写正确条形码');
                }
            }
            ,btn2: function(index, layero){
                //按钮【取消】的回调
                //return false //开启该代码可禁止点击该按钮关闭
            }
            ,cancel: function(){
                //右上角关闭回调
                //return false 开启该代码可禁止点击该按钮关闭
            }
        });

    };

</script>
</body>
</html>