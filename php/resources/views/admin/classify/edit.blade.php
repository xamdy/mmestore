
<link rel="stylesheet" href="{{asset('/static/static/goods/css/normalize.css')}}">
<link rel="stylesheet" href="{{asset('/static/static/goods/css/main.min.css')}}">

<link rel="stylesheet" href="{{asset('/static/static/goods/css/iconfont.css')}}">
<link rel="stylesheet" href="{{asset('/static/static/goods/css/star-rating.css')}}">
<link rel="stylesheet" href="{{asset('/static/static/goods/css/timepicki.css')}}">

<link rel="stylesheet" href="{{asset('/static/static/goods/css/addgoods.css')}}">


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
    .nav {
        height: 40px;
        margin-bottom: 0;
        padding-left: 0;
        list-style: none;
    }
    .nav-tabs {
        zoom: 1;
        overflow: hidden;
        border-bottom: 1px solid #ecf0f1;
    }
    .nav>li {
        position: relative;
        display: block;
    }
    .nav-tabs>li {
        float: left;
        margin-bottom: -1px;
    }
    .nav-tabs>li>a {
        color: #18BC9C;
        text-decoration: none;
        margin-right: 2px;
        line-height: 40px;
        position: relative;
        display: block;
        padding: 0 15px;
        border: 1px solid transparent;
        border-radius: 0 0 0 0;
    }
    .active a {
        color: #2C3E50 !important;
    }
    .addKtv-price {
        height: auto;
        min-height: 34px;
        line-height: 34px;
        padding-left: 100px;
        margin-top: 20px;
    }
    .addKtv-price label {
        float: left;
        width: 130px;
        display: block;
        text-align: right;
    }
    .same_input {
        height: 34px;
        width: 466px;
        margin-left: 20px;
    }
    .icon_img {
        float: left;
        margin-left: 20px;
    }
    .time {
        height: 34px;
        width: 200px;
        color: #777777;
        font-size: 14px;
        padding-left: 10px;
        margin-left: 20px;
    }
    .addGoods {
        color: #fff;
        background-color: #2C3E50;
        border-color: #2C3E50;
        margin-left: 300px;
        margin-top: 30px;
        cursor: pointer;
        padding: 6px 12px;
        font-size: 14px;
    }
</style>
</head>


<!-- 1.添加meta csrf_token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<body>
	<div class="wrap">
		<ul class="nav nav-tabs">
			<li ><a href="{{url('admin/classify/index')}}">分类管理</a></li>
			<li ><a href="{{url('admin/classify/add')}}">添加分类</a></li>
			<li class="active"><a href="javascript:;">编辑分类</a></li>
		</ul>

			<div class="form-group">
			<div class="addKtv-price clearfix" id="biaoqian">
					<label for="input-user_login" class="col-sm-2 control-label"><span class="form-required">*</span>分类名称:(中文)</label>
					<div class="col-md-6 col-sm-10">
					<!-- <div class="houseListss pull-lefts clearfix" id="type"> -->
					<input type="text" class="form-control same_input"  id="name" name="name" value="{{$res->name}}" maxlength='4'>
						<!-- <button  class="btn fenlei" item="1" type="button">血压仪</button>
						<button  class="btn fenlei" item="2" type="button">血糖仪</button> -->
					<!-- </div> -->
					</div>
				</div>
			</div>

            <div class="form-group">
                <div class="addKtv-price clearfix" id="biaoqian">
                    <label for="input-user_login" class="col-sm-2 control-label"><span class="form-required">*</span>（英文）</label>
                    <div class="col-md-6 col-sm-10">
                        <!-- <div class="houseListss pull-lefts clearfix" id="type"> -->
                        <input type="text" class="form-control same_input"  id="English_name" name="English_name" value="{{$res->English_name}}" maxlength='15'>
                        <!-- <button  class="btn fenlei" item="1" type="button">血压仪</button>
                        <button  class="btn fenlei" item="2" type="button">血糖仪</button> -->
                        <!-- </div> -->
                    </div>
                </div>
            </div>


			<div class="form-group">
                <div class="addKtv-price clearfix">
    				<label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>排序值</label>
    				<div class="col-md-6 col-sm-10">
    					<input type="text" class="form-control same_input"  id="num" name="num" value="{{$res->sorts}}" >
    				</div>
                </div>
			</div>


            <div class="form-group">
                <div class="addKtv-price clearfix">
                    <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>产品主图</label>
                    <div class="col-md-6 col-sm-10 icon_img">
                        <div class="uploader-file" id="uploader-file">
                            <div class="img">

                                <img src="{{ URL::asset($res->img) }}" name = "img_id1" id="img_id1">

                                <p>点击上传</p>
                            </div>
                        </div>
                        <p class="uploader-info">提示：请上传jpg/png文件，且不超过5MB</p>
                    </div>
                </div>
            </div>

			<div class="form-group">
                <div class="addKtv-price clearfix">
    				<label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>添加时间</label>
    				<div class="col-md-6 col-sm-10">
    					<input type="text" class="form-control time"  id="num" name="num" value='{{date("Y-m-d H:i:s",$res->add_time)}}' disabled="disabled">
    				</div>
                </div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<input type="hidden" class="form-control" value="{{$res->id}}" name="c_id">
					<input type="hidden" class="form-control" value="{{ Session::token() }}" name="_token">
					<input type="hidden" class="form-control"  name="id" value="{{$res->id}}">
					{{--<button type="submit" class="btn btn-primary js-ajax-submit" >保存分类</button>--}}
                    <button class="addGoods" id="submits">保存分类</button>
                    <button type="button" class="addGoods" onclick="back()">返回</button>
				</div>
			</div>
	</div>

    <div class="dialog addfoods-dialog">
        <div class="mask"></div>
        <div class="dialog-content " id="dialog-content">
            <div class="dialog-bd">
                <img src="../images/tanghao_03.jpg" alt="" style="vertical-align: middle;">
                <span class="error"></span>
            </div>
            <div class="dialog-ft">
                <button type="button" class="btn black-btn btn-lg submit-check-b">关闭</button>
            </div>
        </div>
    </div>

    <div class="dialog update-avatar-dialog">
        <div class="mask"></div>
        <div class="dialog-content">
            <div class="dialog-hd">
                <p>上传分类图</p>
                <span class="close-b">×</span> </div>
            <div class="dialog-bd">
                <div class="update-img imageBox" id="update-img1">
                    <div class="thumbBox" id="thumbBox"></div>
                    <div class="spinner" id="spinner"> </div>
                </div>
            </div>
            <div class="dialog-ft">
                <div class="btn black-btn-hollow reset-b" style="position:relative;"> 选择
                    <input type="file" class="file1" name="file1" id="file1" value="" />
                </div>
                <button type="button" class="btn black-btn confirm-b" style="position: relative;z-index: 10000;" id="imgEnter1">确认</button>
            </div>
        </div>
    </div>



    <script src="{{ asset('/static/themes/admin_simpleboot3/public/assets/js/jquery-1.10.2.min.js')}}"></script>
    <script src="{{ asset('/static/static/js/admin.js')}}"></script>
    <script type="text/javascript" src="{{ asset('/lib/layer/2.4/layer.js') }}"></script>

    <script src="{{ asset('/static/static/goods/js/jquery.min.js')}}"></script>
    <script src="{{ asset('/static/static/goods/js/times/timepicki.js')}}"></script>
    <script src="{{ asset('/static/static/goods/js/laydate/laydate.js')}}"></script>
    <script src="{{ asset('/static/static/goods/js/laydate/laydate.js')}}"></script>
    <script src="{{ asset('/static/static/goods/js/cutimg.js')}}"></script>

    <script src="{{ asset('/static/static/goods/js/goodsimg.js')}}"></script>


