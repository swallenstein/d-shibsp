<html>
<head>
    <!-- author: Rainer Hoerbe -->
    <title> SAML Echo Service </title>
    <link type="text/css" rel="stylesheet" media="all" href="default.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" ></script>
    <!-- To obtain the status page it must be access with the valid session cookie. 
         php/SSI etc. will not work therefore. A client-side include using jQuery is used instead. 
         Its contents is transferred to a <div> to allow for variable height.
    -->
    <!--script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js"></script-->
    
    <link rel="stylesheet" href="http://yandex.st/highlightjs/8.0/styles/default.min.css">

    <link rel="stylesheet" href="styles/default.css">

    <script>
        function loadXMLDoc() {
            var xmlhttp1;
            xmlhttp1=new XMLHttpRequest();
            xmlhttp1.onreadystatechange=function() {
              if (xmlhttp1.readyState==4 && xmlhttp1.status==200) {
                document.getElementById("SAMLAssertion").innerHTML=xmlhttp1.responseText;
              }
            }
            xmlhttp1.open("POST","/cgi-bin/prettyxml.cgi", true);
            xmlhttp1.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            xmlhttp1.send('xmlurl=<?php echo urlencode($_SERVER["Shib-Assertion-01"]); ?>');

            var xmlhttp2;
            xmlhttp2=new XMLHttpRequest();

            xmlhttp2.onreadystatechange=function() {
              if (xmlhttp2.readyState==4 && xmlhttp2.status==200) {
                document.getElementById("ShibStatus").innerHTML=xmlhttp2.responseText;
              }
            }
            xmlhttp2.open("GET","https://echo.test.portalverbund.gv.at/Shibboleth.sso/Session", true);
            xmlhttp2.send();
        }
    </script>

</head>

<body onload="loadXMLDoc()">

<p style="text-align: center; font-size: 1.7em">Echo Service showing SAML response</p>
<p style="text-align: right; font-size: 1.2em"><a href="/Shibboleth.sso/Logout?return=/index.html">Logout</a></p>
<div id="ShibStatus"></div>

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
    <!--colgroup> <col width="300"> <col width="800"> <col width=" 320"> </colgroup-->
    <caption>PVP eGov Token 2.1</caption>
    <tr><th style="width=13em">Name</th><th>Wert</th>
         </tr><tr><td colspan="2" style="padding-left: 13em">Attribute der Entity Category http://www.ref.gv.at/ns/names/agiz/pvp/egovtoken</td>
    </tr><tr><td style="color:black<?php echo $_SERVER["X-PVP-VERSION"] ? "" : ";background-color:red"; ?>">X-PVP-VERSION         </td><td><?php echo $_SERVER["X-PVP-VERSION"]; ?></td>
    </tr><tr><td>X-PVP-PARTICIPANT-ID  </td><td><?php echo $_SERVER["X-PVP-PARTICIPANT-ID"]; ?></td>
    </tr><tr><td>X-PVP-USERID          </td><td><?php echo $_SERVER["X-PVP-USERID"]; ?></td>
    </tr><tr><td>X-PVP-GID             </td><td><?php echo $_SERVER["X-PVP-GID"]; ?></td>
    </tr><tr><td>X-PVP-BPK             </td><td><?php echo $_SERVER["X-PVP-BPK"]; ?></td>
    </tr><tr><td>X-PVP-PARTICIPANT-OKZ </td><td><?php echo $_SERVER["X-PVP-PARTICIPANT-OKZ"]; ?></td>
    </tr><tr><td>X-PVP-GIVENNAME       </td><td><?php echo $_SERVER["X-PVP-GIVENNAME"]; ?></td>
    </tr><tr><td>X-PVP-PRINCIPALNAME   </td><td><?php echo $_SERVER["X-PVP-PRINCIPALNAME"]; ?></td>
    </tr><tr><td>X-PVP-MAIL            </td><td><?php echo $_SERVER["X-PVP-MAIL"]; ?></td>
    </tr><tr><td>X-PVP-TEL             </td><td><?php echo $_SERVER["X-PVP-TEL"]; ?></td>
    </tr><tr><td>X-PVP-OU-OKZ          </td><td><?php echo $_SERVER["X-PVP-OU-OKZ"]; ?></td>
    </tr><tr><td>X-PVP-OU-GV-OU-ID     </td><td><?php echo $_SERVER["X-PVP-OU-GV-OU-ID"]; ?></td>
    </tr><tr><td>X-PVP-OU              </td><td><?php echo $_SERVER["X-PVP-OU"]; ?></td>
    </tr><tr><td>X-PVP-ROLES           </td><td><?php echo preg_replace('/;/', '; ', $_SERVER["X-PVP-ROLES"]); ?></td>
    </tr><tr><td <?php echo $_SERVER["X-PVP-BIRTHDATE"] ? 'style="color:black"' : ""; ?>X-PVP-BIRTHDATE        </td><td><?php echo $_SERVER["X-PVP-BIRTHDATE"]; ?></td>
    </tr><tr><td><?php echo $_SERVER["X-PVP-FUNCTION"] ? 'style="color:black"' : ""; ?>X-PVP-FUNCTION        </td><td><?php echo $_SERVER["X-PVP-FUNCTION"]; ?></td>   
    </tr><tr><td <?php echo $_SERVER["X-PVP-ENC-BPK-LIST"] ? 'style="color:black"' : ""; ?>X-PVP-ENC-BPK-LIST           </td><td><?php echo preg_replace('/;/', '; ', $_SERVER["X-PVP-ENC-BPK-LIST"]); ?></td>
    </tr><tr><td colspan="2" style="padding-left: 13em">Attribute der Entity Category http://www.ref.gv.at/ns/names/agiz/pvp/egovtoken-charge</td>
    </tr><tr><td <?php echo $_SERVER["X-PVP-INVOICE-RECPT-ID"] ? 'style="color:black"' : ""; ?>X-PVP-INVOICE-RECPT-ID</td><td><?php echo $_SERVER["X-PVP-INVOICE-RECPT-ID"]; ?></td> 
    </tr><tr><td <?php echo $_SERVER["X-PVP-COST-CENTER-ID"] ? 'style="color:black"' : ""; ?>X-PVP-COST-CENTER-ID  </td><td><?php echo $_SERVER["X-PVP-COST-CENTER-ID"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["X-PVP-CHARGE-CODE"] ? 'style="color:black"' : ""; ?>X-PVP-CHARGE-CODE     </td><td><?php echo $_SERVER["X-PVP-CHARGE-CODE"]; ?></td>
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
    <div><pre class="prettyprint linenums"><p id="SAMLAssertion"></p></pre></div>

<hr>

<h1>SP Metadata</h1>
<div id="XMLHolder"> </div>
<LINK href="XMLDisplay.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="XMLDisplay.js"> </script>
<script>LoadXML('XMLHolder','https://echo.test.portalverbund.gv.at/sp.xml'); </script>
<script src="http://yandex.st/highlightjs/8.0/highlight.min.js"></script>

</body>
</html>
