#!/usr/bin/env bash

main() {
    _get_commandline_opts "$@"
    _exec_them_all
}


_get_commandline_opts() {
    wait=0
    while getopts ":dlrw:" opt; do
      case $opt in
        d) dryrun='True';;
        w) wait=$OPTARG;;
        *) echo "usage: $0 -l | -r ] [-w seconds] command
             execute command for all dc*.y*ml files in the current directory
             -d  dryrun: print docker commands instead of executing them
             -w integer  number of seconds to wait in between commands; default: no wait
           command is one of:
             config: validate config
             listsrv: list configured volumes (depends on docker-compose version)
             listvol: list configured volumes (depends on docker-compose version)
             logs: 'docker-compose logs'
             restart alias 'docker-compose down/up -d'
           "; exit 0;;
      esac
    done
    shift $((OPTIND-1))
    case "$1" in
       config) cmdopt='config --quiet';;
       listsrv) cmdopt='config --services';;
       listvol) cmdopt='config --volumes';;
       logs) cmdopt='logs';;
       restart) cmdopt='restart';;
       *) echo "command must be either logs or restart"; exit 1;;
    esac
}


_exec_them_all() {
    for f in dc[0-9][0-9].y*ml; do
        case "$cmdopt" in
           restart) cmd="docker-compose -f ${f} down && docker-compose -f ${f} up -d";;
           *) cmd="docker-compose -f ${f} ${cmdopt}";;
        esac
        if [[ "${dryrun}" ]]; then
            echo "${cmd}"
        else
            $cmd
            sleep $wait
        fi
    done
}


main "$@"

