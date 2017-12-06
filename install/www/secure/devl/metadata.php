<html>
<head>
<title>Shibboleth SP Metadata</title>
</head>
<body>
<h1>SP Metadata</h1>
<?php
$xml = file_get_contents('https://echo.test.portalverbund.gv.at/sp.xml', 'r');
//echo '<pre>', htmlentities($xml), '</pre>'; 
?>

<!-- following code requires http://www.levmuchnik.net/Content/ProgrammingTips/WEB/XMLDisplay/XMLDisplay.css and XMLDisplay.js from same dir -->
<div id="XMLHolder"> </div>
<LINK href="XMLDisplay.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="XMLDisplay.js"> </script>
<script>LoadXML('XMLHolder','https://echo.test.portalverbund.gv.at/sp.xml'); </script>

</body>
</html>




