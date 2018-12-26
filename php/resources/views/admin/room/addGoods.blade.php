@extends('admin/public/header')
</head>
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .btn{
        width: 80px;
    }
</style>
<body>
    <div class="wrap js-check-wrap">    
        <form class="js-ajax-form" action="" method="post">
            <a href="javascript:;" onclick="addallgood({{$id}})" class="btn btn-default  btn-lg btn-block" style="margin-bottom: 5px">一键补货</a>
            <table class="table table-hover table-bordered table-list">
                <thead>
                    <tr>
                        <th >商品名称</th>
                        <th >数量</th>
                        <th >操作</th>
                    </tr>
                    @foreach($result as $k => $v)
                    <tr>
                        <td id="data-{{$v->goods_id}}">
                            {{ $v->goods_name }}
                        </td>
                        <td>
                            @if($v->inventory_status == 2)
                            0
                            @else
                            1
                            @endif
                        </td>
                        <td>
                            @if($v->inventory_status == 2)
                            <a href="javascript:;" onclick="addgood({{$v->id}},{{$v->goods_id}})" class="btn btn-default" role="button">补货</a>
                                <a href="javascript:;" class="btn btn-default" role="button">无货损</a>
                            @elseif($v->inventory_status == 1)
                            <a href="javascript:;"  class="btn btn-default" role="button">已补货</a>
                                <a href="javascript:;" onclick="DamageGoods({{$v->id}},{{$v->goods_id}},{{$v->inventory_status}})" class="btn btn-default" role="button">货损</a>
                            @endif
                        </td>
                    </tr>

                    @endforeach

                </thead>
            </table>

            <ul class="pagination">{{ $result->links() }}</ul>
        </form>
    </div>
</body>
</html>
<script src="{{ asset('static/themes/admin_simpleboot3/public/assets/js/jquery-1.10.2.min.js')}}"></script>
<script src="{{ asset('static/layer/layer.js')}}"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var _token = $('meta[name="csrf-token"]').attr('content');
    // id：货柜商品表id
    function addgood(id,goods_id){
        $.ajax({
            url: "{{url('admin/room/addgood')}}",
            type: 'post',
            dataType: 'json',
            data: {'id': id,'goods_id':goods_id,'_token':_token},
            success: function (json) {
                if(json.code == 1){
                    layer.msg('补货成功', {
                        icon: 6,//提示的样式
                        time: 2000, //2秒关闭（如果不配置，默认是3秒）//设置后不需要自己写定时关闭了，单位是毫秒
                        end:function(){
//                            window.location.reload(); //刷新页面
                            window.parent.location.reload(); //刷新父页面
                            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                            parent.layer.close(index);  // 关闭layer
                        }
                    });
                }else{
                    layer.msg(json.msg, {
                        icon: 5,//提示的样式
                        time: 2000, //2秒关闭（如果不配置，默认是3秒）//设置后不需要自己写定时关闭了，单位是毫秒
                        end:function(){
//                            window.location.reload(); //刷新页面
                            window.parent.location.reload(); //刷新父页面
                            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                            parent.layer.close(index);  // 关闭layer
                        }
                    });
                }
            }
        })
        
        .done(function() {
            console.log("success");
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
        
    }
    // id:房间id
    function addallgood(id){
        $.ajax({
            url: "{{url('admin/room/addallgood')}}",
            type: 'post',
            dataType: 'json',
            data: {'id': id,'_token':_token},
            success: function (data) {
                console.log(data);
                if(data.code ==1){
                    layer.msg(data.msg, {
                        icon: 6,//提示的样式
                        time: 2000, //2秒关闭（如果不配置，默认是3秒）//设置后不需要自己写定时关闭了，单位是毫秒
                        end:function(){
                            window.parent.location.reload(); //刷新父页面
                            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                            parent.layer.close(index);  // 关闭layer
                        }
                    }); 
                }else{
                    layer.msg(data.msg, {
                        icon: 5,//提示的样式
                        time: 2000, //2秒关闭（如果不配置，默认是3秒）//设置后不需要自己写定时关闭了，单位是毫秒
                        end:function(){
                            window.location.reload(); //刷新父页面
                           
                        }
                    }); 
                }
            }
        })
        
        .done(function() {
            console.log("success");
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
        
    }
    function DamageGoods(id,goods_id,inventory_status) {
      var radio="<div style='width: 200px;margin: 10px'>" +
          "<input type='radio' name='damage_type' style='margin-left: 7px' value='1'>损坏" +
          "<input type='radio' name='damage_type' style='margin-left: 5px' value='2'>过期" +
          "<input type='radio' name='damage_type' style='margin-left: 5px' value='3'>丢失" +
          "<input type='radio' name='damage_type' style='margin-left: 5px' value='4'>其他" +
          "</div>";

        layer.open({
            type: 1,
            title: '请选择货损类型',
            content: radio
            , btn: ['提交', '取消']
            , yes: function (index, layero) {
                var damage_type = $('input:radio[name="damage_type"]:checked').val();
                var goods_name=$('#data-'+goods_id).text();
        $.ajax({
            url:"{{url('admin/room/damageGoods')}}",
            type:'post',
            dataType:'json',
            data: {'id': id,'goods_id':goods_id,'inventory_status':inventory_status,'damage_type':damage_type,'goods_name':goods_name,'_token':_token},
            success: function (json) {
                if(json.code == 1){
                    layer.msg('添加货损成功', {
                        icon: 6,//提示的样式
                        time: 2000, //2秒关闭（如果不配置，默认是3秒）//设置后不需要自己写定时关闭了，单位是毫秒
                        end:function(){
//                            window.location.reload(); //刷新页面
                            window.parent.location.reload(); //刷新父页面
                            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                            parent.layer.close(index);  // 关闭layer
                        }
                    });
                }else{
                    layer.msg(json.msg, {
                        icon: 5,//提示的样式
                        time: 2000, //2秒关闭（如果不配置，默认是3秒）//设置后不需要自己写定时关闭了，单位是毫秒
                        end:function(){
//                            window.location.reload(); //刷新页面
                            window.parent.location.reload(); //刷新父页面
                            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                            parent.layer.close(index);  // 关闭layer
                        }
                    });
                }
            }
        })
            .done(function() {
                console.log("success");
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });
            }

        })

    }
</script>
