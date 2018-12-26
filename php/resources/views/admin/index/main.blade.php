@extends('admin/public/header')

<style>
    ul,li{
        list-style: none;
    }
    .home-info-ul{
        width: 100%;
        overflow: hidden;
        height: 47px;
        background: #929292;
        line-height: 47px;
    }
    .home-info-ul li{
        float: left;
        width: 30%;
        text-align: center;
        font-weight: bold;
        color: #101010;
        font-size: 16px;
    }
    .home-info-ulchild{
        height: 75px;
        line-height: 75px;
        border: 1px solid rgba(187, 187, 187, 1);
    }
    .home-info-ulchild li{
        float: left;
        width: 30%;
        text-align: center;
    }
    .single_wrap {
        width: 800px;
        height: 360px;
        float: left;
        color: #666666;
        font-size: 20px;
    }
    .one {
        width: 100%;
        text-align: center;
    }
    #remove,#rankPrice {
        float: right;
    }
</style>


<!-- 1.添加meta csrf_token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<switch name="name">


    <case value="CmfHub">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">当前位置：首页</h3>
            </div>

            <div class="panel-body home-info">
                <ul class="home-info-ul">
                    <li>今日销售金额（元）</li>
                    <li>本周销售金额（元）</li>
                    <li>本月销售金额（元）</li>
                </ul>

                <ul class="home-info-ulchild">
                    <li>{{ isset($todayOrder[0]['price']) ? $todayOrder[0]['price'] : '0' }}</li>
                    <li>{{ isset($weekOrder[0]['price']) ? $weekOrder[0]['price'] : '0'  }}</li>
                    <li>{{ isset($monthOrder[0]['price']) ? $monthOrder[0]['price'] : '0'  }}</li>
                </ul>
            </div>

            <div class="panel-body home-info">
                <ul class="home-info-ul">
                    <li>今日有效订单（笔）</li>
                    <li>本周有效订单（笔）</li>
                    <li>本月有效订单（笔）</li>
                </ul>

                <ul class="home-info-ulchild">
                    <li>{{ $todayOrder[0]['count']  }}</li>
                    <li>{{ $weekOrder[0]['count']  }}</li>
                    <li>{{ $monthOrder[0]['count']  }}</li>
                </ul>
            </div>

        </div>
    </case>


    <div class="single_wrap">
        <div class="one">
            <select name="type" id="remove">
                <option value="1">日</option>
                <option value="2">周</option>
                <option value="3">月</option>
            </select>
            <p>订单量：（单位笔）</p>
        </div>
        <div id="order" style="position:relative; height:300px; width: 800px;display:inline-block;"></div>
    </div>

    <div class="single_wrap">
        <div class="one">
            <select name="types" id="rankPrice">
                <option value="1">日</option>
                <option value="2">周</option>
                <option value="3">月</option>
            </select>
            <p>销售流水：（单位笔）</p>
        </div>
        <div id="price" style="height:300px; width: 800px;display:inline-block"></div>
    </div>

    <div id="hotelOrder" style="height:300px; width: 800px;display:inline-block"></div>
    <div id="hotelPrice" style="height:300px; width: 800px;display:inline-block"></div>


    {{--<case value="MainContributors">--}}
        {{--<div class="panel panel-default">--}}
            {{--<div class="panel-heading">--}}
                {{--<h3 class="panel-title">主要功能</h3>--}}
            {{--</div>--}}
            {{--<div class="panel-body home-info">--}}
                {{--<ul class="list-inline">--}}
                    {{--<li>权限管理</li>--}}
                    {{--<li>积分商场</li>--}}
                    {{--<li>营销管理</li>--}}
                {{--</ul>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</case>--}}


</switch>


<script src="{{ asset('/js/echarts/echarts.min.js')}}"></script>
<script src="{{ asset('/js/jquery-1.8.3.min.js')}}"></script>

