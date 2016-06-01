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
            xmlhttp2.open("GET","/Shibboleth.sso/Session", true);
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
    <caption>WVP Standard Attribute Bundle</caption>
    <tr><th style="width=13em">Name</th><th>Wert</th>
    </tr><tr><td colspan="2" style="padding-left: 13em">Attribute der Entity Category http://wirtschaftsportalverbund.at/namespaces/ecStandardAttributes/20160310</td>
    </tr><tr><td <?php echo $_SERVER["country"] ? 'style="color:black"' : ""; ?>>country           </td><td><?php echo preg_replace('/;/', '; ', $_SERVER["country"]); ?></td>
    </tr><tr><td <?php echo $_SERVER["cn"] ? 'style="color:black"' : ""; ?>>cn</td><td><?php echo $_SERVER["cn"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["displayName"] ? 'style="color:black"' : ""; ?>>displayName</td><td><?php echo $_SERVER["displayName"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["gender"] ? 'style="color:black"' : ""; ?>>gender</td><td><?php echo $_SERVER["gender"]; ?></td> 
    </tr><tr><td <?php echo $_SERVER["gln"] ? 'style="color:black"' : ""; ?>>gln   </td><td><?php echo $_SERVER["gln"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["givenName"] ? 'style="color:black"' : ""; ?>>givenName       </td><td><?php echo $_SERVER["givenName"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["localityName"] ? 'style="color:black"' : ""; ?>>localityName          </td><td><?php echo $_SERVER["localityName"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["mail"] ? 'style="color:black"' : ""; ?>>mail             </td><td><?php echo $_SERVER["mail"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["o"] ? 'style="color:black"' : ""; ?>>o            </td><td><?php echo $_SERVER["o"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["postalAddress"] ? 'style="color:black"' : ""; ?>>postalAddress             </td><td><?php echo $_SERVER["postalAddress"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["postalCode"] ? 'style="color:black"' : ""; ?>>postalCode             </td><td><?php echo $_SERVER["postalCode"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["postOfficeBox"] ? 'style="color:black"' : ""; ?>>postOfficeBox  </td><td><?php echo $_SERVER["postOfficeBox"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["street"] ? 'style="color:black"' : ""; ?>>street </td><td><?php echo $_SERVER["street"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["surname"] ? 'style="color:black"' : ""; ?>>surname          </td><td><?php echo $_SERVER["surname"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["telephoneNumber"] ? 'style="color:black"' : ""; ?>>telephoneNumber     </td><td><?php echo $_SERVER["telephoneNumber"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["title"] ? 'style="color:black"' : ""; ?>>title              </td><td><?php echo $_SERVER["title"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["uid"] ? 'style="color:black"' : ""; ?>>uid        </td><td><?php echo $_SERVER["uid"]; ?></td>   
    </tr><tr><td <?php echo $_SERVER["gid"] ? 'style="color:black"' : ""; ?>>gid           </td><td><?php echo preg_replace('/;/', '; ', $_SERVER["gid"]); ?></td>
    </tr><tr><td <?php echo $_SERVER["preferredLanguage"] ? 'style="color:black"' : ""; ?>>preferredLanguage</td><td><?php echo $_SERVER["preferredLanguage"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["rights"] ? 'style="color:black"' : ""; ?>>rights        </td><td><?php echo $_SERVER["rights"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["wpv-AuthenticationClass"] ? 'style="color:black"' : ""; ?>>wpv-AuthenticationClass     </td><td><?php echo $_SERVER["wpv-AuthenticationClass"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["wpv-RegistrationClassOrg"] ? 'style="color:black"' : ""; ?>>wpv-RegistrationClassOrg</td><td><?php echo $_SERVER["wpv-RegistrationClassOrg"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["wpv-RegistrationClassUser"] ? 'style="color:black"' : ""; ?>>wpv-RegistrationClassUser</td><td><?php echo $_SERVER["wpv-RegistrationClassUser"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["wbpkHash"] ? 'style="color:black"' : ""; ?>>wbpkHash  </td><td><?php echo $_SERVER["wbpkHash"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["WKIS-Redirect"] ? 'style="color:black"' : ""; ?>>WKIS-Redirect  </td><td><?php echo $_SERVER["WKIS-Redirect"]; ?></td>
    </tr><tr><td colspan="2" style="padding-left: 13em">Andere SAML Assertion Elemente</td></tr>
    <?php echo $_SERVER["NameID-persistent"] ? '<tr><td style="color:black">saml:NameID (persistent)</td><td>' . $_SERVER["NameID-persistent"] . "</td></tr>" : ""; ?>
    <?php echo $_SERVER["NameID"]            ? '<tr><td style="color:black">saml:NameID (transient)</td><td>' . $_SERVER["NameID"] . "</td></tr>" : ""; ?>
    <?php echo $_SERVER["NameID-unspec"]     ? '<tr><td style="color:black">saml:NameID (unspecified)</td><td>' . $_SERVER["NameID-unspec"] . "</td></tr>" : ""; ?>
    <tr><td <?php echo $_SERVER["samlAuthnContextClassRef"] ? 'style="color:black"' : ""; ?>>saml:AuthnContextClassRef</td><td><?php echo $_SERVER["samlAuthnContextClassRef"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["samlConsent"] ? 'style="color:black"' : ""; ?>>saml:Consent</td><td><?php echo $_SERVER["samlConsent"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["samlIssuer"] ? 'style="color:black"' : ""; ?>>saml:Issuer</td><td><?php echo $_SERVER["samlIssuer"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["samlNotOnOrAfter"] ? 'style="color:black"' : ""; ?>>saml:NotOnOrAfter</td><td><?php echo $_SERVER["samlNotOnOrAfter"]; ?></td>
    </tr><tr><td <?php echo $_SERVER["samlSessionIndex"] ? 'style="color:black"' : ""; ?>>saml:SessionIndex</td><td><?php echo $_SERVER["samlSessionIndex"]; ?></td>
    </tr>
</table>

<hr>

<h1>SAML Assertion 1</h1>
  <div>
    <pre class="prettyprint linenums">
      <p id="SAMLAssertion">
      <?php echo $_SERVER["Shib-Assertion-01"]; ?>
      </p>
    </pre>
  </div>

<hr>

<!--
<h1>SP Metadata</h1>
<div id="XMLHolder"> </div>
<LINK href="XMLDisplay.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="XMLDisplay.js"> </script>
<script>LoadXML('XMLHolder','https://sp2.test.wpv.portalverbund.at/sp.xml'); </script>
<script src="http://yandex.st/highlightjs/8.0/highlight.min.js"></script>
-->

</body>
</html>
