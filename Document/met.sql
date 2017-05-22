/*
Navicat MariaDB Data Transfer

Source Server         : localhost
Source Server Version : 100113
Source Host           : localhost:3306
Source Database       : mets

Target Server Type    : MariaDB
Target Server Version : 100113
File Encoding         : 65001

Date: 2016-12-19 09:56:10
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for acl
-- ----------------------------
DROP TABLE IF EXISTS `acl`;
CREATE TABLE `acl` (
  `a_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '后台用户权限 id',
  `u_id` int(10) unsigned NOT NULL COMMENT '后台登录用户 id',
  `resource_id` varchar(255) NOT NULL COMMENT '后台用户权限',
  PRIMARY KEY (`a_id`),
  UNIQUE KEY `UNQ_ACL_U_ID` (`u_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='后台权限表';

-- ----------------------------
-- Records of acl
-- ----------------------------
INSERT INTO `acl` VALUES ('1', '1', 'all');
INSERT INTO `acl` VALUES ('2', '2', 'a:10:{i:0;s:1:\"1\";i:1;s:3:\"1_1\";i:2;s:5:\"1_1_1\";i:3;s:5:\"1_1_2\";i:4;s:5:\"1_1_3\";i:5;s:1:\"3\";i:6;s:3:\"3_1\";i:7;s:3:\"7_1\";i:8;s:5:\"7_1_1\";i:9;s:5:\"7_1_2\";}');
INSERT INTO `acl` VALUES ('3', '3', 'a:7:{i:0;s:1:\"1\";i:1;s:3:\"1_1\";i:2;s:5:\"1_1_1\";i:3;s:5:\"1_1_2\";i:4;s:5:\"1_1_3\";i:5;s:5:\"1_1_4\";i:6;s:5:\"1_1_5\";}');
INSERT INTO `acl` VALUES ('4', '5', '0');
INSERT INTO `acl` VALUES ('5', '6', '0');
INSERT INTO `acl` VALUES ('6', '10', '0');
INSERT INTO `acl` VALUES ('7', '11', '0');
INSERT INTO `acl` VALUES ('8', '12', '0');
INSERT INTO `acl` VALUES ('9', '13', 'a:0:{}');
INSERT INTO `acl` VALUES ('10', '14', 'a:0:{}');
INSERT INTO `acl` VALUES ('11', '15', '0');
INSERT INTO `acl` VALUES ('12', '19', 'a:9:{i:0;s:1:\"1\";i:1;s:3:\"1_1\";i:2;s:5:\"1_1_1\";i:3;s:5:\"1_1_2\";i:4;s:5:\"1_1_3\";i:5;s:5:\"1_1_4\";i:6;s:5:\"1_1_5\";i:7;s:3:\"1_2\";i:8;s:5:\"1_2_1\";}');
INSERT INTO `acl` VALUES ('13', '20', '0');
INSERT INTO `acl` VALUES ('14', '21', '0');
INSERT INTO `acl` VALUES ('15', '22', '0');
INSERT INTO `acl` VALUES ('16', '23', '0');
INSERT INTO `acl` VALUES ('17', '24', 'a:8:{i:0;s:1:\"1\";i:1;s:3:\"1_1\";i:2;s:5:\"1_1_1\";i:3;s:5:\"1_1_2\";i:4;s:3:\"7_1\";i:5;s:5:\"7_1_1\";i:6;s:5:\"7_1_2\";i:7;s:5:\"7_1_3\";}');

-- ----------------------------
-- Table structure for admin_user
-- ----------------------------
DROP TABLE IF EXISTS `admin_user`;
CREATE TABLE `admin_user` (
  `u_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '后台登录用户 id',
  `username` varchar(32) NOT NULL COMMENT '登录名',
  `password` varchar(100) NOT NULL DEFAULT '' COMMENT '密码',
  `realname` varchar(32) NOT NULL COMMENT '真实姓名',
  `email` varchar(128) NOT NULL COMMENT '邮箱',
  `gender` tinyint(2) NOT NULL COMMENT '性别',
  `phone` bigint(13) NOT NULL COMMENT '电话号码',
  `position` varchar(64) NOT NULL COMMENT '职位',
  `created` int(13) NOT NULL COMMENT '创建时间',
  `modified` int(13) NOT NULL COMMENT '修改时间',
  `lognum` smallint(6) NOT NULL COMMENT '登录次数',
  `is_active` tinyint(2) NOT NULL COMMENT '是否激活',
  `is_lock` tinyint(2) NOT NULL COMMENT '是否锁定帐号',
  PRIMARY KEY (`u_id`),
  UNIQUE KEY `UNQ_ADMIN_USER_USERNAME` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='后台登录用户表';

-- ----------------------------
-- Records of admin_user
-- ----------------------------
INSERT INTO `admin_user` VALUES ('1', 'admins', '579d9ec9d0c3d687aaa91289ac2854e4', 'luoyi', '', '1', '1869336609', 'php', '0', '0', '61', '1', '0');
INSERT INTO `admin_user` VALUES ('14', '678678', '202cb962ac59075b964b07152d234b70', '地方的', '', '1', '90987654321', 'er而热饿', '0', '0', '0', '0', '0');
INSERT INTO `admin_user` VALUES ('15', '352525', '579d9ec9d0c3d687aaa91289ac2854e4', '地方', '', '2', '12345678901', '版本', '0', '0', '0', '0', '0');
INSERT INTO `admin_user` VALUES ('20', '666666', '202cb962ac59075b964b07152d234b70', '蓝色的', '', '1', '12345678901', '个发送', '0', '0', '0', '0', '0');
INSERT INTO `admin_user` VALUES ('23', '123123', '65d387a1cf29ac8bb1988bd6ae83aeb6', '是', '', '1', '32423', ' 啊', '0', '0', '0', '0', '0');
INSERT INTO `admin_user` VALUES ('24', '66666666', '579d9ec9d0c3d687aaa91289ac2854e4', '是', '', '2', '545756', '的', '0', '0', '3', '0', '0');

-- ----------------------------
-- Table structure for goods
-- ----------------------------
DROP TABLE IF EXISTS `goods`;
CREATE TABLE `goods` (
  `g_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品ID',
  `goods_num` int(13) unsigned NOT NULL COMMENT '商品号',
  `title` varchar(64) NOT NULL COMMENT '商品名',
  `created` int(13) unsigned NOT NULL COMMENT '商品创建时间',
  `modified` int(13) NOT NULL COMMENT '商品修改时间',
  `is_active` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '是否激活.1表示通过审核.2表示拒绝',
  `sort` smallint(6) unsigned NOT NULL COMMENT '排序',
  `u_id` int(10) unsigned NOT NULL COMMENT '创建者ID',
  `integral` decimal(12,2) NOT NULL COMMENT '积分',
  `price` decimal(12,2) unsigned NOT NULL COMMENT '商品价格',
  `special_price` decimal(12,2) unsigned NOT NULL COMMENT '特价',
  PRIMARY KEY (`g_id`),
  KEY `IDX_GOODS_NUM` (`goods_num`),
  KEY `IDX_GOODS_TITLE` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of goods
-- ----------------------------

-- ----------------------------
-- Table structure for goods_info
-- ----------------------------
DROP TABLE IF EXISTS `goods_info`;
CREATE TABLE `goods_info` (
  `gi_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品信息 ID',
  `g_id` int(10) unsigned NOT NULL COMMENT '商品ID',
  `header_img` varchar(128) NOT NULL COMMENT '标题图片',
  `img` varchar(128) NOT NULL COMMENT '商品详细页图片',
  `content` text NOT NULL COMMENT '商品详细页内容',
  `desc` varchar(255) NOT NULL COMMENT '商品描述',
  PRIMARY KEY (`gi_id`),
  KEY `IDX_GOODS_INFO_G_ID` (`g_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of goods_info
-- ----------------------------

-- ----------------------------
-- Table structure for log_event
-- ----------------------------
DROP TABLE IF EXISTS `log_event`;
CREATE TABLE `log_event` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志 ID',
  `ip` varchar(16) NOT NULL COMMENT 'ip地址',
  `x_forwarded_ip` varchar(16) DEFAULT NULL COMMENT '代理 ip',
  `event_code` varchar(64) NOT NULL COMMENT '操作标题',
  `time` int(13) NOT NULL COMMENT '操作时间',
  `action` varchar(64) NOT NULL COMMENT '具体操作',
  `info` varchar(255) NOT NULL COMMENT '操作说明',
  `status` tinyint(2) NOT NULL COMMENT '状态 0失败1成功',
  `u_id` int(10) unsigned NOT NULL COMMENT '用户 Id',
  `fullaction` varchar(200) DEFAULT NULL COMMENT '具体地址',
  `error_message` text COMMENT '错误信息',
  PRIMARY KEY (`log_id`),
  KEY `IDX_LOG_EVENT_USER_ID` (`u_id`)
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of log_event
-- ----------------------------
INSERT INTO `log_event` VALUES ('1', '', null, 'admin login', '0', 'index', 'test', '1', '0', '/index.php/Test/index', '');
INSERT INTO `log_event` VALUES ('2', '', null, 'admin login', '0', 'index', 'test', '1', '0', '/index.php/Test/index', '');
INSERT INTO `log_event` VALUES ('3', '192.168.120.80', null, 'admin login', '1481261397', 'index', 'test++', '1', '1', '/index.php/Test/index', '');
INSERT INTO `log_event` VALUES ('4', '192.168.120.80', null, 'Admin login', '1481262069', 'logInto', '用户登录', '1', '1', '/index.php/Login/logInto', '');
INSERT INTO `log_event` VALUES ('5', '192.168.120.80', null, 'Admin login', '1481262895', 'logInto', '用户登录', '1', '1', '/index.php/Login/logInto', '');
INSERT INTO `log_event` VALUES ('6', '192.168.120.80', null, 'Admin login', '1481268928', 'logInto', '用户登录', '1', '1', '/index.php/Login/logInto', '');
INSERT INTO `log_event` VALUES ('7', '192.168.120.80', null, 'Admin login', '1481268985', 'logInto', '用户登录', '1', '1', '/index.php/Login/logInto', '');
INSERT INTO `log_event` VALUES ('8', '192.168.120.12', null, 'Admin login', '1481507820', 'logInto', '用户登录', '1', '1', '/index.php/Login/logInto', '');
INSERT INTO `log_event` VALUES ('9', '192.168.120.12', null, 'admin login', '1481523086', 'indexs', 'test', '1', '1', '/index.php/Test/indexs', '');
INSERT INTO `log_event` VALUES ('10', '192.168.120.12', null, 'admin login', '1481523108', 'indexs', 'test', '1', '1', '/index.php/Test/indexs', '');
INSERT INTO `log_event` VALUES ('11', '192.168.120.12', null, 'Admin login', '1481594723', 'logInto', '用户登录', '1', '1', '/index.php/Login/logInto', '');
INSERT INTO `log_event` VALUES ('12', '192.168.120.12', null, 'Admin login', '1481680621', 'logInto', '用户登录', '1', '1', '/index.php/Login/logInto', '');
INSERT INTO `log_event` VALUES ('13', '127.0.0.1', null, 'Admin login', '1481699443', 'logInto', '用户登录', '1', '3', '/index.php/Login/logInto', '');
INSERT INTO `log_event` VALUES ('14', '127.0.0.1', null, 'Admin login', '1481699691', 'logInto', '用户登录', '1', '1', '/index.php/Login/logInto', '');
INSERT INTO `log_event` VALUES ('15', '127.0.0.1', null, 'Admin login', '1481699950', 'logInto', '用户登录', '1', '1', '/index.php/Login/logInto', '');
INSERT INTO `log_event` VALUES ('16', '127.0.0.1', null, 'Admin login', '1481700153', 'logInto', '用户登录', '1', '1', '/index.php/Login/logInto', '');
INSERT INTO `log_event` VALUES ('17', '127.0.0.1', null, 'Admin login', '1481700514', 'logInto', '用户登录', '1', '3', '/index.php/Login/logInto', '');
INSERT INTO `log_event` VALUES ('19', '127.0.0.1', null, 'Add User', '1481708371', 'addUser', '添加用户 565656', '1', '1', '/index.php/Acl/addUser', '');
INSERT INTO `log_event` VALUES ('20', '127.0.0.1', null, 'delete User', '1481708852', 'deleteUser', '删除用户: 3', '1', '1', '/index.php/Acl/deleteUser', '');
INSERT INTO `log_event` VALUES ('21', '127.0.0.1', null, 'edit User', '1481708906', 'editUser', '修改用户: 678678', '1', '1', '/index.php/Acl/editUser', '');
INSERT INTO `log_event` VALUES ('22', '127.0.0.1', null, 'Admin login', '1481709436', 'logInto', '用户登录', '1', '19', '/index.php/Login/logInto', '');
INSERT INTO `log_event` VALUES ('23', '127.0.0.1', null, 'Admin login', '1481709663', 'logInto', '用户登录', '1', '19', '/index.php/Login/logInto', '');
INSERT INTO `log_event` VALUES ('24', '127.0.0.1', null, 'Admin login', '1481767271', 'logInto', '用户登录', '1', '1', '/index.php/Login/logInto', '');
INSERT INTO `log_event` VALUES ('25', '127.0.0.1', null, 'Admin login', '1481772876', 'logInto', '用户登录', '1', '1', '/index.php/Login/logInto', '');
INSERT INTO `log_event` VALUES ('26', '127.0.0.1', null, 'admin login', '1481779385', 'logInto', 'admins:用户登录', '1', '1', '/index.php/Login/logInto', '');
INSERT INTO `log_event` VALUES ('27', '127.0.0.1', null, '', '1481779517', 'logInto', 'admins:用户登录', '1', '1', '/index.php/Login/logInto', '');
INSERT INTO `log_event` VALUES ('28', '127.0.0.1', null, '', '1481780464', 'user', '浏览后台用户信息', '1', '1', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('29', '127.0.0.1', null, '', '1481785743', 'user', '浏览后台用户信息', '1', '1', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('30', '127.0.0.1', null, '', '1481785743', 'user', '浏览后台用户信息', '1', '1', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('31', '127.0.0.1', null, '', '1481785793', 'user', '浏览后台用户信息', '1', '1', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('32', '127.0.0.1', null, '', '1481785794', 'user', '浏览后台用户信息', '1', '1', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('33', '127.0.0.1', null, '', '1481785877', 'addUser', '添加后台用户:555555', '1', '1', '/index.php/Acl/addUser', '');
INSERT INTO `log_event` VALUES ('34', '127.0.0.1', null, '', '1481785881', 'user', '浏览后台用户信息', '1', '1', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('35', '127.0.0.1', null, '', '1481785922', 'editUser', '编辑后台用户信息', '1', '1', '/index.php/Acl/editUser', '');
INSERT INTO `log_event` VALUES ('36', '127.0.0.1', null, '', '1481785978', 'editUser', '编辑后台用户666666信息', '1', '1', '/index.php/Acl/editUser', '');
INSERT INTO `log_event` VALUES ('37', '127.0.0.1', null, '', '1481786277', 'deleteUser', '删除后台用户', '1', '1', '/index.php/Acl/deleteUser', '');
INSERT INTO `log_event` VALUES ('38', '127.0.0.1', null, '', '1481786281', 'user', '浏览后台用户信息', '1', '1', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('39', '127.0.0.1', null, '', '1481786443', 'addUser', '添加后台用户:6756765', '1', '1', '/index.php/Acl/addUser', '');
INSERT INTO `log_event` VALUES ('40', '127.0.0.1', null, '', '1481786470', 'user', '浏览后台用户信息', '1', '1', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('41', '127.0.0.1', null, '', '1481786496', 'addUser', '添加后台用户:123123', '1', '1', '/index.php/Acl/addUser', '');
INSERT INTO `log_event` VALUES ('42', '127.0.0.1', null, '', '1481786557', 'user', '浏览后台用户信息', '1', '1', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('43', '127.0.0.1', null, '', '1481786557', 'user', '浏览后台用户信息', '1', '1', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('44', '127.0.0.1', null, '', '1481786557', 'user', '浏览后台用户信息', '1', '1', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('45', '127.0.0.1', null, '', '1481786557', 'user', '浏览后台用户信息', '1', '1', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('46', '127.0.0.1', null, '', '1481786572', 'addUser', '添加后台用户:45345', '1', '1', '/index.php/Acl/addUser', '');
INSERT INTO `log_event` VALUES ('47', '127.0.0.1', null, '', '1481786572', 'user', '浏览后台用户信息', '1', '1', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('48', '127.0.0.1', null, '', '1481786643', 'editUser', '编辑后台用户:45345sd的信息', '1', '1', '/index.php/Acl/editUser', '');
INSERT INTO `log_event` VALUES ('49', '127.0.0.1', null, '', '1481786689', 'user', '浏览后台用户信息', '1', '1', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('50', '127.0.0.1', null, '', '1481786689', 'user', '浏览后台用户信息', '1', '1', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('51', '127.0.0.1', null, '', '1481786729', 'user', '浏览后台用户信息', '1', '1', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('52', '127.0.0.1', null, '', '1481786730', 'user', '浏览后台用户信息', '1', '1', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('53', '127.0.0.1', null, '', '1481786730', 'user', '浏览后台用户信息', '1', '1', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('54', '127.0.0.1', null, '', '1481786730', 'user', '浏览后台用户信息', '1', '1', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('55', '127.0.0.1', null, '', '1481786745', 'deleteUser', '删除后台用户', '1', '1', '/index.php/Acl/deleteUser', '');
INSERT INTO `log_event` VALUES ('56', '127.0.0.1', null, '', '1481786748', 'user', '浏览后台用户信息', '1', '1', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('57', '127.0.0.1', null, '', '1481786868', 'user', '浏览后台用户信息', '1', '1', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('58', '127.0.0.1', null, '', '1481786868', 'user', '浏览后台用户信息', '1', '1', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('59', '127.0.0.1', null, '', '1481786869', 'user', '浏览后台用户信息', '1', '1', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('60', '127.0.0.1', null, '', '1481786869', 'user', '浏览后台用户信息', '1', '1', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('61', '127.0.0.1', null, '', '1481786869', 'user', '浏览后台用户信息', '1', '1', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('62', '127.0.0.1', null, '', '1481786873', 'deleteUser', '删除后台用户', '1', '1', '/index.php/Acl/deleteUser', '');
INSERT INTO `log_event` VALUES ('63', '127.0.0.1', null, '', '1481786873', 'user', '浏览后台用户信息', '1', '1', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('64', '127.0.0.1', null, '', '1481786903', 'editUser', '编辑后台用户:66666666的信息', '1', '1', '/index.php/Acl/editUser', '');
INSERT INTO `log_event` VALUES ('65', '127.0.0.1', null, '', '1481786969', 'logInto', '66666666:用户登录', '1', '24', '/index.php/Login/logInto', '');
INSERT INTO `log_event` VALUES ('66', '127.0.0.1', null, '', '1481786977', 'save', '设置后台用户权限', '1', '1', '/index.php/Acl/save', '');
INSERT INTO `log_event` VALUES ('67', '127.0.0.1', null, '', '1481787051', 'save', '设置后台用户权限', '1', '1', '/index.php/Acl/save', '');
INSERT INTO `log_event` VALUES ('68', '127.0.0.1', null, '', '1481787081', 'user', '浏览后台用户信息', '1', '24', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('69', '127.0.0.1', null, '', '1481787118', 'user', '浏览后台用户信息', '1', '24', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('70', '127.0.0.1', null, '', '1481787151', 'user', '浏览后台用户信息', '1', '24', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('71', '127.0.0.1', null, '', '1481787190', 'save', '设置后台用户权限', '1', '1', '/index.php/Acl/save', '');
INSERT INTO `log_event` VALUES ('72', '127.0.0.1', null, '', '1481787215', 'save', '设置后台用户权限', '1', '1', '/index.php/Acl/save', '');
INSERT INTO `log_event` VALUES ('73', '127.0.0.1', null, '', '1481787255', 'user', '浏览后台用户信息', '1', '1', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('74', '127.0.0.1', null, '', '1481787267', 'save', '设置后台用户权限', '1', '1', '/index.php/Acl/save', '');
INSERT INTO `log_event` VALUES ('75', '127.0.0.1', null, '', '1481787357', 'save', '设置后台用户权限', '1', '1', '/index.php/Acl/save', '');
INSERT INTO `log_event` VALUES ('76', '127.0.0.1', null, '', '1481787475', 'logInto', '66666666:用户登录', '1', '24', '/index.php/Login/logInto', '');
INSERT INTO `log_event` VALUES ('77', '127.0.0.1', null, '', '1481787487', 'logout', '设置后台用户权限', '1', '24', '/index.php/Login/logout', '');
INSERT INTO `log_event` VALUES ('78', '127.0.0.1', null, '', '1481787499', 'logInto', '66666666:用户登录', '1', '24', '/index.php/Login/logInto', '');
INSERT INTO `log_event` VALUES ('79', '127.0.0.1', null, 'admin login', '1481787621', 'index', 'test', '1', '24', '/index.php/Test/index', '');
INSERT INTO `log_event` VALUES ('80', '127.0.0.1', null, '', '1481787858', 'save', '设置后台用户权限', '1', '1', '/index.php/Acl/save', '');
INSERT INTO `log_event` VALUES ('81', '127.0.0.1', null, '', '1481787993', 'logout', 'admins退出', '1', '1', '/index.php/Login/logout', '');
INSERT INTO `log_event` VALUES ('82', '127.0.0.1', null, '', '1481788003', 'logInto', 'admins:用户登录', '1', '1', '/index.php/Login/logInto', '');
INSERT INTO `log_event` VALUES ('83', '127.0.0.1', null, '', '1481788098', 'user', '浏览后台用户信息', '1', '1', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('84', '127.0.0.1', null, '', '1481788103', 'user', '浏览后台用户信息', '1', '1', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('85', '127.0.0.1', null, '', '1481788148', 'save', '设置后台用户权限', '1', '1', '/index.php/Acl/save', '');
INSERT INTO `log_event` VALUES ('86', '127.0.0.1', null, '', '1481788155', 'user', '浏览后台用户信息', '1', '24', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('87', '127.0.0.1', null, '', '1481788172', 'save', '设置后台用户权限', '1', '1', '/index.php/Acl/save', '');
INSERT INTO `log_event` VALUES ('88', '127.0.0.1', null, '', '1481788181', 'user', '浏览后台用户信息', '1', '24', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('89', '127.0.0.1', null, '', '1481853356', 'logInto', 'admins:用户登录', '1', '1', '/index.php/Login/logInto', '');
INSERT INTO `log_event` VALUES ('90', '127.0.0.1', null, '', '1481867850', 'user', '浏览后台用户信息', '1', '1', '/index.php/Acl/user', '');
INSERT INTO `log_event` VALUES ('91', '127.0.0.1', null, '', '1482112043', 'logInto', 'admins:用户登录', '1', '1', '/index.php/Login/logInto', '');

-- ----------------------------
-- Table structure for log_event_changes
-- ----------------------------
DROP TABLE IF EXISTS `log_event_changes`;
CREATE TABLE `log_event_changes` (
  `c_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '具体更改内容 id',
  `log_id` int(11) unsigned DEFAULT NULL COMMENT '日志 id',
  `original_data` text COMMENT '更改前数据',
  `result_data` text COMMENT '更改后数据',
  PRIMARY KEY (`c_id`),
  KEY `IDX_LOG_EVENT_CHANGES_LOG_ID` (`log_id`),
  CONSTRAINT `FK_ENT_LOG_EVENT_CHANGES_LOG_ID_ENT_LOG_EVENT_LOG_ID` FOREIGN KEY (`log_id`) REFERENCES `log_event` (`log_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of log_event_changes
-- ----------------------------
INSERT INTO `log_event_changes` VALUES ('1', '1', '1', '1');

-- ----------------------------
-- Table structure for notice
-- ----------------------------
DROP TABLE IF EXISTS `notice`;
CREATE TABLE `notice` (
  `n_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '公告 id',
  `title` varchar(64) NOT NULL COMMENT '公告标题',
  `img` varchar(128) NOT NULL COMMENT '顶部banner slider',
  `link` varchar(128) NOT NULL COMMENT '链接地址',
  `sort` smallint(6) NOT NULL COMMENT '排序',
  `created` int(13) NOT NULL COMMENT '创建时间',
  `column` int(6) NOT NULL COMMENT '预留字段',
  PRIMARY KEY (`n_id`),
  KEY `IDX_NOTICE_SORT` (`sort`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='公告表';

-- ----------------------------
-- Records of notice
-- ----------------------------

-- ----------------------------
-- Table structure for order
-- ----------------------------
DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `o_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单ID',
  `order_num` int(13) unsigned NOT NULL COMMENT '订单号',
  `pay` decimal(12,2) unsigned NOT NULL COMMENT '实收',
  `total` decimal(12,2) unsigned NOT NULL COMMENT '商品总价格',
  `change` decimal(12,2) unsigned NOT NULL COMMENT '找零',
  `created` int(13) unsigned NOT NULL COMMENT '下单时间',
  `u_id` int(10) unsigned NOT NULL COMMENT '创建者',
  `s_id` int(10) unsigned NOT NULL COMMENT '商品信息',
  PRIMARY KEY (`o_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of order
-- ----------------------------

-- ----------------------------
-- Table structure for ship
-- ----------------------------
DROP TABLE IF EXISTS `ship`;
CREATE TABLE `ship` (
  `s_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '购物车ID',
  `shiper` varchar(64) NOT NULL COMMENT '购买者',
  `goods_num` varchar(64) NOT NULL COMMENT '商品号',
  `num` smallint(6) unsigned NOT NULL COMMENT '购买数',
  `price` decimal(12,2) NOT NULL COMMENT '当前单价格',
  `total` decimal(12,2) NOT NULL COMMENT '此类商品总数下的价格',
  `created` int(10) NOT NULL COMMENT '创建时间',
  `buy` int(10) NOT NULL COMMENT '分组',
  PRIMARY KEY (`s_id`),
  KEY `IDX_CART_GOODS_NUM` (`goods_num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ship
-- ----------------------------

-- ----------------------------
-- Table structure for subject
-- ----------------------------
DROP TABLE IF EXISTS `subject`;
CREATE TABLE `subject` (
  `s_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '专题 id',
  `title` varchar(64) NOT NULL COMMENT '标题',
  `icon` varchar(64) NOT NULL COMMENT '标题图片',
  `created` int(13) NOT NULL COMMENT '创建时间',
  `modified` int(13) NOT NULL COMMENT '修改时间',
  `sort` smallint(6) NOT NULL COMMENT '排序',
  `is_active` tinyint(2) NOT NULL COMMENT '是否激活1表示通过2表示拒绝3表示删除4表示修改5表示添加',
  `u_id` int(10) unsigned NOT NULL COMMENT '创建者 id',
  `is_sub` tinyint(2) NOT NULL COMMENT '是否是专题',
  `re_id` varchar(64) NOT NULL COMMENT '通过审核后返回的id',
  PRIMARY KEY (`s_id`),
  KEY `IDX_SUBJECT_TITLE` (`title`),
  KEY `IDX_SUBJECT_SORT` (`sort`)
) ENGINE=MyISAM AUTO_INCREMENT=108 DEFAULT CHARSET=utf8 COMMENT='专题表';

-- ----------------------------
-- Records of subject
-- ----------------------------

-- ----------------------------
-- Table structure for subject_cotent
-- ----------------------------
DROP TABLE IF EXISTS `subject_content`;
CREATE TABLE `subject_content` (
  `sc_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '专题内容表',
  `s_id` int(10) unsigned NOT NULL COMMENT '专题 id',
  `type` tinyint(2) unsigned NOT NULL COMMENT '类型1.表示图文2.表示视频',
  `video` varchar(128) NOT NULL COMMENT '视频url',
  `picUrl` varchar(128) NOT NULL COMMENT '视频封面url',
  `pageUrl` varchar(128) NOT NULL COMMENT '专题页面url',
  `u_info` text NOT NULL COMMENT '编辑者信息',
  `content` text NOT NULL COMMENT '专题内容',
  `description` text NOT NULL COMMENT '提交说明',
  `column1` int(10) NOT NULL COMMENT '备用',
  PRIMARY KEY (`sc_id`),
  KEY `IDX_SUBJECT_CONTENT_S_ID` (`s_id`),
  KEY `IDX_SUBJECT_CONTENT_TYPE` (`type`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='专题内容表';

-- ----------------------------
-- Records of subject_cotent
-- ----------------------------

-- ----------------------------
-- Table structure for team
-- ----------------------------
DROP TABLE IF EXISTS `team`;
CREATE TABLE `team` (
  `t_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '战队 ID',
  `title` varchar(64) NOT NULL COMMENT '战队名称',
  `logo` varchar(128) NOT NULL COMMENT '战队logo',
  `declare` varchar(255) NOT NULL COMMENT '战队宣言',
  `brief` text NOT NULL COMMENT '战队简介',
  `is_ck` tinyint(2) NOT NULL COMMENT '是否是创客队伍',
  `ck_num` varchar(32) NOT NULL COMMENT '创客号',
  `created` int(13) unsigned NOT NULL COMMENT '创建时间',
  `modified` int(13) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`t_id`),
  KEY `IDX_TEAM_TITLE` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of team
-- ----------------------------

-- ----------------------------
-- Table structure for team_user
-- ----------------------------
DROP TABLE IF EXISTS `team_user`;
CREATE TABLE `team_user` (
  `tu_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '战队成员ID',
  `t_id` int(10) unsigned NOT NULL COMMENT '战队id',
  `username` varchar(64) NOT NULL COMMENT '姓名',
  `gender` tinyint(2) unsigned NOT NULL COMMENT '性别',
  `age` smallint(3) NOT NULL COMMENT '年龄',
  `skill` varchar(255) NOT NULL COMMENT '技能',
  `prize` varchar(255) NOT NULL COMMENT '获奖经历',
  PRIMARY KEY (`tu_id`),
  KEY `IDX_TEAM_USER_T_ID` (`t_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of team_user
-- ----------------------------

-- ----------------------------
-- Table structure for ticket
-- ----------------------------
DROP TABLE IF EXISTS `ticket`;
CREATE TABLE `ticket` (
  `t_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '票务 ID',
  `img` varchar(128) NOT NULL COMMENT '票务图片',
  `title` varchar(64) NOT NULL COMMENT '票务名称',
  `exp_date` int(13) NOT NULL COMMENT '过期时间',
  `content` text NOT NULL COMMENT '票务内容',
  `is_active` tinyint(2) unsigned NOT NULL COMMENT '是否激活',
  PRIMARY KEY (`t_id`),
  KEY `IDX_TICKET_TITLE` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ticket
-- ----------------------------


-- 2016-12-29 资讯标签库-----------------------------------------------------
DROP TABLE IF EXISTS `tag`;
CREATE TABLE  `tag` (
  `t_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '标签 id',
  `re_id` varchar(32) NOT NULL COMMENT '返回 id',
  `tag` varchar(32) NOT NULL COMMENT '标签',
  `user` varchar(32) NOT NULL COMMENT '创建者',
  `created` int(10) NOT NULL COMMENT '创建时间',
  `modified` int(10) NOT NULL COMMENT '修改时间',
  `is_active` tinyint(2) NOT NULL DEFAULT 0 COMMENT '状态1表是新增2表示修改3表示删除4表示拒绝通过5表示通过',
  PRIMARY KEY (`t_id`),
  KEY `IDX_TAG_TAG` (`tag`),
  KEY `IDX_TAG_RE_ID` (`re_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- 2016-12-30 现场商品购买 -----------------------------------------------------
DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `p_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '产品 id',
  `sku` varchar(32) NOT NULL COMMENT '产品号',
  `name` varchar(64) NOT NULL COMMENT '产品名',
  `price` decimal(10,2) unsigned NOT NULL COMMENT '产品价格',
  `special_price` decimal(10, 2) unsigned NOT NULL COMMENT '产品特殊价格',
  `img` varchar(128) NOT NULL COMMENT '产品图片',
  `stock` int(10) unsigned NOT NULL COMMENT '库存',
  PRIMARY KEY (`p_id`),
  UNIQUE KEY `IDX_PRODUCT_SKU` (`sku`),
  KEY `IDX_PRODUCT_NAME` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--设置积分比例
DROP TABLE IF EXISTS `ratio`;
CREATE TABLE `ratio` (
  `r_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '积分比例 id',
  `rmb` decimal(10, 2) unsigned NOT NULL COMMENT '人民币基准数',
  `integral` decimal(10, 2) unsigned NOT NULL COMMENT '积分基准数',
  `ratio` decimal(10, 3) unsigned NOT NULL COMMENT '人民币/积分 比例',
  `u_id` int(10) unsigned NOT NULL COMMENT '创建者',
  `created` int(10) unsigned NOT NULL COMMENT '创建时间',
  `modified` int(10) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`r_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 用户购物信息
DROP TABLE IF EXISTS `product_ship`;
CREATE TABLE `product_ship` (
  `ps_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '信息id',
  `shiper` varchar(64) NOT NULL COMMENT '购买者',
  `sku` varchar(64) NOT NULL COMMENT '产品号',
  `num` smallint(6) unsigned NOT NULL COMMENT '购买数量',
  `created` int(10) unsigned NOT NULL COMMENT '购买时间',
  `u_id` int(10) unsigned NOT NULL COMMENT '谁进行的后台操作',
  `order_number`  varchar(64)  NOT NULL COMMENT '订单号',
  PRIMARY KEY (`ps_id`),
  KEY `IDX_PRODUCT_SHIP_SKU` (`sku`),
  KEY `IDX_PRODUCT_SHIP_SHIPER` (`shiper`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 订单信息
DROP TABLE IF EXISTS `product_order`;
CREATE TABLE `product_order` (
  `po_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单 id',
  `order_number` varchar(64) NOT NULL COMMENT '订单号',
  `shiper` varchar(64) NOT NULL COMMENT '买家',
  `username` varchar(64) NOT NULL COMMENT '操作人员', 
  `amount` decimal(10, 2) NOT NULL COMMENT '买家实付',
  `total` decimal(10, 2) NOT NULL COMMENT '商品总价',
  `change` decimal(10, 2) NOT NULL COMMENT '找零',
  `ratio` decimal(10, 2) NOT NULL COMMENT '应用积分兑换比例',
  `integral` decimal(12,2) NOT NULL COMMENT '积分',
  `created` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`po_id`),
  UNIQUE KEY `IDX_PRODUCT_ORDER_ORDER_NUMBER` (`order_number`),
  KEY `IDX_PRODUCT_ORDER_SHIPER` (`shiper`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

--积分卡总表
DROP TABLE IF EXISTS `integral_card`;
CREATE TABLE `integral_card` (
  `ic_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '积分卡 id',
  `account` varchar(32) NOT NULL COMMENT '持有者',
  `card_number` varchar(32) NOT NULL COMMENT '卡号',
  `integral` decimal(10, 2) NOT NULL COMMENT '积分',
  `updated` int(10) NOT NULL COMMENT '最近一次修改',
  PRIMARY KEY (`ic_id`),
  KEY `IDX_INTEGRAL_CARD_IC_ID` (`ic_id`),
  KEY `IDX_INTEGRAL_CARD_ACCOUNT` (`account`),
  KEY `IDX_INTEGRAL_CARD_CARD_NUMBER` (`card_number`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '积分总表';

--积分消费详情
DROP TABLE IF EXISTS `integral_info`;
CREATE TABLE `integral_info` (
  `if_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '积分详情 id',
  `account` varchar(32) NOT NULL COMMENT '用户',
  `use` decimal(10, 2) unsigned NOT NULL COMMENT '用掉的积分',
  `get` decimal(10, 2) unsigned NOT NULL COMMENT '获得的积分',
  `desc` varchar(128) NOT NULL COMMENT '说明',
  `g_id` int(10) unsigned NOT NULL COMMENT '买的商品或兑换的商品 id',
  `created` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`if_id`),
  KEY `IDX_INTEGRAL_INFO_ACCOUNT` (`account`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '积分消费详情';

-- 兑换码
DROP TABLE IF EXISTS `redeem_code`;
CREATE TABLE `redeem_code` (
  `rc_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '兑换码 id',
  `account` varchar(32) NOT NULL COMMENT '兑换码的持有者',
  `code` varchar(32) NOT NULL COMMENT '兑换码',
  `sku` varchar(32) NOT NULL COMMENT '商品号',
  `num` tinyint(2) unsigned NOT NULL COMMENT '兑换数量',
  `integral` decimal(10, 2) unsigned NOT NULL COMMENT '积分',
  `created` int(10) unsigned NOT NULL COMMENT '创建时间',
  `status` tinyint(2) unsigned NOT NULL COMMENT '是否使用1表示获得2表示使用了兑换码',
  `modified` int(10) unsigned NOT NULL COMMENT '什么时候使用了兑换码',
  `username` varchar(32) NOT NULL COMMENT '操作人员',
  PRIMARY KEY (`rc_id`),
  KEY `IDX_REDEEM_CODE_ACCOUNT` (`account`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '兑换码信息';

DROP TABLE IF EXISTS `tmp_ticket`;
CREATE TABLE `tmp_ticket` (
  `t_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '票据id',
  `img` varchar(128) NOT NULL COMMENT '票据图片',
  `title` varchar(64) NOT NULL COMMENT '票据标题',
  `exp_date` int(10) NOT NULL DEFAULT 0 COMMENT '过期时间',
  `content` text NOT NULL COMMENT '票据内容',
  `creater` varchar(64) NOT NULL COMMENT '创建者',
  `modified` int(10) NOT NULL DEFAULT 0 COMMENT '修改时间',
  `is_active` tinyint(2) NOT NULL DEFAULT 0 COMMENT '状态',
  PRIMARY KEY (`t_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '票据临时表';

/*-----------2017-1-19----------------*/
DROP TABLE IF EXISTS `report`;
CREATE TABLE `report` (
  `r_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '文字战报id',
  `title` varchar(32) NOT NULL COMMENT '标题',
  `event` varchar(255) NOT NULL COMMENT '内容',
  `source` varchar(32) NOT NULL COMMENT '比分',
  `created` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `modified` int(10) NOT NULL DEFAULT 0 COMMENT '修改时间',
  `another` varchar(32) NOT NULL DEFAULT 0 COMMENT '创建者',
  PRIMARY KEY (`r_id`),
  KEY `IDX_REPORT_TITLE` (`title`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT '文字战报';