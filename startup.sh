#!/bin/bash

# output to logfile
LOGFILE=$(dirname $0)/$(basename $0 | cut -d. -f1).log

# check if user is running the script as sudo
if [ "$EUID" -ne 0 ]; then
	echo "Please run this script as root...";
	exit 1;
fi;

# basic function that outputs a message and runs the command and checks if the previous
# command ran successfully
function runCommand() {
	if [[ $? == 0 ]]; then
		echo -ne "> $1: ";
		eval $2 >> $LOGFILE 2>&1 && echo "done" || echo "failed";
	else
		echo "ERROR: Last command did not end with a exit code 0, please check the log file $LOGFILE...";
	fi;
}

# install docker if it does not already exist
## check if docker is installed
command -v docker | grep -Eq ".*" && DOCKER=INSTALLED
## check if docker-compose is installed
command -v docker-compose | grep -Eq ".*" && DOCKER_COMPOSE=INSTALLED

### this function installs docker to the system
function installDocker() {
	echo "====: INSTALLING DOCKER :====";
	runCommand "Updating packages" "sudo apt-get update";
	runCommand "Installing necessary packages" "sudo apt-get install -y apt-transport-https ca-certificates curl gnupg-agent software-properties-common"
	runCommand "Adding docker's official GPG key" "curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -"
	runCommand "Adding the fingerprint" "sudo apt-key fingerprint 0EBFCD88"
	runCommand "Adding repository for docker" "sudo add-apt-repository \"deb [arch=amd64] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable\""
	runCommand "Updating packaged again" "sudo apt-get update"
	runCommand "Installing docker" "sudo apt-get install -y docker-ce docker-ce-cli containerd.io"
	echo "====: DONE :===="
}

### this function installs docker-compose to the system
function installDockerCompose() {
	echo "====: INSTALLING DOCKER-COMPOSE :====";
	runCommand "Downloading binary from external" "sudo curl -L \"https://github.com/docker/compose/releases/download/1.24.0/docker-compose-$(uname -s)-$(uname -m)\" -o /usr/local/bin/docker-compose"
	runCommand "Adding execution permission on the binary" "sudo chmod -x /usr/local/bin/docker-compose"
	runCommand "Linking docker-compose binary for easy access" "sudo ln -s /usr/local/bin/docker-compose /usr/bin/docker-compose"
	echo "====: DONE :===="
}

#### install docker if not installed
if [[ "$DOCKER" != "INSTALLED" ]]; then installDocker; fi;

#### install docker-compose if not installed
if [[ "$DOCKER_COMPOSE" != "INSTALLED" ]]; then installDockerCompose; fi;


#### start all services
echo "====: STARTING ALL SERVICES :===="
runCommand "Changing to main directory" "cd $(dirname $0)"
runCommand "Starting services" "docker-compose up -d"
echo "====: DONE :===="