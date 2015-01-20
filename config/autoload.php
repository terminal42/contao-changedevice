<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package Changedevice
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'ChangeDevice'       => 'system/modules/changedevice/ChangeDevice.php',
	'ModuleChangeDevice' => 'system/modules/changedevice/ModuleChangeDevice.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_changedevice' => 'system/modules/changedevice/templates',
));
