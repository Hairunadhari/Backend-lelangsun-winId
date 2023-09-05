<!DOCTYPE html>
<html>
<head>
    <title>Barcode</title>
</head>
<body>
    <h1>Barcode</h1>
    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG('4', 'C39+')  }}" alt="barcode">
    <img src="data:image/png;base64,{{DNS1D::getBarcodePNG('Hello World', 'C39')}}" alt="barcode for hello world">

</body>
</html>
