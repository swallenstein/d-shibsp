<?php

/* create an empty file with the name of the token query parameter
*/

#$_SERVER = array('QUERY_STRING' => 'token=value');
parse_str($_SERVER['QUERY_STRING'], $query_param);
$value = $query_param["token"];
#print_r(var_dump($query_param));
#echo("token=$value\n");

$myfile = fopen("/tmp/$value", "w") or die("Unable to create /tmp/$token");
fclose($myfile);
?>