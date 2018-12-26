@extends('admin/public/header')
<link href="{{ asset('/static/static/js/treeTable/treeTable.css?v=') }}" rel="stylesheet" type="text/css">

<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li class="active"><a href="{{url('admin/menu/index')}}">后台菜单</a></li>
        <li><a href="{{url('admin/menu/add/0/1')}}">添加菜单</a></li>
        <li><a href="{{url('admin/menu/lists')}}">所有菜单</a></li>
    </ul>
    <div class="alert alert-warning" style="margin: 0 0 5px 0;">
                 请在开发人员指导下进行以上操作！
            </div>
    <!-- <form class="js-ajax-form" action="{:url('menu/listOrder')}" method="post"> -->
        <div class="table-actions">
            <button class="btn btn-primary btn-sm js-ajax-submit" type="submit">排序</button>
        </div>
        <table class="table table-hover table-bordered table-list" id="menus-table">
            <thead>
            <tr>
                <th width="80">排序</th>
                <th width="50">ID</th>
                <th>菜单名称</th>
                <th>操作</th>
                <th width="80">状态</th>
                <th width="180">操作</th>
            </tr>
            </thead>
            <tbody>
            {!! $category !!}
            </tbody>
            <tfoot>
            <tr>
               <th width="80">排序</th>
                <th width="50">ID</th>
                <th>菜单名称</th>
                <th>操作</th>
                <th width="80">状态</th>
                <th width="180">操作</th>
            </tr>
            </tfoot>
        </table>
        <div class="table-actions">
            <button class="btn btn-primary btn-sm js-ajax-submit" type="submit">排序</button>
        </div>
    <!-- </form> -->
</div>
<script src="{{ asset('/static/static/js/admin.js')}}"></script>
<script src="{{ asset('/static/static/js/artDialog/artDialog.js?v=')}}"></script>
<script type="text/javascript" src="{{ asset('/lib/layer/2.4/layer.js') }}"></script>
<script>
    $(document).ready(function() {
        Wind.css('/static/static/js/treeTable/treeTable.css');
        Wind.use('/static/static/js/treeTable/treeTable.js', function() {
            $("#menus-table").treeTable({
                indent : 20
            });
        });

        //删除
        $('.js-ajax-delete').click(function(){
                var url = $(this).attr('href');
               if(confirm('你确定要删除吗？')){
                    // alert();return false;
                            window.location=url;
                }
                return false;
        })

    });



</script>
</body>
</html>