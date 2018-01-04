<?php
/* Output Shibboleth SP session for the authenticated owner.
   Use case: login with the browser, delegate shib-sp session to a script

   The cookie is suffixed with a random value, therefore search for /^_shibsession_/
   Use this to delegate a Shibboleth SP session from the browser to curl on the same client.

   Authentication is required with the 'token' URL parameter.

   Rainer Hoerbe 2018-01-03
*/

$token_dir = '/var/www/tmp/token_dir';
_handle_get_request($token_dir);


function _handle_get_request($token_dir) {
    parse_str($_SERVER['QUERY_STRING'], $query_param);
    if (array_key_exists('token', $query_param)) {
        $token = filter_var($query_param["token"], FILTER_SANITIZE_URL);
        if (strlen($token) < 40) die("sanitized token value length must be >= 40 characters.\n");
        if (strlen($token) > 400) die("sanitized token value length must be <= 400 characters.\n");
        _purge_expired_tokens($token_dir);
        _return_session_cookie($token_dir, $token);
    } else {
        echo("missing query argument 'token'\n");
    }
}


function _purge_expired_tokens($token_dir) {
    $now = time();
    $fileSystemIterator = new FilesystemIterator($token_dir);
    foreach ($fileSystemIterator as $file) {
        $age = $now - $file->getMTime();
        if ($age >= 60 * 5) // 5 minutes
            $fpath = $file->getPathname();
            # unlink does not work from httpd ('file not found') -> TODO: make it working
            unlink($fpath);  # or die("unable to delete expired ". $fpath . "\n"));
    }
}


function _return_session_cookie($token_dir, $token) {
    if (file_exists("$token_dir/$token")) {
        header('Content-Type: text/plain');
        foreach($_COOKIE as $name=>$token) {
            if (stripos($name, "_shibsession_") === 0) {
                print_r($name . "=" . $token . "\n");
            }
        }
    } else {
        die("Unauthorized. You need to post the token before accessing this service.");
    }
}


?>
