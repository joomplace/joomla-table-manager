<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport( 'ftpaccount', JPATH_SITE );

$table = new FtpAccount();
$form = $table->getForm();
$fieldset = $form->getFieldsets();
foreach ($form->getFieldset() as $field){
	echo "<div>";
	echo $field->label;
	echo "<div>";
	echo $field->input;
	echo "</div>";
	echo "</div>";
}
