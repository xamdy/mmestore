@extends('admin/public/header')
</head>

<style type="text/css">
#menu {
    font:12px verdana, arial, sans-serif; /* 设置文字大小和字体样式 */
}
#menu, #menu li {
    float:left; /* 往左浮动 */
    list-style:none; /* 将默认的列表符号去掉 */
    padding:20px; /* 将默认的内边距去掉 */
    margin:0; /* 将默认的外边距去掉 */
}
</style>

<body>
    <div class="wrap">
        <ul class="nav nav-tabs">
            <li><a href="{{url('admin/goods/goodsList')}}">商品列表</a></li>
            <li><a href="{{url('admin/goods/add')}}">商品添加</a></li>
            <li class="active"><a>商品详情</a></li>
        </ul>
        <form method="post" class="form-horizontal js-ajax-form margin-top-20" action="{{url('admin/goods/addGoods')}}" enctype="multipart/form-data">
            <div class="form-group">
                <label for="input-user_login" class="col-sm-2 control-label"><span class="form-required">*</span>商品名称：</label>
                <div class="col-md-6 col-sm-10">
                    <p class="form-control-static">{{ $China['goods_name'] }}</p>
                </div>
            </div>

            <div class="form-group">
                <label for="input-user_login" class="col-sm-2 control-label"><span class="form-required"></span></label>
                <div class="col-md-6 col-sm-10">
                    <p class="form-control-static">{{ $English['goods_name'] }}</p>
                </div>
            </div>


            <div class="form-group">
                <label for="input-user_login" class="col-sm-2 control-label"><span class="form-required"></span>商品主图：</label>
                <div class="col-md-6 col-sm-10">
                    {{--<p class="form-control-static"><img width="100px" height="100px" src="{{ URL::asset($goods['main_img']) }}" id="img"/></p>--}}
                    <a href="javascript:;" onclick='showImg("{{ URL::asset($goods['main_img']) }}")'><img width="100px" height="100px" src="{{ URL::asset($goods['main_img']) }}" id="img"/></a>
                </div>
            </div>

            <div class="form-group">

                {{csrf_field()}}
                <label for="input-user_login" class="col-sm-2 control-label"><span class="form-required">*</span>商品图片：</label>
                <div class="col-md-6 col-sm-10">
                    {{--<ul class="list_btn" id="menu">--}}

                        @foreach($imgUrl as $key => $value)
                        <a href="javascript:;" onclick='showImg("{{ URL::asset($value) }}")'><img class="sz" width="100px" height="100px" src="{{ URL::asset($value) }}" alt=""></a>
                        {{-- <li><img id="imgone" class="sz" width="100px" height="100px" src="{{ URL::asset($value) }}"></li> --}}
                        @endforeach

                    {{--</ul>--}}
                </div>
                {{--<div class="col-md-6 col-sm-10">--}}
                    {{--<input type="file" class="default" name="goods_img">--}}
                {{--</div>--}}
            </div>

            {{--<div class="form-group">--}}
                {{--<label for="input-user_pass" class="col-sm-2 control-label"><span class="form-required">*</span>商品图片：</label>--}}
                {{--<div class="col-md-6 col-sm-10">--}}
                    {{--<ul id="photos" class="pic-list unstyled"></ul>--}}
                    {{--<a href="javascript:upload_multi_image('图片上传','#photos','photos-item-wrapper');" class="btn btn-small">选择图片</a>--}}
                {{--</div>--}}
            {{--</div>--}}


            <div class="form-group">
                <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>商品简介：</label>
                <div class="col-md-6 col-sm-10">
                    {{--<textarea readonly name="goods_introduction_Chinese" style="width: 250px;height: 80px">{{ $China['goods_introduction'] }}</textarea>--}}
                    <p class="form-control-static">{{ $China['goods_introduction'] }}</p>
                </div>
            </div>

            <div class="form-group">
                <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required"></span></label>
                <div class="col-md-6 col-sm-10">
                    {{--<textarea readonly name="goods_introduction_English" style="width: 250px;height: 80px">{{ $English['goods_introduction'] }}</textarea>--}}
                    <p class="form-control-static">{{ $English['goods_introduction'] }}</p>
                    {{--<input type="text" class="form-control" id="input-user_email" name="goods_introduction_English">--}}
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


            <div class="form-group">
                <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>原价：</label>
                <div class="col-md-6 col-sm-10">
                    <p class="form-control-static">{{ $goods['original_price'] }}</p>
                </div>
            </div>

            <div class="form-group">
                <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>现价：</label>
                <div class="col-md-6 col-sm-10">
                    <p class="form-control-static">{{ $goods['present_price'] }}</p>
                </div>
            </div>

            <div class="form-group">
                <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>所属分类：</label>
                <div class="col-md-6 col-sm-10">
                    <p class="form-control-static">{{ $goods['name'] }}</p>
                </div>
            </div>

            <div class="form-group">
                <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>库存量：</label>
                <div class="col-md-6 col-sm-10">
                    <p class="form-control-static">{{ $goods['inventory'] }}</p>
                </div>
            </div>

            <div class="form-group">
                <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>销售量：</label>
                <div class="col-md-6 col-sm-10">
                    <p class="form-control-static">{{ $goods['sold'] }}</p>
                </div>
            </div>

            <div class="form-group">
                <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>条形码：</label>
                <div class="col-md-6 col-sm-10">
                    <p class="form-control-static">{{ $goods['barcode'] }}</p>
                </div>
            </div>

            <div class="form-group">
                <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>优先级：</label>
                <div class="col-md-6 col-sm-10">
                    <p class="form-control-static">{{ $goods['order'] }}</p>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="button" class="btn btn-primary js-ajax-submit" onclick="back();">返回</button>
                </div>
            </div>
        </form>
    </div>
    <script src="{{ asset('/js/jquery-1.8.3.min.js')}}"></script>
    {{-- <script src="{{ asset('/static/static/js/admin.js')}}"></script> --}}
    <script src="{{ asset('/static/static/js/ueditor/ueditor.config.js')}}"></script>
    <script src="{{ asset('/static/static/js/ueditor/ueditor.all.min.js')}}"></script>
    <script src="{{ asset('static/layer/layer.js')}}"></script>
    <script>


        function showImg(url){
    // console.log("进来了")
    var img = "<img src='" + url + "' />";
    layer.closeAll(); 
    layer.open({  
        type: 1,  
        shade: false,  
        title: false, //不显示标题  
        offset: ['200px'], 
        area: [img.width + 'px', img.height+'px'],   
        content: img, //捕获的元素，注意：最好该指定的元素要存放在body最外层，否则可能被其它的相对元素所影响
        tipsMore: true, 
        cancel: function () {
            // layer.msg('图片查看结束！', { time: 5000, icon: 6 });  
        }  
    });  
}  

</script>
</body>
</html>

<script src="{{ asset('/static/ueditor/1.4.3/ueditor.config.js')}}"></script>
<script src="{{ asset('/static/ueditor/1.4.3/ueditor.all.min.js')}}"></script>
<script src="{{ asset('/static/ueditor/1.4.3/lang/zh-cn/zh-cn.js')}}"></script>

<script>

    var ue = UE.getEditor('fit_member1');
    var ue = UE.getEditor('fit_member2');

    // 返回上一步骤
    function back() {
        return history.go(-1);
    }

</script>
