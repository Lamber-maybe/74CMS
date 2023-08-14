DROP TABLE IF EXISTS `qs_ad`;
CREATE TABLE `qs_ad` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `is_display` tinyint(1) NOT NULL DEFAULT '1',
  `cid` int(10) NOT NULL,
  `title` varchar(100) NOT NULL,
  `imageid` int(10) unsigned NOT NULL,
  `imageurl` varchar(255) NOT NULL,
  `explain` varchar(255) NOT NULL,
  `sort_id` int(10) unsigned NOT NULL DEFAULT '0',
  `starttime` int(10) unsigned NOT NULL,
  `deadline` int(10) NOT NULL DEFAULT '0',
  `target` tinyint(1) unsigned NOT NULL,
  `link_url` varchar(255) NOT NULL,
  `inner_link` varchar(30) NOT NULL,
  `inner_link_params` int(10) NOT NULL,
  `company_id` int(10) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_ad||-_-||

DROP TABLE IF EXISTS `qs_ad_category`;
CREATE TABLE `qs_ad_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `width` int(10) NOT NULL,
  `height` int(10) NOT NULL,
  `ad_num` int(10) unsigned NOT NULL,
  `is_sys` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `platform` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_ad_category||-_-||

DROP TABLE IF EXISTS `qs_admin`;
CREATE TABLE `qs_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(15) NOT NULL,
  `password` varchar(32) NOT NULL,
  `pwd_hash` varchar(10) NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `last_login_time` int(10) unsigned NOT NULL,
  `last_login_ip` varchar(30) NOT NULL,
  `last_login_ipaddress` varchar(30) NOT NULL,
  `openid` varchar(50) NOT NULL DEFAULT '',
  `is_sc` tinyint(3) NOT NULL DEFAULT 0 COMMENT '是否是销售',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_admin||-_-||

DROP TABLE IF EXISTS `qs_admin_log`;
CREATE TABLE `qs_admin_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL,
  `admin_name` varchar(30) NOT NULL,
  `content` varchar(255) NOT NULL,
  `is_login` tinyint(1) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `ip` varchar(30) NOT NULL,
  `ip_addr` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `index_fulltext_index` (`content`) /*!50100 WITH PARSER `ngram` */
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_admin_log||-_-||

DROP TABLE IF EXISTS `qs_admin_role`;
CREATE TABLE `qs_admin_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(15) NOT NULL,
  `access` text NOT NULL,
  `access_mobile` text NOT NULL,
  `access_export` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `access_delete` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `access_set_service` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_admin_role||-_-||


DROP TABLE IF EXISTS `qs_ali_axb`;
CREATE TABLE `qs_ali_axb` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `a` varchar(15) NOT NULL,
  `b` varchar(15) NOT NULL,
  `x` varchar(15) NOT NULL DEFAULT '',
  `sub_id` varchar(20) NOT NULL DEFAULT '',
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_ali_axb||-_-||


DROP TABLE IF EXISTS `qs_article`;
CREATE TABLE `qs_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` longtext NOT NULL,
  `attach` text NOT NULL,
  `thumb` int(10) unsigned NOT NULL DEFAULT '0',
  `is_display` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `link_url` varchar(200) NOT NULL DEFAULT '',
  `seo_keywords` varchar(100) NOT NULL DEFAULT '',
  `seo_description` varchar(200) NOT NULL DEFAULT '',
  `click` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL,
  `sort_id` int(10) unsigned NOT NULL DEFAULT '0',
  `source` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_click` (`click`),
  KEY `index_addtime` (`addtime`),
  KEY `index_cid_sort_id` (`cid`,`sort_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_article||-_-||

DROP TABLE IF EXISTS `qs_article_category`;
CREATE TABLE `qs_article_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL,
  `sort_id` int(10) unsigned NOT NULL DEFAULT '0',
  `seo_description` varchar(200) NOT NULL DEFAULT '',
  `seo_keywords` varchar(100) NOT NULL DEFAULT '',
  `is_sys` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_article_category||-_-||

DROP TABLE IF EXISTS `qs_attention_company`;
CREATE TABLE `qs_attention_company` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_uid` int(10) unsigned NOT NULL,
  `personal_uid` int(10) unsigned NOT NULL,
  `comid` int(10) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_company_uid` (`company_uid`),
  KEY `index_personal_uid` (`personal_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_attention_company||-_-||

