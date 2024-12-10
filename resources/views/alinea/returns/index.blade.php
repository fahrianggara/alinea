@extends('alinea.index')

@section('content')
    <div class="container">
        <div class="col-lg-6 mx-auto">
            <div id="qr-reader" style="width:100%"></div>
            <div id="qr-reader-results"></div>
        </div>
    </div>
    <script src="https://unpkg.com/html5-qrcode"></script>


    <script>
        var lastResult;
    
        function onScanSuccess(decodedText, decodedResult) {
            if (decodedText !== lastResult) {
                lastResult = decodedText;
    
                // Redirect ke route tertentu menggunakan JavaScript
                window.location.href = `/returns/${decodedText}`;
            }
        }
    
        var html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader", {
                fps: 30,
                qrbox: 250
            });
        html5QrcodeScanner.render(onScanSuccess);
    </script>
@endsection
