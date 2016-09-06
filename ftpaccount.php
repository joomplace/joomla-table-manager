<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport( 'manager', dirname(__FILE__) );

class FtpAccount extends JTableManager
{

	protected $_table = '#__accounts_ftp';
	protected $_field_defenitions = array(
		'id' => array(
			'mysql_type' => 'int(10) unsigned',
			'type' => 'hidden',
			'filter' => 'integer',
			'group' => '',
			'fieldset' => 'basic',
			'class' => '',
			'read_only' => null,
			'nullable' => false,
			'default' => null,
		),
		'asset_id' => array(
			'mysql_type' => 'int(10) unsigned',
			'type' => 'hidden',
			'filter' => 'unset',
			'group' => '',
			'fieldset' => 'basic',
			'class' => '',
			'read_only' => null,
			'nullable' => false,
			'default' => 0,
		),
		'title' => array(
			'mysql_type' => 'varchar(255)',
			'type' => 'text',
			'filter' => 'string',
			'group' => '',
			'fieldset' => 'basic',
			'class' => 'input',
			'read_only' => null,
			'nullable' => false,
			'default' => '',
		),
		'type' => array(
			'mysql_type' => 'varchar(255)',
			'type' => 'radio',
			'group' => '',
			'fieldset' => 'basic',
			'class' => 'btn-group',
			'read_only' => null,
			'nullable' => false,
			'default' => 'ftp',
			'option' => array(
				'ftp' => 'FTP',
				'sftp' => 'SFTP',
			)
		),
		'server' => array(
			'mysql_type' => 'varchar(255)',
			'type' => 'text',
			'filter' => 'safehtml',
			'group' => '',
			'fieldset' => 'basic',
			'class' => '',
			'read_only' => null,
			'nullable' => false,
			'default' => '',
		),
		'port' => array(
			'mysql_type' => 'varchar(4)',
			'type' => 'text',
			'filter' => 'integer',
			'group' => '',
			'fieldset' => 'basic',
			'class' => '',
			'read_only' => null,
			'nullable' => false,
			'default' => '21',
		),
		'login' => array(
			'mysql_type' => 'varchar(255)',
			'type' => 'text',
			'filter' => 'string',
			'group' => '',
			'fieldset' => 'basic',
			'class' => '',
			'read_only' => null,
			'nullable' => false,
			'default' => '',
		),
		'password' => array(
			'mysql_type' => 'varchar(255)',
			'type' => 'password',
			'filter' => 'RAW',
			'group' => '',
			'fieldset' => 'basic',
			'class' => '',
			'read_only' => null,
			'nullable' => false,
			'default' => '',
		),
	);

	protected function onBeforeInit(){
		return true;
	}

	protected function onAfterInit(){
		return true;
	}
}
