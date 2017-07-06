<?php
/*
 * Class kActiveForm
 * version 1.0
 * requirement - bootstrap 3
 */
 
Yii::import('bootstrap.widgets.TbActiveForm');
 
class kActiveForm extends TbActiveForm
{
    public function textFieldRowEx($model, $attribute, $htmlOptions = array())
    {
        return $this->inputRowEx(TbInput::TYPE_TEXT, $model, $attribute, null, $htmlOptions);
    }

    public function radioButtonListRowEx($model, $attribute, $data = array(), $htmlOptions = array())
    {
        return $this->inputRowEx(TbInput::TYPE_RADIOLIST, $model, $attribute, $data, $htmlOptions);
    }

    public function textAreaRowEx($model, $attribute, $htmlOptions = array())
    {
        return $this->inputRowEx(TbInput::TYPE_TEXTAREA, $model, $attribute, null, $htmlOptions);
    }

    public function fileFieldRowEx($model, $attribute, $htmlOptions = array())
    {
        return $this->inputRowEx(TbInput::TYPE_FILE, $model, $attribute, null, $htmlOptions);
    }

    public function dropDownListRowEx($model, $attribute, $data = array(), $htmlOptions = array())
    {
        return $this->inputRowEx(TbInput::TYPE_DROPDOWN, $model, $attribute, $data, $htmlOptions);
    }

	public function inputRowEx($type, $model, $attribute, $data = null, $htmlOptions = array())
	{
		ob_start();
        echo CHtml::openTag('div',array('class'=>'boxFilter'));
        echo CHtml::openTag('div',array('class'=>'boxListFilter'));
		Yii::app()->controller->widget(
			$this->getInputClassName(),
			array(
				'type' => $type,
				'form' => $this,
				'model' => $model,
				'attribute' => $attribute,
				'data' => $data,
				'htmlOptions' => $htmlOptions,
			)
		);
		echo "\n";
        echo CHtml::closeTag('div');
        echo CHtml::closeTag('div');
		return ob_get_clean();
	}
}