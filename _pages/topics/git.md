---
layout:     default
title:      Git
permalink:  /topics/git/
---

### Sources:

* [https://www.atlassian.com/git/tutorials/using-branches/git-branch/](git-branch), carina e con illustrazioni grafiche

Simpatico tutorial visivo:

https://marklodato.github.io/visual-git-guide/index-en.html


#### File Binary

https://robinwinslow.uk/2013/06/11/dont-ever-commit-binary-files-to-git/


TroubleShooting
================


macbook done. error: RPC failed; curl 56 SSLRead() return error -3603.00 KiB/s fatal: The remote end hung up unexpectedly fatal: early EOF fatal: index-pack failed

**FIRST METHOD**

this works both commandline and sourcetree:

````
git config --global http.postBuffer 1048576000
````


**SECOND METHOD**

this works without change settings, but need set to command line:

export GIT_TRACE_PACKET=1 && \
export GIT_TRACE=1 && \
export GIT_CURL_VERBOSE=1


**THIRD METHOD**

ssh, but needs set ssh key into github...
so set you couple of rsa key private-public and clone via terminale as follow:
x`
````
ssh-agent $(ssh-add /home/christoffer/ssh_keys/theuser; git clone git@github.com:TheUser/TheProject.git)
````

or (but not tested)

````
ssh-agent bash -c 'ssh-add /home/christoffer/ssh_keys/theuser; git clone git@github.com:TheUser/TheProject.git'
````


GENERAL
=======

#### Revert changes to modified files.
git reset --hard

#### Remove all untracked files and directories. (`-f` is `force`, `-d` is `remove directories`)
git clean -fd


#### Trovare branch che sono stati mergiati oppure no

git branch --merged master 
	lists branches merged into master

git branch --merged 
	lists branches merged into HEAD (i.e. tip of current branch)

git branch --no-merged 
	lists branches that have not been merged

By default this applies to only the local branches. The -a flag will show both local and remote branches, and the -r flag shows only the remote branches.


#### Fare in modo che dato un branch locale...

fare in modo che dato un branch locale, questi venga poi pushato in remoto e contemporaneamente associato al locale:

````bash
git remote add upstream https://github.com/ORIGINAL_OWNER/ORIGINAL_REPOSITORY.git
# oppure: git remote add -u https://github.com/ORIGINAL_OWNER/ORIGINAL_REPOSITORY.git
````

#### Fare in modo che dato un branch locale...

fare in modo che dato un branch remot, questi venga poi pullato e seguito associato a un branch locale:

````bash
git branch --track style origin/style
````



LECTURES
========


#### rerere

Per dare regole generali sul merge tra branch: 
ad esempio se ho un branch locale con un mio `.htaccess` e non voglio che questo `.htaccess` 
sovrascriva quello di produzione quando lo mergio, posso esplicitarlo mediante `rerere` !!!

- [https://git-scm.com/blog/2010/03/08/rerere.html](https://git-scm.com/blog/2010/03/08/rerere.html) 


#### Git Flow + Semantica

- [https://engineering.facile.it/blog/ita/git-flow-semantic-versioning/](https://engineering.facile.it/blog/ita/git-flow-semantic-versioning/)