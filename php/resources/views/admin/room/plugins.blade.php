@extends('admin/public/header')
</head>
<body>
    <div class="wrap js-check-wrap">
        <form action="{{ url('admin/excel/import') }}" method="post"  enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="table-actions">
                <a class="btn btn-success" href="{{ url('admin/excel/export') }}">导出Excel模板</a>
            </div>
            <input type="file" name="cover" required>
            <div class="form-group">
                <div class="col-sm-offset-0">
                    <button type="submit" class="btn btn-primary" >添加</button>
                </div>
            </div>
        </form>
    </div>
    
</body>
</html>