@extends('business/public/header')
<style>
    .home-info li em {
        float: left;
        width: 120px;
        font-style: normal;
        font-weight: bold;
    }
 
    .home-info ul {
        padding: 0;
        margin: 0;
    }
 
    .panel {
        margin-bottom: 0;
    }
 
    .grid-sizer {
        width: 10%;
    }
 
    .grid-item {
        margin-bottom: 5px;
        padding: 5px;
    }
 
    .col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12 {
        padding-left: 5px;
        padding-right: 5px;
        float: none;
    }
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


</switch>
</head>
</html>

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

    // 订单量选择天、周、月
    $('#remove').change(function(){
        var type = $("select[name='type']").val();
        $.ajax({
            type: 'post',
            url: "{{url('business/index/orderCharts')}}",
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
            url: "{{url('business/index/orderCharts')}}",
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
            url: "{{url('business/index/priceCharts')}}",
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
            url: "{{url('business/index/priceCharts')}}",
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


</script>
