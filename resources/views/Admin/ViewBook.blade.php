<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$data->Book_Name}}</title>
</head>
<body>
    
        {{-- <div class="container">
            <embed src="/assets_7/{{$data->Book_pdf}}#toolbar=0&navpanes=0&scrollbar=0" type="application/Pdf" width="100%" height="900px">
        </div>  --}}

        {{-- <iframe height="400"  width="400" src="/assets_7/{{$data->Book_pdf}}"></iframe> --}}
        <iframe src="/assets_7/{{$data->Book_pdf}}#toolbar=0&navpanes=0&scrollbar=0"  width="100%" height="900px"></iframe>
</body>
</html>