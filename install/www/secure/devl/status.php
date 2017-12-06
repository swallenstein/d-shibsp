<html>
<head>
<title>Shibboleth SP Metadata</title>
</head>
<body>
<h1>SP Metadata</h1>
<?php
$xml = file_get_contents('https://echo.test.portalverbund.gv.at/Shibboleth.sso/Status', 'r');
echo '<pre>', htmlentities($xml), '</pre>'; 
?>
</body>
</html>




