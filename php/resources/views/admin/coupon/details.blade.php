@extends('admin/public/header')
</head>
<body>
<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li><a href="{{ url('admin/coupon/list') }}">优惠券列表</a></li>
        <li class="active"><a href="javascript:;">优惠券详情</a></li>
    </ul>
    <form method="post" class="form-horizontal js-ajax-form margin-top-20" id="form" action="#">

        <div class="form-group">
            <label for="input-user_login" class="col-sm-2 control-label">优惠卷名称</label>
            <div class="col-md-2 col-sm-10">
                {{$list->coupon_name}}
            </div>
        </div>

        <div class="form-group">
            <label  class="col-sm-2 control-label">优惠卷金额</label>
            <div class="col-md-2 col-sm-10">
                {{$list->coupon_money}}
            </div>
        </div>

        <div class="form-group">
            <label  class="col-sm-2 control-label">优惠卷描述</label>
            <div class="col-md-2 col-sm-10">
                {{$list->coupon_desc}}
            </div>
        </div>

        <div class="form-group">
            <label  class="col-sm-2 control-label">优惠卷类型</label>
            <div class="col-md-2 col-sm-10">
                @if ($list->coupon_type==1)
                    满减
                @else
                    打折
                @endif

            </div>
        </div>

        <div class="form-group">
            <label  class="col-sm-2 control-label">发放数量</label>
            <div class="col-md-2 col-sm-10">
                {{$list->send_num}}
            </div>
        </div>

        <div class="form-group">
            <label  class="col-sm-2 control-label">领取数量</label>
            <div class="col-md-2 col-sm-10">
                @if ($list->receive_num=='')
                    0
                @else
                    {{$list->receive_num}}
                @endif
            </div>
        </div>

        <div class="form-group">
            <label  class="col-sm-2 control-label">活动开始时间</label>
            <div class="col-md-2 col-sm-10">
                {{date("Y-m_d H:i",$list->vaild_start_time)}}
            </div>


        </div> <div class="form-group">
            <label  class="col-sm-2 control-label">活动结束时间</label>
            <div class="col-md-2 col-sm-10">
                {{date("Y-m_d H:i",$list->vaild_end_time)}}
            </div>
        </div>
        @if($list->coupon_type==2)
            <div class="form-group">
                <label  class="col-sm-2 control-label">商品信息</label>
                <div class="col-md-6 col-sm-10">
                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th>商品名称</th>
                        </tr>
                        </thead>
                        <tbody id="add">
                        @foreach($goods as $key=>$vo)
                            <tr>
                                <td>{{$vo->goods_name}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <div class="form-group">
            <label  class="col-sm-2 control-label">创建时间</label>
            <div class="col-md-2 col-sm-10">
                {{date("Y-m_d H:i",$list->create_time)}}
            </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <a class="btn btn-default" href="{{url('admin/coupon/list')}}" >返回</a>
            </div>
        </div>
        </div>
    </form>
</div>
</body>
<script src="{{ asset('/js/jquery-1.8.3.min.js')}}"></script>
</html>