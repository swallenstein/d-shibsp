# Shibboleth SP docker image with static network addresses 

A Shibboleth configuration running apache and shibd in a container:
1. both apache and shibd run as non-root users
2. The user-defined network uses static IP addresses.
3. Image is based on centos7     

The image produces immutable containers, i.e. a container can be removed and re-created
any time without loss of data, because data is stored on mounted volumes.

# Build the docker image
1. adapt conf.sh
2. run build.sh: 


## Usage:
 First run run1.sh to start shibd:
 
    run1.sh -h  # print usage
    run1.sh -ipr bash  # interactive, print run command, root user 
    run1.sh     # daemon mode
    
 Then run2.sh for apache:
    run2.sh -h  # print usage
    run2.sh     # start httpd & return
 