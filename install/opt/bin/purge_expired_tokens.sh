#!/usr/bin/env bash

# Run this as the httpd owner!

find /var/www/tmp/token_dir/* -mmin +5  | xargs rm -f > /var/log/httpd/purge_expired_tokens.log