{{--	<script src="{{ asset('/static/themes/admin_simpleboot3/public/assets/js/jquery-1.10.2.min.js')}}"></script>--}}
{{--    <script type="text/javascript" src="{{ asset('/lib/layer/2.4/layer.js') }}"></script>--}}
	<script type="text/javascript">


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

	var type='';
	var cash_tixian='';

		// $('.fenlei').click(function(){
		// 	type = $(this).attr('item');
		// 	$("input[name='type']").val(type);
		// 	$(this).css('background-color','#1addb6');
		// 	$(".fenlei").not(this).css('background-color','');
		// })

    //折
    $('input[name=num]').blur(function(){
        //获取用户名中的值
        cash_tixian = $('#num').val();
    	var regss=/^[0-9]+$/g;
    	if(regss.test(cash_tixian)){
    		if(!cash_tixian || cash_tixian>99999){
	     		$('#num').val('');
	     		layer.msg('请您输入数字,数字不能超过99999',{icon:2,time:2000});
	     	}else{
	     	}
    	}else{
    		$('#num').val('');
	     	layer.msg('请您输入数字',{icon:2,time:2000});
    	}
    });
    function checkForm(){
    	type = $('#name').val();
    	num = $('#num').val();
    	if(!type ){
    		layer.msg('请输入分类名称',{icon:2,time:2000});return false;
    	}
    	if(!num){
    		layer.msg('请您填写排序值',{icon:2,time:2000}); return false;
    	}
    	var regss=/^[0-9]+$/g;
    	if(regss.test(num)){
    		if(!num || num>99999){
	     		$('#num').val('');
	     		layer.msg('请您输入数字,数字不能超过99999',{icon:2,time:2000});return false;
	     	}else{
	     		return true;
	     	}
    	}else{
    		layer.msg('请您输入数字',{icon:2,time:2000});return false;
    	}
	}


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


        var imgs='';
        if(imgbase64_1){
            var imgs = $('#img_id1').attr('src');
        }else {
            var imgs = 'a';
        }

        // 获取到要传的数据
        var name = $("input[name='name']").val();
        var English_name = $("input[name='English_name']").val();
        var num = $("input[name='num']").val();
        var c_id = $("input[name='c_id']").val();

        if(1){
            //重复提交赋值
            submits = 2;
            $.ajax({
                type: 'POST',
                url: "{{url('admin/classify/editpost')}}",
                dataType: 'json',
                data: {
                    "img":imgs,
                    _token:_token,
                    "name":name,
                    "English_name":English_name,
                    "num":num,
                    "c_id":c_id
                },
                success: function (json) {
                    if(json.code == 1){
                        layer.msg('修改成功!', {icon: 6, time: 1000});
//                    window.location.reload();
                        setTimeout(window.location.href='{{url('admin/classify/index')}}', 1000);
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
</body>
</html>