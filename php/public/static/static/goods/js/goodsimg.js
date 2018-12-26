//上传产品主图
$('#img_id1').on('click', function() {
	$('.update-avatar-dialog').show()
	$('.dialog-content').show();
	$('.mask').show();
})
$('.close-b').on('click', function() {
	$('.dialog-content').hide();
	$('.mask').hide();
})
var imgarr = [];
var imgbase64_1 = '';
var options = {
	thumbBox: '#thumbBox',
	spinner: '#spinner',
	imageBox: '#update-img1',
	imgSrc: ''
}
var cropper = $('#update-img1').cropbox(options);
$('#file1').on('change', function() {
	var reader = new FileReader();
	
	reader.onload = function(e) {
		options.imgSrc = e.target.result;
		imgbase64_1 = e.target.result;

		cropper = $('#update-img1').cropbox(options);
		console.log(cropper)
	}
	reader.readAsDataURL(this.files[0]);
})
$('#imgEnter1').on('click', function() {
	$('.update-avatar-dialog').hide()
	$('#img_id1').attr('src', cropper.getDataURL())
})
//上传产品图片
$('#img_id2').on('click', function() {
	if(imgarr.length*1+imgnum*1 > 4){
		layer.msg('最多上传5张!', {icon: 6, time: 2000});
        return false;
	}else{
		$('.update-storeimg-dialog').show();
		$('.dialog-content').show();
		$('.mask').show();
	}
})

var options2 = {
	thumbBox: '#thumbBox',
	spinner: '#spinner',
	imageBox: '#update-img2',
	imgSrc: ''
}

var cropper2 = $('#update-img2').cropbox(options2);
$('#file2').on('change', function() {
	var reader = new FileReader();
	
	reader.onload = function(e) {
		options2.imgSrc = e.target.result;
		 imgbase64_2 =  e.target.result;

		cropper2 = $('#update-img2').cropbox(options2);
		console.log(cropper2)
	}
	reader.readAsDataURL(this.files[0]);
})



$('#imgEnter2').on('click', function(e) {
	e.stopPropagation();
	$('.update-storeimg-dialog').hide();
	imgbase64_2 = cropper2.getDataURL();
	
	imgarr.push(imgbase64_2);
	var str = $(
		'<div class="store-img pso_img">' +
		'<div class="del_img">×</div>' +
		'<img style="width:100%;height:100%;" src="' + imgbase64_2 + '" alt="" />' +
		'</div>'
	)
	$('#img-group').append(str);
})

// $('#img-group').on('click',".del_img",function(e){
// 	e.stopPropagation();
// 	var ss=$(this).parent(".pso_img").index();
// 	imgarr.splice(ss,1);
// 	$(this).parent(".pso_img").remove();
	
// });	
		$('#img-group').on('click',".del_img",function(e){
			var thiss=$(this);
				layer.confirm('你确定要删除该图片吗？',function(){	
					e.stopPropagation();
					var ss=thiss.parent(".pso_img").index();
					imgarr.splice(ss,1);
					var id = thiss.attr('item');
                    var urls=thiss.parent(".pso_img").find('img').attr('src');
                    var goods_id = $("input[name='goods_id']").val();
					if(urls){
						$.ajax({
							type: 'POST',
							url: img_url,
							dataType: 'json',
							data: { "goods_id":goods_id,"url":urls,_token:_token},
							success: function (json) {
								console.log(111,json);
								if(json.code == 1){
									imgnum = imgnum-1;
									layer.msg('删除成功!', {icon: 6, time: 2000});
									thiss.parent(".pso_img").remove();
                                    window.location.reload();
								}else{
                                    imgnum = imgnum-1;
                                    thiss.parent(".pso_img").remove();
									layer.msg(json.msg, {icon: 2, time: 2000});
                                    window.location.reload();
									// setTimeout("location.reload()", 1000);
								}
							},
							error: function (json) {
								console.log(json);
							},
						});
					}else{
						imgnum = imgnum-1;
						thiss.parent(".pso_img").remove();
					}
					});
				});


	

