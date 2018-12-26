@extends('admin/public/header')
</head>

<!-- 1.添加meta csrf_token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .error {
        color: red;
    }
    #img-error {
        font-size: 20px;
        margin-left: -600px;
    }
    .uploader-file {
        position: relative;
        height: 140px;
        width: 140px;
    }
    .uploader-file .img {
        position: relative; height: 140px; width: 140px;
    }
    .uploader-file .img img {
        width: 100%;    height: 100%;
    }
    .uploader-file .img p {
        position: absolute;bottom: -10px;width: 140px;text-align: center;background: #000;color: #fff;
        opacity: .5;filter: alpha(opacity=50);line-height: 24px;font-size: 12px;font-weight: 200;
    }
    select{

        width: auto;
        padding: 0 2%;
        margin: 0;

    }

    option{

        text-align:center;

    }
</style>
<body>
	<div class="wrap">
		<ul class="nav nav-tabs">
			<li><a href="{{url('admin/goods/goodsList')}}">商品列表</a></li>
			<li class="active"><a href="{{url('admin/goods/add')}}">商品添加</a></li>
		</ul>
		<form method="post" class="form-horizontal js-ajax-form margin-top-20" action="{{url('admin/goods/addGoods')}}" enctype="multipart/form-data" id="fm">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="form-group">
                <label for="input-user_login" class="col-sm-2 control-label"><span class="form-required">*</span>商品名称：</label>
                <div class="col-md-6 col-sm-10">
                   <input type="text" class="form-control"  name="goods_name_Chinese" required maxlength='30' placeholder="请输入中文" style="width: 400px" value="">
               </div>
           </div>

           <div class="form-group">
                <label for="input-user_login" class="col-sm-2 control-label"><span class="form-required"></span></label>
                <div class="col-md-6 col-sm-10">
                    <input type="text" class="form-control"  name="goods_name_English" placeholder="Please enter English" style="width: 400px" required maxlength='60'>
                </div>
           </div>

            <div class="form-group">
                <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>产品主图：</label>
                <div class="col-md-6 col-sm-10">
                    <input type="file" class="form-control"  id="" name="main_img"  style="border: 0px;outline:none;cursor: pointer;">

                </div>
            </div>

            <div class="form-group">
                <label for="input-user_login" class="col-sm-2 control-label"><span class="form-required"></span></label>
                    <div class="col-md-6 col-sm-10">
                <p class="form-control-static form-required">* 提示：请上传jpg/png文件，且不超过5MB；</p>
                </div>
            </div>

            <div class="form-group">


             {{csrf_field()}}
                <label for="input-user_login" class="col-sm-2 control-label"><span class="form-required">*</span>商品图片：</label>
                <div class="col-md-6 col-sm-10">

                    <div id="fileUploadContent" class="fileUploadContent"></div>

                    <span class="form-required ">* 最多可上传6张图片；仅可上传800px*800px大小；仅支持png格式；</span>

                    {{--<ul class="list_btn">--}}
                        {{--<li><img id="imgone" class="sz" width="100px" height="100px" src="" style="display: none;"></li>--}}
                        {{--<li>  <input type="file" id="house_img_one1" name="goods_img" multiple="multiple" onchange="houseImgOne(this)"></li>--}}
                    {{--</ul>--}}
                </div>
            <input type="hidden" class="srcs" style="width: 100px" id="img" name="img" value="">


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
            <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>商品分类：</label>
            <div class="col-md-6 col-sm-10">
                <select class="form-control" name="id" style="width: 150px;height: 40px;">
                    <option value="0">请选择分类</option>
                    @foreach($category as $k => $v)
                    <option value="{{ $v['id'] }}">{{ $v['name'] }}</option>
                    @endforeach
                </select>
                {{--<input type="text" class="form-control" id="input-user_email" name="goods_introduction_Chinese">--}}
            </div>
        </div>


        <div class="form-group">
            <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>商品简介：</label>
            <div class="col-md-6 col-sm-10">
                <textarea name="goods_introduction_Chinese"  rows="4" cols="50" autofocus  placeholder="请输入中文" maxlength='30' style="min-height: 100px;min-width: 200px;max-height: 100px; max-width: 200px;"></textarea>
                {{--<input type="text" class="form-control" id="input-user_email" name="goods_introduction_Chinese">--}}
            </div>
        </div>

        <div class="form-group">
            <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required"></span></label>
            <div class="col-md-6 col-sm-10">
                <textarea name="goods_introduction_English" style="min-height: 100px;min-width: 200px;max-height: 100px; max-width: 200px;" placeholder="Please enter English" required maxlength='60' rows="10" cols="10"></textarea>
                {{--<input type="text" class="form-control" id="input-user_email" name="goods_introduction_English">--}}
            </div>
        </div>

        {{--<div class="form-group">--}}
            {{--<label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>商品描述：</label>--}}
            {{--<div class="col-md-6 col-sm-10">--}}
                {{--<textarea name="goods_description_Chinese" style="width: 250px;height: 80px" placeholder="请输入中文" maxlength='30'></textarea>--}}
                {{--<input type="text" class="form-control" id="input-user_email" name="goods_description_Chinese">--}}
            {{--</div>--}}
        {{--</div>--}}

        {{--<div class="form-group">--}}
            {{--<label for="input-user_email" class="col-sm-2 control-label"><span class="form-required"></span></label>--}}
            {{--<div class="col-md-6 col-sm-10">--}}
                {{--<textarea name="goods_description_English" style="width: 250px;height: 80px" placeholder="Please enter English" required maxlength='60'></textarea>--}}
                {{--<input type="text" class="form-control" id="input-user_email" name="goods_description_English">--}}
            {{--</div>--}}
        {{--</div>--}}

        <div class="form-group">
            <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>商品描述(中文)：</label>
            <div class="col-md-6 col-sm-10">
                <script id="fit_member1" name="goods_description_Chinese" type="text/plain"  ></script>
            </div>
        </div>


        <div class="form-group">
            <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>（英文）：</label>
            <div class="col-md-6 col-sm-10">
                <script id="fit_member2" name="goods_description_English" type="text/plain"  ></script>
            </div>
        </div>

        <div class="form-group">
            <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>库存：</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control"  name="inventory" style="width: 300px" placeholder="请设置库存">
            </div>
        </div>

        <div class="form-group">
            <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>原价：</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control" id="stockMaxAmount" name="original_price" style="width: 300px" placeholder="请设置原价">
            </div>
        </div>

        <div class="form-group">
            <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>现价：</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control" id="stockMinAmount" name="present_price" style="width: 300px" placeholder="请设置现价  现价不可高于原价">
            </div>
        </div>

        <div class="form-group">
            <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>条形码：</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control" onmouseout  ="goodsBarcode();" name="barcode" style="width: 300px" placeholder="请设置条形码">
                <span id='error_num' class='form-required'></span>
            </div>
        </div>

        <div class="form-group">
            <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>优先级：</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control" name="order" value="1" style="width: 300px" placeholder="请设置优先级">
            </div>
        </div>


        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="hidden" class="form-control" value="{{ Session::token() }}" name="_token">
                <button type="submit" class="btn btn-primary js-ajax-submit">添加</button>
            </div>
        </div>
		</form>
	    </div>

    </div>


    <script src="{{ asset('/fileUpload/js/jquery-2.1.3.min.js') }}"></script>
    <link href="{{ asset('/fileUpload/css/iconfont.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/fileUpload/css/fileUpload.css') }}" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="{{ asset('/fileUpload/js/fileUpload.js') }}"></script>

    {{--<script src="{{ asset('/js/jquery-1.8.3.min.js')}}"></script>--}}
    <script src="{{ asset('/static/static/js/admin.js')}}"></script>
    <script src="{{ asset('/static/static/js/ueditor/ueditor.config.js')}}"></script>
    <script src="{{ asset('/static/static/js/ueditor/ueditor.all.min.js')}}"></script>
    <script src="{{ asset('/static/static/goods/js/goodsimg.js')}}"></script>
    <script src="{{ asset('/static/ueditor/1.4.3/ueditor.config.js')}}"></script>
    <script src="{{ asset('/static/ueditor/1.4.3/ueditor.all.min.js')}}"></script>
    <script src="{{ asset('/static/ueditor/1.4.3/lang/zh-cn/zh-cn.js')}}"></script>
    <script src="{{ asset('static/themes/admin_simpleboot3/public/assets/js/jquery.validate.min.js')}}"></script>

