// JavaScript Document

$(function(){
	var longitude;//经度
	var latitude; //纬度
	var imgbase64_1;
	var imgbase64_2;
	var imgarr = [];
	
	//店铺分类
	$('#adds_type .select_adds_conts p').on('click', function(e) {
		e.stopPropagation()
		var ul = $(this).siblings('ul');
		var lilength = ul.find('li').length
		var ulHeight = 36 * lilength
		ul.css('height', ulHeight);
		ul.toggleClass('none')
	})
	$('#adds_type .select_adds_conts ul li').on('click', function() {
		var text = $(this).text();
		var value = $(this).attr('name');
		$(this).parent().siblings('p').text(text);
		$(this).parent().siblings('p').attr('name',value);
		$(this).parent().removeClass('none');
		if(value == 2)
		{
			var str = '<li class="clearfix m-b24">'+
							'<div class="pull-left"> <span class="f-color"><i>*&nbsp;</i>配送价格（元）</span></div>'+
							'<div class="pull-left">'+
								'<input id="stores_freight" class="type-text" type="text">'+
							'</div>'+
						'</li>'+
						'<li class="clearfix m-b24" id="delivery_time" style="z-index:222;">'+
							'<div class="pull-left"> <span class="f-color"><i>*&nbsp;</i>送达时间（分钟）</span> </div>'+
							'<div class="pull-left list">'+
								'<div class="time-list">'+
									'<div class="select_adds_time pull-left m-r16">'+
										'<div class="select_adds_conts">'+
											'<p id="delivery_time_p">请选择</p>'+
											'<ul style="z-index:888">'+
												'<li>10</li>'+
												'<li>20</li>'+
												'<li>30</li>'+
												'<li>40</li>'+
												'<li>50</li>'+
												'<li>60</li>'+
												'<li>70</li>'+
												'<li>120</li>'+
											'</ul>'+
										'</div>'+
									'</div>'+
								'</div>'+
							'</div>'+
						'</li>'+
						'<li class="clearfix m-b24">'+
							'<div class="pull-left"> <span class="f-color"><i>*&nbsp;</i>折扣活动</span> </div>'+
							'<div class="pull-left" style="line-height: 30px;">'+
								'<label for="b" style="margin-right:10px; color:#171717 ">'+
									'<i class="biao" ></i>'+
									'<input type="radio" class="radio discount-type" id="b" name="discount-type" checked="checked" value="1" style="margin-right:6px;"/>'+
									'不参与折扣'+
								'</label>'+
								'<label for="a"  style="margin-right:10px; color:#808080 ">'+
									'<i class="biao"></i>'+
									'<input type="radio" class="radio discount-type" id="a" name="discount-type" value="2" style="margin-right:6px;"/>'+
									'参与折扣'+
								'</label>'+
							'</div>'+
						'</li>'+
						'<li class="clearfix m-b24" id="discount_num" style="display:none;">'+
							'<div class="pull-left"> <span class="f-color"><i>*&nbsp;</i>折扣数（折）</span> </div>'+
							'<div class="pull-left">'+
								'<input class="type-text" type="text" id="discount" value="" placeholder="请您输入1-10之间的数字" />'+
							'</div>'+
						'</li>';
		}
		else if(value == 3)
		{
			var str = '<li class="clearfix m-b24" style="z-index:666;">'+
							'<div class="pull-left"> <span class="f-color"><i>*&nbsp;</i>酒店星级</span> </div>'+
							'<div class="pull-left">'+
								'<div id="test1">'+
									'<div id="stores_start" class="select_adds_conts mar12px width100">'+
										'<p value="">请选择</p>'+
										'<ul id="stores_start_ul"  class="city_1 mar12px">'+
										'</ul>'+
									'</div>'+
								'</div>'+
							'</div>'+
						'</li>'+
						'<li class="clearfix m-b24" style="z-index:555;">'+
							'<div class="pull-left"> <span class="f-color"><i>*&nbsp;</i>酒店品牌</span> </div>'+
							'<div class="pull-left">'+
								'<div id="test1">'+
									'<div id="stores_brand" class="select_adds_conts mar12px width100">'+
										'<p value="">请选择</p>'+
										'<ul id="stores_brand_ul"  class="city_1 mar12px">'+
										'</ul>'+
									'</div>'+
								'</div>'+
							'</div>'+
						'</li>'+
						'<li class="clearfix m-b24" style="z-index:444;">'+
							'<div class="pull-left"> <span class="f-color"><i>*&nbsp;</i>入住类型</span> </div>'+
							'<div class="pull-left">'+
								'<div id="test1">'+
									'<div id="stores_enter" class="select_adds_conts mar12px width100">'+
										'<p value="">请选择</p>'+
										'<ul id="stores_enter_ul"  class="city_1 mar12px">'+
										'</ul>'+
									'</div>'+
								'</div>'+
							'</div>'+
						'</li>';
		}
		else
		{
			var str = '';

		}
		$('#storesMore').html(str);
	})
	


	
	//编辑上传店铺头像
	$('#img_id1').on('click',function(){
		
		$('.update-avatar-dialog').show();
		$("body").css({"overflow":"hidden"})
	})
	var options ={
		thumbBox: '#thumbBox',
		spinner: '#spinner',
		imageBox :'#update-img1',
		imgSrc: ''
	}
	var cropper = $('#update-img1').cropbox(options);
	$('#file1').on('change',function(){
		var reader = new FileReader();
		reader.onload = function(e) {
			options.imgSrc = e.target.result;
			imgbase64_1 =  e.target.result;
			cropper = $('#update-img1').cropbox(options);
		}
		reader.readAsDataURL(this.files[0]);
	})
	$('#imgEnter1').on('click',function(){
		$('.update-avatar-dialog').hide()
		$('#img_id1').attr('src',cropper.getDataURL())
		imgbase64_1 =  cropper.getDataURL();
	})
	//编辑上传店铺实景图片
	$('#img_id2').on('click',function(){
		if(imgarr.length > 4){
			alert('最多上传5张')
		}else{
			$("body").css({"overflow":"hidden"})
			$('.update-storeimg-dialog').show()
		}
	})
	var options2 ={
		thumbBox: '#thumbBox',
		spinner: '#spinner',
		imageBox :'#update-img2',
		imgSrc: ''
	}
	var cropper2 = $('#update-img2').cropbox(options2);
	
	$('#file2').on('change',function(){
		var reader = new FileReader();
		reader.onload = function(e) {
			options2.imgSrc = e.target.result;
			cropper2 = $('#update-img2').cropbox(options2);
		}
		reader.readAsDataURL(this.files[0]);
	})
	
	$('#imgEnter2').on('click',function(e){
		e.stopPropagation();
		$('.update-storeimg-dialog').hide();		
		imgbase64_2 = cropper2.getDataURL();
		imgarr.push(imgbase64_2);	
		var str = $(
				'<div class="store-img pso_img">'
				+'<div class="del_img">×</div>'
				+'<img style="width:100%;height:100%;" src="'+ imgbase64_2 +'" alt="" />'
				+'</div>'
		)
		$('#img-group').append(str);

	})
	$('#img-group').on('click',".del_img",function(e){
		e.stopPropagation();
		var ss=$(this).parent(".pso_img").index();
		imgarr.splice(ss,1);
		$(this).parent(".pso_img").remove();
		
	});	

	//营业时间
	var zIndex = 80;
	$('#time_add').on('click', function() {
		zIndex--;
		var timelist = $('#select_time .time-list').eq(0).clone(true);
		timelist.css('z-index', zIndex)
		$('#select_time .list').append(timelist)
	});
	$('#time_rem').on('click', function(){
		var timelist = $('#select_time .time-list').length;
		if(timelist<=1){
			return false;
		}else{
			$('#select_time .time-list').eq(timelist-1).remove()

		}
	});
	$('#select_time .select_adds_conts p').on('click', function(e) {
		e.stopPropagation()
		var ul = $(this).siblings('ul');
		ul.toggleClass('none')
	})
	$('#select_time .select_adds_conts ul li').on('click', function() {
		var text = $(this).text();
		$(this).parent().siblings('p').text(text);
		$(this).parent().removeClass('none')
	})
	
	$('#storesMore').on('click', '#delivery_time p', function(e) {
		e.stopPropagation()
		var ul = $(this).siblings('ul');
		ul.toggleClass('none')
	})
	$('#storesMore').on('click', '#delivery_time ul li', function() {
		var text = $(this).text();
		$(this).parent().siblings('p').text(text);
		$(this).parent().removeClass('none')
	})
	

/*****地图实例*****/
	//地图精度纬度
	//地图实例，自动定位		
var map = new AMap.Map('container', {
        resizeEnable: true
    });
    map.plugin(['AMap.Geolocation','AMap.Autocomplete','AMap.PlaceSearch'], function() {
        geolocation = new AMap.Geolocation({
            enableHighAccuracy: true,//是否使用高精度定位，默认:true
            timeout: 10000,          //超过10秒后停止定位，默认：无穷大
            buttonOffset: new AMap.Pixel(10, 20),//定位按钮与设置的停靠位置的偏移量，默认：Pixel(10, 20)
            zoomToAccuracy: true,      //定位成功后调整地图视野范围使定位位置及精度范围视野内可见，默认：false
            buttonPosition:'RB'
        });
        map.addControl(geolocation);

        geolocation.getCurrentPosition();
        AMap.event.addListener(geolocation, 'complete', onComplete);//返回定位信息
        AMap.event.addListener(geolocation, 'error', onError);      //返回定位出错信息
    });

    var autoOptions = {
        input: "suggestId"
    };
    var auto = new AMap.Autocomplete(autoOptions);
    var placeSearch = new AMap.PlaceSearch({
        map: map
    });  //构造地点查询类
    AMap.event.addListener(auto, "select", select);//注册监听，当选中某条记录时会触发
    function select(e) {
        placeSearch.setCity(e.poi.adcode);
        placeSearch.search(e.poi.name);  //关键字查询查询
    }


    //解析定位结果
    function onComplete(data) {
        console.log(data)
        //定位成功回调
    }
    //解析定位错误信息
    function onError(data) {
        //定位失败回调
    }






    AMap.plugin('AMap.Geocoder',function(){
        var geocoder = new AMap.Geocoder({
            city: "010"//城市，默认：“全国”
        });

        var input = document.getElementById('suggestId');
        var message = document.getElementById('message');
        map.on('click',function(e){
            map.clearMap();
            latitude = e.lnglat.lat;//纬度
            longitude = e.lnglat.lng;//经度
            var  marker = new AMap.Marker({
                position : [longitude,latitude],
                map : map
            })

            map.setZoomAndCenter(14, [longitude,latitude]);
            geocoder.getAddress(e.lnglat,function(status,result){
            	console.log(status)
                if(status=='complete'){
                    input.value = result.regeocode.formattedAddress
                }else{
                }
            })
        })

        input.onchange = function(e){
            var address = input.value;
            geocoder.getLocation(address,function(status,result){
                if(status=='complete'&&result.geocodes.length){
                    marker.setPosition(result.geocodes[0].location);
                    map.setCenter(marker.getPosition())
                    message.innerHTML = ''
                }else{
                    message.innerHTML = '无法获取位置'
                }
            })
        }

    });




	//省
	$('#city_1 p').on('click', function() {
		$.ajax({
			url : linkUurlA,
			type : 'post',
			data : {'parent_id':'0'},
			success:function(data){
				var data = JSON.parse(data)
				if(data.code == 1)
				{
					$("#city_ul_1").html("")
					for(var i=0; i<data.datas.length; i++)
					{
						var _l2 = $('<li data-value="'+ data.datas[i].area_id+'">'+ data.datas[i].area_name +'</li>');
						$("#city_ul_1").append(_l2)
					}
					$("#city_ul_1 li").on('click', function(){
						var text = $(this).text();
						var parent_id = $(this).attr('data-value');
						$(this).parent().siblings('p').text(text);
						$(this).parent().siblings('p').attr('value', parent_id);
						$(this).parent().removeClass('none');
						$('#city_2 ul li').remove();
						$('#city_2 p').text('市');
						$('#city_2 p').attr('value','');
						$('#city_3 p').text('区(县)');
						$('#city_3 p').attr('value','');
						$('#city_3 ul li').remove();		
					});
				}
				else
				{
					layer.msg(data.msg, {icon: 6, time: 1000});
					return false;
				}
			}
		});
		
		var ul = $(this).siblings('ul');
		ul.toggleClass('none')
	})
	//市区
	$('#city_2 p').on('click', function() {
		var parent_id = $("#city_1 p").attr('value');
		if(parent_id)
		{
			$.ajax({
				url : linkUurlA,
				type : 'post',
				data : {'parent_id':parent_id},
				success:function(data){
					var data = JSON.parse(data)
					if(data.code == 1)
					{
						$("#city_ul_2").html("");
						for(var i=0; i<data.datas.length; i++)
						{
							$("#city_ul_2").append($('<li data-value="'+ data.datas[i].area_id+'">'+ data.datas[i].area_name +'</li>'))
						}
						$("#city_ul_2 li").on('click', function(){
							var text = $(this).text();
							var parent_id = $(this).attr('data-value');
							$(this).parent().siblings('p').text(text);
							$(this).parent().siblings('p').attr('value', parent_id);
							$(this).parent().removeClass('none');
							$('#city_3 p').text('区(县)');
							$('#city_3 p').attr('value','');
							$('#city_3 ul li').remove();		
						});
					}
					else
					{
						layer.msg(data.msg, {icon: 6, time: 1000});
						return false;
					}
				}
			});
			
			var ul = $(this).siblings('ul');
			ul.toggleClass('none');
		}
	})
	//县区
	$('#city_3 p').on('click', function() {
		var parent_id = $("#city_2 p").attr('value');
		if(parent_id)
		{
			$.ajax({
				url : linkUurlA,
				type : 'post',
				data : {'parent_id':parent_id},
				success:function(data){
					var data = JSON.parse(data)
					if(data.code == 1)
					{
						$("#city_ul_3").html("")
						for(var i=0; i<data.datas.length; i++)
						{
							$("#city_ul_3").append($('<li data-value="'+ data.datas[i].area_id+'">'+ data.datas[i].area_name +'</li>'))
						}
						$("#city_ul_3 li").on('click', function(){
							var text = $(this).text();
							var parent_id = $(this).attr('data-value');
							$(this).parent().siblings('p').text(text);
							$(this).parent().siblings('p').attr('value', parent_id);
							$(this).parent().removeClass('none');	
						});
					}
					else
					{
						layer.msg(data.msg, {icon: 6, time: 1000});
						return false;
					}
				}
			});		
			var ul = $(this).siblings('ul');
			ul.toggleClass('none');
		}
	})
	
	//是否有折扣
	$('#storesMore').on('click','.discount-type', function() {
		var discount = $(this).val();
		if(discount == 2)
		{
			$("#discount_num").css('display', 'block');
		}
		else
		{
			$("#discount_num").css('display', 'none');
		}
		if($('input:radio[name="discount-type"]:checked')){
			$(this).parents('label').css('color','#171717').siblings('label').css('color','#808080')
		}
		
	})
	
	//酒店星级
	$('#storesMore').on('click','#stores_start p', function() {
		$.ajax({
			url : linkUurlS,
			type : 'post',
			data : {},
			success:function(data){
				var data = JSON.parse(data)

				if(data.code == 1)
				{
					for(var i=0; i<data.data.length; i++)
					{
						$("#stores_start_ul").append($('<li data-value="'+ data.data[i].stores_start_id+'">'+ data.data[i].stores_start_name +'</li>'))
					}
					$("#stores_start_ul li").on('click', function(){
						var text = $(this).text();
						var parent_id = $(this).attr('data-value');
						$(this).parent().siblings('p').text(text);
						$(this).parent().siblings('p').attr('value', parent_id);
						$(this).parent().removeClass('none');
						$('#stores_enter_ul ul li').remove();		
					});
				}
				else
				{
					layer.msg(data.msg, {icon: 6, time: 1000});
					return false;
				}
			}
		});
		
		var ul = $(this).siblings('ul');
		ul.toggleClass('none')
	})
	
	//酒店入住类型
	$('#storesMore').on('click','#stores_brand p', function() {
		$.ajax({
			url : linkUurlB,
			type : 'post',
			data : {},
			success:function(data){
				var data = JSON.parse(data)

				if(data.code == 1)
				{
					for(var i=0; i<data.data.length; i++)
					{
						$("#stores_brand_ul").append($('<li data-value="'+ data.data[i].stores_brand_id+'">'+ data.data[i].stores_brand_name	 +'</li>'))
					}
					$("#stores_brand_ul li").on('click', function(){
						var text = $(this).text();
						var parent_id = $(this).attr('data-value');
						$(this).parent().siblings('p').text(text);
						$(this).parent().siblings('p').attr('value', parent_id);
						$(this).parent().removeClass('none');
						$('#stores_enter_ul ul li').remove();		
					});
				}
				else
				{
					layer.msg(data.msg, {icon: 6, time: 1000});
					return false;
				}
			}
		});
		
		var ul = $(this).siblings('ul');
		ul.toggleClass('none')
	})
	
	//酒店入住类型
	$('#storesMore').on('click','#stores_enter p', function() {
		$.ajax({
			url : linkUurlN,
			type : 'post',
			data : {},
			success:function(data){
				var data = JSON.parse(data)

				if(data.code == 1)
				{
					for(var i=0; i<data.data.length; i++)
					{
						$("#stores_enter_ul").append($('<li data-value="'+ data.data[i].stores_enter_id+'">'+ data.data[i].stores_enter_name +'</li>'))
					}
					$("#stores_enter_ul li").on('click', function(){
						var text = $(this).text();
						var parent_id = $(this).attr('data-value');
						$(this).parent().siblings('p').text(text);
						$(this).parent().siblings('p').attr('value', parent_id);
						$(this).parent().removeClass('none');
						$('#stores_enter_ul ul li').remove();		
					});
				}
				else
				{
					layer.msg(data.msg, {icon: 6, time: 1000});
					return false;
				}
			}
		});
		
		var ul = $(this).siblings('ul');
		ul.toggleClass('none')
	})

	//点击下一步
	$('#sub_data').on('click',function(){

		//店铺名称
		var stores_name = $("#stores_name").val();
		//店铺类型
		var stores_type = $("#stores_type").attr("name");
		//店铺头像
		var stores_img = imgbase64_1;
		//店铺手机好
		var stores_phone = $("#stores_phone").val();
		//店铺实景
		var stores_live = imgarr;
		//省
		var province_id = $('#city_1 p').attr('value');
		//市
		var city_id = $('#city_2 p').attr('value');
		//县区
		var area_id = $('#city_3 p').attr('value');
		//详细地址
		var address = $('#suggestId').val();
		//起订价
		var delivery_price = $("#delivery_price").val();
		//超市
		//配送价
		var stores_freight = $("#stores_freight").val();
		//送达时间
		var delivery_time = $("#delivery_time_p").text();
		//是否猜折扣
		var if_discount = $("input[name='radioName'][checked]").val();
		//折扣
		var discount = $("#discount").val();
		//酒店
		//星级
		var stores_brand = $('#stores_brand p').attr('value');
		//品牌
		var stores_start = $('#stores_start p').attr('value');
		//类型
		var stores_enter = $('#stores_enter p').attr('value');
		
		
		
		
		
		
		
		
		//获取营业时间
		var arr_time = ''
		var list = $('.ul .time-list');
		for(var i = 0 ; i<list.length; i++){
			var start_time = list.eq(i).find('.select_adds_time p').eq(0).text()
			var end_time = list.eq(i).find('.select_adds_time p').eq(1).text()
			arr_time+=(start_time+'-'+end_time)+','
		}
		if(arr_time.indexOf('请选择' ) !== -1)
		{
			arr_time = '24'
		}
		
		$.ajax({
			url : linkUurlE,
			type : 'post',
			data : {'stores_name':stores_name, 'stores_type':stores_type, 'stores_img':stores_img, 'stores_freight':stores_freight, 'stores_morningtime':arr_time, 'stores_phone':stores_phone, 'stores_live':stores_live, 'province_id':province_id, 'city_id':city_id, 'area_id':area_id, 'address':address, 'longitude':longitude, 'latitude':latitude, 'discount':discount, 'stores_freight':stores_freight, 'delivery_price':delivery_price, 'delivery_time':delivery_time, 'stores_brand':stores_brand, 'stores_start':stores_start, 'stores_enter':stores_enter, 'if_discount':if_discount},
			success:function(data){
				var data = JSON.parse(data)
				if(data.code == 1)
				{
					layer.msg(data.msg, {icon: 6, time: 2000});
					// window.location.href = linkUurl;
					window.location.href = linkUurlQ;
				}
				else
				{
					layer.msg(data.msg, {icon: 6, time: 2000});
					return false;
				}
			}
		});
	})
	

	$(document).on('click', function() {
		$('#adds_type .select_adds_conts ul').removeClass('none')
		$('#select_time .select_adds_conts ul').removeClass('none')
		//$('#sheng .select_adds_conts ul').removeClass('none')
	});
})