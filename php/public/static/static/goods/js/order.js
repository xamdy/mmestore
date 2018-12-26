
/**
 * Created by 邓建军 on 2017-07-07.
 */

$(function(){

    //单选按钮的样式变化
    $(".order-but li input[type=radio],.id-times").click(function(){
        //alert($(this).val());
        $(this).parent('li').addClass('order-checked').siblings('li').removeClass("order-checked");
    });

});