<script>

    //     2.设置全局ajax选项
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var _token = $('meta[name="csrf-token"]').attr('content');

    // 本月酒店订单量排行 请求ajax
    $(function() {
        $.ajax({
            type: 'post',
            url: "{{url('admin/index/hotelOrderRank')}}",
            data: {hotelOrderRank: 1, _token: _token,hotelPriceRank:1},
            dataType: 'json',
            success: function (data) {
//                content.log(data);
//                return false;
//                char(data.time,data.count)
                zhuOrder(data.name, data.count);
            }
        })
    })

    // 本月酒店销售金额排行 请求ajax
    $(function() {
        $.ajax({
            type: 'post',
            url: "{{url('admin/index/hotelPriceRank')}}",
            data: {_token: _token,hotelPriceRank:1},
            dataType: 'json',
            success: function (data) {
                zhuPrice(data.name, data.price);
            }
        })
    })


    // 订单量选择天、周、月
    $('#remove').change(function(){
        var type = $("select[name='type']").val();
        $.ajax({
            type: 'post',
            url: "{{url('admin/index/orderRank')}}",
            data: {_token: _token,type:type},
            dataType: 'json',
            success: function (data) {
                zheOrder(data.name, data.value);
            }
        })
    })


    // 订单量 请求ajax
    $(function() {
        var type = $("select[name='type']").val();
        $.ajax({
            type: 'post',
            url: "{{url('admin/index/orderRank')}}",
            data: {_token: _token,type:type},
            dataType: 'json',
            success: function (data) {
                zheOrder(data.name, data.value);
            }
        })
    })

    // 订单量 折线图
    function zheOrder(name,value) {
        var dom = document.getElementById("order");
        var myChart = echarts.init(dom);
        var app = {};
        option = null;
        option = {
            xAxis: {
                type: 'category',
                data: name
            },
            tooltip: {},
            yAxis: {
                type: 'value'
            },
            series: [{
                data: value,
                name: '订单量',
//                type: 'bar'
                type: 'line'
            }]
        };

        myChart.setOption(option, true);
    }


    // 销售金额选择天、周、月
    $('#rankPrice').change(function(){
        var type = $("select[name='types']").val();
        $.ajax({
            type: 'post',
            url: "{{url('admin/index/priceRank')}}",
            data: {_token: _token,type:type},
            dataType: 'json',
            success: function (data) {
                zhePrice(data.name, data.value);
            }
        })
    })


    // 销售金额 请求ajax
    $(function() {
        var type = $("select[name='types']").val();
        $.ajax({
            type: 'post',
            url: "{{url('admin/index/priceRank')}}",
            data: {_token: _token,type:type},
            dataType: 'json',
            success: function (data) {
                zhePrice(data.name, data.value);
            }
        })
    })

    // 销售金额 折线图
    function zhePrice(name,value) {
        var dom = document.getElementById("price");
        var myChart = echarts.init(dom);
        var app = {};
        option = null;
        option = {
            xAxis: {
                type: 'category',
                data: name
            },
            tooltip: {},
            yAxis: {
                type: 'value'
            },
            series: [{
                data: value,
                name: '订单量',
    //                type: 'bar'
                type: 'line'
            }]
        };

        myChart.setOption(option, true);
    }

    // 订单量图形方法
    function zhuOrder(name,value){
        var myChart = echarts.init(document.getElementById('hotelOrder'));
        // 绘制图表
        myChart.setOption({
            title: {
                text: '本月酒店订单量排行（单位：笔）'
            },
            tooltip: {},

            xAxis: {
                data:name
            },
            yAxis: {},
            series: [{
                name: '订单量',
                type: 'bar',
                data: value
            }]
        });
    }

    // 销售金额排行图形方法
    function zhuPrice(name,value){
        var myChart = echarts.init(document.getElementById('hotelPrice'));
        // 绘制图表
        myChart.setOption({
            title: {
                text: '本月酒店销售金额排行（单位：元）'
            },
            tooltip: {},

            xAxis: {
                data:name
            },
            yAxis: {},
            series: [{
                name: '订单金额',
                type: 'bar',
                data: value
            }]
        });
    }


</script>