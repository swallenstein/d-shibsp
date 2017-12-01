import os
import sys
import yaml
from jinja2 import Template

if len(sys.argv) != 3:
    raise Exception("Usage: {} template_arguments.yaml metadata_template".format(os.path.basename(sys.argv[0])))
try:
    fd_yaml = open(sys.argv[1], 'r')
except Exception as e:
    print('cannot open template_arguments: {}\n{}'.format(os.path.abspath(sys.argv[1]), str(e)))
    exit(1)

template_vars = {}
try:
    template_vars = yaml.load(fd_yaml)
except yaml.YAMLError as e:
    print(e)


try:
    fd_templ = open(sys.argv[2], 'r', encoding='UTF-8')
except Exception as e:
    print('cannot open XML metadata template {}\n{}'.format(os.path.abspath(sys.argv[2]), str(e)))
    exit(1)
t = Template(fd_templ.read())
#template_vars = {'ContactPerson.technical.Email': 'fxmeier@bka.gv.at', 'ContactPerson.support.Surname': 'Meier', 'ContactPerson.support.Email': 'fxmeier@bka.gv.at', 'IDPSSODescriptor.wantsAuthnRequestSigned': True, 'OrganizationUrl': 'https://www.bka.gv.at', 'ContactPerson.support.Givenname': 'Franz', 'xml.lang': 'en', 'mdui.Logo': 'https://fairchat.net/rcstatic/img/rot-weiss-rot-logo300x100px.png', 'mdui.Description': 'BKA Mitarbeiter Stammportal (Test, extern)', 'ContactPerson.technical.Surname': 'Meier', 'EntityDescriptor.entity-category': 'http://www.ref.gv.at/ns/names/agiz/pvp/egovtoken', 'OrganizationName': 'BKA', 'ContactPerson.technical.Givenname': 'Franz', 'mdui.DisplayName': 'BKA Mitarbeiter (Test, extern)', 'OrganizationDisplayName': 'Bundeskanzleramt'}

#print( t.render({'mdui_DisplayName': 'BKA Mitarbeiter (Test, extern)', }))
print( t.render({'entity_category': 'fxmeier@bka.gv.at', 'mdui_DisplayName': 'BKA Mitarbeiter (Test, extern)', }))
#print( t.render(template_vars))

