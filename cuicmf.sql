-- MySQL dump 10.13  Distrib 5.7.21, for Linux (x86_64)
--
-- Host: localhost    Database: cuicmf
-- ------------------------------------------------------
-- Server version	5.7.21-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `1cui_addon`
--

DROP TABLE IF EXISTS `1cui_addon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `1cui_addon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL DEFAULT '' COMMENT '插件名或标识',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '中文名称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '插件描述',
  `config` text NOT NULL COMMENT '配置',
  `author` varchar(40) NOT NULL DEFAULT '' COMMENT '作者',
  `version` varchar(20) NOT NULL DEFAULT '' COMMENT '版本号',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='插件表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `1cui_addon`
--

LOCK TABLES `1cui_addon` WRITE;
/*!40000 ALTER TABLE `1cui_addon` DISABLE KEYS */;
INSERT INTO `1cui_addon` VALUES (1,'System','后台信息','后台首页信息显示','{\"display\":\"1\"}','cuiyuanxin','0.1',1,1500036394,1500036394),(2,'Security','日常维护','后台首页日常维护','{\"display\":\"1\"}','cuiyuanxin','0.1',1,1501424281,1501424281);
/*!40000 ALTER TABLE `1cui_addon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `1cui_hook`
--

DROP TABLE IF EXISTS `1cui_hook`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `1cui_hook` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `description` text COMMENT '描述',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `addons` varchar(255) NOT NULL DEFAULT '' COMMENT '钩子挂载的插件 ''，''分割',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '钩子状态',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `name` (`name`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `1cui_hook`
--

LOCK TABLES `1cui_hook` WRITE;
/*!40000 ALTER TABLE `1cui_hook` DISABLE KEYS */;
INSERT INTO `1cui_hook` VALUES (1,'AdminIndex','首页小格子个性化显示',1499678706,'System,Security',1),(2,'Email','邮件调用钩子',0,'',1);
/*!40000 ALTER TABLE `1cui_hook` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cui_action`
--

DROP TABLE IF EXISTS `cui_action`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cui_action` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '行为唯一标识',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '行为说明',
  `remark` char(140) NOT NULL DEFAULT '' COMMENT '行为描述',
  `rule` text COMMENT '行为规则',
  `log` text COMMENT '日志规则',
  `type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='系统行为表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cui_action`
--

LOCK TABLES `cui_action` WRITE;
/*!40000 ALTER TABLE `cui_action` DISABLE KEYS */;
INSERT INTO `cui_action` VALUES (12,'user_login','登录','权限组内用户登录','','[username|user_auth]在[date]登录了后台',1,1,1523955976),(13,'change_password','修改密码','修改后台登录密码','','[username|user_auth]在[date]修改后台登录密码',2,1,1524036788),(14,'update_config','更新系统配置','更新系统配置','','[username|user_auth]在[date]更新系统配置',1,1,1524037276),(15,'config','配置','添加/编辑配置','','[username|user_auth]在[date]添加/编辑配置',1,1,1524037767),(16,'change_status_config','配置状态','启用/禁用/删除配置','','[username|user_auth]在[date]启用/禁用/删除了配置',1,1,1524039890),(17,'update_rule_menu','后台菜单','添加/编辑后台菜单','','[username|user_auth]在[date]添加/编辑后台菜单',1,1,1524043287),(18,'change_status_rule_menu','菜单状态','启用/禁用/显示/隐藏/检查/不检查/删除后台菜单','','[username|user_auth]在[date]启用/禁用/显示/隐藏/检查/不检查/删除后台菜单',1,1,1524043614),(19,'update_user','管理员','添加/编辑管理员','','[username|user_auth]在[date]添加/编辑管理员',1,1,1524047252),(20,'change_status_user','管理员状态','启用/禁用/删除管理员','','[username|user_auth]在[date]启用/禁用/删除管理员',1,1,1524047867);
/*!40000 ALTER TABLE `cui_action` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cui_action_log`
--

DROP TABLE IF EXISTS `cui_action_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cui_action_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `action_id` int(10) DEFAULT NULL,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '执行用户id',
  `action_ip` bigint(20) NOT NULL COMMENT '执行行为者ip',
  `model` varchar(50) NOT NULL DEFAULT '' COMMENT '触发行为的表',
  `record_id` varchar(50) DEFAULT NULL,
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '日志备注',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '操作url',
  `method` varchar(10) NOT NULL DEFAULT '' COMMENT '请求类型',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '执行行为的时间',
  PRIMARY KEY (`id`),
  KEY `action_ip_ix` (`action_ip`),
  KEY `action_id_ix` (`action_id`),
  KEY `user_id_ix` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='行为日志表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cui_action_log`
--

LOCK TABLES `cui_action_log` WRITE;
/*!40000 ALTER TABLE `cui_action_log` DISABLE KEYS */;
INSERT INTO `cui_action_log` VALUES (3,12,1,2130706433,'user','1','cuiyuanxin在2018-04-17 18:49:56登录了后台','/admin/login/login.html','POST',1,1523962196),(4,12,1,2130706433,'user','1','cuiyuanxin在2018-04-18 11:29:44登录了后台','/admin/login/login.html','POST',1,1524022184),(5,12,1,2130706433,'user','1','cuiyuanxin在2018-04-18 12:01:31登录了后台','/admin/action/index.html','GET',1,1524024091),(6,12,1,2130706433,'user','1','cuiyuanxin在2018-04-18 14:53:14登录了后台','/admin/login/login.html','POST',1,1524034394),(7,12,1,2130706433,'user','1','cuiyuanxin在2018-04-18 14:57:59登录了后台','/admin/login/login.html','POST',1,1524034679),(8,12,1,2130706433,'user','1','cuiyuanxin在2018-04-18 14:58:21登录了后台','/admin/action/index.html','GET',1,1524034701),(9,12,1,2130706433,'user','1','cuiyuanxin在2018-04-18 15:20:33登录了后台','/admin/action/index.html','GET',1,1524036033),(10,12,1,2130706433,'user','1','cuiyuanxin在2018-04-18 15:26:33登录了后台','/admin/action/index.html','GET',1,1524036393),(11,12,1,2130706433,'user','1','cuiyuanxin在2018-04-18 15:33:29登录了后台','/admin/action/index.html','GET',1,1524036809),(12,13,1,2130706433,'user','1','cuiyuanxin在2018-04-18 15:34:06修改后台登录密码','/admin/user/change_password.html','POST',1,1524036846),(13,12,1,2130706433,'user','1','cuiyuanxin在2018-04-18 15:34:24登录了后台','/admin/login/login.html','POST',1,1524036864),(14,12,1,2130706433,'user','1','cuiyuanxin在2018-04-18 15:39:54登录了后台','/admin/action/index.html','GET',1,1524037194),(15,12,1,2130706433,'user','1','cuiyuanxin在2018-04-18 15:40:51登录了后台','/admin/action/index.html','GET',1,1524037251),(16,12,1,2130706433,'user','1','cuiyuanxin在2018-04-18 15:41:18登录了后台','/admin/action/index.html','GET',1,1524037278),(17,14,1,2130706433,'config','1','cuiyuanxin在2018-04-18 15:44:08更新系统配置','/admin/system/write_save/group_id/1.html','POST',1,1524037448),(18,12,1,2130706433,'user','1','cuiyuanxin在2018-04-18 15:49:28登录了后台','/admin/action/index.html','GET',1,1524037768),(19,15,1,2130706433,'config','2','cuiyuanxin在2018-04-18 15:49:43添加/编辑配置','/admin/system/write_configuration.html','POST',1,1524037783),(20,12,1,2130706433,'user','1','cuiyuanxin在2018-04-18 16:24:52登录了后台','/admin/action/index.html','GET',1,1524039892),(21,16,1,2130706433,'config','22','cuiyuanxin在2018-04-18 16:27:11启用/禁用/删除了配置','/admin/system/change_status/method/forbid/id/22.html','GET',1,1524040031),(22,16,1,2130706433,'config','22','cuiyuanxin在2018-04-18 16:29:43启用/禁用/删除了配置','/admin/system/change_status/method/resume/id/22.html','GET',1,1524040183),(23,12,1,2130706433,'user','1','cuiyuanxin在2018-04-18 17:21:29登录了后台','/admin/action/index.html','GET',1,1524043289),(24,17,1,2130706433,'auth_rule','1','cuiyuanxin在2018-04-18 17:22:10添加/编辑后台菜单','/admin/menu/write_menu.html','POST',1,1524043330),(25,12,1,2130706433,'user','1','cuiyuanxin在2018-04-18 17:26:56登录了后台','/admin/action/index.html','GET',1,1524043616),(26,18,1,2130706433,'auth_rule','1','cuiyuanxin在2018-04-18 17:27:01启用/禁用/显示/隐藏/检查/不检查/删除后台菜单','/admin/menu/change_status/method/checked/filed/inspect/id/1.html','GET',1,1524043621),(27,18,1,2130706433,'auth_rule','1','cuiyuanxin在2018-04-18 17:27:06启用/禁用/显示/隐藏/检查/不检查/删除后台菜单','/admin/menu/change_status/method/unchecked/filed/inspect/id/1.html','GET',1,1524043626),(28,12,1,2130706433,'user','1','cuiyuanxin在2018-04-18 17:46:08登录了后台','/admin/login/login.html','POST',1,1524044768),(29,12,1,2130706433,'user','1','cuiyuanxin在2018-04-19 10:34:30登录了后台','/admin/login/login.html','POST',1,1524105270),(30,20,1,2130706433,'user','14','cuiyuanxin在2018-04-19 10:35:29启用/禁用/删除管理员','/admin/user/change_status/method/forbid/id/14.html','GET',1,1524105329),(31,20,1,2130706433,'user','14','cuiyuanxin在2018-04-19 10:35:42启用/禁用/删除管理员','/admin/user/change_status/method/resume/id/14.html','GET',1,1524105342),(32,19,1,2130706433,'user','14','cuiyuanxin在2018-04-19 10:35:53添加/编辑管理员','/admin/user/write_user.html','POST',1,1524105353),(33,12,1,2130706433,'user','1','cuiyuanxin在2018-04-19 11:46:21登录了后台','/admin/login/login.html','POST',1,1524109581),(34,20,1,2130706433,'user','14,13,12,11','cuiyuanxin在2018-04-19 16:18:12启用/禁用/删除管理员','/admin/user/change_status/method/forbid.html','POST',1,1524125892),(35,20,1,2130706433,'user','14,13,12,11','cuiyuanxin在2018-04-19 16:18:17启用/禁用/删除管理员','/admin/user/change_status/method/resume.html','POST',1,1524125897);
/*!40000 ALTER TABLE `cui_action_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cui_auth_group`
--

DROP TABLE IF EXISTS `cui_auth_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cui_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限组id,自增主键',
  `module` varchar(20) NOT NULL DEFAULT '' COMMENT '权限组所属模块',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `description` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
  `rules` varchar(500) NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id，多个规则 , 隔开',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cui_auth_group`
--

LOCK TABLES `cui_auth_group` WRITE;
/*!40000 ALTER TABLE `cui_auth_group` DISABLE KEYS */;
INSERT INTO `cui_auth_group` VALUES (1,'admin','超级管理员','拥有所有的管理权限',1,''),(2,'admin','管理员','拥有大部分管理权限',1,'1,2,34,43,3,4,5,10,11,12,13,14,15,16,17,18,46,6,24,25,26,27,28,29,30,44'),(3,'admin','微信管理员','拥有所有微信相关的权限',1,''),(4,'admin','编辑','只能发布文章',1,'');
/*!40000 ALTER TABLE `cui_auth_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cui_auth_group_access`
--

DROP TABLE IF EXISTS `cui_auth_group_access`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cui_auth_group_access` (
  `uid` int(11) unsigned NOT NULL COMMENT '用户id',
  `group_id` mediumint(8) unsigned NOT NULL COMMENT '用户组id',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `group_id` (`group_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='用户组明细表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cui_auth_group_access`
--

LOCK TABLES `cui_auth_group_access` WRITE;
/*!40000 ALTER TABLE `cui_auth_group_access` DISABLE KEYS */;
INSERT INTO `cui_auth_group_access` VALUES (7,2),(8,2),(9,2),(10,2),(11,2),(12,4),(13,4),(14,4);
/*!40000 ALTER TABLE `cui_auth_group_access` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cui_auth_rule`
--

DROP TABLE IF EXISTS `cui_auth_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cui_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '父级id',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文名称',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `module` varchar(20) NOT NULL DEFAULT 'admin' COMMENT '模块',
  `url` varchar(100) NOT NULL DEFAULT '' COMMENT '规则地址',
  `condition` char(100) NOT NULL DEFAULT '' COMMENT '附加queryString',
  `hide` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否显示到菜单',
  `inspect` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '权限检查',
  `icon` varchar(45) NOT NULL DEFAULT '' COMMENT '图标',
  `tip` varchar(100) NOT NULL DEFAULT '' COMMENT '菜单说明',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态：为1正常，为0禁用',
  `level` tinyint(1) NOT NULL DEFAULT '1' COMMENT '菜单层级',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `title` (`title`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='规则表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cui_auth_rule`
--

LOCK TABLES `cui_auth_rule` WRITE;
/*!40000 ALTER TABLE `cui_auth_rule` DISABLE KEYS */;
INSERT INTO `cui_auth_rule` VALUES (1,0,'首页',0,'admin','Index/index','',1,0,'fa fa-dashboard','首页',1,1),(2,0,'系统',1,'admin','System/index','',1,1,'fa fa-gear','系统',1,1),(3,2,'菜单管理',30,'admin','Menu/index','',1,1,'','菜单管理',1,2),(4,3,'菜单列表',100,'admin','Menu/index','',1,1,'','菜单列表',1,3),(5,3,'添加菜单',101,'admin','Menu/create_menu','',1,1,'','添加菜单',1,3),(6,0,'会员管理',2,'admin','Member/index','',1,1,'fa fa-group','会员管理',1,1),(7,6,'权限管理',20,'admin','Auth/index','',1,1,'','权限管理',1,2),(8,7,'权限列表',100,'admin','Auth/index','',1,1,'','权限列表',1,3),(9,7,'添加权限',101,'admin','Auth/create_group','',1,1,'','添加权限',1,3),(10,3,'编辑菜单',102,'admin','Menu/edit_menu','',0,1,'','编辑菜单',1,3),(11,3,'复制菜单',103,'admin','Menu/copy_menu','',0,1,'','复制菜单',1,3),(12,3,'启用菜单',104,'admin','Menu/change_status?method=resume','',0,1,'','启用菜单',1,3),(13,3,'禁用菜单',105,'admin','Menu/change_status?method=forbid','',0,1,'','禁用菜单',1,3),(14,3,'显示菜单',106,'admin','Menu/change_status?method=show&filed=hide','',0,1,'','显示菜单',1,3),(15,3,'隐藏菜单',107,'admin','Menu/change_status?method=hide&filed=hide','',0,1,'','隐藏菜单',1,3),(16,3,'检查权限',108,'admin','Menu/change_status?method=checked&filed=inspect','',0,1,'','检查权限',1,3),(17,3,'不检查权限',109,'admin','Menu/change_status?method=unchecked&filed=inspect','',0,1,'','不检查权限',1,3),(18,3,'删除菜单',110,'admin','Menu/change_status?method=delete&all=all','',0,1,'','删除菜单',1,3),(19,3,'排序菜单',111,'admin','Menu/sort_menu','',0,1,'','排序菜单',1,3),(20,7,'编辑权限',102,'admin','Auth/edit_group','',0,1,'','编辑权限',1,3),(21,7,'启用权限',103,'admin','Auth/change_status?method=resume','',0,1,'','启用权限',1,3),(22,7,'禁用权限',104,'admin','Auth/change_status?method=forbid','',0,1,'','禁用权限',1,3),(23,7,'删除权限',105,'admin','Auth/change_status?method=delete','',0,1,'','删除权限',1,3),(24,6,'管理员管理',10,'admin','User/index','',1,1,'','管理员管理',1,2),(25,24,'管理员列表',100,'admin','User/index','',1,1,'','管理员列表',1,3),(26,24,'添加管理员',101,'admin','User/create_user','',1,1,'','添加管理员',1,3),(27,24,'编辑管理员',102,'admin','User/edit_user','',0,1,'','编辑管理员',1,3),(28,24,'启用管理员',103,'admin','User/change_status?method=resume','',0,1,'','启用管理员',1,3),(29,24,'禁用管理员',104,'admin','User/change_status?method=forbid','',0,1,'','禁用管理员',1,3),(30,24,'删除管理员',105,'admin','User/change_status?method=delete','',0,1,'','删除管理员',1,3),(31,2,'配置管理',20,'admin','System/configuration','',1,1,'','配置管理',1,2),(32,31,'配置列表',100,'admin','System/configuration','',1,1,'','配置列表',1,3),(33,31,'添加配置',101,'admin','System/create_configuration','',1,1,'','添加配置',1,3),(34,2,'系统设置',10,'admin','System/index','',1,1,'','系统设置',1,2),(35,2,'数据库管理',40,'admin','Database/index','',1,1,'','菜单管理',1,2),(36,35,'数据库备份',100,'admin','Database/index','',1,1,'','数据库备份',1,3),(37,35,'数据库还原',101,'admin','Database/import','',1,1,'','数据库还原',1,3),(38,31,'编辑配置',102,'admin','System/edit_configuration','',0,1,'','编辑配置',1,3),(39,31,'启用配置',104,'admin','System/change_status?method=resume','',0,1,'','启用配置',1,3),(40,31,'禁用配置',105,'admin','System/change_status?method=forbid','',0,1,'','禁用配置',1,3),(41,31,'删除配置',106,'admin','System/change_status?method=delete','',0,1,'','删除配置',1,3),(42,7,'菜单权限',106,'admin','Auth/rule_group','',0,1,'','菜单权限',1,3),(43,34,'设置保存',100,'admin','System/write_save','',0,1,'','设置保存',1,3),(44,24,'保存管理员',106,'admin','User/write_user','',0,1,'','保存管理员',1,3),(45,31,'保存配置',107,'admin','System/write_configuration','',0,1,'','保存配置',1,3),(46,3,'保存菜单',112,'admin','Menu/write_menu','',0,1,'','保存菜单',1,3),(47,35,'优化数据表',102,'admin','Database/optimize','',0,1,'','优化数据表',1,3),(48,35,'修复数据表',103,'admin','Database/repair','',0,1,'','修复数据表',1,3),(49,35,'备份数据库',104,'admin','Database/export','',0,1,'','备份数据库',1,3),(50,35,'还原数据库',105,'admin','Database/restore','',0,1,'','还原数据库',1,3),(51,35,'删除数据库',106,'admin','Database/del','',0,1,'','删除数据库',1,3),(52,7,'保存权限组',107,'admin','Auth/write_group','',0,1,'','保存权限组',1,3),(53,2,'行为管理',50,'admin','Action/index','',1,1,'','行为管理',1,2),(54,53,'行为列表',100,'admin','Action/index','',1,1,'','行为列表',1,3),(55,53,'添加行为',101,'admin','Action/create_action','',0,1,'','添加行为',1,3),(56,53,'编辑行为',102,'admin','Action/edit_action','',0,1,'','编辑行为',1,3),(57,53,'启用行为',104,'admin','Action/change_status?method=resume','',0,1,'','启用行为',1,3),(58,53,'禁用行为',105,'admin','Action/change_status?method=forbid','',0,1,'','禁用行为',1,3),(59,53,'删除行为',106,'admin','Action/change_status?method=delete','',0,1,'','删除行为',1,3),(60,53,'保存行为',107,'admin','Action/write_action','',0,1,'','保存行为',1,3),(61,53,'行为日志',108,'admin','Action/actionlog','',1,1,'','行为日志',1,3),(62,53,'行为日志清空',109,'admin','Action/action_clear','',0,1,'','行为日志清空',1,3),(63,53,'行为日志详情',110,'admin','Action/action_details','',0,1,'','行为日志详情',1,3),(64,53,'行为日志删除',111,'admin','Action/actionlog_del','',0,1,'','行为日志删除',1,3);
/*!40000 ALTER TABLE `cui_auth_rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cui_config`
--

DROP TABLE IF EXISTS `cui_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cui_config` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '配置标识',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '配置标题',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型',
  `group` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置分组',
  `value` text COMMENT '配置值',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '配置项',
  `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '配置说明',
  `sort` mediumint(8) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uk_name` (`name`) USING BTREE,
  KEY `type` (`type`) USING BTREE,
  KEY `group` (`group`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cui_config`
--

LOCK TABLES `cui_config` WRITE;
/*!40000 ALTER TABLE `cui_config` DISABLE KEYS */;
INSERT INTO `cui_config` VALUES (1,'config_group_list','配置分组',3,2,'1:基本\r\n2:系统\r\n3:上传\r\n4:数据库','','配置分组',0,1515742043,1520923151,1),(2,'config_type_list','配置类型',3,2,'0:数字\n1:字符\n2:文本\n3:数组\n4:枚举\n5:多选枚举\n6:单图片\n7:多图片\n8:单文件\n9:多文件\n10:富文本\n11:单选\n12:多选\n13:日期\n14:时间\n15:颜色\n16:音频\n17:视频','','主要用于数据解析和页面表单的生成',0,1516012393,1524037783,1),(3,'admin_allow_ip','后台允许访问IP',2,2,'','','多个用逗号分隔，如果不配置表示不限制IP访问',0,1516012517,1521013592,1),(4,'allow_visit','不受限控制器方法',3,2,'0:Ajax/filepath\r\n1:Ajax/index\r\n2:Ajax/upload\r\n3:User/change_password\r\n4:System/clear','','不受限控制器方法',0,1516012781,1516956164,1),(5,'deny_visit','超管专限控制器方法',3,2,'','','仅超级管理员可访问的控制器方法',0,1516012820,1516956181,1),(6,'image_ext','图片文件格式',3,3,'0:jpg\n1:jpeg\n2:png\n3:gif\n4:bmp','','允许上传的图片文件格式',0,1517473580,1517908208,1),(7,'upload_driver','上传驱动',11,3,'0','local:本地\nqiniu:七牛','图片或文件上传驱动',0,1517908455,1520848872,1),(8,'ceshi','测试',0,1,'50','','',0,1517914439,1517914508,1),(9,'ceshi1','测试1',1,1,'测试文本','','',0,1517914536,1517914536,1),(10,'ceshi2','ceshi2',2,1,'测试多行文本','','',0,1517914550,1517914550,1),(11,'ceshi3','ceshi3',3,1,'0:1\r\n1:2','','',0,1517914567,1517914567,1),(12,'ceshi4','ceshi4',4,1,'0','0:aaa,1:bb','',0,1517914618,1517971587,1),(13,'ceshi5','ceshi5',5,1,'0,1','0:aaaa,1:bbbbbbbbb,2:ccccccc','',0,1517971958,1517982188,1),(14,'ceshi6','ceshi6',6,1,'16','','',0,1517971977,1517971977,1),(15,'ceshi7','ceshi7',7,1,'1,2,3','','',0,1517971991,1517971991,1),(16,'ceshi8','ceshi8',8,1,'10','','',0,1517972019,1517972019,1),(17,'ceshi9','ceshi9',9,1,'8,10','','',0,1517972033,1517972033,1),(18,'ceshi10','ceshi10',10,1,'<p>测试富文本</p>','','',0,1517972050,1517972050,1),(19,'ceshi11','ceshi11',11,1,'0','0:aaaaaaaaaaa,1:bbbbbbbbbbbbbbb,2:cccccccccc,4:dddddddd','',0,1517972101,1517985480,1),(20,'ceshi12','ceshi12',12,1,'0,4','0:aaaaaaaaaaa,1:bbbbbbbbbbbbbbb,2:cccccccccc,4:dddddddd','',0,1517972118,1517985144,1),(21,'ceshi13','ceshi13',13,1,'1518019200','','',0,1517972133,1517972133,1),(22,'ceshi14','ceshi14',14,1,'1518080943','','',0,1517972152,1517972152,1),(23,'ceshi15','ceshi15',15,1,'#050606','','',0,1517972165,1517972165,1),(24,'file_ext','文件格式',3,3,'0:zip\n1:rar\n2:tar\n3:gz\n4:7z\n5:doc\n6:docx\n7:txt\n8:xml','','常用文件格式',0,1518338799,1518338799,1),(25,'ceshi16','jpaudio',16,1,'7','','jpaudio',0,1520565288,1520565288,1),(26,'ceshi17','ceshi17',17,1,'11','','ceshi17',0,1520565308,1520565308,1),(27,'audio_ext','音频文件格式',3,3,'0:mp3\n1:ogg\n3:wav','','音频文件格式',0,1520589239,1520589239,1),(28,'video_ext','视频文件格式',3,3,'0:mp4\n1:flv\n2:avi\n3:wmv\n4:rm\n5:rmvb\n6:3gp','','视频文件格式',0,1520590704,1520590704,1),(29,'data_backup_path','数据库备份根路径',1,4,'./data/backup/','','数据库备份存放的目录，路径必须以 / 结尾',0,1520923404,1520924848,1),(30,'data_backup_part_size','数据库备份卷大小',0,4,'20971520','','该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M',1,1520925255,1520925255,1),(31,'data_backup_compress','数据库备份文件是否启用压缩',4,4,'1','0:不压缩\n1:启用压缩','压缩备份文件需要PHP环境支持gzopen,gzwrite函数',2,1520925401,1520925401,1),(32,'data_backup_compress_level','数据库备份文件压缩级别',4,4,'9','1:普通\n4:一般\n9:最高','数据库备份文件的压缩级别，该配置在开启压缩时生效',3,1520925547,1520925547,1);
/*!40000 ALTER TABLE `cui_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cui_file`
--

DROP TABLE IF EXISTS `cui_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cui_file` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '文件ID',
  `name` text NOT NULL COMMENT '原始文件名',
  `savename` text NOT NULL COMMENT '保存名称',
  `savepath` text NOT NULL COMMENT '文件保存路径',
  `ext` char(5) NOT NULL DEFAULT '' COMMENT '文件后缀',
  `mime` char(40) NOT NULL DEFAULT '' COMMENT '文件mime类型',
  `size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `location` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '文件保存位置',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '远程地址',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上传时间',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `file_type` enum('1','2','3','4') NOT NULL COMMENT '附件类型1图片2音频3视频0其他文件',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uk_md5` (`md5`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='文件表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cui_file`
--

LOCK TABLES `cui_file` WRITE;
/*!40000 ALTER TABLE `cui_file` DISABLE KEYS */;
INSERT INTO `cui_file` VALUES (4,'59a3e2886bb94','a235ddc03e4a55b38c80f4d5cf0a0dab','/uploads/file/20180308/','mp3','application/octet-stream',2690656,'18217d9322e479b19dacda8b7d7e40d8','b6f78a1252700f234a6830c4911c30283d4408d1',0,'',1520491315,1,'2'),(5,'Monster Summer Game-凝固汽油弹(napa_爱给网_aigei_com','69aad0f0d056cbe679519d8b55bc315d','/uploads/file/20180308/','ogg','application/ogg',198954,'d10cb062b8d6e678cfd1b5e2d32fa2fa','88ffd684a8d4061429b784235129c84025b8e682',0,'',1520502172,1,'2'),(6,'纯雨声','6c4be7867795459f61699852a79415d6','/uploads/file/20180308/','wma','video/x-ms-asf',927365,'31c3fb7d2876ce6d0e69318aa868d140','c05bd8a765cdc8b3ea1ca5639d97f8b215ba9900',0,'',1520502760,1,'2'),(12,'59a3fe76d33c1','10d810e819e3f51c3bd499747cedbafa','/uploads/file/20180312/','3gp','video/3gpp',445825,'dc3364cc8b60af6b50183376ab70d016','5dcc02d2cc62c5a2a923fee07c463e15cd46ba2f',0,'',1520822602,1,'3'),(13,'59a3fe76d33c1','60d65af637e36243e748e5c7a0754a94','/uploads/file/20180312/','avi','video/x-msvideo',864632,'b4e544af58047e98ad36035026826ba5','613e09dd88cf4c51d74ae4b2982bf007569206dc',0,'',1520822728,1,'3'),(14,'59a3fe76d33c1','3ee8525d719d5044882769f9abf290c2','/uploads/file/20180312/','flv','video/x-flv',636575,'3cf7efa20d9ecac23d3c57f37e461368','7d4ed807a40b65d5770e49e6c03c6ce43f4b122a',0,'',1520822740,1,'3'),(15,'59a3fe76d33c1','29e0792f655dfdeac593ae09393bcfe8','/uploads/file/20180312/','wmv','video/x-ms-asf',980393,'71905f48093c620947c5965a1ae24706','df8a0599a9aa45ef29dafe192dc7f862d73db139',0,'',1520826041,1,'3');
/*!40000 ALTER TABLE `cui_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cui_user`
--

DROP TABLE IF EXISTS `cui_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cui_user` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(60) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(64) NOT NULL DEFAULT '' COMMENT '登录密码',
  `name` varchar(45) NOT NULL DEFAULT '' COMMENT '姓名',
  `nickname` varchar(45) NOT NULL DEFAULT '' COMMENT '昵称',
  `email` char(32) NOT NULL DEFAULT '' COMMENT '邮箱',
  `mobile` bigint(11) unsigned NOT NULL COMMENT '手机号',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `reg_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后登录ip',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '用户状态 0：禁用； 1：正常',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `user_login_key` (`username`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cui_user`
--

LOCK TABLES `cui_user` WRITE;
/*!40000 ALTER TABLE `cui_user` DISABLE KEYS */;
INSERT INTO `cui_user` VALUES (1,'cuiyuanxin','ccba12ad2927d5860c157622754fa38d','cuiyuanxin','cuiyuanxin','745454106@qq.com',15811506097,1515484283,2130706433,1524125836,2130706433,1523521682,1),(11,'cuiyuanxin1','e0099adb99e85085b5d55d5e83c9970a','cuiyuanxin1','cuiyuanxin1','745454106@qq.com',13037047497,1521009250,2130706433,1523341233,0,1523521712,1),(12,'cuiyuanxin2','f3da7ac8caab9630c306950b63235091','cuiyuanxin2','cuiyuanxin2','15811506097@163.com',15811506097,1523267136,0,0,0,1523330261,1),(13,'cuiyuanxin5','e0099adb99e85085b5d55d5e83c9970a','cuiyuanxin5','cuiyuanxin5','admin@qq.com',15811506097,1523523747,0,0,0,1523523747,1),(14,'cuiyuanxin6','e0099adb99e85085b5d55d5e83c9970a','cuiyuanxin6','cuiyuanxin6','admin@qq.com',15811506097,1523526212,0,1523526318,0,1524105353,1);
/*!40000 ALTER TABLE `cui_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-06-01 10:57:42
