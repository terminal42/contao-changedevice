-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************


--
-- Table `tl_module`
--

CREATE TABLE `tl_module` (
  `desktopLabel` varchar(255) NOT NULL default '',
  `desktopTarget` char(1) NOT NULL default '',
  `desktopTitle` varchar(255) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table `tl_page`
--

CREATE TABLE `tl_page` (
  `isMobileDevice` char(1) NOT NULL default '',
  `desktopRoot` int(10) unsigned NOT NULL default '0',
  `desktopPage` int(10) unsigned NOT NULL default '0',
  `deviceDetection` varchar(8) NOT NULL default '',
  `deviceMedia` varchar(255) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table `tl_article`
--

CREATE TABLE `tl_article` (
  `desktopArticle` int(10) unsigned NOT NULL default '0',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

