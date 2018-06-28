import yaml

with open('docker-compose.yaml.default') as fd:
     doc = yaml.load(fd)

print(yaml.dump(doc))
