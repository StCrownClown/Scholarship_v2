<?php
/**
 * This is the template for generating the model class of a specified table.
 * - $this: the ModelCode object
 * - $tableName: the table name for this class (prefix is already removed if necessary)
 * - $modelClass: the model class name
 * - $columns: list of table columns (name=>CDbColumnSchema)
 * - $labels: list of attribute labels (name=>label)
 * - $rules: list of validation rules
 * - $relations: list of relations (name=>relation declaration)
 */
?>
<?php echo "<?php\n"; ?>

/**
 * This is the model class for table "<?php echo $tableName; ?>".
 *
 * The followings are the available columns in table '<?php echo $tableName; ?>':

<?php if(!empty($relations)): ?>
 *
 * The followings are the available model relations:
<?php foreach($relations as $name=>$relation): ?>
 * @property <?php
	if (preg_match("~^array\(self::([^,]+), '([^']+)', '([^']+)'\)$~", $relation, $matches))
    {
        $relationType = $matches[1];
        $relationModel = $matches[2];

        switch($relationType){
            case 'HAS_ONE':
                echo $relationModel.' $'.$name."\n";
            break;
            case 'BELONGS_TO':
                echo $relationModel.' $'.$name."\n";
            break;
            case 'HAS_MANY':
                echo $relationModel.'[] $'.$name."\n";
            break;
            case 'MANY_MANY':
                echo $relationModel.'[] $'.$name."\n";
            break;
            default:
                echo 'mixed $'.$name."\n";
        }
	}
    ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?php echo $modelClass; ?> extends <?php echo $this->baseClass."\n"; ?>
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return <?php echo $modelClass; ?> the static model class
	 */
<? $firstColumn = ''; ?>
<? $pk = ''; ?>
<?php foreach($columns as $column): ?>
<?php echo "\t".'public $'.$column->name.";\n"; ?>
<?php
    if(empty($firstColumn))$firstColumn = $column->name;
    if($column->isPrimaryKey === true && empty($pk))$pk = $column->name;
?>
<?php endforeach; ?>
<?php
$firstColumn = (empty($pk))?$firstColumn:$pk;
?>
	<? if($this->buildGetFnSearch == 1): ?>
public $oSearchBar; 
	<? endif; ?>
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '<?php echo $tableName; ?>';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
<?php foreach($rules as $rule): ?>
			<?php echo $rule.",\n"; ?>
<?php endforeach; ?>
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('<?php echo implode(', ', array_keys($columns)); ?>', 'safe', 'on'=>'search'),
			<? if($this->buildGetFnSearch == 1): ?>
