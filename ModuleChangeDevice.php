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


class ModuleChangeDevice extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_changedevice';


	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### CHANGE DEVICE ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		global $objPage;

		$objRootPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")->execute($objPage->rootId);

		if (!$objRootPage->isMobileDevice || $objRootPage->desktopRoot == 0)
		{
			return '';
		}

		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
		global $objPage;

		$intRedirect = $objPage->desktopPage;

		if ($intRedirect == 0)
		{
			$intRedirect = $this->recursiveFindDesktopPage($objPage->pid);
		}

		$objRedirect = $this->getPageDetails($intRedirect);

		$strUrl = ($this->Environment->ssl ? 'https://' : 'http://') . $objRedirect->domain . '/' . $this->generateFrontendUrl($objRedirect->row(), null, $objRedirect->language);
		$strUrl .= (strpos($strUrl, '?') === false ? '?' : '&') . 'desktop=1';

		$this->Template->href = $strUrl;
		$this->Template->label = $this->desktopLabel;
		$this->Template->title = $this->desktopTitle;
		$this->Template->target = ($this->desktopTarget ? true : false);
	}


	protected function recursiveFindDesktopPage($intPid)
	{
		$objPage = $this->Database->prepare("SELECT pid, desktopPage, type, desktopRoot FROM tl_page WHERE id=?")->execute($intPid);

		if ($objPage->desktopPage > 0)
		{
			return $objPage->desktopPage;
		}
		elseif ($objPage->type == 'root' || $objPage->pid == 0)
		{
			return $objPage->desktopRoot;
		}
		else
		{
			return $this->recursiveFindDesktopPage($objPage->pid);
		}
	}
}
