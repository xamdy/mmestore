@extends('admin/public/header')
<style>.expander{margin-left: -20px;}</style>
</head>
<body>
	<div class="wrap js-check-wrap">
		<ul class="nav nav-tabs">
			<li><a href="{{url('admin/rbac/index')}}">角色管理</a></li>
        	<li><a href="{{url('admin/rbac/roleadd')}}">添加角色</a></li>
			<li class="active"><a href="javascript:;">权限设置</a></li>
		</ul>
		<form class="js-ajax-form margin-top-20"  action="{{url('admin/rbac/authspost')}}" method="post">
			<div class="table_full">
				<table class="table table-bordered" id="authrule-tree">
					<tbody>
						{!!$category!!}
					</tbody>
				</table>
			</div>
			<div class="form-actions">
				<input type="hidden" name="roleId" value="{{$roleId}}" />
                <input type="hidden" class="form-control" value="{{ Session::token() }}" name="_token">
				<button class="btn btn-primary js-ajax-submit" type="submit">保存</button>
				<a class="btn btn-default" href="javascript:history.back(-1);">返回</a>
			</div>
		</form>
	</div>
	<script src="{{ asset('/static/static/js/admin.js')}}"></script>
	<script src="{{ asset('/static/static/js/artDialog/artDialog.js?v=')}}"></script>
	<script type="text/javascript" src="{{ asset('/lib/layer/2.4/layer.js') }}"></script>
	<script type="text/javascript">
	$(document).ready(function () {
		Wind.css('/static/static/js/treeTable/treeTable.css');
	    Wind.use('/static/static/js/treeTable/treeTable.js', function () {
	        $("#authrule-tree").treeTable({
	            indent: 20
	        });
	    });
	});

    function checknode(obj) {
        var chk = $("input[type='checkbox']");
        var count = chk.length;

        var num = chk.index(obj);
        var level_top = level_bottom = chk.eq(num).attr('level');
        for (var i = num; i >= 0; i--) {
            var le = chk.eq(i).attr('level');
            if (le <level_top) {
                chk.eq(i).prop("checked", true);
                var level_top = level_top - 1;
            }
        }
        for (var j = num + 1; j < count; j++) {
            var le = chk.eq(j).attr('level');
            if (chk.eq(num).prop("checked")) {

                if (le > level_bottom){
                    chk.eq(j).prop("checked", true);
                }
                else if (le == level_bottom){
                    break;
                }
            } else {
                if (le >level_bottom){
                    chk.eq(j).prop("checked", false);
                }else if(le == level_bottom){
                    break;
                }
            }
        }
    }
	</script>
</body>
</html>