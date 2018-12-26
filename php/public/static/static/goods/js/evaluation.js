/**
 * Created by 邓建军 on 2017-07-07.
 */

$(function(){
    // select框
    $(".evaluation-right").on("click",".evaluation-list",function(){
        $(this).next(".evaluation-Status").slideToggle().parent().siblings().find(".evaluation-Status").slideUp();
    })
    $(".evaluation-Status").on("click",".evaluation-val",function(){
        $(this).parent().prev(".evaluation-list").find(".evaluation-show").attr("value",$(this).text());
        $(this).parent().slideUp();
    })
});