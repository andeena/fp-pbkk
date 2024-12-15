<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generated Barcode</title>
</head>
<body>
    <h1>Generated Barcode</h1>
    @if($barcode)
        <img src="data:image/png;base64, {{ $barcode }}" alt="Barcode">
    @else
        <p>No Barcode Available</p>
    @endif
</body>
</html>
