<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<form action="{{url('home/index/edit')}}" method="post">
		{{ csrf_field() }}
	<input type="text"name="content"value="{{$name}}">
	<input type="hidden" name="id" id="id" value="{{$id}}" />
	<input type="submit" value="修改">
	</form>
</body>
</html>