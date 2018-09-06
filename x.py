import yaml

with open('dc.yaml') as fd:
     doc = yaml.load(fd)

print(yaml.dump(doc))
