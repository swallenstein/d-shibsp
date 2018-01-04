<?php

/* store token passed as query parameter by creating an empty file with the name of the token parameter value
*/

$token_dir = '/var/www/tmp/token_dir';
_handle_get_request($token_dir);


function _handle_get_request($token_dir) {
    parse_str($_SERVER['QUERY_STRING'], $query_param);
    if (array_key_exists('token', $query_param)) {
        $token = filter_var($query_param["token"], FILTER_SANITIZE_URL);
        if (strlen($token) < 40) die("sanitized token value length must be >= 40 characters.\n");
        if (strlen($token) > 400) die("sanitized token value length must be <= 400 characters.\n");
        _store_token($token_dir, $token);
    } else {
        echo("missing query argument 'token'\n");
    }
}


function _store_token($token_dir, $token) {
    _create_tokendir($token_dir);
    if (!file_exists("$token_dir/$token")) {
        $myfile = fopen("$token_dir/$token", "w") or die("Unable to create $token_dir/$token\n");
        fclose($myfile);
        echo("<html><body>Session token created. Close window.</body></html>");
    } else {
        unlink("$token_dir/$token") or die("Unable to delete existing /tmp/$token\n");
        $myfile = fopen("$token_dir/$token", "w") or die("Unable to create /tmp/$token\n");
        fclose($myfile);
        echo('<html><body>Session token refreshed. Close window.</body></html>');
    }
}


function _create_tokendir($token_dir) {
    if (!file_exists('$token_dir')) {
        #echo("creating directory $token_dir\n");
        mkdir($token_dir, 0755, $recursive = true);
    }
}
?>