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
 * Config
 */
$GLOBALS['TL_DCA']['tl_page']['config']['onload_callback'][] = array('tl_page_changedevice', 'prepareDataContainer');


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_page']['fields']['isMobileDevice'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_page']['isMobileDevice'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('submitOnChange'=>true),
	'sql'					  => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['desktopRoot'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_page']['desktopRoot'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_page_changedevice', 'getDesktopRootPages'),
	'eval'                    => array('mandatory'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50'),
	'sql'					  => "int(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['desktopPage'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_page']['desktopPage'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_page_changedevice', 'getDesktopPages'),
	'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
	'sql'					  => "int(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['deviceDetection'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_page']['deviceDetection'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('server', 'client'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_page']['deviceDetection'],
	'eval'                    => array('mandatory'=>true, 'submitOnChange'=>true, 'tl_class'=>'w50'),
	'sql'					  => "varchar(8) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['deviceMedia'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_page']['deviceMedia'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true, 'decodeEntities'=>true, 'tl_class'=>'clr long'),
	'sql'					  => "varchar(255) NOT NULL default ''"
);


class tl_page_changedevice extends Backend
{

	/**
	 * Show the dropdown field to select the desktop site if appropriate
	 *
	 * @param $dc
	 */
	public function prepareDataContainer($dc)
	{
		if (\Input::get('act') == 'edit')
		{
			$objPage = PageModel::findWithDetails($dc->id);

			if ($objPage->type == 'root')
			{
				$strPalette = '{device_legend},isMobileDevice';

				if ($objPage->isMobileDevice)
				{
					$strPalette .= ',desktopRoot,deviceDetection';

					if ($objPage->deviceDetection == 'client')
					{
						$strPalette .= ',deviceMedia';
					}
				}

				$GLOBALS['TL_DCA']['tl_page']['palettes']['root'] = str_replace('{sitemap_legend:hide}', $strPalette . ';{sitemap_legend:hide}', $GLOBALS['TL_DCA']['tl_page']['palettes']['root']);
			}
			else
			{
				$objRootPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")->execute($objPage->rootId);

				if ($objRootPage->isMobileDevice)
				{
					$GLOBALS['TL_DCA']['tl_page']['palettes'][$objPage->type] = preg_replace('@([,|;]type)([,|;])@','$1,desktopPage$2', $GLOBALS['TL_DCA']['tl_page']['palettes'][$objPage->type]);
				}
			}
		}
		elseif (\Input::get('act') == 'editAll')
		{
			foreach( $GLOBALS['TL_DCA']['tl_page']['palettes'] as $name => $palette )
			{
				if ($name == '__selector__' || $name == 'root')
					continue;

				$GLOBALS['TL_DCA']['tl_page']['palettes'][$name] = preg_replace('@([,|;]type)([,|;])@','$1,desktopPage$2', $palette);
			}
		}
	}


	/**
	 * Get a list of root pages that could be a desktop site
	 *
	 * @param $dc
	 * @return array
	 */
	public function getDesktopRootPages($dc)
	{
		$arrPages = array();
		$objPages = $this->Database->prepare("SELECT id,title FROM tl_page WHERE type='root' AND isMobileDevice='' AND id NOT IN (SELECT desktopRoot FROM tl_page WHERE isMobileDevice='1' AND id!=?)")->execute($dc->id);

		while ($objPages->next())
		{
			$arrPages[$objPages->id] = $objPages->title;
		}

		return $arrPages;
	}


	/**
	 * Generate a list of pages for the desktop site
	 *
	 * @param $dc
	 * @return array
	 */
	public function getDesktopPages($dc)
	{
		$arrPages = array();
		$objPage = PageModel::findWithDetails($dc->id);
		$objRootPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")->execute($objPage->rootId);

		if ($objRootPage->isMobileDevice && $objRootPage->desktopRoot != 0)
		{
			$this->generatePageOptions($arrPages, $objRootPage->desktopRoot);
		}

		return $arrPages;
	}


	/**
	 * Generates a list of all subpages
	 *
	 * @param array
	 * @param int
	 * @param int
	 */
	protected function generatePageOptions(&$arrPages, $intId=0, $level=-1)
	{
		// Add child pages
		$objPages = $this->Database->prepare("SELECT id, title FROM tl_page WHERE pid=? AND type != 'root' AND type != 'error_403' AND type != 'error_404' ORDER BY sorting")
								   ->execute($intId);

		if ($objPages->numRows < 1)
		{
			return;
		}

		++$level;

		while ($objPages->next())
		{
			$arrPages[$objPages->id] = str_repeat("&nbsp;", (3 * $level)) . $objPages->title;

			$this->generatePageOptions($arrPages, $objPages->id, $level);
		}
	}
}

