<html>
<head>
    <!-- author: Rainer Hoerbe -->
    <title> PVP2 SAML Echo Service </title>
    <link type="text/css" rel="stylesheet" media="all" href="default.css" />
    <!-- To obtain the status page it must be access with the valid session cookie. 
         php/SSI etc. will not work therefore. A client-side include using jQuery is used instead. 
         Its contents is transferred to a <div> to allow for variable height.
    -->
    <!--script type="text/javascript" src="formatXml.js"></script-->
    <script type="text/javascript" src="formatXML.js"></script>
    <!--script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js"></script-->

    <link rel="stylesheet" href="styles/default.css">

    <script src="highlight.pack.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>

    <script>
        $(function(){
          $("#includeSession").load("https://echo.test.portalverbund.gv.at/Shibboleth.sso/Session"); 
        });
    </script>
</head>

<body>

<p style="text-align: center; font-size: 1.7em">Session</p>
<div id="includeSession"></div>

<hr>

<table>
    <colgroup> <col width="300"> <col width="800"> <col width=" 320"> </colgroup>
    <caption>Request Information</caption>
    <tr><th style="width=13em">Name</th><th>Wert</th>
    <tr><td>HTTP_HOST</td><td><?php echo $_SERVER["HTTP_HOST"]; ?></td>
    </tr><tr><td>REQUEST_URI           </td><td><?php echo $_SERVER["REQUEST_URI"]; ?></td>
    </tr>
</table>

