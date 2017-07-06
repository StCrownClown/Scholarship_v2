<?php
class COciSchemaMod extends COciSchema
{
	protected function findColumns($table)
{
    list($schemaName,$tableName) = $this->getSchemaTableName($table->name);
 
 
    $sql=<<<EOD
        SELECT Upper(COLUMN_NAME) as COLUMN_NAME, Upper(DATA_TYPE) as DATA_TYPE, NULLABLE, DATA_DEFAULT, KEY
        FROM www_tab_columns
        Where Upper(table_name) = Upper('{$tableName}')
        ORDER by column_id
EOD;
 
    $command=$this->getDbConnection()->createCommand($sql);
 
    if(($columns=$command->queryAll())===array()){          
        return false;
    }
 
    foreach($columns as $column)
    {           
        $c=$this->createColumn($column);            
 
        $table->columns[$c->name]=$c;
        if($c->isPrimaryKey)
        {
            if($table->primaryKey===null)
                $table->primaryKey=$c->name;
            else if(is_string($table->primaryKey))
                $table->primaryKey=array($table->primaryKey,$c->name);
            else
                $table->primaryKey[]=$c->name;
            /*if(strpos(strtolower($column['Extra']),'auto_increment')!==false)
                $table->sequenceName='';*/
        }
    }
    return true;
}
 
protected function findConstraints($table)
{
    $sql=<<<EOD
    SELECT upper(COLUMN_NAME) As COLUMN_NAME, upper(TABLE_REF) As TABLE_REF, upper(COLUMN_REF) As COLUMN_REF
    FROM WWW_TAB_CONS
     WHERE upper(TABLE_NAME) = upper('{$table->name}')
    Order By POSITION
EOD;
    $command=$this->getDbConnection()->createCommand($sql);
    foreach($command->queryAll() as $row)
    {
        $name = $row["COLUMN_NAME"];
        $table->foreignKeys[$name]=array($row["TABLE_REF"], $row["COLUMN_REF"]);
        if(isset($table->columns[$name]))
            $table->columns[$name]->isForeignKey=true;
    }
}
 
protected function findTableNames($schema='')
{   
    $sql='SELECT upper(table_name) as TABLE_NAME FROM www_tables';
 
    $command=$this->getDbConnection()->createCommand($sql);
    $rows=$command->queryAll();
    $names=array();
    foreach($rows as $row)
    {
        $names[]=$row['TABLE_NAME'];
    }
    return $names;
}
}
