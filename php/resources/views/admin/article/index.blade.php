@extends('admin/public/header')
</head>
<body>
<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li class="active"><a href="javascript:;">所有文章</a></li>
        <li><a href="{{url('admin/article/create')}}">添加文章</a></li>
    </ul>
    <form class="well form-inline margin-top-20" method="post" action="{:url('AdminArticle/index')}">
        分类:
        <select class="form-control" name="category" style="width: 140px;">
            <option value='0'>全部</option>
            {$category_tree|default=''}
        </select> &nbsp;&nbsp;
        时间:
        <input type="text" class="form-control js-bootstrap-datetime" name="start_time"
               value="{$start_time|default=''}"
               style="width: 140px;" autocomplete="off">-
        <input type="text" class="form-control js-bootstrap-datetime" name="end_time"
               value="{$end_time|default=''}"
               style="width: 140px;" autocomplete="off"> &nbsp; &nbsp;
        关键字:
        <input type="text" class="form-control" name="keyword" style="width: 200px;"
               value="{$keyword|default=''}" placeholder="请输入关键字...">
        <input type="submit" class="btn btn-primary" value="搜索"/>
        <a class="btn btn-danger" href="{:url('AdminArticle/index')}">清空</a>
    </form>
    <form class="js-ajax-form" action="" method="post">
        <div class="table-actions">
            <notempty name="category">
                <button class="btn btn-primary btn-sm js-ajax-submit" type="submit"
                        data-action="{:url('AdminArticle/listOrder')}">{:lang('SORT')}
                </button>
            </notempty>
            <button class="btn btn-primary btn-sm js-ajax-submit" type="submit"
                    data-action="{:url('AdminArticle/publish',array('yes'=>1))}" data-subcheck="true">发布
            </button>
            <button class="btn btn-primary btn-sm js-ajax-submit" type="submit"
                    data-action="{:url('AdminArticle/publish',array('no'=>1))}" data-subcheck="true">取消发布
            </button>
            <button class="btn btn-primary btn-sm js-ajax-submit" type="submit"
                    data-action="{:url('AdminArticle/top',array('yes'=>1))}" data-subcheck="true">置顶
            </button>
            <button class="btn btn-primary btn-sm js-ajax-submit" type="submit"
                    data-action="{:url('AdminArticle/top',array('no'=>1))}" data-subcheck="true">取消置顶
            </button>
            <button class="btn btn-primary btn-sm js-ajax-submit" type="submit"
                    data-action="{:url('AdminArticle/recommend',array('yes'=>1))}" data-subcheck="true">推荐
            </button>
            <button class="btn btn-primary btn-sm js-ajax-submit" type="submit"
                    data-action="{:url('AdminArticle/recommend',array('no'=>1))}" data-subcheck="true">取消推荐
            </button>
            <!--
            <notempty name="category">
                <button class="btn btn-primary btn-sm js-articles-move" type="button">批量移动</button>
            </notempty>
            <button class="btn btn-primary btn-sm js-articles-copy" type="button">批量复制</button>
            -->
            <button class="btn btn-danger btn-sm js-ajax-submit" type="submit"
                    data-action="{:url('AdminArticle/delete')}" data-subcheck="true" data-msg="您确定删除吗？">
                {:lang('DELETE')}
            </button>
        </div>
        <table class="table table-hover table-bordered table-list">
            <thead>
            <tr>
                <th width="15">
                    <label>
                        <input type="checkbox" class="js-check-all" data-direction="x" data-checklist="js-check-x">
                    </label>
                </th>
                <notempty name="category">
                    <th width="50">{:lang('SORT')}</th>
                </notempty>
                <th width="50">ID</th>
                <th>标题</th>
                <th>分类</th>
                <th width="50">作者</th>
                <th width="65">点击量</th>
                <th width="65">评论量</th>
                <th width="160">关键字/来源<br>摘要/缩略图</th>
                <th width="130">更新时间</th>
                <th width="130">发布时间</th>
                <th width="70">状态</th>
                <th width="90">操作</th>
            </tr>
            </thead>
            <foreach name="articles" item="vo">
                <tr>
                    <td>
                        <input type="checkbox" class="js-check" data-yid="js-check-y" data-xid="js-check-x" name="ids[]"
                               value="{$vo.id}" title="ID:{$vo.id}">
                    </td>
                    <notempty name="category">
                        <td>
                            <input name="list_orders[{$vo.post_category_id}]" class="input-order" type="text"
                                   value="{$vo.list_order}">
                        </td>
                    </notempty>
                    <td><b>{$vo.id}</b></td>
                    <td>
                        <notempty name="category">
                            <a href="{:cmf_url('portal/Article/index',array('id'=>$vo['id'],'cid'=>$vo['category_id']))}"
                               target="_blank">{$vo.post_title}</a>
                            <else/>
                            <a href="{:cmf_url('portal/Article/index',array('id'=>$vo['id']))}"
                               target="_blank">{$vo.post_title}</a>
                        </notempty>
                    </td>
                    <td>
                        <foreach name="vo.categories" item="voo">
                            <span class="label label-default">
                                <a href="{:cmf_url('portal/List/index',array('id'=>$voo['id']))}"
                                   style="color: #fff;"
                                   target="_blank"
                                >{$voo.name}</a>
                            </span>&nbsp;
                        </foreach>
                    </td>
                    <td>{$vo.user_nickname}</td>
                    <td>{$vo.post_hits|default=0}</td>
                    <td>
                        <notempty name="vo.comment_count">
                            {$vo.comment_count|default='0'}
                            <!--<a href="javascript:parent.openIframeDialog('{:url('comment/commentadmin/index',array('post_id'=>$vo['id']))}','评论列表')">{$vo.comment_count}</a>-->
                            <else/>
                            {$vo.comment_count|default='0'}
                        </notempty>
                    </td>
                    <td>
                        <notempty name="vo.post_keywords">
                            <i class="fa fa-check fa-fw"></i>
                            <else/>
                            <i class="fa fa-close fa-fw"></i>
                        </notempty>
                        <notempty name="vo.post_source">
                            <i class="fa fa-check fa-fw"></i>
                            <else/>
                            <i class="fa fa-close fa-fw"></i>
                        </notempty>
                        <notempty name="vo.post_excerpt">
                            <i class="fa fa-check fa-fw"></i>
                            <else/>
                            <i class="fa fa-close fa-fw"></i>
                        </notempty>

                        <notempty name="vo.more.thumbnail">
                            <a href="javascript:parent.imagePreviewDialog('{:cmf_get_image_preview_url($vo.more.thumbnail)}');">
                                <i class="fa fa-photo fa-fw"></i>
                            </a>
                        </notempty>
                    </td>
                    <td>
                        <notempty name="vo.update_time">
                            {:date('Y-m-d H:i',$vo['update_time'])}
                        </notempty>

                    </td>
                    <td>
                        <empty name="vo.published_time">
                            未发布
                            <else/>
                            {:date('Y-m-d H:i',$vo['published_time'])}
                        </empty>

                    </td>
                    <td>
                        <notempty name="vo.post_status">
                            <a data-toggle="tooltip" title="已发布"><i class="fa fa-check"></i></a>
                            <else/>
                            <a data-toggle="tooltip" title="未发布"><i class="fa fa-close"></i></a>
                        </notempty>
                        <notempty name="vo.is_top">
                            <a data-toggle="tooltip" title="已置顶"><i class="fa fa-arrow-up"></i></a>
                            <else/>
                            <a data-toggle="tooltip" title="未置顶"><i class="fa fa-arrow-down"></i></a>
                        </notempty>
                        <notempty name="vo.recommended">
                            <a data-toggle="tooltip" title="已推荐"><i class="fa fa-thumbs-up"></i></a>
                            <else/>
                            <a data-toggle="tooltip" title="未推荐"><i class="fa fa-thumbs-down"></i></a>
                        </notempty>
                    </td>
                    <td>
                        <a href="{:url('AdminArticle/edit',array('id'=>$vo['id']))}">{:lang('EDIT')}</a>
                        <a href="{:url('AdminArticle/delete',array('id'=>$vo['id']))}" class="js-ajax-delete">{:lang('DELETE')}</a>
                    </td>
                </tr>
            </foreach>
            <tfoot>
            <tr>
                <th width="15"><label><input type="checkbox" class="js-check-all" data-direction="x"
                                             data-checklist="js-check-x"></label></th>
                <notempty name="category">
                    <th width="50">{:lang('SORT')}</th>
                </notempty>
                <th width="50">ID</th>
                <th>标题</th>
                <th>分类</th>
                <th width="50">作者</th>
                <th width="65">点击量</th>
                <th width="65">评论量</th>
                <th width="160">关键字/来源<br>摘要/缩略图</th>
                <th width="130">更新时间</th>
                <th width="130">发布时间</th>
                <th width="70">状态</th>
                <th width="90">操作</th>
            </tr>
            </tfoot>
        </table>
        <div class="table-actions">
            <notempty name="category">
                <button class="btn btn-primary btn-sm js-ajax-submit" type="submit"
                        data-action="{:url('AdminArticle/listOrder')}">{:lang('SORT')}
                </button>
            </notempty>
            <button class="btn btn-primary btn-sm js-ajax-submit" type="submit"
                    data-action="{:url('AdminArticle/publish',array('yes'=>1))}" data-subcheck="true">发布
            </button>
            <button class="btn btn-primary btn-sm js-ajax-submit" type="submit"
                    data-action="{:url('AdminArticle/publish',array('no'=>1))}" data-subcheck="true">取消发布
            </button>
            <button class="btn btn-primary btn-sm js-ajax-submit" type="submit"
                    data-action="{:url('AdminArticle/top',array('yes'=>1))}" data-subcheck="true">置顶
            </button>
            <button class="btn btn-primary btn-sm js-ajax-submit" type="submit"
                    data-action="{:url('AdminArticle/top',array('no'=>1))}" data-subcheck="true">取消置顶
            </button>
            <button class="btn btn-primary btn-sm js-ajax-submit" type="submit"
                    data-action="{:url('AdminArticle/recommend',array('yes'=>1))}" data-subcheck="true">推荐
            </button>
            <button class="btn btn-primary btn-sm js-ajax-submit" type="submit"
                    data-action="{:url('AdminArticle/recommend',array('no'=>1))}" data-subcheck="true">取消推荐
            </button>
            <!--
            <notempty name="category">
                <button class="btn btn-primary btn-sm js-articles-move" type="button">批量移动</button>
            </notempty>
            <button class="btn btn-primary btn-sm js-articles-copy" type="button">批量复制</button>
            -->
            <button class="btn btn-danger btn-sm js-ajax-submit" type="submit"
                    data-action="{:url('AdminArticle/delete')}" data-subcheck="true" data-msg="您确定删除吗？">
                {:lang('DELETE')}
            </button>
        </div>
        <ul class="pagination">{$page|default=''}</ul>
    </form>
