import json
import os
import sys
import yaml

if len(sys.argv) != 2:
    print("Usage: {} myfile.yaml".format(os.path.basename(sys.argv[0])))
    exit(1)
try:
    fd_yaml = open(sys.argv[1], 'r')
except Exception as e:
    print('cannot open yaml file: {}\n{}'.format(os.path.abspath(sys.argv[1]), str(e)))
    exit(2)

try:
    yaml_import = yaml.load(fd_yaml)
    print(json.dumps(yaml_import, sort_keys=True, indent=4))
except yaml.YAMLError as e:
    print('YAML input file cannot be parsed\n' + str(e))
    exit(3)

