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
	protected $_columns;
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
			'extra' => 'auto_increment',
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

		$this->_columns = $db->getTableColumns($this->_table,false);
		foreach ($this->_field_defenitions as $field => $defenition){
			$this->checkField($field, $defenition['mysql_type'], $defenition['nullable'],$defenition['default'],base64_encode(json_encode($defenition)), $defenition['extra']);
		}
		$this->_columns = $db->getTableColumns($this->_table,false);

		parent::__construct($this->_table, $this->_primary_key, $db);

		if(!$this->onAfterInit()){
			/*
			 * TODO: Raise ERRORs
			 */
			return false;
		}
	}

	protected function checkField($name, $type = 'text', $is_null = false, $default = false, $comment = '', $extra = ''){
		/** @var JDatabaseDriver $db */
		$db = $this->_db;

		$column = (array)$this->_columns[$name];

		$sql = $this->fieldSql($name, $type, $is_null, $default, $comment, $extra);
		$chitem = JSchemaChangeitem::getInstance($db,null,$sql);
		if($chitem->checkQueryExpected){
			if($chitem->check() !== -2)
			{
				/*
				 * check isn't failed need to check deeper
				 */
				if ($column['Type']!=$type){
					$chitem->checkStatus = -2;
				}elseif ($column['Collation'] && $column['Collation'] != $this->_charset.'_'.$this->_collation){
					$chitem->checkStatus = -2;
				}elseif (($column['NULL']=='NO' && !$is_null) || ($column['NULL']=='YES' && $is_null)){
					$chitem->checkStatus = -2;
				}elseif ($column['Default'] != $default){
					$chitem->checkStatus = -2;
				}elseif ($column['Comment'] != $comment){
					$chitem->checkStatus = -2;
				}

			}

			if($chitem->checkStatus === -2){
				$chitem->fix();
			}
		}
	}

	protected function onBeforeInit(){
		return true;
	}

	protected function onAfterInit(){
		return true;
	}

	protected function fieldSql($name, $type = 'text', $is_null = false, $default = false, $comment = '', $extra = ''){
		$db = $this->_db;
		$sql = 'ALTER TABLE '.$db->qn($this->_table).' '.(array_key_exists($name,$this->_columns)?'MODIFY':'ADD COLUMN').' ';
		if(strpos($type,'text')!==false){
			$type .= ' COLLATE='.$this->_charset.'_'.$this->_collation;
		}elseif (strpos($type,'varchar')!==false){
			$type .= ' CHARACTER SET '.$this->_charset.' COLLATE '.$this->_charset.'_'.$this->_collation;
		}
		$sql .= $name.' '.$type.' '.($is_null?'NULL':'NOT NULL').' '.(is_null($default)?'':('DEFAULT '.$db->q($default)));
		$sql .= ' COMMENT '.$db->q($comment);
		$sql .= ' '.$extra;
		return $sql;
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
