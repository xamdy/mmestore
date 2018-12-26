(function($){
    $.extend({
       createYclassImg : function(fileId,imgId,fn){
            var base64;
            var reader = new FileReader();
            var img = document.getElementById(imgId);
            var file =  document.getElementById(fileId);
            if(file.files && file.files[0]){
                reader.onload = function(evt){
                    img.src = evt.target.result
                    base64 = evt.target.result;
                    fn(base64)
                };
	            reader.readAsDataURL(file.files[0]);
	        }
	     }



    });
})(jQuery);