<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  terminal42 gmbh 2012
 * @author     Andreas Schempp <andreas.schempp@terminal42.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['changedevice'] = '{title_legend},name,headline,type;{config_legend},desktopLabel,desktopTarget,desktopTitle;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['desktopLabel'] = array
(
	'label'			=> &$GLOBALS['TL_LANG']['tl_module']['desktopLabel'],
	'exclude'		=> true,
	'inputType'		=> 'text',
	'eval'			=> array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
	'sql'			=> "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['desktopTarget'] = array
(
	'label'			=> &$GLOBALS['TL_LANG']['tl_module']['desktopTarget'],
	'exclude'		=> true,
	'inputType'		=> 'checkbox',
	'eval'			=> array('tl_class'=>'w50 m12'),
	'sql'			=> "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['desktopTitle'] = array
(
	'label'			=> &$GLOBALS['TL_LANG']['tl_module']['desktopTitle'],
	'exclude'		=> true,
	'inputType'		=> 'text',
	'eval'			=> array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'clr long'),
	'sql'			=> "varchar(255) NOT NULL default ''"
);

