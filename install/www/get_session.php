<?PHP
# output shibboleth SP session. 
# Use this to delegate a Shibboleth SP session from the browser to curl on the same client.
# Rainer Hoerbe 2018-01-03

header('Content-Type: text/plain');
foreach($_COOKIE as $name=>$value) {
    if (stripos($name, "_shibsession_") === 0) {
        print_r($name . "=" . $value . "\n");
    }
}

?>