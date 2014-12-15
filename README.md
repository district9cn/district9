district9
=========

1.run apache
install apache2:
sudo apt-get install apache2
put district9 to apache work directory:

bin117@bin117-MS-7577:~/workspace/district9$ ll /var/www/district9/
总用量 84
drwxrwxr-x 2 bin117 bin117  4096 12月  4 11:38 control
drwxrwxr-x 2 bin117 bin117  4096 12月  4 11:38 core
drwxrwxr-x 2 bin117 bin117  4096 12月  4 11:38 doc
drwxrwxr-x 4 bin117 bin117  4096 12月  4 11:38 etc
-rw-rw-r-- 1 bin117 bin117   105 12月  4 11:38 index.php
-rw-rw-r-- 1 bin117 bin117 35122 12月  4 11:38 LICENSE
drwxrwxrwx 3 root   root    4096 12月  4 13:56 log
drwxrwxr-x 2 bin117 bin117  4096 12月  4 11:38 model
drwxrwxr-x 3 bin117 bin117  4096 12月  4 11:38 plog
-rw-rw-r-- 1 bin117 bin117    75 12月  4 11:38 README.md
drwxrwxr-x 4 bin117 bin117  4096 12月  4 11:38 static
drwxrwxrwx 2 root   root    4096 12月  4 13:55 temp
drwxrwxr-x 2 bin117 bin117  4096 12月  5 17:43 view

make sure log and temp primission is 777(drwxrwxrwx)

2.create database
execute doc/mysql.sql

3.install ssh2
execute doc/ssh2

4.run wsshd
bin117@bin117-MS-7577:/mnt/Download$ cd wssh-master
bin117@bin117-MS-7577:/mnt/Download/wssh-master$ ls bin/
wssh  wsshd
bin117@bin117-MS-7577:/mnt/Download/wssh-master$ ./bin/wsshd 
wsshd/0.1.0 running on 0.0.0.0:5000
--------------------------------------------------------------------------------
DEBUG in wsshd [./bin/wsshd:27]:
127.0.0.1 -> root@192.168.1.42: [interactive shell]

5.config file
vi core/conf.php

