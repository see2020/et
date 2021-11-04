CREATE TABLE IF NOT EXISTS `tst_tblfiles` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`tbl` varchar(50) NOT NULL DEFAULT '',
    `type_row` varchar(11) NOT NULL DEFAULT 'list' COMMENT '��������� ������ �� �����. list - ������ ��� �������, field_once - ��� ���� �����, field_list - ��� ���� ������ �����',
	`id_row` int(11) NOT NULL DEFAULT '0',
	`dt` int(20) NOT NULL DEFAULT '0',
	`f_name` varchar(250) NOT NULL DEFAULT '',
	`f_path` text DEFAULT NULL,
	`f_descr` varchar(250) NOT NULL DEFAULT '',
	`st` int(1) NOT NULL DEFAULT '1',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 COMMENT '������ ������ ��� ������';

CREATE TABLE IF NOT EXISTS `tst_tbllist` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`tbl` varchar(50) NOT NULL DEFAULT '',
    `type_row` varchar(11) NOT NULL DEFAULT 'list' COMMENT '��������� ��� �������. list - ������ ��� �������, field_list - ��� ���� ������',
	`id_row` int(11) NOT NULL DEFAULT '0',
	`dt` int(20) NOT NULL DEFAULT '0',
	`descr` text DEFAULT NULL,
    `lnk` varchar(150) NOT NULL DEFAULT '' COMMENT '���� ������������ ��� ���� ������ ������',
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
  `title` varchar(250) NOT NULL DEFAULT '' COMMENT '��������� ��������',
  `description` text DEFAULT NULL,
  `is_separator` int(1) NOT NULL DEFAULT '0' COMMENT '��� �����������',
  `tbl_ico` varchar(50) NOT NULL DEFAULT '' COMMENT '������ ������ ��������',
  `lnk` varchar(250) NOT NULL DEFAULT '' COMMENT '������ ������ ����� �� �������',
  `lnk_blank` int(1) NOT NULL DEFAULT '0' COMMENT '��������� � ����� ����',
  `position` int(11) NOT NULL DEFAULT '0' COMMENT '������� ����������',
  `type_row` int(11) NOT NULL DEFAULT '0' COMMENT '��� ������',
  `u_access` varchar(10) NOT NULL DEFAULT '' COMMENT '������, �� ��������� ����� ����',
  `show_submenu` int(1) NOT NULL DEFAULT '0' COMMENT '���������� �������',
  `st` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 COMMENT '��������� ����';

CREATE TABLE IF NOT EXISTS `tst_users` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`id_root` int(11) NOT NULL DEFAULT '0',
	`login` varchar(50) NOT NULL DEFAULT '',
	`password` varchar(100) NOT NULL DEFAULT '',
	`fio` varchar(250) NOT NULL DEFAULT '',
	`user_type` varchar(5) NOT NULL DEFAULT '',
	`description` text DEFAULT NULL,
	`table_default` varchar(60) NOT NULL DEFAULT '' COMMENT '������� �� ��������',
	`type_row` int(1) NOT NULL DEFAULT '0',
	`st` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 COMMENT '���������� �������������';


-- CREATE TABLE IF NOT EXISTS `tst_users_permit` (
	-- `id` int(11) NOT NULL AUTO_INCREMENT,
	-- `id_root` int(11) NOT NULL DEFAULT '0',
	-- `name` varchar(50) NOT NULL DEFAULT '',
	-- `description` varchar(250) NOT NULL DEFAULT '',
	-- `type_row` int(1) NOT NULL DEFAULT '0',
	-- `st` int(1) NOT NULL DEFAULT '1',
  -- PRIMARY KEY (`id`)
-- ) ENGINE=MyISAM DEFAULT CHARSET=cp1251 COMMENT '���������� ����� ����������';

-- INSERT INTO `tst_users_permit` (`name`, `description` ) VALUES
-- ('read', '������'),
-- ('new', '����� ������'),
-- ('edit', '��������������'),
-- ('admin', '�����'),
-- ('root', '������� �����');


CREATE TABLE IF NOT EXISTS `tst_users_tbl` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`id_user` int(1) NOT NULL DEFAULT '0',
	`table_name` varchar(50) NOT NULL DEFAULT '',
	`description` text DEFAULT NULL,
	`user_type` varchar(5) NOT NULL DEFAULT '',
	`st` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 COMMENT '���������� ���������� �� �������';
