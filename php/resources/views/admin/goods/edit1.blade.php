

<link rel="stylesheet" href="{{asset('/static/static/goods/css/normalize.css')}}">
<link rel="stylesheet" href="{{asset('/static/static/goods/css/main.min.css')}}">

<link rel="stylesheet" href="{{asset('/static/static/goods/css/iconfont.css')}}">
<link rel="stylesheet" href="{{asset('/static/static/goods/css/star-rating.css')}}">
<link rel="stylesheet" href="{{asset('/static/static/goods/css/timepicki.css')}}">

<link rel="stylesheet" href="{{asset('/static/static/goods/css/addgoods.css')}}">

</head>

<style>
    .radio-i {
        top: 4px !important;
    }
    .addKtv-cont .addGoods, .addKtv-cont .mart8{
        margin-top: 0 !important;
    }
    .change{
        width: 100px;
        height: 35px;
        background: #171717;
        color: #fff;
        margin-left: 183px;
        text-align: center;
        border-radius: 4px;
        font-size: 14px;
    }
    .addKtv-cont {
        padding-top: 50px;
        padding-left: 100px;
    }
    .goods-name {
        width: 100px;
        height: 33px;
        color: #2c3e50;
        font-size: 14px;
        font-weight: bold;
        display: block;
        text-align: left;
    }
    .good-text {
        width: 510px !important;
    }
    .text_in {
        padding: 10px;
        color: #777777;
        font-size: 14px;
        line-height: 16px;
    }
    .unique {
        height: 33px;
        padding: 0 10px;
    }
    .flex_wrap {
        height: 35px;
        width: auto;
        display: table;
        margin-left: 100px;
        margin-top: 50px;
    }
    .flex_wrap button {
        float: left;
        margin-left: 30px !important;
    }


    .layui-layer{
        position: fixed;
        top: 40% !important;
        left: 50%;
    }
</style>