DROP TABLE IF EXISTS `qs_category`;
CREATE TABLE `qs_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `sort_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `index_c_alias` (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_category||-_-||

DROP TABLE IF EXISTS `qs_category_district`;
CREATE TABLE `qs_category_district` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `name` varchar(30) NOT NULL,
  `spell` varchar(50) NOT NULL,
  `alias` varchar(10) NOT NULL,
  `sort_id` int(10) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_category_district||-_-||

DROP TABLE IF EXISTS `qs_category_group`;
CREATE TABLE `qs_category_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `is_sys` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_category_group||-_-||

DROP TABLE IF EXISTS `qs_category_job`;
CREATE TABLE `qs_category_job` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `name` varchar(80) NOT NULL,
  `sort_id` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `spell` varchar(200) NOT NULL,
  `level` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_parentid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_category_job||-_-||

DROP TABLE IF EXISTS `qs_category_major`;
CREATE TABLE `qs_category_major` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `sort_id` int(10) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_category_major||-_-||

DROP TABLE IF EXISTS `qs_company`;
CREATE TABLE `qs_company` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `companyname` varchar(60) NOT NULL,
  `short_name` varchar(60) NOT NULL,
  `nature` int(10) unsigned NOT NULL,
  `trade` int(10) unsigned NOT NULL,
  `district1` int(10) unsigned NOT NULL,
  `district2` int(10) unsigned NOT NULL,
  `district3` int(10) unsigned NOT NULL,
  `district` int(10) unsigned NOT NULL,
  `scale` int(10) unsigned NOT NULL,
  `registered` varchar(15) NOT NULL,
  `currency` tinyint(1) unsigned NOT NULL,
  `tag` varchar(100) NOT NULL,
  `map_lat` decimal(9,6) NOT NULL,
  `map_lng` decimal(9,6) NOT NULL,
  `map_zoom` tinyint(1) unsigned NOT NULL,
  `audit` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `logo` int(10) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `refreshtime` int(10) unsigned NOT NULL,
  `updatetime` int(10) unsigned NOT NULL COMMENT '更新时间',
  `click` int(10) unsigned NOT NULL DEFAULT '1',
  `robot` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `user_status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `cs_id` int(10) unsigned NOT NULL DEFAULT '0',
  `platform` varchar(30) NOT NULL DEFAULT '',
  `setmeal_id` int(10) unsigned NOT NULL DEFAULT '0',
  `is_display` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_company||-_-||

DROP TABLE IF EXISTS `qs_company_auth`;
CREATE TABLE `qs_company_auth` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `legal_person_idcard_front` int(10) unsigned NOT NULL,
  `legal_person_idcard_back` int(10) unsigned NOT NULL,
  `license` int(10) unsigned NOT NULL,
  `proxy` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_comid_uid` (`comid`,`uid`),
  UNIQUE KEY `index_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_company_auth||-_-||

DROP TABLE IF EXISTS `qs_company_auth_log`;
CREATE TABLE `qs_company_auth_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `audit` tinyint(1) unsigned NOT NULL,
  `reason` varchar(200) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_comid_uid` (`comid`,`uid`),
  KEY `index_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_company_auth_log||-_-||

DROP TABLE IF EXISTS `qs_company_contact`;
CREATE TABLE `qs_company_contact` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `contact` varchar(30) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `weixin` varchar(15) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `qq` varchar(15) NOT NULL,
  `email` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_comid_uid` (`comid`,`uid`),
  UNIQUE KEY `index_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_company_contact||-_-||

DROP TABLE IF EXISTS `qs_company_down_resume`;
CREATE TABLE `qs_company_down_resume` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `comid` int(10) unsigned NOT NULL,
  `resume_id` int(10) unsigned NOT NULL,
  `personal_uid` int(10) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `platform` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `index_uid` (`uid`),
  KEY `index_personal_uid` (`personal_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_company_down_resume||-_-||

DROP TABLE IF EXISTS `qs_company_img`;
CREATE TABLE `qs_company_img` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `comid` int(10) unsigned NOT NULL,
  `img` int(10) NOT NULL,
  `title` varchar(20) CHARACTER SET utf8 NOT NULL,
  `addtime` int(100) unsigned NOT NULL,
  `audit` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `index_uid` (`uid`),
  KEY `index_comid_uid` (`comid`,`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_company_img||-_-||

DROP TABLE IF EXISTS `qs_company_info`;
CREATE TABLE `qs_company_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `website` varchar(200) NOT NULL,
  `short_desc` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `address` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_comid_uid` (`comid`,`uid`),
  UNIQUE KEY `index_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_company_info||-_-||

DROP TABLE IF EXISTS `qs_company_interview`;
CREATE TABLE `qs_company_interview` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comid` int(10) unsigned NOT NULL,
  `companyname` varchar(100) NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `jobid` int(10) unsigned NOT NULL,
  `jobname` varchar(30) NOT NULL,
  `personal_uid` int(10) unsigned NOT NULL,
  `resume_id` int(10) unsigned NOT NULL,
  `fullname` varchar(30) NOT NULL,
  `interview_time` int(10) unsigned NOT NULL,
  `contact` varchar(30) NOT NULL,
  `address` varchar(200) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `note` varchar(100) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `is_look` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_uid` (`uid`),
  KEY `index_personal_uid` (`personal_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_company_interview||-_-||

DROP TABLE IF EXISTS `qs_company_interview_video`;
CREATE TABLE `qs_company_interview_video` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comid` int(10) unsigned NOT NULL,
  `companyname` varchar(100) NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `jobid` int(10) unsigned NOT NULL,
  `jobname` varchar(30) NOT NULL,
  `personal_uid` int(10) unsigned NOT NULL,
  `resume_id` int(10) unsigned NOT NULL,
  `fullname` varchar(30) NOT NULL,
  `interview_time` int(10) unsigned NOT NULL,
  `contact` varchar(30) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `deadline` int(10) unsigned NOT NULL,
  `note` varchar(100) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `is_look` tinyint(1) unsigned NOT NULL,
  `company_donotice_time` int(10) unsigned NOT NULL DEFAULT '0',
  `personal_donotice_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `index_uid` (`uid`),
  KEY `index_personal_uid` (`personal_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_company_interview_video||-_-||

DROP TABLE IF EXISTS `qs_company_report`;
CREATE TABLE `qs_company_report` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `company_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公司id',
  `corporate` varchar(30) NOT NULL DEFAULT '' COMMENT '企业法人',
  `com_type` varchar(60) NOT NULL DEFAULT '' COMMENT '主体类型',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创立时间',
  `reg_capital` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册资金',
  `reg_address` varchar(100) NOT NULL DEFAULT '' COMMENT '注册地址',
  `office_address` varchar(100) NOT NULL DEFAULT '' COMMENT '办公地址',
  `registrar` varchar(60) NOT NULL DEFAULT '' COMMENT '登记机关',
  `scope` text NOT NULL COMMENT '经营范围',
  `office_area` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '办公面积',
  `office_env` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '办公环境（1一般2良好3优美）',
  `workplace` varchar(30) NOT NULL DEFAULT '' COMMENT '办公场所',
  `number` varchar(30) NOT NULL DEFAULT '' COMMENT '员工人数',
  `sex_ratio` varchar(30) NOT NULL DEFAULT '' COMMENT '男女比例',
  `average_age` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '平均年龄',
  `route` varchar(100) NOT NULL DEFAULT '' COMMENT '乘车路线',
  `img` text COMMENT '企业照片',
  `evaluation` text NOT NULL COMMENT '评价',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '认证时间',
  `certifier` varchar(30) NOT NULL DEFAULT '' COMMENT '认证师',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_company_id` (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_company_report||-_-||

DROP TABLE IF EXISTS `qs_company_service_emergency`;
CREATE TABLE `qs_company_service_emergency` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `recommend` tinyint(1) unsigned NOT NULL,
  `days` int(10) unsigned NOT NULL,
  `expense` decimal(10,2) unsigned NOT NULL,
  `enable_points_deduct` tinyint(1) unsigned NOT NULL,
  `deduct_max` decimal(10,2) unsigned NOT NULL,
  `is_display` tinyint(1) unsigned NOT NULL,
  `sort_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_company_service_emergency||-_-||

DROP TABLE IF EXISTS `qs_company_service_points`;
CREATE TABLE `qs_company_service_points` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `recommend` tinyint(1) unsigned NOT NULL,
  `points` int(10) unsigned NOT NULL,
  `expense` decimal(10,2) unsigned NOT NULL,
  `is_display` tinyint(1) unsigned NOT NULL,
  `sort_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_company_service_points||-_-||

DROP TABLE IF EXISTS `qs_company_service_refresh_job_package`;
CREATE TABLE `qs_company_service_refresh_job_package` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `recommend` tinyint(1) unsigned NOT NULL,
  `times` int(10) unsigned NOT NULL,
  `expense` decimal(10,2) unsigned NOT NULL,
  `enable_points_deduct` tinyint(1) unsigned NOT NULL,
  `deduct_max` decimal(10,2) unsigned NOT NULL,
  `is_display` tinyint(1) unsigned NOT NULL,
  `sort_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_company_service_refresh_job_package||-_-||

DROP TABLE IF EXISTS `qs_company_service_resume_package`;
CREATE TABLE `qs_company_service_resume_package` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `recommend` tinyint(1) unsigned NOT NULL,
  `download_resume_point` int(10) unsigned NOT NULL,
  `expense` decimal(10,2) unsigned NOT NULL,
  `enable_points_deduct` tinyint(1) unsigned NOT NULL,
  `deduct_max` decimal(10,2) unsigned NOT NULL,
  `is_display` tinyint(1) unsigned NOT NULL,
  `sort_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_company_service_resume_package||-_-||

DROP TABLE IF EXISTS `qs_company_service_stick`;
CREATE TABLE `qs_company_service_stick` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `recommend` tinyint(1) unsigned NOT NULL,
  `days` int(10) unsigned NOT NULL,
  `expense` decimal(10,2) unsigned NOT NULL,
  `enable_points_deduct` tinyint(1) unsigned NOT NULL,
  `deduct_max` decimal(10,2) unsigned NOT NULL,
  `is_display` tinyint(1) unsigned NOT NULL,
  `sort_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_company_service_stick||-_-||

DROP TABLE IF EXISTS `qs_config`;
CREATE TABLE `qs_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `is_frontend` tinyint(1) unsigned NOT NULL,
  `value` text NOT NULL,
  `note` varchar(100) NOT NULL,
  `is_secret` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_config||-_-||

DROP TABLE IF EXISTS `qs_coupon`;
CREATE TABLE `qs_coupon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `face_value` double(10,2) unsigned NOT NULL,
  `bind_setmeal_id` int(10) unsigned NOT NULL,
  `days` int(10) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_coupon||-_-||

DROP TABLE IF EXISTS `qs_coupon_log`;
CREATE TABLE `qs_coupon_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `coupon_id` text NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `admin_name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_coupon_log||-_-||

DROP TABLE IF EXISTS `qs_coupon_record`;
CREATE TABLE `qs_coupon_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_id` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `coupon_name` varchar(30) NOT NULL,
  `coupon_face_value` double(10,2) unsigned NOT NULL,
  `coupon_bind_setmeal_id` int(10) unsigned NOT NULL,
  `deadline` int(10) unsigned NOT NULL,
  `usetime` int(10) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_log_id` (`log_id`),
  KEY `index_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_coupon_record||-_-||

DROP TABLE IF EXISTS `qs_cron`;
CREATE TABLE `qs_cron` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `action` varchar(30) NOT NULL,
  `weekday` tinyint(1) NOT NULL,
  `day` tinyint(2) NOT NULL,
  `hour` tinyint(2) NOT NULL,
  `minute` varchar(10) NOT NULL,
  `next_execute_time` int(10) unsigned NOT NULL,
  `last_execute_time` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `is_sys` tinyint(1) unsigned NOT NULL,
  `disable_edit` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_cron||-_-||

DROP TABLE IF EXISTS `qs_cron_log`;
CREATE TABLE `qs_cron_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cron_id` int(10) unsigned NOT NULL,
  `cron_name` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `seconds` double(10,4) unsigned NOT NULL,
  `is_auto` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_cron_log||-_-||

DROP TABLE IF EXISTS `qs_customer_service`;
CREATE TABLE `qs_customer_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `photo` int(10) unsigned NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `tel` varchar(30) NOT NULL,
  `weixin` varchar(30) NOT NULL,
  `qq` varchar(20) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `wx_qrcode` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_customer_service||-_-||

DROP TABLE IF EXISTS `qs_customer_service_complaint`;
CREATE TABLE `qs_customer_service_complaint` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `cs_id` int(10) unsigned NOT NULL,
  `content` varchar(255) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_customer_service_complaint||-_-||

DROP TABLE IF EXISTS `qs_entrust`;
CREATE TABLE `qs_entrust` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `days` int(10) unsigned NOT NULL,
  `deadline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_entrust||-_-||

DROP TABLE IF EXISTS `qs_explain`;
CREATE TABLE `qs_explain` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `content` longtext NOT NULL,
  `attach` text NOT NULL,
  `is_display` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `link_url` varchar(200) NOT NULL DEFAULT '',
  `seo_keywords` varchar(100) NOT NULL DEFAULT '',
  `seo_description` varchar(200) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL,
  `sort_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `index_addtime` (`addtime`),
  KEY `index_sort_id` (`sort_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_explain||-_-||

DROP TABLE IF EXISTS `qs_fav_job`;
CREATE TABLE `qs_fav_job` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_uid` int(10) unsigned NOT NULL,
  `personal_uid` int(10) unsigned NOT NULL,
  `jobid` int(10) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_company_uid` (`company_uid`),
  KEY `index_personal_uid` (`personal_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_fav_job||-_-||

DROP TABLE IF EXISTS `qs_fav_resume`;
CREATE TABLE `qs_fav_resume` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_uid` int(10) unsigned NOT NULL,
  `personal_uid` int(10) unsigned NOT NULL,
  `resume_id` int(10) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_company_uid` (`company_uid`),
  KEY `index_personal_uid` (`personal_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_fav_resume||-_-||

DROP TABLE IF EXISTS `qs_feedback`;
CREATE TABLE `qs_feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `utype` tinyint(1) unsigned NOT NULL,
  `content` varchar(255) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_feedback||-_-||

DROP TABLE IF EXISTS `qs_field_rule`;
CREATE TABLE `qs_field_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `model_name` varchar(30) NOT NULL,
  `field_name` varchar(30) NOT NULL,
  `is_require` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_display` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `enable_close` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_cn` varchar(10) NOT NULL,
  `is_custom` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_field_rule||-_-||

DROP TABLE IF EXISTS `qs_help`;
CREATE TABLE `qs_help` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` longtext NOT NULL,
  `is_display` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `seo_keywords` varchar(100) NOT NULL DEFAULT '',
  `seo_description` varchar(200) NOT NULL DEFAULT '',
  `sort_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `index_cid_sort_id` (`cid`,`sort_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_help||-_-||

DROP TABLE IF EXISTS `qs_help_category`;
CREATE TABLE `qs_help_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL,
  `is_sys` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_help_category||-_-||

DROP TABLE IF EXISTS `qs_hotword`;
CREATE TABLE `qs_hotword` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `word` varchar(120) NOT NULL,
  `hot` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `index_word` (`word`),
  KEY `index_hot` (`hot`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_hotword||-_-||

DROP TABLE IF EXISTS `qs_hrtool`;
CREATE TABLE `qs_hrtool` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL,
  `filename` varchar(200) NOT NULL,
  `fileurl` varchar(200) NOT NULL,
  `sort_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_hrtool||-_-||

DROP TABLE IF EXISTS `qs_hrtool_category`;
CREATE TABLE `qs_hrtool_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `sort_id` int(10) unsigned NOT NULL DEFAULT '0',
  `is_sys` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `describe` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_hrtool_category||-_-||

DROP TABLE IF EXISTS `qs_im_quickmsg`;
CREATE TABLE `qs_im_quickmsg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(100) NOT NULL,
  `utype` tinyint(1) unsigned NOT NULL,
  `sort_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_im_quickmsg||-_-||

DROP TABLE IF EXISTS `qs_im_token`;
CREATE TABLE `qs_im_token` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `im_userid` varchar(50) NOT NULL,
  `token` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_im_token||-_-||

DROP TABLE IF EXISTS `qs_job`;
CREATE TABLE `qs_job` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `company_id` int(10) unsigned NOT NULL,
  `jobname` varchar(50) NOT NULL,
  `emergency` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `stick` tinyint(1) NOT NULL,
  `nature` int(10) unsigned NOT NULL,
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `category1` int(10) unsigned NOT NULL,
  `category2` int(10) unsigned NOT NULL,
  `category3` int(10) unsigned NOT NULL,
  `category` int(10) unsigned NOT NULL,
  `minwage` int(10) NOT NULL,
  `maxwage` int(10) NOT NULL,
  `negotiable` tinyint(1) unsigned NOT NULL,
  `education` int(10) unsigned NOT NULL,
  `experience` int(10) unsigned NOT NULL,
  `content` text NOT NULL,
  `tag` varchar(100) NOT NULL,
  `amount` smallint(5) unsigned NOT NULL,
  `department` varchar(15) NOT NULL,
  `minage` tinyint(2) unsigned NOT NULL,
  `maxage` tinyint(2) unsigned NOT NULL,
  `age_na` tinyint(1) unsigned NOT NULL,
  `district1` int(10) unsigned NOT NULL,
  `district2` int(10) unsigned NOT NULL,
  `district3` int(10) unsigned NOT NULL,
  `district` int(10) unsigned NOT NULL,
  `address` varchar(200) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `refreshtime` int(10) unsigned NOT NULL,
  `updatetime` int(10) unsigned NOT NULL COMMENT '更新时间',
  `setmeal_id` int(10) unsigned NOT NULL,
  `audit` tinyint(1) unsigned NOT NULL,
  `is_display` tinyint(1) unsigned NOT NULL,
  `click` int(10) unsigned NOT NULL DEFAULT '0',
  `user_status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `robot` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `map_lat` decimal(9,6) NOT NULL COMMENT '纬度',
  `map_lng` decimal(9,6) NOT NULL COMMENT '经度',
  `map_zoom` tinyint(3) unsigned NOT NULL,
  `custom_field_1` varchar(255) NOT NULL DEFAULT '',
  `custom_field_2` varchar(255) NOT NULL DEFAULT '',
  `custom_field_3` varchar(255) NOT NULL DEFAULT '',
  `platform` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `index_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_job||-_-||

DROP TABLE IF EXISTS `qs_job_apply`;
CREATE TABLE `qs_job_apply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comid` int(10) unsigned NOT NULL,
  `companyname` varchar(100) NOT NULL,
  `company_uid` int(10) unsigned NOT NULL,
  `jobid` int(10) unsigned NOT NULL,
  `jobname` varchar(30) NOT NULL,
  `personal_uid` int(10) unsigned NOT NULL,
  `resume_id` int(10) unsigned NOT NULL,
  `fullname` varchar(30) NOT NULL,
  `note` varchar(100) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `is_look` tinyint(1) unsigned NOT NULL,
  `handle_status` tinyint(1) unsigned NOT NULL,
  `source` tinyint(1) unsigned NOT NULL COMMENT '0自主投递 1委托投递',
  `platform` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `index_company_uid` (`company_uid`),
  KEY `index_personal_uid` (`personal_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_job_apply||-_-||

DROP TABLE IF EXISTS `qs_job_audit_log`;
CREATE TABLE `qs_job_audit_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `jobid` int(10) unsigned NOT NULL,
  `audit` tinyint(1) unsigned NOT NULL,
  `reason` varchar(200) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_job_audit_log||-_-||

DROP TABLE IF EXISTS `qs_job_contact`;
CREATE TABLE `qs_job_contact` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `jid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `contact` varchar(30) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `weixin` varchar(15) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `qq` varchar(15) NOT NULL,
  `email` varchar(30) NOT NULL,
  `is_display` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `use_company_contact` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_jid_uid` (`jid`,`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_job_contact||-_-||

DROP TABLE IF EXISTS `qs_job_search_key`;
CREATE TABLE `qs_job_search_key` (
  `id` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `company_id` int(10) unsigned NOT NULL,
  `company_nature_id` int(10) unsigned NOT NULL,
  `emergency` tinyint(1) unsigned NOT NULL,
  `license` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `stick` tinyint(1) NOT NULL,
  `setmeal_id` int(10) unsigned NOT NULL,
  `nature` int(10) unsigned NOT NULL,
  `category1` int(10) unsigned NOT NULL,
  `category2` int(10) unsigned NOT NULL,
  `category3` int(10) unsigned NOT NULL,
  `category` int(10) unsigned NOT NULL,
  `trade` int(10) unsigned NOT NULL,
  `scale` int(10) unsigned NOT NULL,
  `district1` int(10) unsigned NOT NULL,
  `district2` int(10) unsigned NOT NULL,
  `district3` int(10) unsigned NOT NULL,
  `district` int(10) unsigned NOT NULL,
  `tag` varchar(100) NOT NULL,
  `education` int(10) unsigned NOT NULL,
  `experience` int(10) unsigned NOT NULL,
  `minwage` int(10) NOT NULL,
  `maxwage` int(10) NOT NULL,
  `refreshtime` int(10) unsigned NOT NULL,
  `map_lat` decimal(9,6) NOT NULL COMMENT '纬度',
  `map_lng` decimal(9,6) NOT NULL COMMENT '经度',
  `jobname` varchar(50) NOT NULL,
  `companyname` varchar(50) NOT NULL,
  `company_nature` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_uid` (`uid`),
  KEY `index_company_id` (`company_id`),
  FULLTEXT KEY `index_jobname` (`jobname`) /*!50100 WITH PARSER `ngram` */ ,
  FULLTEXT KEY `index_companyname` (`companyname`) /*!50100 WITH PARSER `ngram` */ ,
  FULLTEXT KEY `index_fulltext_index` (`jobname`,`companyname`,`company_nature`) /*!50100 WITH PARSER `ngram` */ ,
  FULLTEXT KEY `index_company_nature` (`company_nature`) /*!50100 WITH PARSER `ngram` */ 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_job_search_key||-_-||

DROP TABLE IF EXISTS `qs_job_search_rtime`;
CREATE TABLE `qs_job_search_rtime` (
  `id` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `company_id` int(10) unsigned NOT NULL,
  `company_nature_id` int(10) unsigned NOT NULL,
  `emergency` tinyint(1) unsigned NOT NULL,
  `license` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `stick` tinyint(1) NOT NULL,
  `setmeal_id` int(10) unsigned NOT NULL,
  `nature` int(10) unsigned NOT NULL,
  `category1` int(10) unsigned NOT NULL,
  `category2` int(10) unsigned NOT NULL,
  `category3` int(10) unsigned NOT NULL,
  `category` int(10) unsigned NOT NULL,
  `trade` int(10) unsigned NOT NULL,
  `scale` int(10) unsigned NOT NULL,
  `district1` int(10) unsigned NOT NULL,
  `district2` int(10) unsigned NOT NULL,
  `district3` int(10) unsigned NOT NULL,
  `district` int(10) unsigned NOT NULL,
  `tag` varchar(100) NOT NULL,
  `education` int(10) unsigned NOT NULL,
  `experience` int(10) unsigned NOT NULL,
  `minwage` int(10) NOT NULL,
  `maxwage` int(10) NOT NULL,
  `refreshtime` int(10) unsigned NOT NULL,
  `map_lat` decimal(9,6) NOT NULL COMMENT '纬度',
  `map_lng` decimal(9,6) NOT NULL COMMENT '经度',
  PRIMARY KEY (`id`),
  KEY `index_uid` (`uid`),
  KEY `index_stick_rtime` (`stick`,`refreshtime`),
  KEY `index_rtime` (`refreshtime`),
  KEY `index_company_id` (`company_id`),
  KEY `index_emergency_rtime` (`emergency`,`refreshtime`),
  KEY `index_category1` (`category1`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_job_search_rtime||-_-||

DROP TABLE IF EXISTS `qs_link`;
CREATE TABLE `qs_link` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `is_display` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `name` varchar(20) NOT NULL,
  `link_url` varchar(255) NOT NULL,
  `sort_id` int(10) unsigned NOT NULL DEFAULT '0',
  `notes` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `index_show_order` (`sort_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_link||-_-||

DROP TABLE IF EXISTS `qs_mail_tpl`;
CREATE TABLE `qs_mail_tpl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(30) NOT NULL,
  `name` varchar(100) NOT NULL,
  `title` text NOT NULL,
  `value` text NOT NULL,
  `variate` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_mail_tpl||-_-||

DROP TABLE IF EXISTS `qs_market_queue`;
CREATE TABLE `qs_market_queue` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `message` tinyint(1) unsigned NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `weixin_openid` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_task_id` (`task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_market_queue||-_-||

DROP TABLE IF EXISTS `qs_market_task`;
CREATE TABLE `qs_market_task` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `content` text NOT NULL,
  `send_type` varchar(30) NOT NULL,
  `target` varchar(30) NOT NULL,
  `condition` text NOT NULL,
  `total` int(10) unsigned NOT NULL,
  `success` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_market_task||-_-||

DROP TABLE IF EXISTS `qs_market_tpl`;
CREATE TABLE `qs_market_tpl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_market_tpl||-_-||

DROP TABLE IF EXISTS `qs_member`;
CREATE TABLE `qs_member` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `utype` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `mobile` varchar(11) NOT NULL,
  `username` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL DEFAULT '',
  `pwd_hash` varchar(30) NOT NULL,
  `reg_time` int(10) unsigned NOT NULL,
  `reg_ip` varchar(30) NOT NULL,
  `reg_address` varchar(30) NOT NULL,
  `last_login_time` int(10) unsigned NOT NULL,
  `last_login_ip` varchar(30) NOT NULL,
  `last_login_address` varchar(30) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `avatar` int(10) unsigned NOT NULL,
  `robot` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `platform` varchar(30) NOT NULL DEFAULT '',
  `nologin_notice_counter` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `index_username` (`username`,`utype`),
  UNIQUE KEY `index_mobile` (`mobile`,`utype`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_member||-_-||

DROP TABLE IF EXISTS `qs_member_appeal`;
CREATE TABLE `qs_member_appeal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `realname` varchar(30) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_member_appeal||-_-||

DROP TABLE IF EXISTS `qs_member_bind`;
CREATE TABLE `qs_member_bind` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `type` varchar(30) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `unionid` varchar(50) NOT NULL,
  `nickname` varchar(30) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `bindtime` int(10) unsigned NOT NULL,
  `is_subscribe` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_member_bind||-_-||

DROP TABLE IF EXISTS `qs_member_cancel_apply`;
CREATE TABLE `qs_member_cancel_apply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `handlertime` int(10) unsigned NOT NULL,
  `regtime` varchar(30) NOT NULL,
  `companyname` varchar(100) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `contact` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_member_cancel_apply||-_-||

DROP TABLE IF EXISTS `qs_member_points`;
CREATE TABLE `qs_member_points` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `points` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_member_points||-_-||

DROP TABLE IF EXISTS `qs_member_points_log`;
CREATE TABLE `qs_member_points_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `op` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1增加 2减少',
  `points` int(10) unsigned NOT NULL,
  `content` varchar(255) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_member_points_log||-_-||

DROP TABLE IF EXISTS `qs_member_setmeal`;
CREATE TABLE `qs_member_setmeal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `expired` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `setmeal_id` int(10) unsigned NOT NULL,
  `deadline` int(10) unsigned NOT NULL,
  `download_resume_point` int(10) unsigned NOT NULL DEFAULT '0',
  `jobs_meanwhile` int(10) unsigned NOT NULL DEFAULT '0',
  `refresh_jobs_free_perday` int(10) unsigned NOT NULL,
  `download_resume_max_perday` int(10) unsigned NOT NULL DEFAULT '0',
  `service_added_discount` double(2,1) unsigned NOT NULL,
  `enable_video_interview` tinyint(1) unsigned NOT NULL,
  `enable_poster` tinyint(1) unsigned NOT NULL,
  `show_apply_contact` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_uid` (`uid`),
  KEY `index_setmeal_id` (`setmeal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_member_setmeal||-_-||

DROP TABLE IF EXISTS `qs_member_setmeal_log`;
CREATE TABLE `qs_member_setmeal_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `content` varchar(255) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_member_setmeal_log||-_-||

DROP TABLE IF EXISTS `qs_message`;
CREATE TABLE `qs_message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `content` text NOT NULL,
  `inner_link` varchar(30) NOT NULL,
  `inner_link_params` int(10) unsigned NOT NULL,
  `spe_link_params` varchar(100) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL,
  `is_readed` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_message||-_-||

DROP TABLE IF EXISTS `qs_microposte_tpl`;
CREATE TABLE `qs_microposte_tpl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `jobnum` tinyint(1) NOT NULL,
  `name` varchar(30) NOT NULL,
  `thumb` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_microposte_tpl||-_-||

DROP TABLE IF EXISTS `qs_mobile_index_menu`;
CREATE TABLE `qs_mobile_index_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(30) NOT NULL,
  `title` varchar(30) NOT NULL,
  `custom_title` varchar(30) NOT NULL,
  `icon` int(10) unsigned NOT NULL,
  `link_url` varchar(255) NOT NULL,
  `sort_id` int(10) unsigned NOT NULL,
  `is_display` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_mobile_index_menu||-_-||

DROP TABLE IF EXISTS `qs_mobile_index_module`;
CREATE TABLE `qs_mobile_index_module` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(30) NOT NULL,
  `is_display` tinyint(1) unsigned NOT NULL,
  `plan_id` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_mobile_index_module||-_-||

DROP TABLE IF EXISTS `qs_navigation`;
CREATE TABLE `qs_navigation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `is_display` tinyint(1) unsigned NOT NULL,
  `title` varchar(15) NOT NULL,
  `link_type` tinyint(1) unsigned NOT NULL,
  `page` varchar(30) NOT NULL,
  `url` varchar(200) NOT NULL,
  `target` varchar(10) NOT NULL,
  `sort_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_navigation||-_-||

DROP TABLE IF EXISTS `qs_notice`;
CREATE TABLE `qs_notice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `content` longtext NOT NULL,
  `attach` text NOT NULL,
  `is_display` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `link_url` varchar(200) NOT NULL DEFAULT '',
  `seo_keywords` varchar(100) NOT NULL DEFAULT '',
  `seo_description` varchar(200) NOT NULL DEFAULT '',
  `click` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL,
  `sort_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `index_sort_id_addtime` (`sort_id`,`addtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_notice||-_-||

DROP TABLE IF EXISTS `qs_notify_log`;
CREATE TABLE `qs_notify_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `alias` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid_time` (`uid`,`addtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_notify_log||-_-||

DROP TABLE IF EXISTS `qs_notify_rule`;
CREATE TABLE `qs_notify_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(30) NOT NULL,
  `utype` tinyint(1) unsigned NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `title` varchar(30) NOT NULL,
  `content` text NOT NULL,
  `inner_link` varchar(30) NOT NULL,
  `open_message` tinyint(1) NOT NULL,
  `open_sms` tinyint(1) NOT NULL,
  `open_email` tinyint(1) NOT NULL,
  `open_push` tinyint(1) NOT NULL,
  `max_time_per_day` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_notify_rule||-_-||

DROP TABLE IF EXISTS `qs_order`;
CREATE TABLE `qs_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `utype` tinyint(1) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `oid` varchar(50) NOT NULL,
  `service_type` varchar(30) NOT NULL,
  `service_name` varchar(30) NOT NULL,
  `amount` decimal(10,2) unsigned NOT NULL COMMENT '最终支付金额',
  `service_amount` decimal(10,2) unsigned NOT NULL COMMENT '服务价格',
  `service_amount_after_discount` decimal(10,2) unsigned NOT NULL COMMENT '折后价格',
  `deduct_amount` decimal(10,2) unsigned NOT NULL COMMENT '抵扣掉的金额',
  `deduct_points` int(10) unsigned NOT NULL COMMENT '抵扣积分数',
  `payment` varchar(20) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `paytime` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `extra` text NOT NULL,
  `note` varchar(200) NOT NULL,
  `add_platform` varchar(30) NOT NULL DEFAULT '',
  `pay_platform` varchar(30) NOT NULL DEFAULT '',
  `service_id` int(10) unsigned NOT NULL DEFAULT '0',
  `return_url` varchar(255) NOT NULL,
  `deadline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_order||-_-||

DROP TABLE IF EXISTS `qs_order_tmp`;
CREATE TABLE `qs_order_tmp` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `utype` tinyint(1) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `oid` varchar(50) NOT NULL,
  `service_type` varchar(30) NOT NULL,
  `service_name` varchar(30) NOT NULL,
  `amount` decimal(10,2) unsigned NOT NULL COMMENT '最终支付金额',
  `service_amount` decimal(10,2) unsigned NOT NULL COMMENT '服务价格',
  `service_amount_after_discount` decimal(10,2) unsigned NOT NULL COMMENT '折后价格',
  `deduct_amount` decimal(10,2) unsigned NOT NULL COMMENT '抵扣掉的金额',
  `deduct_points` int(10) unsigned NOT NULL COMMENT '抵扣积分数',
  `payment` varchar(20) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `paytime` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `extra` text NOT NULL,
  `note` varchar(200) NOT NULL,
  `add_platform` varchar(30) NOT NULL DEFAULT '',
  `pay_platform` varchar(30) NOT NULL DEFAULT '',
  `service_id` int(10) unsigned NOT NULL DEFAULT '0',
  `return_url` varchar(255) NOT NULL,
  `deadline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_order_tmp||-_-||

DROP TABLE IF EXISTS `qs_page`;
CREATE TABLE `qs_page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `alias` varchar(30) NOT NULL,
  `enable_cache` tinyint(1) unsigned NOT NULL,
  `expire` int(10) NOT NULL,
  `seo_title` varchar(100) NOT NULL,
  `seo_keywords` varchar(100) NOT NULL,
  `seo_description` varchar(200) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_page||-_-||

DROP TABLE IF EXISTS `qs_personal_service_stick`;
CREATE TABLE `qs_personal_service_stick` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `recommend` tinyint(1) unsigned NOT NULL,
  `days` int(10) unsigned NOT NULL,
  `expense` decimal(10,2) unsigned NOT NULL,
  `enable_points_deduct` tinyint(1) unsigned NOT NULL,
  `deduct_max` decimal(10,2) unsigned NOT NULL,
  `is_display` tinyint(1) unsigned NOT NULL,
  `sort_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_personal_service_stick||-_-||

DROP TABLE IF EXISTS `qs_personal_service_tag`;
CREATE TABLE `qs_personal_service_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `recommend` tinyint(1) unsigned NOT NULL,
  `days` int(10) unsigned NOT NULL,
  `expense` decimal(10,2) unsigned NOT NULL,
  `enable_points_deduct` tinyint(1) unsigned NOT NULL,
  `deduct_max` decimal(10,2) unsigned NOT NULL,
  `is_display` tinyint(1) unsigned NOT NULL,
  `sort_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_personal_service_tag||-_-||

DROP TABLE IF EXISTS `qs_refresh_job_log`;
CREATE TABLE `qs_refresh_job_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `jobid` int(10) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `platform` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_refresh_job_log||-_-||

DROP TABLE IF EXISTS `qs_refresh_resume_log`;
CREATE TABLE `qs_refresh_resume_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `platform` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_refresh_resume_log||-_-||

DROP TABLE IF EXISTS `qs_refreshjob_queue`;
CREATE TABLE `qs_refreshjob_queue` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `jobid` int(10) unsigned NOT NULL,
  `execute_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_refreshjob_queue||-_-||

DROP TABLE IF EXISTS `qs_resume`;
CREATE TABLE `qs_resume` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `is_display` tinyint(1) unsigned NOT NULL,
  `high_quality` tinyint(1) unsigned NOT NULL,
  `display_name` tinyint(1) unsigned NOT NULL,
  `audit` tinyint(1) unsigned NOT NULL,
  `stick` tinyint(1) unsigned NOT NULL,
  `service_tag` varchar(15) NOT NULL DEFAULT '',
  `fullname` varchar(15) NOT NULL,
  `sex` tinyint(1) unsigned NOT NULL,
  `birthday` varchar(15) NOT NULL,
  `residence` varchar(30) NOT NULL DEFAULT '',
  `height` varchar(5) NOT NULL,
  `marriage` tinyint(1) unsigned NOT NULL,
  `education` int(10) unsigned NOT NULL,
  `enter_job_time` int(10) unsigned NOT NULL,
  `householdaddress` varchar(30) NOT NULL DEFAULT '',
  `major1` int(10) unsigned NOT NULL,
  `major2` int(10) unsigned NOT NULL,
  `major` int(10) unsigned NOT NULL,
  `tag` varchar(100) NOT NULL,
  `idcard` varchar(18) NOT NULL,
  `specialty` text NOT NULL,
  `photo_img` int(10) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `refreshtime` int(10) unsigned NOT NULL,
  `updatetime` int(10) unsigned NOT NULL COMMENT '更新时间',
  `current` int(10) unsigned NOT NULL,
  `click` int(10) unsigned NOT NULL DEFAULT '1',
  `tpl` varchar(30) NOT NULL,
  `custom_field_1` varchar(255) NOT NULL DEFAULT '',
  `custom_field_2` varchar(255) NOT NULL DEFAULT '',
  `custom_field_3` varchar(255) NOT NULL DEFAULT '',
  `platform` varchar(30) NOT NULL DEFAULT '',
  `remark` varchar(200) NOT NULL DEFAULT '',
  `comment` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_uid` (`uid`),
  KEY `index_refreshtime` (`refreshtime`),
  KEY `index_addtime` (`addtime`),
  KEY `index_audit` (`audit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_resume||-_-||

DROP TABLE IF EXISTS `qs_resume_audit_log`;
CREATE TABLE `qs_resume_audit_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `resumeid` int(10) unsigned NOT NULL,
  `audit` tinyint(1) unsigned NOT NULL,
  `reason` varchar(200) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_resume_audit_log||-_-||

DROP TABLE IF EXISTS `qs_resume_certificate`;
CREATE TABLE `qs_resume_certificate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `name` varchar(30) NOT NULL,
  `obtaintime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_rid_uid` (`rid`,`uid`),
  KEY `index_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_resume_certificate||-_-||

DROP TABLE IF EXISTS `qs_resume_complete`;
CREATE TABLE `qs_resume_complete` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `basic` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `intention` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `specialty` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `education` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `work` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `training` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `project` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `certificate` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `language` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `tag` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `img` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_rid_uid` (`rid`,`uid`),
  UNIQUE KEY `index_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_resume_complete||-_-||

DROP TABLE IF EXISTS `qs_resume_contact`;
CREATE TABLE `qs_resume_contact` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `email` varchar(30) NOT NULL DEFAULT '',
  `qq` varchar(30) NOT NULL DEFAULT '',
  `weixin` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_rid_uid` (`rid`,`uid`),
  UNIQUE KEY `index_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_resume_contact||-_-||

DROP TABLE IF EXISTS `qs_resume_education`;
CREATE TABLE `qs_resume_education` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  `todate` int(10) unsigned NOT NULL,
  `school` varchar(30) NOT NULL,
  `major` varchar(20) NOT NULL,
  `education` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_rid_uid` (`rid`,`uid`),
  KEY `index_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_resume_education||-_-||

DROP TABLE IF EXISTS `qs_resume_img`;
CREATE TABLE `qs_resume_img` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `img` int(10) unsigned NOT NULL,
  `title` varchar(20) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL,
  `audit` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_rid_uid` (`rid`,`uid`),
  KEY `index_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_resume_img||-_-||

DROP TABLE IF EXISTS `qs_resume_intention`;
CREATE TABLE `qs_resume_intention` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `nature` int(10) unsigned NOT NULL,
  `category1` int(10) unsigned NOT NULL,
  `category2` int(10) unsigned NOT NULL,
  `category3` int(10) unsigned NOT NULL,
  `category` int(10) unsigned NOT NULL,
  `district1` int(10) unsigned NOT NULL,
  `district2` int(10) unsigned NOT NULL,
  `district3` int(10) unsigned NOT NULL,
  `district` int(10) unsigned NOT NULL,
  `minwage` int(10) NOT NULL,
  `maxwage` int(10) NOT NULL,
  `trade` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `index_rid_uid` (`rid`,`uid`),
  KEY `index_trade` (`trade`),
  KEY `index_wage` (`minwage`,`maxwage`),
  KEY `index_district` (`district`),
  KEY `index_category` (`category`),
  KEY `index_uid` (`uid`),
  KEY `index_category1` (`category1`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_resume_intention||-_-||

DROP TABLE IF EXISTS `qs_resume_language`;
CREATE TABLE `qs_resume_language` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `language` int(10) unsigned NOT NULL,
  `level` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_rid_uid` (`rid`,`uid`),
  KEY `index_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_resume_language||-_-||

DROP TABLE IF EXISTS `qs_resume_module`;
CREATE TABLE `qs_resume_module` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module_name` varchar(30) NOT NULL,
  `module_cn` varchar(30) NOT NULL,
  `score` int(10) unsigned NOT NULL,
  `is_display` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `enable_close` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_module_name` (`module_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_resume_module||-_-||

DROP TABLE IF EXISTS `qs_resume_project`;
CREATE TABLE `qs_resume_project` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  `todate` int(10) unsigned NOT NULL,
  `projectname` varchar(30) NOT NULL,
  `role` varchar(30) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_rid_uid` (`rid`,`uid`),
  KEY `index_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_resume_project||-_-||

DROP TABLE IF EXISTS `qs_resume_search_key`;
CREATE TABLE `qs_resume_search_key` (
  `id` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `high_quality` tinyint(1) unsigned NOT NULL,
  `photo` tinyint(1) unsigned NOT NULL,
  `stick` tinyint(1) unsigned NOT NULL,
  `sex` tinyint(1) unsigned NOT NULL,
  `birthyear` smallint(4) unsigned NOT NULL,
  `education` int(10) unsigned NOT NULL,
  `enter_job_time` int(10) unsigned NOT NULL,
  `major1` int(10) unsigned NOT NULL,
  `major2` int(10) unsigned NOT NULL,
  `major` int(10) unsigned NOT NULL,
  `tag` varchar(50) NOT NULL,
  `intention_jobs` varchar(255) NOT NULL,
  `fulltext_key` text NOT NULL,
  `refreshtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_uid` (`uid`),
  FULLTEXT KEY `index_intention_jobs` (`intention_jobs`) /*!50100 WITH PARSER `ngram` */ ,
  FULLTEXT KEY `index_fulltext_index` (`intention_jobs`,`fulltext_key`) /*!50100 WITH PARSER `ngram` */ 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_resume_search_key||-_-||

DROP TABLE IF EXISTS `qs_resume_search_rtime`;
CREATE TABLE `qs_resume_search_rtime` (
  `id` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `high_quality` tinyint(1) unsigned NOT NULL,
  `photo` tinyint(1) unsigned NOT NULL,
  `stick` tinyint(1) unsigned NOT NULL,
  `sex` tinyint(1) unsigned NOT NULL,
  `birthyear` smallint(4) unsigned NOT NULL,
  `education` int(10) unsigned NOT NULL,
  `enter_job_time` int(10) unsigned NOT NULL,
  `major1` int(10) unsigned NOT NULL,
  `major2` int(10) unsigned NOT NULL,
  `major` int(10) unsigned NOT NULL,
  `tag` varchar(50) NOT NULL,
  `refreshtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_uid` (`uid`),
  KEY `index_stick_rtime` (`stick`,`refreshtime`),
  KEY `index_rtime` (`refreshtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_resume_search_rtime||-_-||

DROP TABLE IF EXISTS `qs_resume_training`;
CREATE TABLE `qs_resume_training` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  `todate` int(10) unsigned NOT NULL,
  `agency` varchar(30) NOT NULL,
  `course` varchar(30) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_rid_uid` (`rid`,`uid`),
  KEY `index_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_resume_training||-_-||

DROP TABLE IF EXISTS `qs_resume_work`;
CREATE TABLE `qs_resume_work` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  `todate` int(10) unsigned NOT NULL,
  `companyname` varchar(30) NOT NULL,
  `jobname` varchar(30) NOT NULL,
  `duty` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_rid_uid` (`rid`,`uid`),
  KEY `index_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_resume_work||-_-||

DROP TABLE IF EXISTS `qs_service_queue`;
CREATE TABLE `qs_service_queue` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(30) NOT NULL,
  `utype` tinyint(1) unsigned NOT NULL,
  `pid` int(10) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `deadline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_service_queue||-_-||

DROP TABLE IF EXISTS `qs_setmeal`;
CREATE TABLE `qs_setmeal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `icon` int(10) unsigned NOT NULL,
  `expense` decimal(10,2) unsigned NOT NULL,
  `days` int(10) unsigned NOT NULL DEFAULT '0',
  `preferential_open` tinyint(1) unsigned NOT NULL,
  `preferential_expense` decimal(10,2) unsigned NOT NULL,
  `preferential_expense_start` int(10) unsigned NOT NULL,
  `preferential_expense_end` int(10) unsigned NOT NULL,
  `download_resume_point` int(10) unsigned NOT NULL DEFAULT '0',
  `gift_point` int(10) unsigned NOT NULL,
  `jobs_meanwhile` int(10) unsigned NOT NULL DEFAULT '0',
  `refresh_jobs_free_perday` int(10) unsigned NOT NULL,
  `download_resume_max_perday` int(10) unsigned NOT NULL DEFAULT '0',
  `service_added_discount` double(2,1) unsigned NOT NULL,
  `enable_video_interview` tinyint(1) unsigned NOT NULL,
  `enable_poster` tinyint(1) unsigned NOT NULL,
  `show_apply_contact` tinyint(1) unsigned NOT NULL,
  `note` varchar(100) NOT NULL,
  `recommend` tinyint(1) unsigned NOT NULL,
  `sort_id` int(10) unsigned NOT NULL DEFAULT '0',
  `is_display` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_apply` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_setmeal||-_-||

DROP TABLE IF EXISTS `qs_shield`;
CREATE TABLE `qs_shield` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_uid` int(10) unsigned NOT NULL,
  `personal_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `company_uid` (`company_uid`),
  KEY `personal_uid` (`personal_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_shield||-_-||

DROP TABLE IF EXISTS `qs_sms_tpl`;
CREATE TABLE `qs_sms_tpl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `code` varchar(30) NOT NULL,
  `alisms_tplcode` varchar(30) NOT NULL,
  `params` varchar(100) NOT NULL,
  `content` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_sms_tpl||-_-||

DROP TABLE IF EXISTS `qs_stat_view_job`;
CREATE TABLE `qs_stat_view_job` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_uid` int(10) unsigned NOT NULL,
  `personal_uid` int(10) unsigned NOT NULL,
  `jobid` int(10) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_comuid_jobid` (`company_uid`,`jobid`),
  KEY `index_peruid_jobid_time` (`personal_uid`,`jobid`,`addtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_stat_view_job||-_-||

DROP TABLE IF EXISTS `qs_subscribe_job`;
CREATE TABLE `qs_subscribe_job` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `category1` int(10) unsigned NOT NULL,
  `category2` int(10) unsigned NOT NULL,
  `category3` int(10) unsigned NOT NULL,
  `category` int(10) unsigned NOT NULL,
  `district1` int(10) unsigned NOT NULL,
  `district2` int(10) unsigned NOT NULL,
  `district3` int(10) unsigned NOT NULL,
  `district` int(10) unsigned NOT NULL,
  `minwage` int(10) unsigned NOT NULL,
  `maxwage` int(10) unsigned NOT NULL,
  `pushtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_subscribe_job||-_-||

DROP TABLE IF EXISTS `qs_task`;
CREATE TABLE `qs_task` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `utype` tinyint(1) unsigned NOT NULL,
  `alias` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `points` int(10) unsigned NOT NULL,
  `daily` tinyint(1) NOT NULL,
  `max_perday` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_task||-_-||

DROP TABLE IF EXISTS `qs_task_record`;
CREATE TABLE `qs_task_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `alias` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `points` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_task_record||-_-||

DROP TABLE IF EXISTS `qs_tipoff`;
CREATE TABLE `qs_tipoff` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `target_id` int(10) unsigned NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `reason` tinyint(1) unsigned NOT NULL,
  `content` varchar(255) NOT NULL,
  `img` varchar(200) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_tipoff||-_-||

DROP TABLE IF EXISTS `qs_uploadfile`;
CREATE TABLE `qs_uploadfile` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `save_path` varchar(255) NOT NULL,
  `platform` varchar(20) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_uploadfile||-_-||

DROP TABLE IF EXISTS `qs_view_job`;
CREATE TABLE `qs_view_job` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_uid` int(10) unsigned NOT NULL,
  `personal_uid` int(10) unsigned NOT NULL,
  `jobid` int(10) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_company_uid` (`company_uid`),
  KEY `index_personal_uid` (`personal_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_view_job||-_-||

DROP TABLE IF EXISTS `qs_view_resume`;
CREATE TABLE `qs_view_resume` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_uid` int(10) unsigned NOT NULL,
  `personal_uid` int(10) unsigned NOT NULL,
  `resume_id` int(10) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_company_uid` (`company_uid`),
  KEY `index_personal_uid` (`personal_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_view_resume||-_-||

DROP TABLE IF EXISTS `qs_wechat_fans`;
CREATE TABLE `qs_wechat_fans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `openid` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_wechat_fans||-_-||

DROP TABLE IF EXISTS `qs_wechat_keyword`;
CREATE TABLE `qs_wechat_keyword` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `word` varchar(30) NOT NULL,
  `return_text` varchar(255) NOT NULL,
  `return_img` varchar(255) NOT NULL,
  `return_img_mediaid` varchar(100) NOT NULL,
  `return_link` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_wechat_keyword||-_-||

DROP TABLE IF EXISTS `qs_wechat_menu`;
CREATE TABLE `qs_wechat_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(30) NOT NULL,
  `key` varchar(30) NOT NULL,
  `type` varchar(30) NOT NULL,
  `url` varchar(255) NOT NULL,
  `pagepath` varchar(100) NOT NULL,
  `sort_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_wechat_menu||-_-||

DROP TABLE IF EXISTS `qs_wechat_notify_rule`;
CREATE TABLE `qs_wechat_notify_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(30) NOT NULL,
  `utype` tinyint(1) unsigned NOT NULL,
  `title` varchar(30) NOT NULL,
  `is_open` tinyint(1) NOT NULL,
  `tpl_name` varchar(30) NOT NULL,
  `tpl_number` varchar(50) NOT NULL,
  `tpl_trade` varchar(30) NOT NULL,
  `tpl_id` varchar(50) NOT NULL,
  `tpl_data` varchar(200) NOT NULL,
  `tpl_param` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_wechat_notify_rule||-_-||

DROP TABLE IF EXISTS `qs_wechat_share`;
CREATE TABLE `qs_wechat_share` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `content` varchar(100) NOT NULL,
  `img` varchar(30) NOT NULL,
  `img_self_cn` varchar(30) NOT NULL,
  `explain` varchar(100) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_wechat_share||-_-||

DROP TABLE IF EXISTS `qs_jobfair_online`;
CREATE TABLE `qs_jobfair_online` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `thumb` int(10) unsigned NOT NULL,
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  `content` longtext NOT NULL,
  `enable_setmeal_id` varchar(100) NOT NULL,
  `must_company_audit` tinyint(1) unsigned NOT NULL,
  `min_complete_percent` int(10) unsigned NOT NULL,
  `click` int(10) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `qrcode` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_jobfair_online||-_-||

DROP TABLE IF EXISTS `qs_jobfair_online_participate`;
CREATE TABLE `qs_jobfair_online_participate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `jobfair_id` int(10) unsigned NOT NULL,
  `utype` tinyint(1) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `audit` tinyint(1) unsigned NOT NULL,
  `qrcode` int(10) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `source` tinyint(1) unsigned NOT NULL,
  `stick` tinyint(1) unsigned NOT NULL,
  `note` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_jobfair_online_participate||-_-||

DROP TABLE IF EXISTS `qs_scene_qrcode`;
CREATE TABLE `qs_scene_qrcode` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(32) NOT NULL,
  `title` varchar(30) NOT NULL,
  `type` varchar(30) NOT NULL,
  `deadline` int(10) unsigned NOT NULL,
  `platform` tinyint(1) unsigned NOT NULL,
  `paramid` int(10) unsigned NOT NULL,
  `qrcode_src` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_scene_qrcode||-_-||

DROP TABLE IF EXISTS `qs_scene_qrcode_reg_log`;
CREATE TABLE `qs_scene_qrcode_reg_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_scene_qrcode_reg_log||-_-||

DROP TABLE IF EXISTS `qs_scene_qrcode_scan_log`;
CREATE TABLE `qs_scene_qrcode_scan_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_scene_qrcode_scan_log||-_-||

DROP TABLE IF EXISTS `qs_scene_qrcode_subscribe_log`;
CREATE TABLE `qs_scene_qrcode_subscribe_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_scene_qrcode_subscribe_log||-_-||

DROP TABLE IF EXISTS `qs_badword`;
CREATE TABLE `qs_badword` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `replace_text` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_badword||-_-||

DROP TABLE IF EXISTS `qs_tweets_label`;
CREATE TABLE `qs_tweets_label` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `value` text NOT NULL,
  `type` tinyint(3) NOT NULL DEFAULT '2' COMMENT '1-头部底部；2-主体',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_tweets_label||-_-||

DROP TABLE IF EXISTS `qs_tweets_template`;
CREATE TABLE `qs_tweets_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `temname` varchar(255) NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `footer` text NOT NULL,
  `addtime` int(10) DEFAULT NULL,
  `is_sys` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_tweets_template||-_-||

DROP TABLE IF EXISTS `qs_identity_token`;
CREATE TABLE `qs_identity_token` (
  `mdtoken` varchar(32) NOT NULL COMMENT 'token',
  `updatetime` int(10) unsigned NOT NULL COMMENT '更新时间',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户uid',
  `expire` int(10) unsigned NOT NULL COMMENT '过期时间',
  PRIMARY KEY (`mdtoken`),
  KEY `index_token` (`mdtoken`) USING BTREE,
  KEY `index_uid` (`uid`) USING BTREE,
  KEY `index_expire` (`expire`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='登录凭证记录表';
||-_-||qs_identity_token||-_-||

||-_-||qs_identity_token||-_-||



DROP TABLE IF EXISTS `qs_member_action_log`;
CREATE TABLE `qs_member_action_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `utype` tinyint(1) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `content` varchar(100) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `ip` varchar(30) NOT NULL,
  `ip_addr` varchar(30) NOT NULL,
  `platform` varchar(30) NOT NULL DEFAULT '',
  `is_login` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  FULLTEXT KEY `index_content` (`content`) /*!50100 WITH PARSER `ngram` */ 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_member_action_log||-_-||

DROP TABLE IF EXISTS `qs_page_mobile`;
CREATE TABLE `qs_page_mobile` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `alias` varchar(30) NOT NULL,
  `enable_cache` tinyint(1) unsigned NOT NULL,
  `expire` int(10) NOT NULL,
  `seo_title` varchar(100) NOT NULL,
  `seo_keywords` varchar(100) NOT NULL,
  `seo_description` varchar(200) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_page_mobile||-_-||

DROP TABLE IF EXISTS `qs_service_ol`;
CREATE TABLE `qs_service_ol` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(15) NOT NULL COMMENT '姓名',
  `mobile` varchar(20) NOT NULL COMMENT '手机',
  `weixin` int(10) NOT NULL COMMENT '微信图片',
  `qq` varchar(30) NOT NULL COMMENT 'QQ',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `display` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_service_ol||-_-||


DROP TABLE IF EXISTS `qs_category_job_template`;
CREATE TABLE `qs_category_job_template` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `pid` int(10) unsigned NOT NULL COMMENT 'pid',
  `title` varchar(30) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='职位分类描述模板表';
||-_-||qs_category_job_template||-_-||

DROP TABLE IF EXISTS `qs_admin_scan_cert`;
CREATE TABLE `qs_admin_scan_cert` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(32) NOT NULL,
  `info` text NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_admin_scan_cert||-_-||


DROP TABLE IF EXISTS `qs_tpl`;
CREATE TABLE `qs_tpl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `alias` varchar(30) NOT NULL,
  `type` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_tpl||-_-||

DROP TABLE IF EXISTS `qs_member_setmeal_open_log`;
CREATE TABLE `qs_member_setmeal_open_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `setmeal_id` int(10) unsigned NOT NULL,
  `setmeal_name` varchar(30) NOT NULL COMMENT '套餐名称',
  `addtime` int(10) unsigned NOT NULL,
  `type` tinyint(1) unsigned NOT NULL COMMENT '开通方式 1注册赠送 2自主开通 3后台开通',
  `type_cn` varchar(30) NOT NULL,
  `order_id` int(10) unsigned NOT NULL COMMENT '订单id',
  `admin_username` varchar(30) NOT NULL,
  `admin_id` int(10) unsigned NOT NULL COMMENT '管理员id',
  PRIMARY KEY (`id`),
  KEY `setmeal_id` (`setmeal_id`),
  KEY `admin_id` (`admin_id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_member_setmeal_open_log||-_-||



DROP TABLE IF EXISTS `qs_sms_blacklist`;
CREATE TABLE `qs_sms_blacklist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mobile` varchar(11) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `note` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mobile` (`mobile`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_sms_blacklist||-_-||


DROP TABLE IF EXISTS `qs_sv_ad`;
CREATE TABLE `qs_sv_ad` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `is_display` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `cid` int(10) NOT NULL COMMENT '分类id',
  `title` varchar(100) NOT NULL COMMENT '标题',
  `imageid` int(10) unsigned NOT NULL COMMENT '图片id',
  `imageurl` varchar(255) NOT NULL COMMENT '图片地址',
  `explain` varchar(255) NOT NULL COMMENT '图片文字说明 ',
  `sort_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `starttime` int(10) unsigned NOT NULL COMMENT '开始时间',
  `deadline` int(10) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `target` tinyint(1) unsigned NOT NULL COMMENT '跳转链接类型',
  `link_url` varchar(255) NOT NULL COMMENT '跳转地址',
  `inner_link` varchar(30) NOT NULL COMMENT '跳转内链地址',
  `inner_link_params` int(10) NOT NULL COMMENT '跳转内链参数',
  `company_id` int(10) unsigned NOT NULL COMMENT '企业id',
  `addtime` int(10) unsigned NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='视频招聘广告表';
||-_-||qs_sv_ad||-_-||

DROP TABLE IF EXISTS `qs_sv_ad_category`;
CREATE TABLE `qs_sv_ad_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(50) NOT NULL COMMENT '调用名称',
  `name` varchar(100) NOT NULL COMMENT '广告位名称',
  `width` int(10) NOT NULL COMMENT '建议宽度',
  `height` int(10) NOT NULL COMMENT '建议高度',
  `ad_num` int(10) unsigned NOT NULL COMMENT '广告数量',
  `is_sys` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否是系统自带，1是，0否',
  `platform` varchar(30) NOT NULL COMMENT '所属平台',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='视频招聘广告分类表';
||-_-||qs_sv_ad_category||-_-||


DROP TABLE IF EXISTS `qs_sv_collect`;
CREATE TABLE `qs_sv_collect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `vid` int(11) NOT NULL COMMENT '视频id',
  `type` tinyint(4) NOT NULL COMMENT '视频类型,1企业,2个人',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uid_type_vid` (`uid`,`type`,`vid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='赞记录表';
||-_-||qs_sv_collect||-_-||

DROP TABLE IF EXISTS `qs_sv_company_video`;
CREATE TABLE `qs_sv_company_video` (
  `id` int(11) NOT NULL COMMENT 'id<10万是未审核,',
  `is_public` tinyint(4) NOT NULL DEFAULT '2' COMMENT '1不公开，2公开',
  `audit` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1未审核,2审核通过,3审核失败',
  `uid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `filesize` int(11) NOT NULL DEFAULT '0' COMMENT '视频大小',
  `fid` int(11) NOT NULL DEFAULT '0' COMMENT '文件id',
  `view_count` int(11) NOT NULL DEFAULT '0' COMMENT '浏览量',
  `lon` double NOT NULL DEFAULT '0' COMMENT '经度',
  `lat` double NOT NULL DEFAULT '0' COMMENT '纬度',
  `address` varchar(30) NOT NULL DEFAULT '' COMMENT '当前地址',
  `like` int(11) NOT NULL DEFAULT '0' COMMENT '赞的数量',
  `real_id` int(11) NOT NULL DEFAULT '0',
  `reason` varchar(50) NOT NULL DEFAULT '' COMMENT '审核未通过理由',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `updatetime` int(11) NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `addtime` (`addtime`),
  KEY `view_count` (`view_count`),
  KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='企业视频表';
||-_-||qs_sv_company_video||-_-||


DROP TABLE IF EXISTS `qs_sv_personal_video`;
CREATE TABLE `qs_sv_personal_video` (
  `id` int(11) NOT NULL COMMENT 'id<10万是未审核,',
  `is_public` tinyint(4) NOT NULL DEFAULT '2' COMMENT '1不公开，2公开',
  `audit` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1未审核,2审核通过,3审核失败',
  `uid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `filesize` int(11) NOT NULL DEFAULT '0' COMMENT '视频大小',
  `fid` int(11) NOT NULL DEFAULT '0' COMMENT '文件id',
  `view_count` int(11) NOT NULL DEFAULT '0' COMMENT '浏览量',
  `lon` double NOT NULL DEFAULT '0' COMMENT '经度',
  `lat` double NOT NULL DEFAULT '0' COMMENT '纬度',
  `address` varchar(30) NOT NULL DEFAULT '' COMMENT '当前地址',
  `like` int(11) NOT NULL DEFAULT '0' COMMENT '赞的数量',
  `real_id` int(11) NOT NULL DEFAULT '0',
  `reason` varchar(50) NOT NULL DEFAULT '' COMMENT '审核未通过理由',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `updatetime` int(11) NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `addtime` (`addtime`),
  KEY `view_count` (`view_count`),
  KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='个人视频表';
||-_-||qs_sv_personal_video||-_-||


DROP TABLE IF EXISTS `qs_short_url`;
CREATE TABLE `qs_short_url` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(125) NOT NULL DEFAULT '' COMMENT '原始链接',
  `code` varbinary(5) NOT NULL DEFAULT '' COMMENT '短码',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `endtime` int(11) NOT NULL DEFAULT '0' COMMENT '截止时间',
  `pv` int(11) NOT NULL DEFAULT '0' COMMENT '点击量',
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '建创者id',
  `remark` varchar(50) NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`),
  KEY `code_endtime` (`code`,`endtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4  COMMENT='短链接表';
||-_-||qs_short_url||-_-||

DROP TABLE IF EXISTS `qs_subsite`;
CREATE TABLE `qs_subsite` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sitename` varchar(30) NOT NULL,
  `district1` int(10) unsigned NOT NULL,
  `district2` int(10) unsigned NOT NULL,
  `district3` int(10) unsigned NOT NULL,
  `district` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `keywords` varchar(100) NOT NULL,
  `description` varchar(200) NOT NULL,
  `tpl` varchar(30) NOT NULL,
  `is_display` tinyint(1) unsigned NOT NULL,
  `sort_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_subsite||-_-||


DROP TABLE IF EXISTS `qs_poster`;
CREATE TABLE `qs_poster` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `indexid` int(10) unsigned NOT NULL,
  `type` tinyint(1) unsigned NOT NULL COMMENT '1职位 2简历 3企业',
  `name` varchar(30) NOT NULL,
  `sort_id` int(10) unsigned NOT NULL,
  `is_display` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
||-_-||qs_poster||-_-||