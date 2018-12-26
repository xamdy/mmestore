@extends('admin/public/header')

</head>
<body>
	<div class="wrap">
		<ul class="nav nav-tabs">
			<li ><a href="{{url('admin/classify/index')}}">分类管理</a></li>
			<li class="active"><a href="{{url('admin/classify/add')}}">添加分类</a></li>
		</ul>
		<form method="post" onsubmit='return checkForm()' enctype="multipart/form-data" class="form-horizontal js-ajax-form margin-top-20" action="{{url('admin/classify/addpost')}}">
			
			<div class="form-group">
			<div class="addKtv-price clearfix" id="biaoqian">
					<label for="input-user_login" class="col-sm-2 control-label"><span class="form-required">*</span>分类名称:(中文)</label>
					<div class="col-md-6 col-sm-10">
					<!-- <div class="houseListss pull-lefts clearfix" id="type"> -->
					<input type="text" class="form-control"  id="name" name="name" maxlength='4' required>
						<!-- <button  class="btn fenlei" item="1" type="button">血压仪</button>
						<button  class="btn fenlei" item="2" type="button">血糖仪</button> -->
					<!-- </div> -->
					</div>
				</div>
			</div>

            <div class="form-group">
                <div class="addKtv-price clearfix" id="biaoqian">
                    <label for="input-user_login" class="col-sm-2 control-label"><span class="form-required">*</span>分类名称:(英文)</label>
                    <div class="col-md-6 col-sm-10">
                        <!-- <div class="houseListss pull-lefts clearfix" id="type"> -->
                        <input type="text" class="form-control"  id="English_name" name="English_name" maxlength='15' required>
                        <!-- <button  class="btn fenlei" item="1" type="button">血压仪</button>
                        <button  class="btn fenlei" item="2" type="button">血糖仪</button> -->
                        <!-- </div> -->
                    </div>
                </div>
            </div>

			<div class="form-group">
				<label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>优先级</label>
				<div class="col-md-6 col-sm-10">
					<input type="text" class="form-control"  id="num" name="num" value="1">
				</div>
			</div>

            <div class="form-group">
                <label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span>图片</label>
                <div class="col-md-6 col-sm-10">
                    <input type="file" class="form-control"  id="img" name="img"  style="border: 0px;outline:none;cursor: pointer;">
                </div>
            </div>

			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<input type="hidden" class="form-control" value="{{ Session::token() }}" name="_token">
					<input type="hidden" class="form-control"  name="type">
					<button type="submit" class="btn btn-primary js-ajax-submit" >添加分类</button>
				</div>
			</div>
		</form>
	</div>
	<script src="{{ asset('/static/themes/admin_simpleboot3/public/assets/js/jquery-1.10.2.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('/lib/layer/2.4/layer.js') }}"></script>
	<!-- <script src="{{ asset('/static/static/js/admin.js')}}"></script> -->
	<script type="text/javascript">
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
	</script>>
</body>
</html>