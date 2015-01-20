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
		if (\Input::get('desktop') === '1')
		{
			$this->setCookie('useDesktop', true, 0);
			\Controller::redirect(preg_replace('/((\?|&(amp;)?)desktop=1$)|desktop=1&(amp;)?/i', '', \Environment::get('request')));
		}

		if (!\Input::cookie('useDesktop'))
		{

			$objMobileRoot = $this->Database->prepare("SELECT * FROM tl_page WHERE type='root' AND isMobileDevice='1' AND desktopRoot=?")->limit(1)->execute($GLOBALS['objPage']->rootId);

			// Found a matching mobile page tree for the current site. We must be on a desktop tree.
			if ($objMobileRoot->numRows && ($this->Environment->agent->mobile || $objMobileRoot->deviceDetection == 'client'))
			{
				$objMobilePages = $this->Database->prepare("SELECT id FROM tl_page WHERE desktopPage=?")->execute($GLOBALS['objPage']->id);

				while ($objMobilePages->next())
				{
					$objMobilePage = PageModel::findWithDetails($objMobilePages->id);

					if ($objMobilePage->rootId == $objMobileRoot->id)
					{
						$strUrl = $this->generateFrontendUrl($objMobilePage->row(), null, $objMobilePage->language);
						if(substr($strUrl,0,4) != 'http' && $objMobilePage->domain)
						{
							$strUrl = ($this->Environment->ssl ? 'https://' : 'http://') . $objMobilePage->domain . '/' . $strUrl;
						}

						if ($objMobileRoot->deviceDetection == 'client')
						{
							$blnXHTML = ($GLOBALS['objPage']->outputFormat != 'html5');

							// Add matchMedia polyfill on mobile only to make sure we detect them correctly
							if ($this->Environment->agent->mobile)
							{
								$GLOBALS['TL_HEAD']['matchMedia'] = '<script' . ($blnXHTML ? ' type="text/javascript"' : '') . '>
/*! matchMedia() polyfill - Test a CSS media type/query in JS. Authors & copyright (c) 2012: Scott Jehl, Paul Irish, Nicholas Zakas. Dual MIT/BSD license */
window.matchMedia=window.matchMedia||(function(e,f){var c,a=e.documentElement,b=a.firstElementChild||a.firstChild,d=e.createElement("body"),g=e.createElement("div");g.id="mq-test-1";g.style.cssText="position:absolute;top:-100em";d.style.background="none";d.appendChild(g);return function(h){g.innerHTML=\'&shy;<style media="\'+h+\'"> #mq-test-1 { width: 42px; }</style>\';a.insertBefore(d,b);c=g.offsetWidth===42;a.removeChild(d);return{matches:c,media:h}}}(document));
</script>';
							}

							$GLOBALS['TL_HEAD'][] = '<script' . ($blnXHTML ? ' type="text/javascript"' : '') . '>if(window.matchMedia && window.matchMedia("' . str_replace('"', '\"', $objMobileRoot->deviceMedia) . '").matches) window.location.href=\'' . $strUrl . '\';</script>';
						}
						else
						{
							\Controller::redirect($strUrl);
						}
					}
				}
			}
		}
	}
}

