<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
</head>

<body>
    <h1>Receipt Preview</h1>

    <!-- Display the PDF in an iframe -->
    <iframe src="{{ $pdfPath }}" width="100%" height="600px">
        Your browser does not support PDFs.
        <a href="{{ $pdfPath }}">Download the PDF</a>
    </iframe>

</body>

</html>
