@extends('admin/public/header')
</head>
<body>
<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li class="active"><a href="javascript:;">所有酒店</a></li>
        <li><a href="{{ url('admin/hotel/add') }}">添加酒店</a></li>
    </ul>
    <form class="well form-inline margin-top-20" method="get" action="{{ url('admin/hotel/index') }}">
        {{csrf_field()}}
        酒店名称:
        <select class="form-control" name="id" style="width: 140px;">
            <option value='0'>请选择</option>
            @foreach($hotelList as $k => $v)
            <option value='{{ $v->id }}'>{{ $v->name }}</option>
            @endforeach
        </select> &nbsp;&nbsp;
        前台电话:
        <input type="text" class="form-control js-bootstrap-datetime" name="tel"
               value=""
               style="width: 140px;" autocomplete="off">&nbsp; &nbsp;
        经理电话:
        <input type="text" class="form-control js-bootstrap-datetime" name="jltel"
               value=""
               style="width: 140px;" autocomplete="off">&nbsp; &nbsp;
        排序:
        <select class="form-control" name="orderby" style="width: 140px;">
            <option value='0'>请选择</option>
            <option value='asc'>商品余量升序</option>
            <option value='desc'>商品余量降序</option>
        </select> &nbsp;&nbsp;
        <input type="submit" class="btn btn-primary" value="筛选"/>
        <a class="btn btn-danger" href="{{ url('admin/hotel/index') }}">清空</a>
    </form>
    <form class="js-ajax-form" action="" method="post">

        <table class="table table-hover table-bordered table-list">
            <thead>
            <tr>
                <th width="80">酒店ID</th>
                <th width="100">酒店名称</th>
                <th width="90">房间数量</th>
                <th width="90">商品总量</th>
                <th width="90">商品剩余</th>
                <th width="90">总销量</th>
                <th width="90">前台电话</th>
                <th width="90">所在地址</th>
                <th width="90">操作</th>
            </tr>
            </thead>
            @foreach($hotelList as $k => $v)
                <tr>
                    <td>
                        {{ $v->id }}
                    </td>
                    <td>
                        {{ $v->name }}
                        <br/>
                        <span class="form-required" style="font-size: 12px">{{ $v->name_en }}</span>
                        
                    </td>
                    <td>
                        {{ $v->count }}
                    </td>
                    <td>
                        {{ $v->good_count }}
                    </td>
                    <td>
                        {{ $v->good_ov }}
                    </td>
                    <td>
                        {{ $v->order_count }}
                    </td>
                    <td>
                        {{ $v->front_phone}}
                    </td>
                    <td>
                        {{ $v->address}}
                    </td>
                    <td>
                        <a href="{{ url('admin/hotel/details',[$v->id]) }}">查看</a>
                        <a href="{{ url('admin/hotel/edit',[$v->id]) }}" class="js-ajax-delete">编辑</a>
                    </td>
                </tr>
            @endforeach

        </table>
        <ul class="pagination">{{ $hotelList->links() }}</ul>
    </form>
</div>
</body>
</html>