<table>
    <colgroup> <col width="300"> <col width="800"> <col width=" 320"> </colgroup>
    <caption>PVP eGov Token 2.1</caption>
    <tr><th style="width=13em">Name</th><th>Wert</th>
         </tr><tr><td colspan="2" style="padding-left: 13em">Attribute der Entity Category http://www.ref.gv.at/ns/names/agiz/pvp/egovtoken</td>
    </tr><tr><td style="color:black<?php echo $_SERVER["X-PVP-VERSION"] ? "" : ";background-color:red"; ?>">X-PVP-VERSION         </td><td><?php echo $_SERVER["X-PVP-VERSION"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["X-PVP-PRINCIPALNAME"] ? 'style="color:black"' : ""; ?>>X-PVP-PRINCIPALNAME   </td><td><?php echo $_SERVER["X-PVP-PRINCIPALNAME"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["X-PVP-GIVENNAME"] ? 'style="color:black"' : ""; ?>>X-PVP-GIVENNAME       </td><td><?php echo $_SERVER["X-PVP-GIVENNAME"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["X-PVP-USERID"] ? 'style="color:black"' : ""; ?>>X-PVP-USERID          </td><td><?php echo $_SERVER["X-PVP-USERID"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["X-PVP-GID"] ? 'style="color:black"' : ""; ?>>X-PVP-GID             </td><td><?php echo $_SERVER["X-PVP-GID"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["X-PVP-BPK"] ? 'style="color:black"' : ""; ?>>X-PVP-BPK             </td><td><?php echo $_SERVER["X-PVP-BPK"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["X-PVP-MAIL"] ? 'style="color:black"' : ""; ?>>X-PVP-MAIL            </td><td><?php echo $_SERVER["X-PVP-MAIL"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["X-PVP-TEL"] ? 'style="color:black"' : ""; ?>>X-PVP-TEL             </td><td><?php echo $_SERVER["X-PVP-TEL"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["X-PVP-PARTICIPANT-ID"] ? 'style="color:black"' : ""; ?>>X-PVP-PARTICIPANT-ID  </td><td><?php echo $_SERVER["X-PVP-PARTICIPANT-ID"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["X-PVP-PARTICIPANT-OKZ"] ? 'style="color:black"' : ""; ?>>X-PVP-PARTICIPANT-OKZ </td><td><?php echo $_SERVER["X-PVP-PARTICIPANT-OKZ"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["X-PVP-OU-OKZ"] ? 'style="color:black"' : ""; ?>>X-PVP-OU-OKZ          </td><td><?php echo $_SERVER["X-PVP-OU-OKZ"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["X-PVP-OU-GV-OU-ID"] ? 'style="color:black"' : ""; ?>>X-PVP-OU-GV-OU-ID     </td><td><?php echo $_SERVER["X-PVP-OU-GV-OU-ID"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["X-PVP-OU"] ? 'style="color:black"' : ""; ?>>X-PVP-OU              </td><td><?php echo $_SERVER["X-PVP-OU"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["X-PVP-FUNCTION"] ? 'style="color:black"' : ""; ?>>X-PVP-FUNCTION        </td><td><?php echo $_SERVER["X-PVP-FUNCTION"]; ?></td>   
    </tr><tr><td <?php echo $_SERVER["X-PVP-ROLES"] ? 'style="color:black"' : ""; ?>>X-PVP-ROLES           </td><td><?php echo preg_replace('/;/', '; ', $_SERVER["X-PVP-ROLES"]); ?></td>
    </tr><tr><td <?php echo $_SERVER["X-PVP-BIRTHDATE"] ? 'style="color:black"' : ""; ?>>X-PVP-BIRTHDATE        </td><td><?php echo $_SERVER["X-PVP-BIRTHDATE"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["X-PVP-ENC-BPK-LIST"] ? 'style="color:black"' : ""; ?>>X-PVP-ENC-BPK-LIST           </td><td><?php echo preg_replace('/;/', '; ', $_SERVER["X-PVP-ENC-BPK-LIST"]); ?></td>
    </tr><tr><td colspan="2" style="padding-left: 13em">Attribute der Entity Category http://www.ref.gv.at/ns/names/agiz/pvp/egovtoken-charge</td>
    </tr><tr><td <?php echo $_SERVER["X-PVP-INVOICE-RECPT-ID"] ? 'style="color:black"' : ""; ?>>X-PVP-INVOICE-RECPT-ID</td><td><?php echo $_SERVER["X-PVP-INVOICE-RECPT-ID"]; ?></td> 
    </tr><tr><td <?php echo $_SERVER["X-PVP-COST-CENTER-ID"] ? 'style="color:black"' : ""; ?>>X-PVP-COST-CENTER-ID  </td><td><?php echo $_SERVER["X-PVP-COST-CENTER-ID"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["X-PVP-CHARGE-CODE"] ? 'style="color:black"' : ""; ?>>X-PVP-CHARGE-CODE     </td><td><?php echo $_SERVER["X-PVP-CHARGE-CODE"]; ?></td>
    </tr><tr><td colspan="2" style="padding-left: 13em">Andere SAML Assertion Elemente</td></tr>
    <?php echo $_SERVER["NameID-persistent"] ? '<tr><td style="color:black">saml:NameID (persistent)</td><td>' . $_SERVER["NameID-persistent"] . "</td></tr>" : ""; ?>
    <?php echo $_SERVER["NameID"]            ? '<tr><td style="color:black">saml:NameID (transient)</td><td>' . $_SERVER["NameID"] . "</td></tr>" : ""; ?>
    <?php echo $_SERVER["NameID-unspec"]     ? '<tr><td style="color:black">saml:NameID (unspecified)</td><td>' . $_SERVER["NameID-unspec"] . "</td></tr>" : ""; ?>
         <tr><td <?php echo $_SERVER["samlAuthnContextClassRef"] ? 'style="color:black"' : ""; ?>>saml:AuthnContextClassRef</td><td><?php echo $_SERVER["samlAuthnContextClassRef"]; ?></td>
    <?php echo ($_SERVER["X-PVP-SECCLASS"]) ? '</tr><tr><td style="color:red">X-PVP-SECCLASS</td><td style="color:red">SecClass soll für PVP2-S-Profile nicht übermittelt werden; statt dessen ist samlp:AuthnContextClassRef zu verwenden</td></tr>' : '' ?>
    </tr><tr><td <?php echo $_SERVER["samlConsent"] ? 'style="color:black"' : ""; ?>>saml:Consent</td><td><?php echo $_SERVER["samlConsent"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["samlIssuer"] ? 'style="color:black"' : ""; ?>>saml:Issuer</td><td><?php echo $_SERVER["samlIssuer"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["samlNotOnOrAfter"] ? 'style="color:black"' : ""; ?>>saml:NotOnOrAfter</td><td><?php echo $_SERVER["samlNotOnOrAfter"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["samlSessionIndex"] ? 'style="color:black"' : ""; ?>>saml:SessionIndex</td><td><?php echo $_SERVER["samlSessionIndex"]; ?></td>
    </tr>
</table>

<hr>

<h1>SAML Assertion 1</h1>

<pre><code>
<?php
$xml = file_get_contents($_SERVER["Shib-Assertion-01"], 'r');
echo htmlentities($xml); 
#echo $xml;
?>
</code></pre>

<hr>

<h1>SP Metadata</h1>
<div id="XMLHolder"> </div>
<LINK href="XMLDisplay.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="XMLDisplay.js"> </script>
<script>LoadXML('XMLHolder','https://echo.test.portalverbund.gv.at/sp.xml'); </script>
</body>
</html>
