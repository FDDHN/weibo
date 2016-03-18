/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 60004
Source Host           : localhost:3306
Source Database       : weibo

Target Server Type    : MYSQL
Target Server Version : 60004
File Encoding         : 65001

Date: 2014-12-15 15:59:57
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `comments`
-- ----------------------------
DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weibo_id` int(11) DEFAULT NULL,
  `com_owner` varchar(200) DEFAULT NULL,
  `content` varchar(200) DEFAULT NULL,
  `com_person` varchar(200) DEFAULT NULL,
  `com_time` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=78 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of comments
-- ----------------------------
INSERT INTO `comments` VALUES ('75', '225', '杨幂', '好美呀', '美美', '2014-12-15 14:55:16');
INSERT INTO `comments` VALUES ('76', '225', '杨幂', '很爱很爱', '美美', '2014-12-15 14:55:34');
INSERT INTO `comments` VALUES ('77', '225', '杨幂', '哈哈，我龙马！', '龙马', '2014-12-15 14:57:38');

-- ----------------------------
-- Table structure for `microblog`
-- ----------------------------
DROP TABLE IF EXISTS `microblog`;
CREATE TABLE `microblog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner` varchar(300) DEFAULT NULL,
  `content` varchar(200) DEFAULT NULL,
  `publishtime` datetime DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `trans_owner` varchar(255) DEFAULT NULL,
  `vedio` varchar(255) DEFAULT NULL,
  `music` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=228 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of microblog
-- ----------------------------
INSERT INTO `microblog` VALUES ('224', '美美', '上传音乐', '2014-12-15 12:26:02', null, null, null, 'php/files/musics/1418617363236.mp3');
INSERT INTO `microblog` VALUES ('219', '龙马', '今天去打球了！', '2014-12-15 11:45:30', null, null, null, null);
INSERT INTO `microblog` VALUES ('220', '龙马', '哈哈，我很高兴！', '2014-12-15 11:47:26', 'php/files/images/1418615233244.gif', null, null, null);
INSERT INTO `microblog` VALUES ('221', '美美', '大家好，我是美美！<img src=\"php/imgs/大笑.gif\" style=\"width:25px;height:25px;\"/><img src=\"php/imgs/开心.gif\" style=\"width:25px;height:25px;\"/>', '2014-12-15 11:57:05', null, null, null, null);
INSERT INTO `microblog` VALUES ('222', '美美', '这是一个图片微博', '2014-12-15 12:10:03', 'php/files/images/1418616592831.jpg', null, null, null);
INSERT INTO `microblog` VALUES ('223', '美美', '上传视频', '2014-12-15 12:15:17', null, null, 'php/files/vedios/1418616833228.mp4', null);
INSERT INTO `microblog` VALUES ('225', '杨幂', '哈哈，我是杨幂', '2014-12-15 12:50:01', null, null, null, null);
INSERT INTO `microblog` VALUES ('226', '美美', '哈哈，我是杨幂', '2014-12-15 15:29:09', null, '杨幂', null, null);
INSERT INTO `microblog` VALUES ('227', '美美', '今天去打球了！', '2014-12-15 15:32:16', null, '龙马', null, null);

-- ----------------------------
-- Table structure for `privatelatter`
-- ----------------------------
DROP TABLE IF EXISTS `privatelatter`;
CREATE TABLE `privatelatter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fromperson` varchar(255) DEFAULT NULL,
  `lattercontent` varchar(1000) DEFAULT NULL,
  `toperson` varchar(255) DEFAULT NULL,
  `lattertime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of privatelatter
-- ----------------------------
INSERT INTO `privatelatter` VALUES ('57', '龙马', '你好呀！', '美美', '2014-12-15 12:33:59');
INSERT INTO `privatelatter` VALUES ('58', '美美', '你好，很高兴认识你！', '龙马', '2014-12-15 12:45:36');

-- ----------------------------
-- Table structure for `relationship`
-- ----------------------------
DROP TABLE IF EXISTS `relationship`;
CREATE TABLE `relationship` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner` varchar(200) DEFAULT NULL,
  `attentions` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=68 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of relationship
-- ----------------------------
INSERT INTO `relationship` VALUES ('65', '美美', '杨幂');
INSERT INTO `relationship` VALUES ('66', '美美', '龙马');
INSERT INTO `relationship` VALUES ('67', '龙马', '杨幂');

-- ----------------------------
-- Table structure for `userinfo`
-- ----------------------------
DROP TABLE IF EXISTS `userinfo`;
CREATE TABLE `userinfo` (
  `username` varchar(30) NOT NULL,
  `password` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `datetime` date DEFAULT NULL,
  `headpicture` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of userinfo
-- ----------------------------
INSERT INTO `userinfo` VALUES ('杨幂', 'cdcdb3bf0e1b87bf97d1a198210bef3b', 'ym@qq.com', '2014-12-15', '/weibo2/php/files/headpictures/1418609284882.jpg');
INSERT INTO `userinfo` VALUES ('龙马', '6512bd43d9caa6e02c990b0a82652dca', 'longma@hotmail.com', '2014-12-15', '/weibo2/php/files/headpictures/1418608702874.jpg');
INSERT INTO `userinfo` VALUES ('美美', '6512bd43d9caa6e02c990b0a82652dca', 'meimei@qq.com', '2014-12-15', '/weibo2/php/files/headpictures/1418610638777.jpg');
