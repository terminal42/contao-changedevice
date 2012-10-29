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


class ChangeDevice extends Frontend
{

	public function handleDeviceRedirect()
	{
		// Set a cookie to keep the desktop site
		if ($this->Input->get('desktop') === '1')
		{
			$this->setCookie('useDesktop', true, 0);
			$this->redirect(preg_replace('/((\?|&(amp;)?)desktop=1$)|desktop=1&(amp;)?/i', '', $this->Environment->request));
		}

		if ($this->Environment->agent->mobile && !$this->Input->cookie('useDesktop'))
		{
			global $objPage;

			$objMobileRoot = $this->Database->prepare("SELECT * FROM tl_page WHERE type='root' AND isMobileDevice='1' AND desktopRoot=?")->execute($objPage->rootId);

			// Found a matching mobile page tree for the current site. We must be on a desktop tree.
			if ($objMobileRoot->numRows)
			{
				$objMobilePages = $this->Database->prepare("SELECT id FROM tl_page WHERE desktopPage=?")->execute($objPage->id);

				while ($objMobilePages->next())
				{
					$objMobilePage = $this->getPageDetails($objMobilePages->id);

					if ($objMobilePage->rootId == $objMobileRoot->id)
					{
						$this->redirect(($this->Environment->ssl ? 'https://' : 'http://') . $objMobilePage->domain . '/' . $this->generateFrontendUrl($objMobilePage->row(), null, $objMobilePage->language));
					}
				}
			}
		}
	}
}