</body>
</html>

<script>

    var ue = UE.getEditor('fit_member1');
    var ue = UE.getEditor('fit_member2');

    //     2.设置全局ajax选项
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $(function(){
        $('.iconfont').click(function(){
//            $(".srcs").val('');
           $("input[name='img']").val('');
        })
    })

    $("#fm").validate({
        ignore : [],
        rules:{
            goods_name_Chinese:{
                required: true
            },
            goods_name_English:{
                required: true
            },
            id:{
                min:1
            },
            goods_introduction_Chinese:{
                required: true
            },
            goods_introduction_English:{
                required: true
            },
            inventory:{
                required: true
            },
            original_price:{
                required: true
            },
            present_price:{
                required: true,
                amountLimit:true
            },
            img:{
                required: true
            }
        },
        messages:{
            goods_name_Chinese:{
                required: "请输入商品名称"
            },
            goods_name_English:{
                required: "Please enter the name of the product"
            },
            id:{
                min:"请选择分类"
            },
            goods_introduction_Chinese:{
                required: "请输入商品简介"
            },
            goods_introduction_English:{
                required: "Please enter product description"
            },
            inventory:{
                required: "请输入库存"
            },
            original_price:{
                required: "请输入原价"
            },
            present_price:{
                required: "请输入现价"
            },
            img:{
                required: "请先点击上传图片按钮"
            }
        },
      //这个地方要注意，修改去控制器验证的事件。
      onsubmit: true
  });
     //验证现价小于原价
  jQuery.validator.addMethod("amountLimit", function (value, element) {
    var startTime=$("input[name='original_price']").val();
    var endTime = $("input[name='present_price']").val();
    var returnval=false;
    if(parseFloat(startTime)>parseFloat(endTime)){
        returnval=true;
    }
    else{
        returnval=false;
    }
    return returnval;
  }, "现价必须小于原价");

    var _token = $('meta[name="csrf-token"]').attr('content');

    // 判断条形码是否存在
    function goodsBarcode() {
        var barcode = $('input[name=barcode]').val();

        if(barcode == "") {
            return $('#error_num').html('&nbsp;&nbsp;条形码不能为空');
        }

        var reg = /^[0-9]+.?[0-9,/]*$/;
        //正则验证输入信息是否正确
        if (reg.exec(barcode)) {
            var url = "{{url('admin/goods/isBarcode')}}";
            $.ajax({
                type:"post",
                url:url,
                data:{'barcode':barcode,_token:_token},
                dataType:"json",
                success:function(data){
                    console.log(data);
                    if(data == 2){
                        //清空输入框原有编号
                        var text = '编号:'+barcode+' 已存在';
                        //追加新的信息
                        $('#error_num').html(text);
                    }else if(data == 1) {
                        var text = '通过';
                        //追加新的信息
                        $('#error_num').html(text);
                    }
                }
            })
        }else {
            $('#error_num').html('&nbsp;&nbsp;格式错误');
        }
    }




    $("#fileUploadContent").initUpload({
        "uploadUrl":"{{ url('admin/goods/imgUpload') }}",//上传文件信息地址
        "size":1024,//文件大小限制，单位kb,默认不限制
        "maxFileNumber":6,//文件个数限制，为整数
        "filelSavePath":"/storage/uploads/",//文件上传地址，后台设置的根目录
        "beforeUpload":beforeUploadFun,//在上传前执行的函数
        "onUpload":onUploadFun,//在上传后执行的函数
        autoCommit:false,//文件是否自动上传
        "fileType":['png']//文件类型限制，默认不限制，注意写的是文件后缀
    });

    function beforeUploadFun(opt){
        opt.otherData =[{ "name":"name","value":"zxm" }];
//        alert(formTake.getDataWithUploadFile('name'));
//        return false;
}
    function onUploadFun(opt,data){
    //        console.log(formTake.getDataWithUploadFile(data));
    console.log(data);
    var img = $('#img').val(data);

        uploadTools.uploadError(opt);//显示上传错误
        uploadTools.uploadSuccess(opt);//显示上传成功
    }

    function testUpload(){
        var opt = uploadTools.getOpt("fileUploadContent");
        uploadEvent.uploadFileEvent(opt);
    }

</script>
