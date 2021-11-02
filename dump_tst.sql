
CREATE TABLE IF NOT EXISTS `tst_maintbl` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`name` varchar(250) NOT NULL DEFAULT '',
`dt` int(20) NOT NULL DEFAULT '0',
`directory_id` int(11) NOT NULL DEFAULT '0',
`directory_name` varchar(250) NOT NULL DEFAULT '',
`files` varchar(250) NOT NULL DEFAULT '',
`images` varchar(250) NOT NULL DEFAULT '',
`numb` int(20) NOT NULL DEFAULT '0',
`radiobutton` int(11) NOT NULL DEFAULT '0',
`selectarea` varchar(250) NOT NULL DEFAULT '',
`textarea` text DEFAULT NULL,
`varbol` int(1) NOT NULL DEFAULT '1',

`str1` varchar(250) NOT NULL DEFAULT '',
`str2` varchar(250) NOT NULL DEFAULT '',
`str3` varchar(250) NOT NULL DEFAULT '',
`numb1` int(20) NOT NULL DEFAULT '0',
`numb2` int(20) NOT NULL DEFAULT '0',
`numb3` int(20) NOT NULL DEFAULT '0',

`st` int(1) NOT NULL DEFAULT '1',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


CREATE TABLE IF NOT EXISTS `tst_spr1` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`id_root` int(11) NOT NULL DEFAULT '0',
`name` varchar(250) NOT NULL DEFAULT '',
`type_row` int(11) NOT NULL DEFAULT '0',
`st` int(1) NOT NULL DEFAULT '1',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

CREATE TABLE IF NOT EXISTS `tst_spr2` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`id_root` int(11) NOT NULL DEFAULT '0',
`name` varchar(250) NOT NULL DEFAULT '',
`type_row` int(11) NOT NULL DEFAULT '0',
`st` int(1) NOT NULL DEFAULT '1',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

CREATE TABLE IF NOT EXISTS `tst_spr3` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`id_root` int(11) NOT NULL DEFAULT '0',
`name` varchar(250) NOT NULL DEFAULT '',
`type_row` int(11) NOT NULL DEFAULT '0',
`st` int(1) NOT NULL DEFAULT '1',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


-- таблицы по умолчанию

CREATE TABLE IF NOT EXISTS `tst_tblfiles` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`tbl` varchar(50) NOT NULL DEFAULT '',
	`id_row` int(11) NOT NULL DEFAULT '0',
	`dt` int(20) NOT NULL DEFAULT '0',
	`f_name` varchar(250) NOT NULL DEFAULT '',
	`f_path` text DEFAULT NULL,
	`f_descr` varchar(250) NOT NULL DEFAULT '',
	`st` int(1) NOT NULL DEFAULT '1',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

CREATE TABLE IF NOT EXISTS `tst_tbllist` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`tbl` varchar(50) NOT NULL DEFAULT '',
	`id_row` int(11) NOT NULL DEFAULT '0',
	`dt` int(20) NOT NULL DEFAULT '0',
	`descr` text DEFAULT NULL,
	`ch1` int(1) NOT NULL DEFAULT '0',
	`ch2` int(1) NOT NULL DEFAULT '0',
	`ch3` int(1) NOT NULL DEFAULT '0',
	`st` int(1) NOT NULL DEFAULT '1',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- справочник
CREATE TABLE IF NOT EXISTS `tst_users` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`id_root` int(11) NOT NULL DEFAULT '0',
	`login` varchar(50) NOT NULL DEFAULT '',
	`password` varchar(100) NOT NULL DEFAULT '',
	`fio` varchar(250) NOT NULL DEFAULT '',
	`user_type` varchar(5) NOT NULL DEFAULT '',
	`description` text DEFAULT NULL,
	`type_row` int(1) NOT NULL DEFAULT '0',
	`st` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

CREATE TABLE IF NOT EXISTS `tst_users_tbl` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`id_user` int(1) NOT NULL DEFAULT '0',
	`table_name` varchar(50) NOT NULL DEFAULT '',
	`description` text DEFAULT NULL,
	`user_type` varchar(5) NOT NULL DEFAULT '',
	`st` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


CREATE TABLE IF NOT EXISTS `tst_tblmenu` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`id_root` int(11) NOT NULL DEFAULT '0',
	`tbl_name` varchar(60) NOT NULL DEFAULT '',
	`title` varchar(250) NOT NULL DEFAULT '' COMMENT 'Заголовок описание',
	`description` text DEFAULT NULL,
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
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 COMMENT 'Cистемное меню';














