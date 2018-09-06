#!/usr/bin/env bash

main() {
    do_all_dir=$(cd $(dirname $BASH_SOURCE[0]) && pwd)
    _get_commandline_opts $@
    _exec_them_all
}


_get_commandline_opts() {
    wait=0
    while getopts ":rw:" opt; do
      case $opt in
        r) cmdopt='run';;
        w) wait=$OPTARG;;
        *) echo "usage: $0 -b | -r ] [-w seconds] [conf-number ]..
             -r  docker-compose down/up -d
             -w integer  number of seconds to wait in between commands; default: no wait
           "; exit 0;;
      esac
    done
    shift $((OPTIND-1))
    items=$@
}


_exec_them_all() {
    (cd $do_all_dir
     for n in $items; do 
         cmd="docker-compose -f dc${n}.yaml down && docker-compose -f dc${n}.yaml up -d"
         if [[ $dryrun ]]; then
             echo $cmd
         else
             $cmd
             sleep $wait
         fi
     done
    )
}


main $@