</div>
<script src="{{ asset('/static/static/js/admin.js')}}"></script>
<script>

    function reloadPage(win) {
        win.location.reload();
    }

    $(function () {
        setCookie("refersh_time", 0);
        Wind.use('ajaxForm', 'artDialog', 'iframeTools', function () {
            //批量复制
            $('.js-articles-copy').click(function (e) {
                var ids = [];
                $("input[name='ids[]']").each(function () {
                    if ($(this).is(':checked')) {
                        ids.push($(this).val());
                    }
                });

                if (ids.length == 0) {
                    art.dialog.through({
                        id: 'error',
                        icon: 'error',
                        content: '您没有勾选信息，无法进行操作！',
                        cancelVal: '关闭',
                        cancel: true
                    });
                    return false;
                }

                ids = ids.join(',');
                art.dialog.open("__ROOT__/index.php?g=portal&m=AdminArticle&a=copy&ids=" + ids, {
                    title: "批量复制",
                    width: "300px"
                });
            });
            //批量移动
            $('.js-articles-move').click(function (e) {
                var ids = [];
                $("input[name='ids[]']").each(function () {
                    if ($(this).is(':checked')) {
                        ids.push($(this).val());
                    }
                });

                if (ids.length == 0) {
                    art.dialog.through({
                        id: 'error',
                        icon: 'error',
                        content: '您没有勾选信息，无法进行操作！',
                        cancelVal: '关闭',
                        cancel: true
                    });
                    return false;
                }

                ids = ids.join(',');
                art.dialog.open("__ROOT__/index.php?g=portal&m=AdminArticle&a=move&old_term_id={$term.term_id|default=0}&ids=" + ids, {
                    title: "批量移动",
                    width: "300px"
                });
            });
        });
    });
</script>
</body>
</html>