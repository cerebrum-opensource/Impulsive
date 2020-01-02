<html lang="de">
<head>
    <meta charset="utf-8">
    <title>Pay Pal Plus Test</title>
    <script src="https://www.paypalobjects.com/webstatic/ppplus/ppplus.min.js" type="text/javascript"></script>
    <script type="application/javascript">
        var ppp = PAYPAL.apps.PPP({
        "approvalUrl": "https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=EC-4TL460441A356253P",
        "placeholder": "ppplus",
        "mode": "live",
        "country": "DE"
        });
    </script>
</head>
<body>
  <div id="ppplus"> </div>
</body>
</html>