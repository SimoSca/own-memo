---
title:      Linux
permalink:  /topics/linux/
---


Nozioni Generali
----------------

- `shebang` ovvero la sequenza `#!` all'inizio di molti script di shell. Vedi [Wikipedia](https://it.wikipedia.org/wiki/Shabang) per info.

- `gpg` per criptare e decriptare dati su linux

- [permissions](#permissions)

- [sytemd](#systemd)


Permissions
-----------


### Prepare


As always docker requires well knows usage of permissions relatively to mounted volumes and unix users (docker desktop avoid this
boredom), so I need to perform some action:

Create a pure service user (that is with non default shell, to avoid troubles for running services):

````
# -r will create a system user - one which does not have a password, a home dir and is unable to login
# useradd -r alice

# user without  shell
# useradd -r bob -s /usr/sbin/nologin

# -g www-data -G wheel,developers -> to create user with primary group www-data and secondary wheel,developers 
# useradd -r bob -s /usr/sbin/nologin -g www-data

# Create SYSTEM user (for service, like www-data): in this case bob hash group "nogroup:x:65534:" 
# adduser --system --no-create-home bob

# So I wanna that my services could run with "enomis" both user and group:
adduser --system --group enomis --no-create-home
# since my login user is "ubuntu" I add "ubuntu to the group
usermod -a -G enomis ubuntu
# and I wanna run docker with that user, so if not yet runned:
usermod -a -G docker ubuntu

# If I wanna remove an "user" from a "group"
# gpasswd -d user group
````


Eventually you can force this to avoid GID or UID overlaps between docker images and host server:
````
### USER AND GROUPS
# Trick to avoid users conflicts, for example id 1000 could exists before adding the logotel 1000 for docker running in ubuntu server ; adduser forces the indexing...
RUN sed -i "s#FIRST_UID=.*#FIRST_UID=10000#g ; s#FIRST_GID=.*#FIRST_GID=10000#g" /etc/adduser.conf \
&& adduser --disabled-password --gecos "" enomis
````


### DEFAULT PERMISSIONS

You have to thinking about `umask` and `acl` to understand `chown` and `chmod`.

First of all install `acl` to gain `getfacl` and similar commands

````
apt install acl
````

so you can run simething like

````
getfacl --all-effective .
````

Use the setgid to perform a right ownership whant create new files:

> Setting the setgid permission on a directory ("chmod g+s") causes new files and subdirectories created within it to inherit its group ID, rather than the primary group ID of the user who created the file (the owner ID is never affected, only the group ID).

Some example:

````
$ umask 002            # allow group write; everyone must do this (that is every user have to set own umask)
$ chgrp GROUPNAME .    # set directory group to GROUPNAME
$ chmod g+s .          # files created in directory will be in group GROUPNAME
````

Note that you have to do the `chgrp/chmod` for every subdirectory; it doesn't propagate automatically (that is, neither existing nor subsequently created directories under a setgid directory will be setgid, although the latter will be in group GROUPNAME).

Also note that `umask` is a process attribute and applies to all files created by that process and its children (which inherit the `umask` in effect in their parent at `fork()` time). Users may need to set this in `~/.profile`, and may need to watch out for things unrelated to your directory that need different permissions. modules may be useful if you need different settings when doing different things.

You can control things a bit better if you can use `POSIX` ACLs; it should be possible to specify both a permissions mask and a group, and have them propagate sensibly. Support for POSIX ACLs is somewhat variable, though.


As alternative to `chmod` you can run

````
setfacl -d -m group:GROUPNAME:rwx /path/to/directory
setfacl -m group:GROUPNAME:rwx /path/to/directory
````

In this case you can see the `reset_permissions.sh` script to check what I've done to manage the ownership/permissions.


#### UMASK

Some example to better understand:

````
umask
````

Get actual umask.

Some umask tests:

````
ubuntu@ubuntu:~/lol$ umask 0002
ubuntu@ubuntu:~/lol$ touch test1
ubuntu@ubuntu:~/lol$ mkdir dir_test1
ubuntu@ubuntu:~/lol$ umask 0770
ubuntu@ubuntu:~/lol$ touch test2
ubuntu@ubuntu:~/lol$ mkdir dir_test2
ubuntu@ubuntu:~/lol$ umask 0730
ubuntu@ubuntu:~/lol$ touch test3
ubuntu@ubuntu:~/lol$ mkdir dir_test3
ubuntu@ubuntu:~/lol$ umask 0007
ubuntu@ubuntu:~/lol$ touch test4
ubuntu@ubuntu:~/lol$ mkdir dir_test4
ubuntu@ubuntu:~/lol$ ls -l
total 12
drwxrwxr-x 2 ubuntu ubuntu 4096 Nov  2 15:23 dir_test1
d------rwx 2 ubuntu ubuntu 4096 Nov  2 15:23 dir_test2
d---r--rwx 2 ubuntu ubuntu 4096 Nov  2 15:23 dir_test3
drwxrwx--- 2 ubuntu ubuntu 4096 Nov  2 15:27 dir_test4/
-rw-rw-r-- 1 ubuntu ubuntu    0 Nov  2 15:23 test1
-------rw- 1 ubuntu ubuntu    0 Nov  2 15:23 test2
----r--rw- 1 ubuntu ubuntu    0 Nov  2 15:23 test3
-rw-rw---- 1 ubuntu ubuntu    0 Nov  2 15:27 test4
````

So every time the default permissions changes, but is per `user, for example if i use another user with umask 00

````
# ubuntu has umask 0007
ubuntu@ubuntu:~/lol$ cd dir_test4/
ubuntu@ubuntu:~/lol/dir_test4$ touch ubuntu.test
# enter to the folder with "enomis" user that belongs to "ubuntu" group and has umask 0555
enomis@ubuntu:/home/ubuntu/lol/dir_test4$ ls -l
total 0
--w--w--w- 1 enomis enomis 0 Nov  2 15:36 enomis.test
-rw-rw---- 1 ubuntu ubuntu 0 Nov  2 15:36 ubuntu.test
````

As you can see the `umask` is a `per user` setting, not `per directory`.

**If you wanna work `per directory` than you have to use `chmod` with the `s` flag!


#### GIT EXAMPLE

When you run "git pull", the permissions are "per process", that is you have to pay attention in `umask`!

For example, suppose that locally I create, add to git and commit this files (see permissions):

````
drwxr-xr-x  3 my_user  my_group    96B Nov  2 16:48 test2
-rw-r--r--  1 my_user  my_group     0B Nov  2 16:48 test2.txt
```` 

and that on server I'm pulling using an user with `umask` to `0077`, than the resulting generated files are:

````
drwsrwsr-x  7 enomis enomis  4096 Nov  2 15:50 ./
drwx--S---  2 ubuntu enomis  4096 Nov  2 15:50 test2/
-rw-------  1 ubuntu enomis     0 Nov  2 15:50 test2.txt
````

which are precisely the permissions related to `0077`!

> The `S` flag is related to `chmod g+s`: in fact the user performing the pull is `ubuntu`, but the folder's parent group id `enomis`, that's becaus the new generated files has that group!

**So in production if you have a shared applicative folder in which each user can operate over `git`, to avoid problems on permissions, could be better if each user set proper umask as `0007` or `0002` based on the applicative.**


### RM - COMMAND and PERMISSIONS

Until recently I believed that to REMOVE files or directories the user should have `write` permissions over that file/directory, but ti was wrong!
What really is important is the permission over the `parent`:

> You need write permission for the direct parent directory and execute permission for all parent directories [cit. from stackoverflow]


For example if I've a folder with permission 773, and I go to that folder with a "other" user (that is neither owner nor in folder's group ownership),
and in this folder there's a file with permission 600, then "other" user CAN DELETE that file... amazing!!!


 
SYSTEMD
-------

Systemd is a very powerful system to manage services and dependency.

For basic usage you have just to setup a specific `/etc/stystemd/system/<my>.service` file, 
or for advanced boot/power-on scopes you can also take advantage of parallel services starting via `socket`, both network and unix socket domain.

In this section it will be treat:

- [Basic Examples](#basic-examples), with extra notes
- [Unix Socket Domain](#unix-socket-domain)

### Basic Examples


#### Example OneShot - 1

Whit a service like this:

````editorconfig
[Service]
TimeoutStartSec=0
Restart=always
RestartSec=60s
Type=notify
NotifyAccess=all
ExecStart=/enomis-raspy/infrastructure/init_all.sh
````
The `init_all.sh` command should be a process without termination.

If process terminates (or exit with some status code), after `RestartSec` seconds the process will retry to start, according to `Restart=always` option.

`systemctl start enomis` will output

````
Job for enomis.service failed because the service did not take the steps required by its unit configuration.
See "systemctl status enomis.service" and "journalctl -xe" for details.
````

#### Example OneShot - 2

Whit a service like this:

````editorconfig
[Service]
TimeoutStartSec=0
Type=notify
NotifyAccess=all
ExecStart=/enomis-raspy/infrastructure/init_all.sh
````
The `init_all.sh` command should be a process without termination.

`systemctl start enomis` will output

````
Job for enomis.service failed because the service did not take the steps required by its unit configuration.
See "systemctl status enomis.service" and "journalctl -xe" for details.
````

In this case `systemd` doesn't try to restart because there's no `Restart` option, but `systemctl` consider it as a failure
(same output of previous test).

#### Example OneShot - 3 - It works!

Whit a service like this:

````editorconfig
[Service]
TimeoutStartSec=0
Type=oneshot
NotifyAccess=all
ExecStart=/enomis-raspy/infrastructure/init_all.sh
````
The `init_all.sh` command should be a process without termination.

`systemctl start enomis` will output nothing and `echo $?` returns 0: that is it works and is right conumed by `systemctl`!

Changing `Type=oneshot` is the solution.

`systemctl status enomis` shows `Active: inactive (dead)`.

Eventually, you can specify **RemainAfterExit=true** so that systemd considers the service as active after the setup action is successfully finished, that is:

````editorconfig
[Service]
TimeoutStartSec=0
Type=oneshot
RemainAfterExit=true
NotifyAccess=all
ExecStart=/enomis-raspy/infrastructure/init_all.sh
````
and this time `systemctl status enomis` shows `Active: active (exited) since Sat 2020-11-07 14:17:19 UTC; 15s ago`,
that is consider as active even if the script is exited!

#### Example Daemon - 1

````editorconfig
[Service]
TimeoutStartSec=0
# the service will be restarted regardless of whether it exited cleanly or not, got terminated abnormally by a signal, or hit a timeout.
Restart=always
RestartSec=60s
# Consider to use "forking" to avid command to stay appended into shell (init_all_systemd.sh is basically a daemon
Type=notify
NotifyAccess=all
KillMode=process
ExecStart=/enomis-raspy/infrastructure/init_all_systemd.sh
````

In this case the script runs as daemon, and I have the follows:

- if start via `systemctl start enomis` or `systemctl restart enomis` the script is runned and attached to current shell,
  this means that that does not background its self and stays attached to the shell.
    - Performing `ctrl+c` doesn't cause `stop` of comman
    - I need to run `systemctl stop enomis` to effectively stop the process (see `systemctl status enomis`)

- `reboot` the system works right and `systemctl` starts the service `enomis` as expected

#### Example Daemon - 2

````editorconfig
[Service]
TimeoutStartSec=0
# the service will be restarted regardless of whether it exited cleanly or not, got terminated abnormally by a signal, or hit a timeout.
Restart=always
RestartSec=60s
Type=forking
PIDFile=/var/run/enomis/enomis.pid
NotifyAccess=all
KillMode=process
ExecStart=/enomis-raspy/infrastructure/init_all_systemd.sh
````
In this case systemd expects that the main commands forked itself... and should be the command itself to take care of creting and manging the PID file

````shell
#!/bin/bash

# Run the following code in background:
(
    while keep_running; do
        do_something
    done
) &

# Write pid of the child to the pidfile:
echo "$!" >/run/daemon.pid
exit
````

and you should set some extra options into service, like

````editorconfig
[Service]
# ... all previous directives
ExecReload=/bin/kill -1 -- $MAINPID
ExecStop=/bin/kill -- $MAINPID
````

**but this usage is discouraged by `systemd`** because is predominantly used into legacy service.

A well explanation [here](https://unix.stackexchange.com/questions/460321/which-pid-belongs-into-the-systemd-pidfile-section-when-creating-a-shell-script).

#### RECAP

> Using `Restart` with standard commands (intendeed as processes that do something and then terminates) or daemon,
is very similar to what happens using `supervisord` linux utility.


#### REFS

- [Good docker tutorial](https://blog.container-solutions.com/running-docker-containers-with-systemd), NON TRIVIAL

- Some useful site in Google via `systemctl start docker container`



### Unix Socket Domain