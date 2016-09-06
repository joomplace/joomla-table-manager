<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport( 'joomla.database.table' );

class JTableManager extends JTable
{

	protected $_db;
	protected $_table = '#__jtable_test';
	protected $_primary_key = 'id';
	protected $_charset = 'utf8mb4';
	protected $_collation = 'unicode_ci';
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
	);

	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  &$db  Database connector object
	 *
	 * @since   1.6
	 */
	public function __construct()
	{
		$db = $this->_db = JFactory::getDbo();
		$this->_charset = ($db->hasUTF8mb4Support())?'utf8mb4':'utf8';

		if(!$this->onBeforeInit()){
			/*
			 * TODO: Raise ERRORs
			 */
			return false;
		}

		$tables = $db->getTableList();
		if(!in_array(str_replace('#__',$db->getPrefix(),$this->_table),$tables)){
			if(!$this->createTable()){
				/*
				 * TODO: Raise ERRORs
				 */
			}
		}
		$columns = $db->getTableColumns($this->_table);

		foreach ($this->_field_defenitions as $field => $defenition){
			if(!array_key_exists($field,$columns)){
				$result = $this->createField($field, $defenition['mysql_type'], $defenition['nullable'],$defenition['default'],base64_encode(json_encode($defenition)));
				if(!$result){
					/*
					 * TODO: Raise ERRORs
					 */
				}
			}else{
				/*
				 * TODO: Check type
				 */
			}
		}

		parent::__construct($this->_table, $this->_primary_key, $db);

		if(!$this->onAfterInit()){
			/*
			 * TODO: Raise ERRORs
			 */
			return false;
		}
	}

	protected function onBeforeInit(){
		return true;
	}

	protected function onAfterInit(){
		return true;
	}

	protected function createField($name, $type = 'text', $is_null = false, $default = false, $comment = ''){
		$db = $this->_db;
		$sql = 'ALTER TABLE '.$db->qn($this->_table).' ADD COLUMN ';
		if(strpos($type,'text')!==false){
			$type .= ' COLLATE='.$this->_charset.'_'.$this->_collation;
		}elseif (strpos($type,'varchar')!==false){
			$type .= ' CHARACTER SET '.$this->_charset.' COLLATE '.$this->_charset.'_'.$this->_collation;
		}
		$sql .= $db->qn($name).' '.$type.' '.($is_null?'NULL':'NOT NULL').' '.(is_null($default)?'':('DEFAULT '.$db->q($default)));
		$sql .= ' COMMENT '.$db->q($comment);
		return $db->setQuery($sql)->execute();

	}

	protected function createTable(){
		$db = $this->_db;
		$sql = "CREATE TABLE ".$db->qn($this->_table)." (
				".$db->qn($this->_primary_key)." int(10) unsigned NOT NULL AUTO_INCREMENT, 
				PRIMARY KEY (".$db->qn($this->_primary_key).")
			) ENGINE=InnoDB DEFAULT CHARSET=".$this->_charset." COLLATE=".$this->_charset."_".$this->_collation."";
		return $db->setQuery($sql)->execute();
	}

	public function getForm(){
		$key = $this->_primary_key;
		$name = str_replace('#__',$this->_table).($this->$key?('.'.$this->$key):'');
		$xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><form></form>');
		$fieldset = $xml->addChild('fieldset');
		foreach ($this->_field_defenitions as $key => $defenition){
			$defenition['name'] = $key;
			$field = $fieldset->addChild('field');
			foreach ($defenition as $attr => $attr_value){
				if(in_array($attr,array('option'))){
					foreach ($attr_value as $kopt => $opt){
						$option = $field->addChild('option',$opt)->addAttribute('value',$kopt);
					}
				}else{
					$field->addAttribute($attr,$attr_value);
				}
			}
		}

		$form = JForm::getInstance($name, $xml->asXML(), array(), true, false);
		$this->preprocessForm($form);

		return $form;
	}

	protected function preprocessForm(JForm $form){

	}
}
