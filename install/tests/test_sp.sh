#!/usr/bin/env bash


# minimum viable test: check for AuthnRequest

curl -s -o /tmp/authRequest.http -w "%{http_code}" http://localhost:8080/secure/test.php > /tmp/http_code
if (( $?>0 )); then
    echo 'Request to SP failed at http://localhost:8080/secure/test.php'
    exit 1
fi

if [[ $(cat /tmp/http_code) != '302' ]]; then
    echo "Request to SP should have returned HTTP code 302, but returned $(cat /tmp/http_code)"
    exit 2
fi

grep 'idp/profile/SAML2/Redirect/SSO?SAMLRequest' idp/profile/SAML2/Redirect/SSO?SAMLRequest
if (( $?>0 )); then
    echo 'Request to SP should contain idp/profile/SAML2/Redirect/SSO?SAMLRequest, bur request was:'
    cat idp/profile/SAML2/Redirect/SSO?SAMLRequest
    exit 3
fi
