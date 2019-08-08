/*
 Navicat Premium Data Transfer

 Source Server         : 本地数据库
 Source Server Type    : MySQL
 Source Server Version : 50725
 Source Host           : localhost:3306
 Source Schema         : cuicmf

 Target Server Type    : MySQL
 Target Server Version : 50725
 File Encoding         : 65001

 Date: 08/08/2019 10:28:29
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cui_addons
-- ----------------------------
DROP TABLE IF EXISTS `cui_addons`;
CREATE TABLE `cui_addons` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '插件唯一标识',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '插件名称',
  `author` varchar(40) NOT NULL DEFAULT '' COMMENT '作者',
  `version` varchar(50) NOT NULL DEFAULT '' COMMENT '插件版本',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '插件描述',
  `is_admin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '插件后台\n0:不存在\n1:存在',
  `is_index` tinyint(1) NOT NULL DEFAULT '0' COMMENT '插件前台\n0:不存在\n1:存在',
  `setting` mediumtext NOT NULL COMMENT '插件配置',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '插件状态\n0:系统\n1:用户',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='插件管理表';

-- ----------------------------
-- Records of cui_addons
-- ----------------------------
BEGIN;
INSERT INTO `cui_addons` VALUES (1, 'systeminfo', '系统环境信息', 'cuiyuanxin', '0.1', '用于显示一些服务器的信息', 0, 0, '{\"title\":\"系统信息\",\"display\":\"1\"}', 1553331579, 1553331579, 1);
INSERT INTO `cui_addons` VALUES (2, 'demo', '演示插件', 'byron sampson', '0.1', 'thinkph5.1 演示插件', 0, 0, '{\"display\":\"1\"}', 1553503839, 1553503839, 1);
INSERT INTO `cui_addons` VALUES (3, 'database', '数据库管理', 'cuiyuanxin', '0.1', '数据库管理工具', 0, 0, '{\"title\":\"系统信息\",\"display\":\"1\"}', 1554014466, 1554014466, 1);
COMMIT;

-- ----------------------------
-- Table structure for cui_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `cui_auth_group`;
CREATE TABLE `cui_auth_group` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '用户组名称',
  `description` varchar(150) NOT NULL DEFAULT '' COMMENT '用户组描述',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '权限组状态\n1:开启\n0:关闭',
  `rules` char(80) NOT NULL DEFAULT '' COMMENT '用户组规则ID',
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COMMENT='用户组表';

-- ----------------------------
-- Records of cui_auth_group
-- ----------------------------
BEGIN;
INSERT INTO `cui_auth_group` VALUES (1, '超级管理员', '拥有基本拥有系统的所有权限是除系统管理员之外拥有权限最多的用户组', 1, '1,2,8,9,10,11,20,3,4,6,7,17,5,12,13,14,15,16,18,19');
INSERT INTO `cui_auth_group` VALUES (2, '管理员', '拥有大部分管理权限', 1, '1,2,8,9,10,11,20');
INSERT INTO `cui_auth_group` VALUES (3, 'ceshi1234', 'ceshi', 1, '1');
INSERT INTO `cui_auth_group` VALUES (4, '编辑', '拥有编辑权限', 1, '');
INSERT INTO `cui_auth_group` VALUES (5, '编辑1', '编辑', 1, '1');
COMMIT;

-- ----------------------------
-- Table structure for cui_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `cui_auth_group_access`;
CREATE TABLE `cui_auth_group_access` (
  `uid` mediumint(8) NOT NULL COMMENT '管理员ID',
  `group_id` mediumint(8) NOT NULL COMMENT '用户组ID',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户组明细表';

-- ----------------------------
-- Records of cui_auth_group_access
-- ----------------------------
BEGIN;
INSERT INTO `cui_auth_group_access` VALUES (2, 2);
INSERT INTO `cui_auth_group_access` VALUES (2, 3);
INSERT INTO `cui_auth_group_access` VALUES (3, 3);
COMMIT;

-- ----------------------------
-- Table structure for cui_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `cui_auth_rule`;
CREATE TABLE `cui_auth_rule` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `pid` mediumint(8) NOT NULL DEFAULT '0' COMMENT '父级ID',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '规则名称',
  `sort` smallint(6) NOT NULL DEFAULT '0' COMMENT '规则排序',
  `url` varchar(100) NOT NULL DEFAULT '' COMMENT '规则标识',
  `condition` varchar(100) NOT NULL DEFAULT '' COMMENT '规则表达式',
  `icon` varchar(45) NOT NULL DEFAULT '' COMMENT '图标代码',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '规则状态\n1:开启\n0:关闭',
  `level` tinyint(1) NOT NULL DEFAULT '1' COMMENT '等级',
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COMMENT='权限规则表';

-- ----------------------------
-- Records of cui_auth_rule
-- ----------------------------
BEGIN;
INSERT INTO `cui_auth_rule` VALUES (1, 0, '控制台', 0, 'Index/index', '', 'fa fa-home', 1, 1);
INSERT INTO `cui_auth_rule` VALUES (2, 0, '菜单管理', 3, 'Menu/index', '', 'fa fa-list', 1, 1);
INSERT INTO `cui_auth_rule` VALUES (3, 0, '管理员管理', 2, 'User/index', '', 'fa fa-users', 1, 1);
INSERT INTO `cui_auth_rule` VALUES (4, 3, '管理员列表', 10, 'User/index', '', 'fa fa-circle-o', 1, 2);
INSERT INTO `cui_auth_rule` VALUES (5, 3, '添加管理员', 11, 'User/create', '', 'fa fa-circle-o', 1, 2);
INSERT INTO `cui_auth_rule` VALUES (6, 4, '编辑管理员', 100, 'User/update', '', 'fa fa-circle-o', 0, 3);
INSERT INTO `cui_auth_rule` VALUES (7, 4, '删除管理员', 101, 'User/delete', '', 'fa fa-circle-o', 0, 3);
INSERT INTO `cui_auth_rule` VALUES (8, 2, '菜单列表', 10, 'Menu/index', '', 'fa fa-circle-o', 1, 2);
INSERT INTO `cui_auth_rule` VALUES (9, 8, '添加菜单', 100, 'Menu/create', '', 'fa fa-circle-o', 0, 3);
INSERT INTO `cui_auth_rule` VALUES (10, 8, '编辑菜单', 101, 'Menu/update', '', 'fa fa-circle-o', 0, 3);
INSERT INTO `cui_auth_rule` VALUES (11, 8, '删除菜单', 102, 'Menu/delete', '', 'fa fa-circle-o', 0, 3);
INSERT INTO `cui_auth_rule` VALUES (12, 3, '角色组列表', 12, 'Auth/index', '', 'fa fa-circle-o', 1, 2);
INSERT INTO `cui_auth_rule` VALUES (13, 12, '添加角色组', 100, 'Auth/create', '', 'fa fa-circle-o', 0, 3);
INSERT INTO `cui_auth_rule` VALUES (14, 12, '编辑角色组', 101, 'Auth/update', '', 'fa fa-circle-o', 0, 3);
INSERT INTO `cui_auth_rule` VALUES (15, 12, '删除角色组', 102, 'Auth/delete', '', 'fa fa-circle-o', 0, 3);
INSERT INTO `cui_auth_rule` VALUES (16, 12, '设置权限', 103, 'Auth/rules', '', 'fa fa-circle-o', 0, 3);
INSERT INTO `cui_auth_rule` VALUES (17, 4, '管理员操作', 102, 'User/write', '', 'fa fa-circle-o', 0, 3);
INSERT INTO `cui_auth_rule` VALUES (18, 12, '角色组操作', 104, 'Auth/write', '', 'fa fa-circle-o', 0, 3);
INSERT INTO `cui_auth_rule` VALUES (19, 12, '权限操作', 105, 'Auth/rules_write', '', 'fa fa-circle-o', 0, 3);
INSERT INTO `cui_auth_rule` VALUES (20, 8, '菜单操作', 103, 'Menu/write', '', 'fa fa-circle-o', 0, 3);
INSERT INTO `cui_auth_rule` VALUES (21, 0, '系统管理', 1, 'System/index', '', 'fa fa-cog', 1, 1);
INSERT INTO `cui_auth_rule` VALUES (22, 21, '插件管理', 10, 'Addons/index', '', 'fa fa-skyatlas', 1, 2);
INSERT INTO `cui_auth_rule` VALUES (23, 22, '插件列表', 100, 'Addons/index', '', 'fa fa-circle-o', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (24, 23, '未安装插件列表', 1000, 'Addons/uninstalled', '', 'fa fa-circle-o', 0, 4);
INSERT INTO `cui_auth_rule` VALUES (25, 23, '创建插件', 1001, 'Addons/create', '', 'fa fa-circle-o', 0, 4);
INSERT INTO `cui_auth_rule` VALUES (26, 23, '预览插件', 1002, 'Addons/preview', '', 'fa fa-circle-o', 0, 4);
INSERT INTO `cui_auth_rule` VALUES (27, 23, '插件操作', 1003, 'Addons/write', '', 'fa fa-circle-o', 0, 4);
INSERT INTO `cui_auth_rule` VALUES (28, 23, '安装插件', 1004, 'Addons/install', '', 'fa fa-circle-o', 0, 4);
INSERT INTO `cui_auth_rule` VALUES (29, 23, '卸载插件', 1005, 'Addons/uninstall', '', 'fa fa-circle-o', 0, 4);
INSERT INTO `cui_auth_rule` VALUES (30, 23, '删除插件', 1006, 'Addons/delete', '', 'fa fa-circle-o', 0, 4);
INSERT INTO `cui_auth_rule` VALUES (31, 21, '钩子管理', 11, 'Hooks/index', '', 'fa fa-anchor', 1, 2);
INSERT INTO `cui_auth_rule` VALUES (32, 31, '钩子列表', 1000, 'Hooks/index', '', 'fa fa-circle-o', 1, 4);
INSERT INTO `cui_auth_rule` VALUES (33, 31, '添加钩子', 1001, 'Hooks/create', '', 'fa fa-circle-o', 0, 4);
INSERT INTO `cui_auth_rule` VALUES (34, 31, '编辑钩子', 1002, 'Hooks/update', '', 'fa fa-circle-o', 0, 4);
INSERT INTO `cui_auth_rule` VALUES (35, 31, '钩子操作', 1003, 'Hooks/write', '', 'fa fa-circle-o', 0, 4);
INSERT INTO `cui_auth_rule` VALUES (36, 31, '删除钩子', 1004, 'Hooks/delete', '', 'fa fa-circle-o', 0, 4);
COMMIT;

-- ----------------------------
-- Table structure for cui_hooks
-- ----------------------------
DROP TABLE IF EXISTS `cui_hooks`;
CREATE TABLE `cui_hooks` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `description` text NOT NULL COMMENT '钩子描述',
  `addons` varchar(255) NOT NULL DEFAULT '' COMMENT '插件列表',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '钩子状态\n0:禁用\n1:启用',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COMMENT='钩子列表';

-- ----------------------------
-- Records of cui_hooks
-- ----------------------------
BEGIN;
INSERT INTO `cui_hooks` VALUES (1, 'pageHeader', '页面header钩子，一般用于加载插件CSS文件和代码', 'demo', 0, 1553503839, 1);
INSERT INTO `cui_hooks` VALUES (2, 'pageFooter', '页面footer钩子，一般加载具体业务内容', '', 0, 1553329170, 1);
INSERT INTO `cui_hooks` VALUES (3, 'adminIndex', '后台管理首页钩子', 'systeminfo', 0, 1553504227, 1);
INSERT INTO `cui_hooks` VALUES (4, 'adminLogin', '后台登录钩子', '', 1553528667, 1553528753, 1);
INSERT INTO `cui_hooks` VALUES (5, 'app', '应用模块', 'database', 1553594491, 1554014466, 1);
COMMIT;

-- ----------------------------
-- Table structure for cui_user
-- ----------------------------
DROP TABLE IF EXISTS `cui_user`;
CREATE TABLE `cui_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `nickname` varchar(30) NOT NULL DEFAULT '' COMMENT '昵称',
  `realname` varchar(30) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `mobile` char(11) NOT NULL DEFAULT '' COMMENT '手机号码',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '邮箱',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '用户状态\n0:禁用\n1:正常\n2:锁定',
  `reg_ip` varchar(15) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `last_login_ip` varchar(15) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `last_login_time` int(10) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `mobile` (`mobile`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='管理员账户';

-- ----------------------------
-- Records of cui_user
-- ----------------------------
BEGIN;
INSERT INTO `cui_user` VALUES (1, 'cuiyuanxin', 'ccba12ad2927d5860c157622754fa38d', 'Redcar', '崔元欣', '15811506097', '15811506097@163.com', 1, '2147483647', '2886795265', 1555495710, 1552379385, 1552379387);
INSERT INTO `cui_user` VALUES (2, 'cuiyuanxin1', '9817133175eb93d9e845e6d513f4f37f', 'CC', 'CC', '13037047497', '745454106@qq.com', 1, '2147483647', '2147483647', 1553182679, 1552636244, 1552636244);
INSERT INTO `cui_user` VALUES (3, 'cuiyuanxin2', '9817133175eb93d9e845e6d513f4f37f', 'cuiyuanxi', 'cuiyuanxi', '18513636097', '15811506097@139.com', 1, '2147483647', '0', 0, 1553518652, 1553518706);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
