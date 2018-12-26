jQuery.fn.rater    = function(options) {
        
    // 默认参数
    var settings = {
        enabled    : false,        //是否可控
        url        : '',        //ajax的url
        method    : 'post',    //ajax访问的方式
        min        : 1,        //最小个数
        max        : 5,        //最大个数
//        step    : 1,
        value    : null,        //
        after_click    : null,    //点击事件回调
        before_ajax    : null,    //ajax调用前回调
        after_ajax    : null,    //ajax调用后回调
//      title_format    : null,    //title重定义
        image_0    : 'star_b.jpg',    //空白时图片
        image_1    : 'star_y.jpg',    //选中时图片
        size    : 25        //图片大小
    }; 
    
    var step = 1;
    // 自定义参数
    if(options) {  
        jQuery.extend(settings, options); 
    }
    // 主容器
    var content    = jQuery(this);    
    //公用方法
    var ComFunc = {
            //设置子容器HTML
            setItemHtml : function(select,value,imgSrc){
                if (typeof settings.title_format == 'function') {
                    select.attr('title' , settings.title_format(value));
                }
                else {
                    select.attr('title' , value);
                }
                if (settings.enabled){
                    select.css('cursor','pointer');
                }
                select.html('<img style="width: '+settings.size+'px;height: '+settings.size+'px;" src='+imgSrc+'>');
            },
            //星星样式处理
            changeStar :  function (obj){
                   obj.prevAll().find('img').attr('src',settings.image_1);
                   if(!valueIsInt){
                       obj.find('img').attr('src',settings.image_0);
                        var item    = jQuery('<div class="kbq-star-div"></div>');
                        var relative_width    = -(settings.size+2)*(obj.nextAll('.kbq-star-item').size()+1);
                        var _width = settings.size*(settings.value%step);
                        item.css('left',relative_width);
                        item.css('width',_width);
                        ComFunc.setItemHtml(item,obj.prevAll('.kbq-star-item').size()+1,settings.image_1);
                        content.parent().append(item);
                   }else{
                       obj.find('img').attr('src',settings.image_1);
                   }
                   obj.nextAll().find('img').attr('src',settings.image_0);
                   }
        };
    
    //星星初始化
    for (var value=settings.min ; value<=settings.max ; value+=step) {
        var item    = jQuery('<li class="kbq-star-item"></li>');
        ComFunc.setItemHtml(item,value,settings.image_0);
        content.append(item);
    }
    

    var current_obj = content.find('li').eq(0);
    var valueIsInt = settings.value%step==0;
    //如果是整数颗星星
    if(valueIsInt)
    {
        current_obj = content.find('li').eq(settings.value/step-1);
    }else{
        current_obj = content.find('li').eq(Math.ceil(settings.value/step)-1);
    }
    //如果星星可以操作
    if (settings.enabled) {
        content.find('li').click(function(){
            var current_number    = jQuery(this).prevAll('.kbq-star-item').size()+1;
            settings.value = current_number;
            ComFunc.changeStar($(this));
            var current_number    = jQuery(this).prevAll('.kbq-star-item').size()+1;
            var current_value    = settings.min + (current_number - 1) * step;
            var data    = {
                value    : current_value,
                number    : current_number,
                count    : star_count,
                min        : settings.min,
                max        : settings.max
            }
            
            // 处理回调事件
            if (typeof settings.after_click == 'function') {
                settings.after_click(data);
            }
            
            // 处理ajax调用
            if (settings.url) {
                
                jQuery.ajax({
                    data        : data,
                    type        : settings.method,
                    url            : settings.url,
                    beforeSend    : function() {
                        if (typeof settings.before_ajax == 'function') {
                            settings.before_ajax(data);
                        }
                    },
                    success        : function(ret) {
                        if (typeof settings.after_ajax == 'function') {
                            data.ajax    = ret;
                            settings.after_ajax(data);
                        }
                    }
                });
                
            }
        }).mouseover(function(){
            ComFunc.changeStar($(this));
        }).mouseout(function(){
            current_obj = content.find('li').eq(Math.ceil(settings.value/step)-1);
            ComFunc.changeStar(current_obj);
        });
    }
    
    ComFunc.changeStar(current_obj);//初始化星星数
}