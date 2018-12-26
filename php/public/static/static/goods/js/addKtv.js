$(function(){
	$(".houseList").on("click",".house-list",function(){
		if($(this).index()!=0){
			$(this).addClass("house-listOn").siblings().eq(0).removeClass("house-listOn");
		}else{
			$(this).addClass("house-listOn").siblings().removeClass("house-listOn");
		}
	})
	$(".addKtv-price").on("click",".jion",function(){
		$(this).find(".radio-i").addClass("radio-on").parent().siblings().find(".radio-i").removeClass("radio-on");
	})
	// 添加商品
	var nub=0;		
		  nub=parseInt($(".nub").text());

	$(".addadd").on("click",function(){
			var goodsLens=$(".mytable").find("tr").length;
			var sheziWei = $(".mytable").find("tr").eq(goodsLens-1).find('td:eq(0) input').val();
			var sheziWeifen = $(".mytable").find("tr").eq(goodsLens-1).find('td:eq(1) input').val();
			var sheziWeijia = $(".mytable").find("tr").eq(goodsLens-1).find('td:eq(2) input').val();
			var sheziWeijias = $(".mytable").find("tr").eq(goodsLens-1).find('td:eq(3) input').val();
			var sheziWeijiaa = $(".mytable").find("tr").eq(goodsLens-1).find('td:eq(4) input').val();
	
		if(!sheziWei){
			layer.msg('套餐内容类型不能为空！',{icon:2,time:2000});return false;
		}
		if(!sheziWeifen){
			layer.msg('套餐内容人数不能为空！',{icon:2,time:2000});return false;
		}
		if(!sheziWeijia){
			layer.msg('套餐内容时长不能为空！',{icon:2,time:2000});return false;
		}
		if(!sheziWeijias){
			layer.msg('套餐内容价格不能为空！',{icon:2,time:2000});return false;
		}
		if(!sheziWeijiaa){
			layer.msg('套餐内容服务不能为空！',{icon:2,time:2000});return false;
		}
		var goodsLen=$(".mytable").find("tr").length;
		if(goodsLen>=11){
			$(".error").text('最多只能添加10个！');
			$(".addfoods-dialog").show(500);
		}else{
			  	var str ='<tr>'+
			    					'<td><input type="text" value="" placeholder="如：豪华包间"/></td>'+
			    					'<td><input type="text" value="" placeholder="如：2~7人"/></td>'+
			    					'<td><input type="text" value="" placeholder="如：3小时填 3"/></td>'+
			    					'<td><input type="text" value="" placeholder="如：120"/></td>'+
			    					'<td><input type="text" value="" placeholder="如：包含酒水果盘"/></td>'+
			    				'</tr>';
			 	 $(".mytable").append(str);
			 	 nub--;
				 $(".nub").text(nub);
			  }
	})
	// 删除商品
	$(".remove").on("click",function(){
		var goodsLen=$(".mytable").find("tr").length;
		if(goodsLen>=3){
			nub++;
			$(".nub").text(nub);
			$(".mytable").find("tr").eq(goodsLen-1).remove();
		}
	})
	$('.submit-check-b').on('click',function(){
		$(".addfoods-dialog").hide(300);
	});
	//退款类型
	$('.house-list').click(function(){
		if($(this).text() == '不支持退款') {
			$('input.houer').attr('disabled',true);
		} else {
			$('input.houer').removeAttr('disabled');
		}
	});
	//  时间插件
	$(".all-input").on("click",function(){
		laydate();
	})
	$(".data-jion").on("click",function(){
		$(".data-mart9").show();
	})
	$(".data-all").on("click",function(){
		$(".data-mart9").hide();
	})
	//check
	$('.label-checkbox').on('click', function() {
		if($(this).children('input[type="checkbox"]').prop('checked') == true){
			$(this).find('.checkbox-i').addClass('checkbox-on');
		} else {
			$(this).find('.checkbox-i').removeClass('checkbox-on');
		}			
	});
	// zhekou
	$('.join-zhe').on("click",function(){
		$(".zhe").show();
	})
	$('.no-join').on("click",function(){
		$(".zhe").hide();
	})
	// 套餐
	$(".set-meal-nh").on("click",function(){
		$(".mytable").hide()
		$(".add").hide()
	})
	$(".set-meal-h").on("click",function(){
		$(".mytable").show()
		$(".add").show()
	})
	//
	$(".jion-ownt").on("click",function(){
		$(".jion-time").show();
	})
	$(".jion-allt").on("click",function(){
		$(".jion-time").hide();
	})
})