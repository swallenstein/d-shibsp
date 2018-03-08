#!/usr/bin/env bash

main() {
    do_all_dir=$(cd $(dirname $BASH_SOURCE[0]) && pwd)
    _get_commandline_opts $@
    _exec_them_all
}


_get_commandline_opts() {
    wait=0
    while getopts ":bcdrw:" opt; do
      case $opt in
        b) cmdopt='build';;
        c) cmdopt='build' && nocache='-c';;
        d) dryrun='True';;
        r) cmdopt='run';;
        w) wait=$OPTARG;;
        *) echo "usage: $0 -b | -r ] [-w seconds] [conf-number ]..
             -b  run dscripts/build.sh
             -c  run dscripts/build.sh -c
             -r  run dscripts/run.sh
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
         cmd="./dscripts/${cmdopt}.sh $nocache -n$n"
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