array('oSearchBar', 'filterSearchBar', 'on'=>'search'),
			<? endif; ?>
		);
	}
	
	public function checkFormatDate($attribute,$params)
    {
        if(!empty($this->$attribute)){
            // Years from 2500 to 2999 are valid. dd/MM/yyyy or dd/MM/yyyy hh:mm:ss
            // http://www.regular-expressions.info/javascriptexample.html
            if (preg_match('/(0[1-9]|[12][0-9]|3[01])[\/](0[1-9]|1[012])[\/](25|26|27|28|29)\d\d(\s(2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9])?/',$this->$attribute) !== 1) {
                $this->addError($attribute,Yii::t('web','Date format is invalid.'));
            }
        }
    }

    protected function beforeSave()
    {
        // Format dates based on Oracle thai > dd/mm/yyyy
        foreach($this->metadata->tableSchema->columns as $columnName => $column)
        {
            if ($column->dbType == 'DATE')
            {
                if(!empty($this->$columnName)){
                    $pos = strpos($this->$columnName, " ");
                    if ($pos !== false){
                        list($date,$time) = explode(" ",$this->$columnName);
                        $pos = strpos($date, "-");
                        if ($pos !== false){
                            $date = strtotime($date);
                            $this->$columnName = Yii::app()->dateFormatter->format('dd/MM/yyyy HH:mm:ss',$date);
                            $this->$columnName = new CDbExpression("to_date('{$this->$columnName}','DD/MM/YYYY HH24:MI:SS')");
                        }else{
                            list($day,$month,$year) = explode("/",$date);
                            $this->$columnName = $day.'/'.$month.'/'.($year-543).' '.$time;
                            $this->$columnName = new CDbExpression("to_date('{$this->$columnName}','DD/MM/YYYY HH24:MI:SS')");
                        }
                    }else{
                        $pos = strpos($this->$columnName, "-");
                        if ($pos !== false){
                            $date = strtotime($this->$columnName);
                            $this->$columnName = Yii::app()->dateFormatter->format('dd/MM/yyyy',$date);
                            $this->$columnName = new CDbExpression("to_date('{$this->$columnName}','DD/MM/YYYY')");
                        }else{
                            list($day,$month,$year) = explode("/",$this->$columnName);
                            $this->$columnName = $day.'/'.$month.'/'.($year-543);
                            $this->$columnName = new CDbExpression("to_date('{$this->$columnName}','DD/MM/YYYY')");
                        }
                    }
                }
            }
        }
        return parent::beforeSave();
    }

    /*protected function afterFind()
    {
        // Format dates based on Oracle thai > dd/mm/yyyy
        foreach($this->metadata->tableSchema->columns as $columnName => $column)
        {
            if (!strlen($this->$columnName)) continue;

            if ($column->dbType == 'DATE')
            {
                $pos = strpos($this->$columnName, " ");
                if ($pos !== false){
                    list($date,$time) = explode(" ",$this->$columnName);
                    $pos = strpos($date, "-");
                    if ($pos !== false){
                        $date = strtotime($date);
                        $this->$columnName = Yii::app()->dateFormatter->format('dd/MM/yyyy HH:mm:ss',$date);
                    }
                }else{
                    $pos = strpos($this->$columnName, "-");
                    if ($pos !== false){
                        $date = strtotime($this->$columnName);
                        $this->$columnName = Yii::app()->dateFormatter->format('dd/MM/yyyy',
                            $date
                        );
                    }
                }

                list($day,$month,$year) = explode("/",$this->$columnName);
                $this->$columnName = $day.'/'.$month.'/'.($year+543);
                $this->$columnName .= (isset($time))?' '.$time:'';
            }

        }
        return parent::afterFind();
    }*/

	<? if($this->buildGetFnSearch == 1): ?>
public function filterSearchBar($attribute,$params)
	{
		if(!empty($this->$attribute)){
            $text = $this->$attribute;
            $text = str_replace(": ", ":", $text);
            $int = strlen($text) - 1;
            $first = false;
            $tmp = '';
            for($i=0;$i<=$int;$i++){
                if($text[$i] == '"'){
                    if($first === false){
                        $first = true;
                        $tmp .= $text[$i];
                    }else{
                        $first = false;
                        $tmp .= $text[$i];
                    }
                }else if($first === true && $text[$i] == ' '){
                    $tmp .= '_';
                }else if ($first === true && $text[$i] == '_') {
                    $tmp .= '^';
                }else{
                    $tmp .= $text[$i];
                }
            }
            $tmp = str_replace(" ", "$", $tmp);
            $tmp = str_replace('"', "", $tmp);
            $tmp = str_replace("_", " ", $tmp);
            $tmp = str_replace("^", "_", $tmp);
            $tmp = explode("$",$tmp);

            $this->$attribute = $tmp;
            if(!is_array($this->$attribute)){
                $this->$attribute = array($this->$attribute);
            }
		}
	}
	<? endif; ?>
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
<?php foreach($relations as $name=>$relation): ?>
			<?php echo "'$name' => $relation,\n"; ?>
<?php endforeach; ?>
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
<?php foreach($labels as $name=>$label): ?>
			<?php echo "'$name' => Yii::t('$modelClass', '$name'),\n"; ?>
<?php endforeach; ?>
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

<?php
foreach($columns as $name=>$column)
{
	if($column->type==='string')
	{
		echo "\t\t\$criteria->compare('$name',\$this->$name,true);\n";
	}
	else
	{
		echo "\t\t\$criteria->compare('$name',\$this->$name);\n";
	}
}
?>

	<? if($this->buildGetFnSearch == 1): ?>
        if (!empty($this->oSearchBar)) {
            $ck = implode(",", $this->oSearchBar);
            if (strpos($ck, ":") !== false) {
                $tmpSearchBar = $this->checkColumnsSearch($this->oSearchBar);
                $criteria->condition = $tmpSearchBar;
            } else {
                $searchAll = "COM like '%\$value%' [,] COM_2 like '%\$value%' ";
                $tmpSearchBar = $this->checkColumnsSearch($this->oSearchBar, false, $searchAll);
                $criteria->condition = $tmpSearchBar;
            }
        }
	<? endif; ?>	
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize' => 10 ),
			// 'sort'=>array(
                // 'defaultOrder'=>'ID DESC',
			// ),
		));
	}
	
		<? if($this->buildGetFnSearch == 1): ?>

