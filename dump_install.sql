CREATE TABLE IF NOT EXISTS `lh_maintbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL DEFAULT '',
  `dt` int(20) NOT NULL DEFAULT '0',
  `directory_id` int(11) NOT NULL DEFAULT '0',
  `directory_nm` varchar(250) NOT NULL DEFAULT '',
  `files` varchar(250) NOT NULL DEFAULT '',
  `images` varchar(250) NOT NULL DEFAULT '',
  `numb` int(20) NOT NULL DEFAULT '0',
  `radiobutton` int(11) NOT NULL DEFAULT '0',
  `selectarea` varchar(250) NOT NULL DEFAULT '',
  `textarea` text,
  `varbol` int(1) NOT NULL DEFAULT '1',
  `str1` varchar(250) NOT NULL DEFAULT '',
  `str2` varchar(250) NOT NULL DEFAULT '',
  `str3` varchar(250) DEFAULT '',
  `numb1` int(20) NOT NULL DEFAULT '0',
  `numb2` int(20) NOT NULL DEFAULT '0',
  `numb3` int(20) NOT NULL DEFAULT '0',
  `st` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
CREATE TABLE IF NOT EXISTS `lh_spr1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_root` int(11) NOT NULL DEFAULT '0',
  `name` varchar(250) NOT NULL DEFAULT '',
  `numb` int(11) NOT NULL DEFAULT '0',
  `txt` varchar(250) NOT NULL DEFAULT '',
  `type_row` int(11) NOT NULL DEFAULT '0',
  `st` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=cp1251;
CREATE TABLE IF NOT EXISTS `lh_tblfiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tbl` varchar(50) NOT NULL DEFAULT '',
  `id_row` int(11) NOT NULL DEFAULT '0',
  `dt` int(20) NOT NULL DEFAULT '0',
  `f_name` varchar(250) NOT NULL DEFAULT '',
  `f_path` text,
  `f_descr` varchar(250) NOT NULL DEFAULT '',
  `st` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=cp1251;
CREATE TABLE IF NOT EXISTS `lh_tbllist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tbl` varchar(50) NOT NULL DEFAULT '',
  `id_row` int(11) NOT NULL DEFAULT '0',
  `dt` int(20) NOT NULL DEFAULT '0',
  `descr` text,
  `ch1` int(1) NOT NULL DEFAULT '0',
  `ch2` int(1) NOT NULL DEFAULT '0',
  `ch3` int(1) NOT NULL DEFAULT '0',
  `st` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=cp1251;
CREATE TABLE IF NOT EXISTS `lh_tblmenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_root` int(11) NOT NULL DEFAULT '0',
  `tbl_name` varchar(60) NOT NULL DEFAULT '',
  `title` varchar(250) NOT NULL DEFAULT '' COMMENT 'Заголовок описание',
  `description` text,
  `is_separator` int(1) NOT NULL DEFAULT '0' COMMENT 'это разделитель',
  `tbl_ico` varchar(50) NOT NULL DEFAULT '' COMMENT 'иконка вместо названия',
  `lnk` varchar(250) NOT NULL DEFAULT '' COMMENT 'Ссылка вместо сылки на таблицу',
  `lnk_blank` int(1) NOT NULL DEFAULT '0' COMMENT 'открывать в новом окне',
  `position` int(11) NOT NULL DEFAULT '0' COMMENT 'порядок сортировки',
  `type_row` int(11) NOT NULL DEFAULT '0' COMMENT 'тип записи',
  `u_access` varchar(10) NOT NULL DEFAULT '' COMMENT 'доступ, по умолчанию виден всем',
  `show_submenu` int(1) NOT NULL DEFAULT '0' COMMENT 'показывать подменю',
  `st` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=cp1251;
INSERT INTO `lh_tblmenu` (`id`,`id_root`,`tbl_name`,`title`,`description`,`is_separator`,`tbl_ico`,`lnk`,`lnk_blank`,`position`,`type_row`,`u_access`,`show_submenu`,`st`) VALUES
('1','0','maintbl','','','0','','','0','40','0','read','0','1'),
('2','0','spr1','','','0','','','0','60','0','read','0','1'),
('5','12','tblfiles','','','0','','','0','160','0','admin','0','1'),
('6','12','tbllist','','','0','','','0','180','0','admin','0','1'),
('7','12','tblmenu','Главное меню','','0','','','0','120','0','admin','0','1'),
('13','0','','sep','','1','','','0','20','0','read','0','1'),
('9','12','users','Пользователи','','0','','','0','140','0','admin','0','1'),
('10','12','users_tbl','Разрешения на таблицы','','0','','','0','200','0','admin','0','1'),
('11','0','','sep','','1','','','0','220','0','admin','0','1'),
('12','0','','System','','0','','javascript:void(0);','0','240','1','admin','1','1');

CREATE TABLE IF NOT EXISTS `lh_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_root` int(11) NOT NULL DEFAULT '0',
  `login` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL DEFAULT '',
  `fio` varchar(250) NOT NULL DEFAULT '',
  `user_type` varchar(5) NOT NULL DEFAULT '',
  `description` text,
  `table_default` varchar(60) NOT NULL DEFAULT '' COMMENT 'таблица по умолчаию',
  `type_row` int(1) NOT NULL DEFAULT '0',
  `st` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=cp1251;

INSERT INTO `lh_users` (`id`,`id_root`,`login`,`password`,`fio`,`user_type`,`description`,`table_default`,`type_row`,`st`) VALUES
('1','0','user','123456','','read','','','0','1'),
('2','0','admin','123456','','admin','','','0','1'),
('3','0','root','123456','','root','','','0','1');
CREATE TABLE IF NOT EXISTS `lh_users_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(1) NOT NULL DEFAULT '0',
  `table_name` varchar(50) NOT NULL DEFAULT '',
  `description` text,
  `user_type` varchar(5) NOT NULL DEFAULT '',
  `st` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=101 DEFAULT CHARSET=cp1251;
INSERT INTO `lh_users_tbl` (`id`,`id_user`,`table_name`,`description`,`user_type`,`st`) VALUES
('96','2','users','','admin','1'),
('95','2','tblmenu','','admin','1'),
('94','2','tbllist','','admin','1'),
('93','2','tblfiles','','admin','1'),
('90','2','spr1','','admin','1'),
('89','2','maintbl','','admin','1'),
('54','1','spr1','','edit','1'),
('97','2','users_tbl','','admin','1'),
('99','1','maintbl','','new','0'),
('100','1','maintbl','','edit','1'),
('88','3','users_tbl','','root','1'),
('87','3','users','','root','1'),
('86','3','tblmenu','','root','1'),
('85','3','tbllist','','root','1'),
('84','3','tblfiles','','root','1'),
('81','3','spr1','','root','1'),
('80','3','maintbl','','root','1');