<!-- 1.添加meta csrf_token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="right">

    <div class="addKtv-cont">
        <div class="addKtv-name clearfix">
            <p class="goods-name pull-left">商品名称:</p>
            <p class="good-text pull-left">
                <input type="text" class="form-control" id="input-user_login" name="goods_name_Chinese" style="width: 400px" value="{{ $China['goods_name'] }}">
            </p>
        </div>

        <div class="addKtv-price clearfix">
            <p class="goods-name pull-left"></p>
            <p class="good-text pull-left"> <input type="text" class="form-control" id="input-user_login" name="goods_name_English" style="width: 400px" value="{{ $English['goods_name'] }}"></p>
            <p class="pull-left"></p>
        </div>
        <div class="addKtv-picture-major clearfix" style="margin-top: 20px;">
            <p class="goods-name pull-left">产品主图：</p>
            <div class="pull-left" style="margin-left: 20px;">
                <input type="file" id="fileselectm" class="form-control" name="fileselect[]" multiple="multiple"/>
                <input type="hidden" name="main_img" class="imgm" value="{{$goods['main_img']}}">

                <div class="uploader-file" id="uploader-file">
                    <div class="img" >

                        <img src="{{ URL::asset($goods['main_img']) }}" name = "" id="selsectm">

                        <p>点击上传</p>
                    </div>
                </div>
                <p class="uploader-info">提示：请上传jpg/png文件，且不超过5MB</p>
            </div>
        </div>
        <div class="addKtv-picture clearfix">
            <p class="goods-name pull-left">产品图片：</p>
            <input type="file" id="fileselect" class="form-control" name="fileselect[]" multiple="multiple"/>
            <div class="pull-left li-right" style="margin-left: 20px;">
                <div id="img-group">
                    {{--<ul class="list_btn" id="menu">--}}
                    @foreach($imgUrl as $key => $value)
                        @if($value)
                            <div class="store-img pso_img">
                                <div class="del_img" item="">×</div>
                                <img class="img_del" style="width:140px;height:140px;" src="{{ URL::asset($value) }}" alt="" />
                            </div>
                        @endif
                    @endforeach
                    {{--</ul>--}}
                </div>
                <div class="uploader-file" style="display: inline-block;">


                    <div class="img" > <img src="{{ URL('/static/static/goods/images/addimg.png') }}" name="" id="selsect" >
                        <p>点击上传</p>
                    </div>

                </div>

                <p class="uploader-info">提示：请上传jpg/png文件，且不超过5MB</p>
            </div>
        </div>

        <div class="addKtv-price clearfix">
            <p class="goods-name pull-left">商品简介：</p>
            <div class="houseList pull-left clearfix">
                <textarea class="text_in" name="goods_introduction_Chinese" style="width: 250px;height: 80px">{{ $China['goods_introduction'] }}</textarea>
            </div>
        </div>

        <div class="addKtv-price clearfix">
            <p class="goods-name pull-left"></p>
            <div class="houseList pull-left clearfix">
                <textarea class="text_in" name="goods_introduction_English" style="width: 250px;height: 80px">{{ $English['goods_introduction'] }}</textarea>
            </div>
        </div>

        <div class="form-group">
            <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>商品描述(中文)：</label>
            <div class="col-md-6 col-sm-10">
                <script id="fit_member1" name="goods_description_Chinese" type="text/plain"  >{!!$China['goods_description']!!}</script>
            </div>
        </div>

        <div class="form-group">
            <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>商品描述(中文)：</label>
            <div class="col-md-6 col-sm-10">
                <script id="fit_member2" name="goods_description_English" type="text/plain"  >{!!$English['goods_description']!!}</script>
            </div>
        </div>



        <div class="addKtv-price clearfix">
            <p class="goods-name pull-left">分类名称：</p>
            <div class="houseList pull-left clearfix">
                <select class="form-control" name="cate_id" style="width: 140px;">
                    @foreach($category as $k => $v)
                        <option value="{{ $v['id'] }}" @if($v['id'] == $goods['cat_id']) selected @endif>{{ $v['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="addKtv-price clearfix">
            <p class="goods-name pull-left">原价：</p>
            <div class="houseList pull-left clearfix">
                <input type="text" class="form-control unique" id="input-user_email" name="original_price" style="width: 300px" value="{{ $goods['original_price'] }}">
            </div>
        </div>

        <div class="addKtv-price clearfix">
            <p class="goods-name pull-left">现价：</p>
            <div class="houseList pull-left clearfix">
                <input type="text" class="form-control unique" id="input-user_email" name="present_price" style="width: 300px" value="{{ $goods['present_price'] }}">
            </div>
        </div>

        <div class="addKtv-price clearfix">
            <p class="goods-name pull-left">条形码：</p>
            <div class="houseList pull-left clearfix">
                <input type="text" class="form-control unique" id="input-user_email" name="barcode" style="width: 300px" value="{{ $goods['barcode'] }}" disabled>
            </div>
        </div>

        <div class="addKtv-price clearfix">
            <p class="goods-name pull-left">库存：</p>
            <div class="houseList pull-left clearfix">
                <input type="text" class="form-control unique" id="input-user_email" name="inventory" style="width: 300px" value="{{ $goods['inventory'] }}" disabled>
            </div>
        </div>


        <div class="addKtv-price clearfix">
            <p class="goods-name pull-left">优先级：</p>
            <div class="houseList pull-left clearfix">
                <input type="text" class="form-control unique" id="input-user_email" name="order" style="width: 300px" value="{{ $goods['order'] }}">
            </div>
        </div>

        <input type="hidden" class="form-control" value="{{ Session::token() }}" name="_token">
        <input type="hidden" class="form-control" value="{{ $goods['goods_id'] }}" name="goods_id">
        <input type="hidden" class="form-control" value="{{ $China['id'] }}" name="China_id">
        <input type="hidden" class="form-control" value="{{ $English['id'] }}" name="English_id">
        <input type="hidden" class="form-control shuffling_figure" value="{{ $goods['shuffling_figure'] }}" name="shuffling_figure" >
        <input type="hidden" class="form-control" value="{{ $goods['main_img'] }}" name="main_img">

        <div class="flex_wrap">
            <button class="addGoods" id="submits">修改商品</button>
            <button type="button" class="addGoods" onclick="back()">返回</button>
        </div>
    </div>
</div>





<script src="{{ asset('/static/themes/admin_simpleboot3/public/assets/js/jquery-1.10.2.min.js')}}"></script>
<script src="{{ asset('/static/static/js/admin.js')}}"></script>
<script type="text/javascript" src="{{ asset('/lib/layer/2.4/layer.js') }}"></script>

<script type="text/javascript">
    var img_url = "{{ url('admin/goods/delImg') }}";
    var imgnum = "{{ $count }}";
</script>
{{-- <script src="{{ asset('/static/static/goods/js/goodsimg.js')}}"></script> --}}
<script src="{{ asset('/static/ueditor/1.4.3/ueditor.config.js')}}"></script>
<script src="{{ asset('/static/ueditor/1.4.3/ueditor.all.min.js')}}"></script>
<script src="{{ asset('/static/ueditor/1.4.3/lang/zh-cn/zh-cn.js')}}"></script>


<script type="text/javascript" charset="utf-8">
    //主图上传
    var ue = UE.getEditor('fit_member1');
    var ue = UE.getEditor('fit_member2');


    // 返回上一步骤
    function back() {
        return history.go(-1);
    }

    //     2.设置全局ajax选项
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var _token = $('meta[name="csrf-token"]').attr('content');


    //验证js开始
    //用户名丧失焦点事件
    $('input[name=goods_storage]').blur(function(){

        //获取用户名中的值
        var cash_tixian = $('#goods_storage').val();

        var regss=/^[0-9]{0,15}$/;
        if(regss.test(cash_tixian)){
        }else{
            $('#goods_storage').attr("value",'');

            layer.msg('请您输入数字',{icon:2,time:2000});
            return false;
        }
    });
    $('input[name=money_time]').blur(function(){
        //获取用户名中的值
        var money_times = $('#money_time').val();
        var regss=/^[0-9]{0,15}$/;
        if(regss.test(money_times)){
        }else{
            $('#money_time').attr("value",'');

            layer.msg('请您输入数字',{icon:2,time:2000});
            return false;
        }
    });
    //用户名丧失焦点事件
    $('input[name=goods_price]').blur(function(){

        //获取用户名中的值
        var cash_tixian = $('#goods_price').val();

        // var regss=/^[0-9]{0,15}$/;
        var regss=/^[0-9]+[0-9\.]{0,15}$/;
        if(regss.test(cash_tixian)){
        }else{
            $('#goods_price').attr("value",'');

            layer.msg('请您输入数字',{icon:2,time:2000});
            return false;
        }
    });

    //折
    $('input[name=zhekoushu]').blur(function(){

        //获取用户名中的值
        var cash_tixian = $('#zhekoushu').val();

        var regss=/^[0-9|.]+$/g;
        if(regss.test(cash_tixian)){
            if(!cash_tixian || cash_tixian>10){
                $('#zhekoushu').attr("value",'');
                layer.msg('请您输入1-10之间的数字',{icon:2,time:2000});
            }else{

            }
        }else{
            $('#zhekoushu').attr("value",'');
            layer.msg('请您输入1-10之间的数字',{icon:2,time:2000});
        }

    });

    //防止重复提交
    var submits = 1;
    $('#submits').click(function(){
        if(submits==2){
            layer.msg('亲！您已经提交过了，请耐心等待', {icon: 6, time: 1000});return false;
        }
        $('#submits').attr('disabled','disabled');
        setTimeout(function(){
            $('#submits').removeAttr('disabled');
        },5000)



        // 获取到要传的数据
        var goods_name_Chinese = $("input[name='goods_name_Chinese']").val();
        var goods_name_English = $("input[name='goods_name_English']").val();
        var goods_introduction_Chinese = $("textarea[name='goods_introduction_Chinese']").val();
        var goods_introduction_English = $("textarea[name='goods_introduction_English']").val();
        var goods_description_Chinese = UE.getEditor('fit_member1').getContent();
        var goods_description_English = UE.getEditor('fit_member2').getContent();
        var original_price = $("input[name='original_price']").val();
        var present_price = $("input[name='present_price']").val();
        var order = $("input[name='order']").val();
        var goods_id = $("input[name='goods_id']").val();
        var China_id = $("input[name='China_id']").val();
        var English_id = $("input[name='English_id']").val();
        var shuffling_figure = $("input[name='shuffling_figure']").val();
        var main_img = $("input[name='main_img']").val();
        var cate_id = $("select[name='cate_id']").val();

        if(submits == 1){
            //重复提交赋值
            submits = 2;
            $.ajax({
                type: 'POST',
                url: "{{url('admin/goods/editPost')}}",
                dataType: 'json',
                data: {
                    _token:_token,
                    "goods_name_Chinese":goods_name_Chinese,
                    "goods_name_English":goods_name_English,
                    "goods_introduction_Chinese":goods_introduction_Chinese,
                    "goods_introduction_English":goods_introduction_English,
                    "goods_description_Chinese":goods_description_Chinese,
                    "goods_description_English":goods_description_English,
                    "original_price":original_price,
                    "present_price":present_price,
                    "order":order,
                    "goods_id":goods_id,
                    "China_id":China_id,
                    "English_id":English_id,
                    "shuffling_figure":shuffling_figure,
                    "main_img":main_img,
                    "cate_id":cate_id
                },
                success: function (json) {
                    console.log(json);
                    if(json.code == 1){
                        layer.msg('修改成功', {
                            icon: 6,//提示的样式
                            time: 1000, //2秒关闭（如果不配置，默认是3秒）//设置后不需要自己写定时关闭了，单位是毫秒
                            end:function(){
                                window.location.href='{{url('admin/goods/goodsList')}}'
                            }
                        });
                    }else{
                        //重复提交赋值
                        submits = 1;
                        layer.msg(json.msg, {icon: 2, time: 1000});
                    }
                },
                error: function (json) {
                    console.log(json.msg);
                }
            });
        }
    })
</script>
<script>
    $(function(){
        $('#selsectm').click(function(event) {
            $('#fileselectm').click();
        });
//选择文件
$('#fileselectm').change(function(event) {
    event.preventDefault();
    var n=event.target.files.length;
    var file;
    for (var i = 0; i < n; i++) {
        file=event.target.files[i];
        html5upm(file);
    };
});
//上传操作
function html5upm(file){
    /* Act on the event */
    var _token = $('meta[name="csrf-token"]').attr('content');
    var form_data=new FormData();
    form_data.append('_token',_token);
    form_data.append("Filedata",file);
    console.log(form_data);
    $.ajax({
        url: "{{url('admin/goods/upload')}}",
        type: 'POST',
        dataType: 'json',
        processData: false,
        contentType: false,
        data: form_data,
    })
    .done(function(data) {
        if (!data.status) {
            alert(data.info);
            return false;
        };
put_imgm(data.savepath,data.savename);//添加图片到img_box
$('.imgm').val(data.savepath+'/'+data.savename);//添加隐藏域
console.log("success");
})
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });

}



//添加图片到img_box
function put_imgm(savepath,savename){
    var img_url='{{ URL::asset("aaa") }}';

    url    = img_url.replace('aaa',savepath+'/'+savename);
    $('#selsectm').attr('src',url);
}


// 轮播图
$('#selsect').click(function(event) {
    $('#fileselect').click();
});
//选择文件
$('#fileselect').change(function(event) {
    event.preventDefault();
    var n=event.target.files.length;
    var file;
    for (var i = 0; i < n; i++) {
        file=event.target.files[i];
        html5up(file);
    };

});
          //上传操作
        function html5up(file){
                /* Act on the event */
                var _token = $('meta[name="csrf-token"]').attr('content');
                var form_data=new FormData();
                form_data.append('_token',_token);
                form_data.append("Filedata",file);
//                console.log(form_data);
                $.ajax({
                    url: "{{url('admin/goods/upload')}}",
                    type: 'POST',
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    data: form_data,
                })
                .done(function(data) {
                    console.log(data);
                    if (!data.status) {
                        alert(data.info);
                        return false;
                    };
                    put_img(data.savepath,data.savename);//添加图片到img_box
                    var bb=$('.shuffling_figure').val();
                    bb+=','+data.savepath+'/'+data.savename;
                    $('.shuffling_figure').val(bb);
                    console.log("success");

                })
                .fail(function() {
                    console.log("error");
                })
                .always(function() {
                    console.log("complete");
                });

            }


            //添加图片到img_box
            function put_img(savepath,savename){
                var img_url='{{ URL::asset("aaa") }}';

                var url    = img_url.replace('aaa',savepath+'/'+savename);
                // alert(img_url);
                var new_img='<div class="store-img pso_img">';
                    new_img+='<div class="del_img" item="">×</div>';
                    new_img+='<img class="img_del" style="width:140px;height:140px;" src="'+url+'" alt="" />';
                    new_img+='</div>';

                    $('#img-group').append(new_img);
            }



            //图片删除
            $(document).on('click', '.del_img', function(event) {
                event.preventDefault();
                /* Act on the event */
                var div=$(this).parents('div.pso_img');
                var img_url=div.find('.img_del').attr('src');
                var aa = '{{ URL::asset("") }}';
                var len = aa.length;
                var img_urll=img_url.substr(len);
                var urlstr = $('.shuffling_figure').val();
                var dataa = urlstr.replace(',/'+img_urll,'');
                var img_urll = img_url.replace(aa,'');
                var goods_id = $("input[name='goods_id']").val();
//               console.log(img_urll);
                $.ajax({
                    url: "{{url('admin/goods/delImg')}}",
                    type: 'POST',
                    dataType: 'json',
                    data: {url: img_urll,'goods_id':goods_id},
                    success:function(data){
                        console.log(data)
                        if (data.code!=1) {
                            console.log('del no');
                            return false;
                        }else{
                            console.log('del ok');
                            if(data.imgStatus==1){
                                $('.shuffling_figure').val('');
                            }else{
                                $('.shuffling_figure').val(dataa);
                            }
                            div.fadeOut(2000);
                            div.remove();
                        };
                    }
                });

            });
})
</script>
