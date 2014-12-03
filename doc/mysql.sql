create database district9;
use district9;

create table groups(id int(11) unsigned NOT NULL AUTO_INCREMENT primary key, name varchar(20) not null, permission int(11));

create table users(id int(11) unsigned NOT NULL AUTO_INCREMENT primary key, username varchar(20) not null, userpass varchar(64) not null, salt varchar(32) not null, name varchar(50), mail varchar(50), joined datetime, groupid int(11) references groups(id));

create table cookies(id int(11) unsigned NOT NULL AUTO_INCREMENT primary key, userid int(11) not null references users(id), hash varchar(64) not null, created datetime);

create table dockers(id varchar(32) NOT NULL unique, userid int(11) not null references users(id), name varchar(32) not null, port int(5), created datetime);

create table sessions(id varchar(32) NOT NULL unique, data text not null, created datetime);