public function getColumnsSearch()
    {
        return array(
            <?
            foreach($columns as $name=>$column){
                echo "\t\t\t\t\$this->getAttributeLabel('{$name}'),\n";
            }
            ?>
            );
    }

public function getColumnsReplace()
    {
        return array(
            <?
            foreach($columns as $name=>$column){
                echo "\t\t\t\t'{$name}',\n";
            }
            ?>
            );
    }

	private function checkColumnsSearch($text,$searchColumn=true,$setSearch = '')
    {
        if(empty($text)) return '';
        $tmpDoc = '';
        $tmpArr = array();

        if($searchColumn === true){
            $textSearch = $this->getColumnsSearch();
            $textReplace = $this->getColumnsReplace();

            foreach($text as $value){
                if(strpos($value, ":") !== false){
                    $co = '';
                    $va = '';
                    list($co,$va) = explode(":",$value);
                    if(!empty($va)){
                        $key = array_search($co, $textSearch);
                        $key = $textReplace[$key];
                        $va = $this->subValueSearch($va);
                        if(isset($tmpArr[$key]) && !empty($tmpArr[$key])){
                            $tmpArr[$key] = str_replace(array('(',')'), "", $tmpArr[$key]);
                            $tmpArr[$key] = "( {$tmpArr[$key]} [,] {$key} {$va} )";
                        }else{
                            $tmpArr[$key] = " {$key} {$va} ";
                        }
                    }
                }
            }
            foreach($tmpArr as $key => $value){
                $tmpDoc .= " {$value} [&]";
            }
        }else{
            if($setSearch != ''){
                if(is_array($text)){
                    $value = implode(" ",$text);
                }else{
                    $value = $text;
                }
                $value = trim($value);
                if($value != ''){
                    eval("\$tmpArr[] = \"(".$setSearch.")\";");
                }
            }
            foreach($tmpArr as $key => $value){
                $tmpDoc .= " {$value} [,]";
            }
        }

        $tmpDoc = substr($tmpDoc, 0, -3);
        $tmpDoc = str_replace("[,]", "or", $tmpDoc);
        $tmpDoc = str_replace("[&]", "and", $tmpDoc);

        return $tmpDoc;
    }

	public function getSeparatorSearch()
	{
		return '(+)';
	}

	private function subValueSearch($val)
	{
		$sp = $this->getSeparatorSearch();
		if(strpos($val, $sp) === false){
			return " like '%{$val}%' ";
		}else{
			$id = '';
			$text = '';
			list($id,$text) = explode($sp,$val);

			return " ='{$id}' ";
		}
	}
	<? endif; ?>
	
	<? if(!empty($this->buildGetNewID)): ?>
public function getID()
	{
		$model=<?php echo $tableName; ?>::model()->find(array(
			'select'=>'max(<? echo $this->buildGetNewID; ?>) as <? echo $this->buildGetNewID; ?>',
		));
		return (isset($model-><? echo $this->buildGetNewID; ?>))?$model-><? echo $this->buildGetNewID; ?>+1:1;
	}
	<? endif; ?>

	public function saveAs()
    {
        Yii::app()->db->createCommand("ALTER SESSION SET NLS_DATE_FORMAT='DD/MM/YYYY'")->execute();
        $doc = <?php echo $tableName; ?>::model()->find(array(
            'select' => '<? echo $firstColumn; ?>',
            'condition' => '<? echo $firstColumn; ?>=:<? echo $firstColumn; ?>',
            'params' => array(':<? echo $firstColumn; ?>' => $this-><? echo $firstColumn; ?>),
        ));
        if(isset($doc-><? echo $firstColumn; ?>)){
            $this->setIsNewRecord(false); // update
        }
        return $this->save();
    }
    
    public function loadData()
    {
        $doc = <?php echo $tableName; ?>::model()->find(array(
            'condition' => '<? echo $firstColumn; ?>=:<? echo $firstColumn; ?>',
            'params' => array(':<? echo $firstColumn; ?>' => $this-><? echo $firstColumn; ?>),
        ));
        if(isset($doc-><? echo $firstColumn; ?>)){
            $array = get_object_vars($doc);
            foreach($array as $name => $value){
                $this->setAttribute($name,$value);
            }
        }
    }
}