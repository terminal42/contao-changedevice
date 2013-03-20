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
$GLOBALS['TL_DCA']['tl_article']['config']['onload_callback'][] = array('tl_article_changedevice', 'showDesktopSelect');


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_article']['fields']['desktopArticle'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_article']['desktopArticle'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_article_changedevice', 'getDesktopArticles'),
	'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
);


class tl_article_changedevice extends Backend
{

	/**
	 * Inject fields if appropriate.
	 *
	 * @access public
	 * @return void
	 */
	public function showDesktopSelect($dc)
	{
		if (\Input::get('act') == 'edit')
		{
			$objArticle = $this->Database->prepare("SELECT pid, inColumn FROM tl_article WHERE id=?")->execute($dc->id);
			$objPage = $this->getPageDetails($objArticle->pid);

			if ($objPage->numRows && $objPage->desktopPage > 0)
			{
				$GLOBALS['TL_DCA']['tl_article']['fields']['title']['eval']['tl_class'] = 'w50';
				$GLOBALS['TL_DCA']['tl_article']['fields']['alias']['eval']['tl_class'] = 'clr w50';
				$GLOBALS['TL_DCA']['tl_article']['palettes']['default'] = preg_replace('@([,|;]title)([,|;])@', '$1,desktopArticle$2', $GLOBALS['TL_DCA']['tl_article']['palettes']['default']);
			}
		}
		elseif (\Input::get('act') == 'editAll')
		{
			$GLOBALS['TL_DCA']['tl_page']['palettes']['default'] = preg_replace('@([,|;]title)([,|;])@','$1,desktopArticle$2', $GLOBALS['TL_DCA']['tl_page']['palettes']['default']);
		}
	}


	/**
	 * Return all fallback articles for the current page (used as options_callback).
	 *
	 * @access public
	 * @return array
	 */
	public function getDesktopArticles($dc)
	{
		$objArticle = $this->Database->prepare("SELECT pid, inColumn FROM tl_article WHERE id=?")->execute($dc->id);
		$objPage = $this->getPageDetails($objArticle->pid);

		$arrArticles = array();

		if ($objPage->numRows && $objPage->desktopPage > 0)
		{
			$objArticles = $this->Database->prepare("SELECT id, title FROM tl_article WHERE pid=? AND inColumn=?")->execute($objPage->desktopPage, $objArticle->inColumn);

			while ($objArticles->next())
			{
				$arrArticles[$objArticles->id] = $objArticles->title;
			}
		}

		return $arrArticles;
	}
}

