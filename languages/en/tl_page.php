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
 * Fields
 */
$GLOBALS['TL_LANG']['tl_page']['isMobileDevice']	= array('Mobile Site', 'Check here if this page tree is for mobile devices.');
$GLOBALS['TL_LANG']['tl_page']['desktopRoot']		= array('Desktop Site', 'Select the root page that contains the desktop equivalent of this page tree.');
$GLOBALS['TL_LANG']['tl_page']['desktopPage']		= array('Desktop Page', 'Select the page from desktop tree that is the same as this mobile page.');
$GLOBALS['TL_LANG']['tl_page']['deviceDetection']	= array('Device detection', 'Select how a device should be detected.');
$GLOBALS['TL_LANG']['tl_page']['deviceMedia']		= array('Media query', 'Enter a valid media query to detect the device. Example: "(max-width: 768px)"');

/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_page']['deviceDetection']['server']	= 'Server-side (Contao/User-Agent)';
$GLOBALS['TL_LANG']['tl_page']['deviceDetection']['client']	= 'Client-side (Browser/Media Query)';

/**
 * Legend
 */
$GLOBALS['TL_LANG']['tl_page']['device_legend']	= 'Change Device';

?>