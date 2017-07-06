<?php
// 1.4.1
Yii::import('gii.generators.model.ModelCode');
class ModelI18NCode extends ModelCode
{
	public $buildGetNewID;
	public $buildGetFnSearch;
	
	public function rules()
	{
		return array_merge(parent::rules(), array(
			array('buildGetNewID, buildGetFnSearch', 'filter', 'filter'=>'trim'),
		));
	}
	
    public function prepare()
	{
		if(($pos=strrpos($this->tableName,'.'))!==false)
		{
			$schema=substr($this->tableName,0,$pos);
			$tableName=substr($this->tableName,$pos+1);
		}
		else
		{
			$schema='';
			$tableName=$this->tableName;
		}
		if($tableName[strlen($tableName)-1]==='*')
		{
			$tables=Yii::app()->db->schema->getTables($schema);
			if($this->tablePrefix!='')
			{
				foreach($tables as $i=>$table)
				{
					if(strpos($table->name,$this->tablePrefix)!==0)
						unset($tables[$i]);
				}
			}
		}
		else
			$tables=array($this->getTableSchema($this->tableName));

		$this->files=array();
		$templatePath=$this->templatePath;
		$this->relations=$this->generateRelations();

		foreach($tables as $table)
		{
			$tableName=$this->removePrefix($table->name);
			$className=$this->generateClassName($table->name);
			$params=array(
				'tableName'=>$schema==='' ? $tableName : $schema.'.'.$tableName,
				'modelClass'=>$className,
				'columns'=>$table->columns,
				'labels'=>$this->generateLabels($table),
				'rules'=>$this->generateRules($table),
				'relations'=>isset($this->relations[$className]) ? $this->relations[$className] : array(),
			);
			$this->files[]=new CCodeFile(
				Yii::getPathOfAlias($this->modelPath).'/table/'.$className.'.php',
				$this->render($templatePath.'/model.php', $params)
			);
			$this->files[]=new CCodeFile(
				Yii::getPathOfAlias('application.messages.en').'/'.$className.'.php',
				$this->render($templatePath.'/i18n.php', $params)
			);
			$this->files[]=new CCodeFile(
				Yii::getPathOfAlias('application.messages.th').'/'.$className.'.php',
				$this->render($templatePath.'/i18n.php', $params)
			);
		}
	}
}
