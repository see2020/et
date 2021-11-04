CREATE TABLE IF NOT EXISTS `tst_tblfiles` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`tbl` varchar(50) NOT NULL DEFAULT '',
    `type_row` varchar(11) NOT NULL DEFAULT 'list' COMMENT 'хранилище ссылок на файлы. list - список для вкладки, field_once - для поля файла, field_list - для поля списка фалов',
	`id_row` int(11) NOT NULL DEFAULT '0',
	`dt` int(20) NOT NULL DEFAULT '0',
	`f_name` varchar(250) NOT NULL DEFAULT '',
	`f_path` text DEFAULT NULL,
	`f_descr` varchar(250) NOT NULL DEFAULT '',
	`st` int(1) NOT NULL DEFAULT '1',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 COMMENT 'Список файлов для таблиц';

CREATE TABLE IF NOT EXISTS `tst_tbllist` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`tbl` varchar(50) NOT NULL DEFAULT '',
    `type_row` varchar(11) NOT NULL DEFAULT 'list' COMMENT 'хранилище для списков. list - список для вкладки, field_list - для поля списка',
	`id_row` int(11) NOT NULL DEFAULT '0',
	`dt` int(20) NOT NULL DEFAULT '0',
	`descr` text DEFAULT NULL,
    `lnk` varchar(150) NOT NULL DEFAULT '' COMMENT 'Если используется для поля списка ссылок',
	`ch1` int(1) NOT NULL DEFAULT '0',
	`ch2` int(1) NOT NULL DEFAULT '0',
	`ch3` int(1) NOT NULL DEFAULT '0',
	`st` int(1) NOT NULL DEFAULT '1',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


CREATE TABLE IF NOT EXISTS `tst_tst_spr` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`id_root` int(11) NOT NULL DEFAULT '0',
`name` varchar(250) NOT NULL DEFAULT '',
`type_row` int(11) NOT NULL DEFAULT '0',
`st` int(1) NOT NULL DEFAULT '1',
PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=cp1251;


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
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 COMMENT 'Системное меню';

CREATE TABLE IF NOT EXISTS `tst_users` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`id_root` int(11) NOT NULL DEFAULT '0',
	`login` varchar(50) NOT NULL DEFAULT '',
	`password` varchar(100) NOT NULL DEFAULT '',
	`fio` varchar(250) NOT NULL DEFAULT '',
	`user_type` varchar(5) NOT NULL DEFAULT '',
	`description` text DEFAULT NULL,
	`table_default` varchar(60) NOT NULL DEFAULT '' COMMENT 'таблица по умолчаию',
	`type_row` int(1) NOT NULL DEFAULT '0',
	`st` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 COMMENT 'Справочник пользователей';


-- CREATE TABLE IF NOT EXISTS `tst_users_permit` (
	-- `id` int(11) NOT NULL AUTO_INCREMENT,
	-- `id_root` int(11) NOT NULL DEFAULT '0',
	-- `name` varchar(50) NOT NULL DEFAULT '',
	-- `description` varchar(250) NOT NULL DEFAULT '',
	-- `type_row` int(1) NOT NULL DEFAULT '0',
	-- `st` int(1) NOT NULL DEFAULT '1',
  -- PRIMARY KEY (`id`)
-- ) ENGINE=MyISAM DEFAULT CHARSET=cp1251 COMMENT 'Справочник видов разрешений';

-- INSERT INTO `tst_users_permit` (`name`, `description` ) VALUES
-- ('read', 'Чтение'),
-- ('new', 'Новая запись'),
-- ('edit', 'Редактирование'),
-- ('admin', 'Админ'),
-- ('root', 'Главный админ');


CREATE TABLE IF NOT EXISTS `tst_users_tbl` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`id_user` int(1) NOT NULL DEFAULT '0',
	`table_name` varchar(50) NOT NULL DEFAULT '',
	`description` text DEFAULT NULL,
	`user_type` varchar(5) NOT NULL DEFAULT '',
	`st` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 COMMENT 'Справочник разрешений на таблицы';
