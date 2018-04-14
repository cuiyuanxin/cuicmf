/*
 Navicat Premium Data Transfer

 Source Server         : 本地
 Source Server Type    : MySQL
 Source Server Version : 50553
 Source Host           : localhost:3306
 Source Schema         : cuicmf

 Target Server Type    : MySQL
 Target Server Version : 50553
 File Encoding         : 65001

 Date: 13/04/2018 16:07:18
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for 1cui_action_log
-- ----------------------------
DROP TABLE IF EXISTS `1cui_action_log`;
CREATE TABLE `1cui_action_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `action_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '行为id',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '执行用户id',
  `action_ip` bigint(20) NOT NULL COMMENT '执行行为者ip',
  `model` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '触发行为的表',
  `record_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '触发行为的数据id',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '日志备注',
  `status` tinyint(2) NOT NULL DEFAULT 1 COMMENT '状态',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '执行行为的时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `action_ip_ix`(`action_ip`) USING BTREE,
  INDEX `action_id_ix`(`action_id`) USING BTREE,
  INDEX `user_id_ix`(`user_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 18 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '行为日志表' ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of 1cui_action_log
-- ----------------------------
INSERT INTO `1cui_action_log` VALUES (1, 1, 1, 0, 'user', 1, 'cuiyuanxin在2017-09-02 14:52登录了后台', 1, 1504335159);
INSERT INTO `1cui_action_log` VALUES (2, 1, 1, 0, 'user', 1, 'cuiyuanxin在2017-09-03 14:04登录了后台', 1, 1504418670);
INSERT INTO `1cui_action_log` VALUES (3, 1, 1, 0, 'user', 1, 'cuiyuanxin在2017-09-03 17:00登录了后台', 1, 1504429211);
INSERT INTO `1cui_action_log` VALUES (4, 1, 1, 0, 'user', 1, 'cuiyuanxin在2017-09-03 19:47登录了后台', 1, 1504439252);
INSERT INTO `1cui_action_log` VALUES (5, 1, 1, 0, 'user', 1, 'cuiyuanxin在2017-09-03 22:26登录了后台', 1, 1504448799);
INSERT INTO `1cui_action_log` VALUES (6, 1, 1, 0, 'user', 1, 'cuiyuanxin在2017-09-09 20:37登录了后台', 1, 1504960631);
INSERT INTO `1cui_action_log` VALUES (7, 1, 1, 0, 'user', 1, 'cuiyuanxin在2017-09-10 00:19登录了后台', 1, 1504973984);
INSERT INTO `1cui_action_log` VALUES (8, 1, 1, 0, 'user', 1, 'cuiyuanxin在2017-09-10 13:13登录了后台', 1, 1505020382);
INSERT INTO `1cui_action_log` VALUES (9, 1, 1, 0, 'user', 1, 'cuiyuanxin在2017-09-10 13:58登录了后台', 1, 1505023133);
INSERT INTO `1cui_action_log` VALUES (10, 1, 1, 0, 'user', 1, 'cuiyuanxin在2017-09-10 22:26登录了后台', 1, 1505053592);
INSERT INTO `1cui_action_log` VALUES (11, 1, 1, 0, 'user', 1, 'cuiyuanxin在2017-09-16 11:46登录了后台', 1, 1505533589);
INSERT INTO `1cui_action_log` VALUES (12, 1, 1, 0, 'user', 1, 'cuiyuanxin在2017-09-16 14:49登录了后台', 1, 1505544565);
INSERT INTO `1cui_action_log` VALUES (13, 1, 1, 0, 'user', 1, 'cuiyuanxin在2017-09-16 21:50登录了后台', 1, 1505569824);
INSERT INTO `1cui_action_log` VALUES (14, 1, 1, 0, 'user', 1, 'cuiyuanxin在2017-09-16 23:36登录了后台', 1, 1505576169);
INSERT INTO `1cui_action_log` VALUES (15, 1, 1, 0, 'user', 1, 'cuiyuanxin在2017-09-17 01:34登录了后台', 1, 1505583250);
INSERT INTO `1cui_action_log` VALUES (16, 1, 1, 0, 'user', 1, 'cuiyuanxin在2017-10-01 19:14登录了后台', 1, 1506856457);
INSERT INTO `1cui_action_log` VALUES (17, 1, 1, 0, 'user', 1, 'cuiyuanxin在2017-10-02 15:58登录了后台', 1, 1506931112);

-- ----------------------------
-- Table structure for 1cui_addon
-- ----------------------------
DROP TABLE IF EXISTS `1cui_addon`;
CREATE TABLE `1cui_addon`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '插件名或标识',
  `title` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '中文名称',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '插件描述',
  `config` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '配置',
  `author` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '作者',
  `version` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '版本号',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '安装时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '插件表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of 1cui_addon
-- ----------------------------
INSERT INTO `1cui_addon` VALUES (1, 'System', '后台信息', '后台首页信息显示', '{\"display\":\"1\"}', 'cuiyuanxin', '0.1', 1, 1500036394, 1500036394);
INSERT INTO `1cui_addon` VALUES (2, 'Security', '日常维护', '后台首页日常维护', '{\"display\":\"1\"}', 'cuiyuanxin', '0.1', 1, 1501424281, 1501424281);

-- ----------------------------
-- Table structure for 1cui_hook
-- ----------------------------
DROP TABLE IF EXISTS `1cui_hook`;
CREATE TABLE `1cui_hook`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '钩子名称',
  `description` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '描述',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `addons` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '钩子挂载的插件 \'，\'分割',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '钩子状态',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `name`(`name`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of 1cui_hook
-- ----------------------------
INSERT INTO `1cui_hook` VALUES (1, 'AdminIndex', '首页小格子个性化显示', 1499678706, 'System,Security', 1);
INSERT INTO `1cui_hook` VALUES (2, 'Email', '邮件调用钩子', 0, '', 1);

-- ----------------------------
-- Table structure for cui_action_log
-- ----------------------------
DROP TABLE IF EXISTS `cui_action_log`;
CREATE TABLE `cui_action_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '执行用户id',
  `action_ip` bigint(20) NOT NULL COMMENT '执行行为者ip',
  `model` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '触发行为的表',
  `record_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '触发行为的数据id',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '日志备注',
  `status` tinyint(2) NOT NULL DEFAULT 1 COMMENT '状态',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '执行行为的时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `action_ip_ix`(`action_ip`) USING BTREE,
  INDEX `user_id_ix`(`user_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '行为日志表' ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for cui_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `cui_auth_group`;
CREATE TABLE `cui_auth_group`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '权限组id,自增主键',
  `module` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '权限组所属模块',
  `title` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `description` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
  `rules` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id，多个规则 , 隔开',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cui_auth_group
-- ----------------------------
INSERT INTO `cui_auth_group` VALUES (1, 'admin', '超级管理员', '拥有所有的管理权限', 1, '');
INSERT INTO `cui_auth_group` VALUES (2, 'admin', '管理员', '拥有大部分管理权限', 1, '1,2,34,43,3,4,5,10,11,12,13,14,15,16,17,18,46,6,24,25,26,27,28,29,30,44');
INSERT INTO `cui_auth_group` VALUES (3, 'admin', '微信管理员', '拥有所有微信相关的权限', 1, '');
INSERT INTO `cui_auth_group` VALUES (4, 'admin', '编辑', '只能发布文章', 1, '');

-- ----------------------------
-- Table structure for cui_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `cui_auth_group_access`;
CREATE TABLE `cui_auth_group_access`  (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户id',
  `group_id` mediumint(8) UNSIGNED NOT NULL COMMENT '用户组id',
  UNIQUE INDEX `uid_group_id`(`uid`, `group_id`) USING BTREE,
  INDEX `uid`(`uid`) USING BTREE,
  INDEX `group_id`(`group_id`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户组明细表' ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of cui_auth_group_access
-- ----------------------------
INSERT INTO `cui_auth_group_access` VALUES (7, 2);
INSERT INTO `cui_auth_group_access` VALUES (8, 2);
INSERT INTO `cui_auth_group_access` VALUES (9, 2);
INSERT INTO `cui_auth_group_access` VALUES (10, 2);
INSERT INTO `cui_auth_group_access` VALUES (11, 2);
INSERT INTO `cui_auth_group_access` VALUES (12, 4);
INSERT INTO `cui_auth_group_access` VALUES (13, 4);
INSERT INTO `cui_auth_group_access` VALUES (14, 4);

-- ----------------------------
-- Table structure for cui_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `cui_auth_rule`;
CREATE TABLE `cui_auth_rule`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pid` mediumint(8) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级id',
  `title` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '规则中文名称',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `module` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'admin' COMMENT '模块',
  `url` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '规则地址',
  `condition` char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '附加queryString',
  `hide` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否显示到菜单',
  `inspect` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '权限检查',
  `icon` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '图标',
  `tip` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '菜单说明',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：为1正常，为0禁用',
  `level` tinyint(1) NOT NULL DEFAULT 1 COMMENT '菜单层级',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `title`(`title`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 53 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '规则表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cui_auth_rule
-- ----------------------------
INSERT INTO `cui_auth_rule` VALUES (1, 0, '首页', 0, 'admin', 'Index/index', '', 1, 0, 'fa fa-dashboard', '', 1, 1);
INSERT INTO `cui_auth_rule` VALUES (2, 0, '系统', 1, 'admin', 'System/index', '', 1, 1, 'fa fa-gear', '', 1, 1);
INSERT INTO `cui_auth_rule` VALUES (3, 2, '菜单管理', 30, 'admin', 'Menu/index', '', 1, 1, '', '菜单管理', 1, 2);
INSERT INTO `cui_auth_rule` VALUES (4, 3, '菜单列表', 100, 'admin', 'Menu/index', '', 1, 1, '', '菜单列表', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (5, 3, '添加菜单', 101, 'admin', 'Menu/create_menu', '', 1, 1, '', '添加菜单', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (6, 0, '会员管理', 2, 'admin', 'Member/index', '', 1, 1, 'fa fa-group', '会员管理', 1, 1);
INSERT INTO `cui_auth_rule` VALUES (7, 6, '权限管理', 20, 'admin', 'Auth/index', '', 1, 1, '', '权限管理', 1, 2);
INSERT INTO `cui_auth_rule` VALUES (8, 7, '权限列表', 100, 'admin', 'Auth/index', '', 1, 1, '', '权限列表', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (9, 7, '添加权限', 101, 'admin', 'Auth/create_group', '', 1, 1, '', '添加权限', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (10, 3, '编辑菜单', 102, 'admin', 'Menu/edit_menu', '', 0, 1, '', '编辑菜单', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (11, 3, '复制菜单', 103, 'admin', 'Menu/copy_menu', '', 0, 1, '', '复制菜单', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (12, 3, '启用菜单', 104, 'admin', 'Menu/change_status?method=resume', '', 0, 1, '', '启用菜单', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (13, 3, '禁用菜单', 105, 'admin', 'Menu/change_status?method=forbid', '', 0, 1, '', '禁用菜单', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (14, 3, '显示菜单', 106, 'admin', 'Menu/change_status?method=show&filed=hide', '', 0, 1, '', '显示菜单', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (15, 3, '隐藏菜单', 107, 'admin', 'Menu/change_status?method=hide&filed=hide', '', 0, 1, '', '隐藏菜单', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (16, 3, '检查权限', 108, 'admin', 'Menu/change_status?method=checked&filed=inspect', '', 0, 1, '', '检查权限', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (17, 3, '不检查权限', 109, 'admin', 'Menu/change_status?method=unchecked&filed=inspect', '', 0, 1, '', '不检查权限', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (18, 3, '删除菜单', 110, 'admin', 'Menu/change_status?method=delete&all=all', '', 0, 1, '', '删除菜单', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (19, 3, '排序菜单', 111, 'admin', 'Menu/sort_menu', '', 0, 1, '', '排序菜单', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (20, 7, '编辑权限', 102, 'admin', 'Auth/edit_group', '', 0, 1, '', '编辑权限', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (21, 7, '启用权限', 103, 'admin', 'Auth/change_status?method=resume', '', 0, 1, '', '启用权限', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (22, 7, '禁用权限', 104, 'admin', 'Auth/change_status?method=forbid', '', 0, 1, '', '禁用权限', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (23, 7, '删除权限', 105, 'admin', 'Auth/change_status?method=delete', '', 0, 1, '', '删除权限', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (24, 6, '管理员管理', 10, 'admin', 'User/index', '', 1, 1, '', '管理员管理', 1, 2);
INSERT INTO `cui_auth_rule` VALUES (25, 24, '管理员列表', 100, 'admin', 'User/index', '', 1, 1, '', '管理员列表', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (26, 24, '添加管理员', 101, 'admin', 'User/create_user', '', 1, 1, '', '添加管理员', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (27, 24, '编辑管理员', 102, 'admin', 'User/edit_user', '', 0, 1, '', '编辑管理员', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (28, 24, '启用管理员', 103, 'admin', 'User/change_status?method=resume', '', 0, 1, '', '启用管理员', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (29, 24, '禁用管理员', 104, 'admin', 'User/change_status?method=forbid', '', 0, 1, '', '禁用管理员', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (30, 24, '删除管理员', 105, 'admin', 'User/change_status?method=delete', '', 0, 1, '', '删除管理员', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (31, 2, '配置管理', 20, 'admin', 'System/configuration', '', 1, 1, '', '配置管理', 1, 2);
INSERT INTO `cui_auth_rule` VALUES (32, 31, '配置列表', 100, 'admin', 'System/configuration', '', 1, 1, '', '配置列表', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (33, 31, '添加配置', 101, 'admin', 'System/create_configuration', '', 1, 1, '', '添加配置', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (34, 2, '系统设置', 10, 'admin', 'System/index', '', 1, 1, '', '系统设置', 1, 2);
INSERT INTO `cui_auth_rule` VALUES (35, 2, '数据库管理', 40, 'admin', 'Database/index', '', 1, 1, '', '菜单管理', 1, 2);
INSERT INTO `cui_auth_rule` VALUES (36, 35, '数据库备份', 100, 'admin', 'Database/index', '', 1, 1, '', '数据库备份', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (37, 35, '数据库还原', 101, 'admin', 'Database/import', '', 1, 1, '', '数据库还原', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (38, 31, '编辑配置', 102, 'admin', 'System/edit_configuration', '', 0, 1, '', '编辑配置', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (39, 31, '启用配置', 104, 'admin', 'System/change_status?method=resume', '', 0, 1, '', '启用配置', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (40, 31, '禁用配置', 105, 'admin', 'System/change_status?method=forbid', '', 0, 1, '', '禁用配置', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (41, 31, '删除配置', 106, 'admin', 'System/change_status?method=delete', '', 0, 1, '', '删除配置', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (42, 7, '菜单权限', 106, 'admin', 'Auth/rule_group', '', 0, 1, '', '菜单权限', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (43, 34, '设置保存', 100, 'admin', 'System/write_save', '', 0, 1, '', '设置保存', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (44, 24, '保存管理员', 106, 'admin', 'User/write_user', '', 0, 1, '', '保存管理员', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (45, 31, '保存配置', 107, 'admin', 'System/write_configuration', '', 0, 1, '', '保存配置', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (46, 3, '保存菜单', 112, 'admin', 'Menu/write_menu', '', 0, 1, '', '保存菜单', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (47, 35, '优化数据表', 102, 'admin', 'Database/optimize', '', 0, 1, '', '优化数据表', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (48, 35, '修复数据表', 103, 'admin', 'Database/repair', '', 0, 1, '', '修复数据表', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (49, 35, '备份数据库', 104, 'admin', 'Database/export', '', 0, 1, '', '备份数据库', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (50, 35, '还原数据库', 105, 'admin', 'Database/restore', '', 0, 1, '', '还原数据库', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (51, 35, '删除数据库', 106, 'admin', 'Database/del', '', 0, 1, '', '删除数据库', 1, 3);
INSERT INTO `cui_auth_rule` VALUES (52, 7, '保存权限组', 107, 'admin', 'Auth/write_group', '', 0, 1, '', '保存权限组', 1, 3);

-- ----------------------------
-- Table structure for cui_config
-- ----------------------------
DROP TABLE IF EXISTS `cui_config`;
CREATE TABLE `cui_config`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '配置标识',
  `title` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '配置标题',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '配置类型',
  `group` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '配置分组',
  `value` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '配置值',
  `extra` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '配置项',
  `remark` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '配置说明',
  `sort` mediumint(8) NOT NULL DEFAULT 0 COMMENT '排序',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_name`(`name`) USING BTREE,
  INDEX `type`(`type`) USING BTREE,
  INDEX `group`(`group`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 33 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cui_config
-- ----------------------------
INSERT INTO `cui_config` VALUES (1, 'config_group_list', '配置分组', 3, 2, '1:基本\r\n2:系统\r\n3:上传\r\n4:数据库', '', '配置分组', 0, 1515742043, 1520923151, 1);
INSERT INTO `cui_config` VALUES (2, 'config_type_list', '配置类型', 3, 2, '0:数字\r\n1:字符\r\n2:文本\r\n3:数组\r\n4:枚举\r\n5:多选枚举\r\n6:单图片\r\n7:多图片\r\n8:单文件\r\n9:多文件\r\n10:富文本\r\n11:单选\r\n12:多选\r\n13:日期\r\n14:时间\r\n15:颜色\r\n16:音频\r\n17:视频', '', '主要用于数据解析和页面表单的生成', 0, 1516012393, 1520565236, 1);
INSERT INTO `cui_config` VALUES (3, 'admin_allow_ip', '后台允许访问IP', 2, 2, '', '', '多个用逗号分隔，如果不配置表示不限制IP访问', 0, 1516012517, 1521013592, 1);
INSERT INTO `cui_config` VALUES (4, 'allow_visit', '不受限控制器方法', 3, 2, '0:Ajax/filepath\r\n1:Ajax/index\r\n2:Ajax/upload\r\n3:User/change_password\r\n4:System/clear', '', '不受限控制器方法', 0, 1516012781, 1516956164, 1);
INSERT INTO `cui_config` VALUES (5, 'deny_visit', '超管专限控制器方法', 3, 2, '', '', '仅超级管理员可访问的控制器方法', 0, 1516012820, 1516956181, 1);
INSERT INTO `cui_config` VALUES (6, 'image_ext', '图片文件格式', 3, 3, '0:jpg\n1:jpeg\n2:png\n3:gif\n4:bmp', '', '允许上传的图片文件格式', 0, 1517473580, 1517908208, 1);
INSERT INTO `cui_config` VALUES (7, 'upload_driver', '上传驱动', 11, 3, '0', 'local:本地\nqiniu:七牛', '图片或文件上传驱动', 0, 1517908455, 1520848872, 1);
INSERT INTO `cui_config` VALUES (8, 'ceshi', '测试', 0, 1, '50', '', '', 0, 1517914439, 1517914508, 1);
INSERT INTO `cui_config` VALUES (9, 'ceshi1', '测试1', 1, 1, '测试文本', '', '', 0, 1517914536, 1517914536, 1);
INSERT INTO `cui_config` VALUES (10, 'ceshi2', 'ceshi2', 2, 1, '测试多行文本', '', '', 0, 1517914550, 1517914550, 1);
INSERT INTO `cui_config` VALUES (11, 'ceshi3', 'ceshi3', 3, 1, '0:1\r\n1:2', '', '', 0, 1517914567, 1517914567, 1);
INSERT INTO `cui_config` VALUES (12, 'ceshi4', 'ceshi4', 4, 1, '0', '0:aaa,1:bb', '', 0, 1517914618, 1517971587, 1);
INSERT INTO `cui_config` VALUES (13, 'ceshi5', 'ceshi5', 5, 1, '0,1', '0:aaaa,1:bbbbbbbbb,2:ccccccc', '', 0, 1517971958, 1517982188, 1);
INSERT INTO `cui_config` VALUES (14, 'ceshi6', 'ceshi6', 6, 1, '16', '', '', 0, 1517971977, 1517971977, 1);
INSERT INTO `cui_config` VALUES (15, 'ceshi7', 'ceshi7', 7, 1, '1,2,3', '', '', 0, 1517971991, 1517971991, 1);
INSERT INTO `cui_config` VALUES (16, 'ceshi8', 'ceshi8', 8, 1, '10', '', '', 0, 1517972019, 1517972019, 1);
INSERT INTO `cui_config` VALUES (17, 'ceshi9', 'ceshi9', 9, 1, '8,10', '', '', 0, 1517972033, 1517972033, 1);
INSERT INTO `cui_config` VALUES (18, 'ceshi10', 'ceshi10', 10, 1, '<p>测试富文本</p>', '', '', 0, 1517972050, 1517972050, 1);
INSERT INTO `cui_config` VALUES (19, 'ceshi11', 'ceshi11', 11, 1, '0', '0:aaaaaaaaaaa,1:bbbbbbbbbbbbbbb,2:cccccccccc,4:dddddddd', '', 0, 1517972101, 1517985480, 1);
INSERT INTO `cui_config` VALUES (20, 'ceshi12', 'ceshi12', 12, 1, '0,4', '0:aaaaaaaaaaa,1:bbbbbbbbbbbbbbb,2:cccccccccc,4:dddddddd', '', 0, 1517972118, 1517985144, 1);
INSERT INTO `cui_config` VALUES (21, 'ceshi13', 'ceshi13', 13, 1, '1518019200', '', '', 0, 1517972133, 1517972133, 1);
INSERT INTO `cui_config` VALUES (22, 'ceshi14', 'ceshi14', 14, 1, '1518080943', '', '', 0, 1517972152, 1517972152, 1);
INSERT INTO `cui_config` VALUES (23, 'ceshi15', 'ceshi15', 15, 1, '#050606', '', '', 0, 1517972165, 1517972165, 1);
INSERT INTO `cui_config` VALUES (24, 'file_ext', '文件格式', 3, 3, '0:zip\n1:rar\n2:tar\n3:gz\n4:7z\n5:doc\n6:docx\n7:txt\n8:xml', '', '常用文件格式', 0, 1518338799, 1518338799, 1);
INSERT INTO `cui_config` VALUES (25, 'ceshi16', 'jpaudio', 16, 1, '7', '', 'jpaudio', 0, 1520565288, 1520565288, 1);
INSERT INTO `cui_config` VALUES (26, 'ceshi17', 'ceshi17', 17, 1, '11', '', 'ceshi17', 0, 1520565308, 1520565308, 1);
INSERT INTO `cui_config` VALUES (27, 'audio_ext', '音频文件格式', 3, 3, '0:mp3\n1:ogg\n3:wav', '', '音频文件格式', 0, 1520589239, 1520589239, 1);
INSERT INTO `cui_config` VALUES (28, 'video_ext', '视频文件格式', 3, 3, '0:mp4\n1:flv\n2:avi\n3:wmv\n4:rm\n5:rmvb\n6:3gp', '', '视频文件格式', 0, 1520590704, 1520590704, 1);
INSERT INTO `cui_config` VALUES (29, 'data_backup_path', '数据库备份根路径', 1, 4, './data/backup/', '', '数据库备份存放的目录，路径必须以 / 结尾', 0, 1520923404, 1520924848, 1);
INSERT INTO `cui_config` VALUES (30, 'data_backup_part_size', '数据库备份卷大小', 0, 4, '20971520', '', '该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M', 1, 1520925255, 1520925255, 1);
INSERT INTO `cui_config` VALUES (31, 'data_backup_compress', '数据库备份文件是否启用压缩', 4, 4, '1', '0:不压缩\n1:启用压缩', '压缩备份文件需要PHP环境支持gzopen,gzwrite函数', 2, 1520925401, 1520925401, 1);
INSERT INTO `cui_config` VALUES (32, 'data_backup_compress_level', '数据库备份文件压缩级别', 4, 4, '9', '1:普通\n4:一般\n9:最高', '数据库备份文件的压缩级别，该配置在开启压缩时生效', 3, 1520925547, 1520925547, 1);

-- ----------------------------
-- Table structure for cui_file
-- ----------------------------
DROP TABLE IF EXISTS `cui_file`;
CREATE TABLE `cui_file`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '文件ID',
  `name` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '原始文件名',
  `savename` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '保存名称',
  `savepath` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '文件保存路径',
  `ext` char(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '文件后缀',
  `mime` char(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '文件mime类型',
  `size` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件大小',
  `md5` char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `location` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件保存位置',
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '远程地址',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上传时间',
  `status` tinyint(2) NOT NULL DEFAULT 0 COMMENT '状态',
  `file_type` enum('1','2','3','4') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '附件类型1图片2音频3视频0其他文件',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_md5`(`md5`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 17 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '文件表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cui_file
-- ----------------------------
INSERT INTO `cui_file` VALUES (1, '0f519bb75cf2466f23f3abafcac96608', '9084674ce888b5a1826ba5e74e0ee997', '/uploads/picture/20180302/', 'jpg', 'image/jpeg', 146337, '52228344ee4cd68f9173de539f1e0f0a', '8927a88603b5fd176fcf548be556fdece3828700', 0, '', 1519979615, 1, '1');
INSERT INTO `cui_file` VALUES (2, '1b23b1002b5eb621ec3f879e8830de5b', 'a6dbf05a7e5693ef09efae5c32154e32', '/uploads/picture/20180302/', 'jpg', 'image/jpeg', 102974, '335180dba3ac5394f3495516b5c14523', '32d3a2c8550486d6b9961a088cc4c484a3427467', 0, '', 1519979616, 1, '1');
INSERT INTO `cui_file` VALUES (3, '5fa339cd6f7b8901e1a121a7f5be355a', 'f3c3dbc1dcb7094454098a17e567f625', '/uploads/picture/20180307/', 'jpg', 'image/jpeg', 119252, '4ab96a89313f3226dd190ec23b0b52d2', '7a3c293ccd2c523b0f537220c0e01157245afe12', 0, '', 1520413371, 1, '1');
INSERT INTO `cui_file` VALUES (4, '59a3e2886bb94', 'a235ddc03e4a55b38c80f4d5cf0a0dab', '/uploads/file/20180308/', 'mp3', 'application/octet-stream', 2690656, '18217d9322e479b19dacda8b7d7e40d8', 'b6f78a1252700f234a6830c4911c30283d4408d1', 0, '', 1520491315, 1, '2');
INSERT INTO `cui_file` VALUES (5, 'Monster Summer Game-凝固汽油弹(napa_爱给网_aigei_com', '69aad0f0d056cbe679519d8b55bc315d', '/uploads/file/20180308/', 'ogg', 'application/ogg', 198954, 'd10cb062b8d6e678cfd1b5e2d32fa2fa', '88ffd684a8d4061429b784235129c84025b8e682', 0, '', 1520502172, 1, '2');
INSERT INTO `cui_file` VALUES (6, '纯雨声', '6c4be7867795459f61699852a79415d6', '/uploads/file/20180308/', 'wma', 'video/x-ms-asf', 927365, '31c3fb7d2876ce6d0e69318aa868d140', 'c05bd8a765cdc8b3ea1ca5639d97f8b215ba9900', 0, '', 1520502760, 1, '2');
INSERT INTO `cui_file` VALUES (7, 'Awesomenauts-v-fg-杀3(v-fg-kill_爱给网_aigei_com', 'e11886518b81f631f82e8c661c13c896', '/uploads/file/20180308/', 'wav', 'audio/x-wav', 291250, '45c227da3ab388cecc26b6ca53f98e5e', 'e8c58529e7ba18d101838b30dbc93d9eff5d9590', 0, '', 1520503029, 1, '2');
INSERT INTO `cui_file` VALUES (8, 'bootstrap-treeview-master', 'df33b694da07cd152455b7b076fbeeeb', '/uploads/file/20180309/', 'zip', 'application/zip', 175841, '06c31e9b0a7fadcfe7e2b8a8ffe7467f', '5d91520030f31a907821699d2cce46bda4e8444b', 0, '', 1520581210, 1, '');
INSERT INTO `cui_file` VALUES (9, 'git常用命令', '0510c138f1ead9a7e1e00b6a49d38065', '/uploads/file/20180309/', 'txt', 'text/plain', 78, '3e253479830c85d2a5dc11f13d42c67d', '2baa110468ba0895e17c110e73fdb88bcbf6bbb2', 0, '', 1520581398, 1, '');
INSERT INTO `cui_file` VALUES (10, 'Sublime语法', '34764457d3398c517754fc73cc8c8f5c', '/uploads/file/20180309/', 'txt', 'text/plain', 6899, 'd5ceb6453ba02bb8318f88f24d91cc17', '5fd7c0b81a97c88473e8ca383540706c899e7cfb', 0, '', 1520584753, 1, '');
INSERT INTO `cui_file` VALUES (11, '59a3fe76d33c1', '8bc985cd6f0e6342d77527451b5459e9', '/uploads/file/20180309/', 'mp4', 'video/mp4', 318465, '3cf571d4cf2a4c4b2df823a27852a7d5', 'c90b44a96ed080c1a6c8ce8888a40a5aaaa7e7ca', 0, '', 1520591932, 1, '3');
INSERT INTO `cui_file` VALUES (12, '59a3fe76d33c1', '10d810e819e3f51c3bd499747cedbafa', '/uploads/file/20180312/', '3gp', 'video/3gpp', 445825, 'dc3364cc8b60af6b50183376ab70d016', '5dcc02d2cc62c5a2a923fee07c463e15cd46ba2f', 0, '', 1520822602, 1, '3');
INSERT INTO `cui_file` VALUES (13, '59a3fe76d33c1', '60d65af637e36243e748e5c7a0754a94', '/uploads/file/20180312/', 'avi', 'video/x-msvideo', 864632, 'b4e544af58047e98ad36035026826ba5', '613e09dd88cf4c51d74ae4b2982bf007569206dc', 0, '', 1520822728, 1, '3');
INSERT INTO `cui_file` VALUES (14, '59a3fe76d33c1', '3ee8525d719d5044882769f9abf290c2', '/uploads/file/20180312/', 'flv', 'video/x-flv', 636575, '3cf7efa20d9ecac23d3c57f37e461368', '7d4ed807a40b65d5770e49e6c03c6ce43f4b122a', 0, '', 1520822740, 1, '3');
INSERT INTO `cui_file` VALUES (15, '59a3fe76d33c1', '29e0792f655dfdeac593ae09393bcfe8', '/uploads/file/20180312/', 'wmv', 'video/x-ms-asf', 980393, '71905f48093c620947c5965a1ae24706', 'df8a0599a9aa45ef29dafe192dc7f862d73db139', 0, '', 1520826041, 1, '3');
INSERT INTO `cui_file` VALUES (16, 'bde34cc2ea6edd40c341387ebbe1b2ad', '6d9ec10953b9921b910be89f02e0679d', '/uploads/picture/20180409/', 'jpg', 'image/jpeg', 373343, '1634fc6822765ec7d6cdf02b7055483c', 'f9a58274dcff9cba57bb86bbe332b126e218987c', 0, '', 1523261322, 1, '1');

-- ----------------------------
-- Table structure for cui_user
-- ----------------------------
DROP TABLE IF EXISTS `cui_user`;
CREATE TABLE `cui_user`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '登录密码',
  `name` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '姓名',
  `nickname` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `email` char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '邮箱',
  `mobile` bigint(11) UNSIGNED NOT NULL COMMENT '手机号',
  `reg_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '注册时间',
  `reg_ip` bigint(20) NOT NULL DEFAULT 0 COMMENT '注册IP',
  `last_login_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最后登录时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT 0 COMMENT '最后登录ip',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '用户状态 0：禁用； 1：正常',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_login_key`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of cui_user
-- ----------------------------
INSERT INTO `cui_user` VALUES (1, 'cuiyuanxin', 'ccba12ad2927d5860c157622754fa38d', 'cuiyuanxin', 'cuiyuanxin', '745454106@qq.com', 15811506097, 1515484283, 2130706433, 1523598962, 0, 1523521682, 1);
INSERT INTO `cui_user` VALUES (11, 'cuiyuanxin1', 'e0099adb99e85085b5d55d5e83c9970a', 'cuiyuanxin1', 'cuiyuanxin1', '745454106@qq.com', 13037047497, 1521009250, 2130706433, 1523341233, 0, 1523521712, 1);
INSERT INTO `cui_user` VALUES (12, 'cuiyuanxin2', 'f3da7ac8caab9630c306950b63235091', 'cuiyuanxin2', 'cuiyuanxin2', '15811506097@163.com', 15811506097, 1523267136, 0, 0, 0, 1523330261, 1);
INSERT INTO `cui_user` VALUES (13, 'cuiyuanxin5', 'e0099adb99e85085b5d55d5e83c9970a', 'cuiyuanxin5', 'cuiyuanxin5', 'admin@qq.com', 15811506097, 1523523747, 0, 0, 0, 1523523747, 1);
INSERT INTO `cui_user` VALUES (14, 'cuiyuanxin6', 'e0099adb99e85085b5d55d5e83c9970a', 'cuiyuanxin6', 'cuiyuanxin6', 'admin@qq.com', 15811506097, 1523526212, 0, 1523526318, 0, 1523526421, 1);

SET FOREIGN_KEY_CHECKS = 1;
