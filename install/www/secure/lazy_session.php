<html>
<body>
<div STYLE="width: 800px; align: center; text-align: center";>
<?php
echo "<br><br<br>";
echo '<h1 style="background-color: #FFA500;">Shibboleth Authentication Status:</h1>';
if (isset($_SERVER["Shib-Identity-Provider"])){
        echo 'Er bestaat een sessie met <b>'.$_SERVER["Shib-Identity-Provider"].'!</b>.<br>';
        echo '<a href="/Shibboleth.sso/Session">Bekijk attributen en sessie informatie</a><br><br>';

        echo 'Log uit: <a href="/Shibboleth.sso/Logout?return='.$_SERVER["Shib-logoutURL"].'?return=https://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"].'">/Shibboleth.sso/Logout?return='.$_SERVER["Shib-logoutURL"].'?return=https://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"].'</a>';

} else {
        echo "Er bestaat nog geen sessie!<br>Log in:</br>";


        echo '<a href="https://'.$_SERVER["SERVER_NAME"].'/Shibboleth.sso/Login?target=https://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"].'">https://'.$_SERVER["SERVER_NAME"].'/Shibboleth.sso/Login?target=https://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"].'</a>';

}
?>
</div>
</body>
</html>

