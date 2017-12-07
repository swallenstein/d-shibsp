#!/usr/bin/python3

import argparse
import os
import sys
import yaml

class Cli:
    """ define CLI invocation """
    def __init__(self):
        self._parser = argparse.ArgumentParser(description='get a value from yaml argument file')
        self._parser.add_argument('arguments_file', help='Arguments file, YAML')
        self._parser.add_argument('arguments_section', help='Section in the arguments file to apply to the template')
        self._parser.add_argument('keyword', )
        self.args = self._parser.parse_args()


def main():
    if sys.version_info < (3, 4):
        raise "must use python 3.4 or higher"
    cli = Cli()
    print(get_template_argument(cli.args), end='')


def get_template_argument(args):
    try:
        fd_yaml = open(args.arguments_file, 'r', encoding='utf-8-sig')
    except Exception as e:
        print('cannot open template_arguments: {}\n{}'.format(os.path.abspath(args.arguments_file), str(e)))
        exit(1)

    try:
        template_vars = yaml.load(fd_yaml)
    except yaml.YAMLError as e:
        print(e)
        exit(1)
    return template_vars[args.arguments_section][args.keyword]


main()