#!/bin/bash

#Bootstrap machine to prepare it for Ansible

set -e

# Download and install Homebrew

if [ ! `which brew` ]; then

echo "Install Homebrew"

ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"

fi

# Download and install Ansible

if [ ! `which ansible` ]; then

echo "Install Ansible"

brew update

#brew tap caskroom/cask
#brew install brew-cask


#brew tap phinze/homebrew-cask
#brew install brew-cask

#brew install homebrew/completions/brew-cask-completition


brew install ansible

fi

echo "Run playbook.yml"

ansible-playbook -K -i hosts playbook.yml