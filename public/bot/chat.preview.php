<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Preview</title>
    <style>
        html, body {
            padding: 0;
            margin: 0;
        }
    </style>
</head>
<body>
<div id="container"></div>
<script src="/bot/botico.js"></script>
<script>
    window.addEventListener("message", (event) => {
        let boticoChat = new BoticoChat({
            server: `//${window.location.host}/`,
            container: '#container',
            isTestMode: true,
            secret: '',
            bots: event.data
        });
    });
</script>
</body>
</html>
