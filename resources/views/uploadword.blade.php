<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<form action="{{route('handle-Conversion')}}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="file" name="word_document">
    <button type="submit">Convert to HTML</button>
</form>
</body>
</html>