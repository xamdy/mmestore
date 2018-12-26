@extends('admin/public/header')
</head>
<body>
	<div class="wrap js-check-wrap">
		<ul class="nav nav-tabs">
			<li class="active"><a href="{{url('admin/classify/index')}}">分类管理</a></li>

            @if($count == 5)
                <li><a href="javascript:return false;" style="opacity: 0.2">添加分类</a></li>
            @else
                <li><a href="{{url('admin/classify/add')}}">添加分类</a></li>
            @endif

		</ul>
        <form class="well form-inline margin-top-20" method="get" action="{{url('admin/device/index')}}">
          
        </form>
		<table class="table table-hover table-bordered">
			<thead>
				<tr style="content: '50';">
					<th width="50">ID</th>
                    <th>图片</th>
					<th>分类名称</th>
					<th>商品数量</th>
					<th>优先级</th>
					{{--<th>创建时间</th>--}}
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				@foreach($res as $vo)
				<tr>
					<td>{{$vo->id}}</td>
                    <td> <img width="88px" height="88px" src="{{ URL::asset($vo->img) }}"> </td>
					<td>
{{--                        <a href='{{url("admin/classify/edit",array("id"=>$vo->id))}}'>{{$vo->name}}</a>--}}
                        <p>{{ $vo->name }}</p>
                        <p>{{ $vo->English_name }}</p>
                    </td>
				
					<td>
                        <a href="{{url("admin/classify/classifyGoods",array("id"=>$vo->id))}}">{{$vo->count}}</a>
                    </td>

					<td>{{$vo->sorts}}</td>

					{{--<td>@if($vo->add_time==0) --}}
						{{--@else--}}
							{{--{{date('Y-m-d H:i:s',$vo->add_time)}}--}}
						{{--@endif--}}
                    {{--</td>--}}

					<td>
                        {{--<a href="javascript:;" onclick="del({{$vo->id}})" item="{{'admin/classify/deletes/',array('id'=>$vo->id)}}" >删除</a>&nbsp;&nbsp;--}}
                        <a href="{{url("admin/classify/edit",array("id"=>$vo->id))}}" >编辑</a>
                    </td>
				</tr>
				@endforeach
			</tbody>
		</table>
		<div class="pagination">{{$res->links()}}</div>
		
	</div>
	<script src="{{ asset('/static/themes/admin_simpleboot3/public/assets/js/jquery-1.10.2.min.js')}}"></script>
	<script src="{{ asset('/static/static/js/admin.js')}}"></script>
	<script type="text/javascript" src="{{ asset('/lib/layer/2.4/layer.js') }}"></script>

	<script type="text/javascript">
	
		function del(url){
			var urls = "{{'/admin/classify/deletes/'}}"+url;
			layer.confirm('你确定要删除该分类吗？',function(){
				window.location.href=urls;
			})
		}
    </script>
</body>
</html>