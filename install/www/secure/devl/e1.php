<html>
<head>
    <!-- author: Rainer Hoerbe -->
    <title> PVP2 SAML Echo Service </title>
    <link type="text/css" rel="stylesheet" media="all" href="default.css" />
    <!-- To obtain the status page it must be access with the valid session cookie. 
         php/SSI etc. will not work therefore. A client-side include using jQuery is used instead. 
         Its contents is transferred to a <div> to allow for variable height.
    -->
    <script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js"></script>

    <link rel="stylesheet" href="default.css">

    <!--link rel="stylesheet" href="http://yandex.st/highlightjs/8.0/styles/default.min.css">
    <script src="http://yandex.st/highlightjs/8.0/highlight.min.js"></script-->
    <script>
    function loadXMLDoc() {
        var xmlhttp;
        xmlhttp=new XMLHttpRequest();

        xmlhttp.onreadystatechange=function()  {
          if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
            document.getElementById("myDiv").innerHTML=xmlhttp.responseText;
            }
        }

        xmlhttp.open("POST","/cgi-bin/prettyxml.cgi",true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xmlhttp.send('xmlurl=<?php echo urlencode($_SERVER["Shib-Assertion-01"]); ?>');
    }
    </script>

</head>

<body onload="loadXMLDoc()">

<h1>SAML Assertion 1</h1>

<pre class="prettyprint linenums">
<?php
$xml = file_get_contents($_SERVER["Shib-Assertion-01"], 'r');
echo nl2br(htmlentities($xml)); 
#echo htmlentities(nl2br($xml), ENT_XML1); 
#echo $xml;
?>
</pre>

<pre>
<?php
$xml = file_get_contents($_SERVER["Shib-Assertion-01"], 'r');
echo nl2br(htmlentities($xml)); 
#echo $xml;
?>
</pre>

<hr>

    <h2>AJAX</h2>
    <p>xmlurl=<?php echo $_SERVER["Shib-Assertion-01"] ?></p>
    <p></p>
    <p></p>
    <button type="button" onclick="loadXMLDoc()">Show SAML Response</button>
    <h2>Assertion</h2>
    <div><pre id="myDiv"></pre></div>

</body>
</html>

