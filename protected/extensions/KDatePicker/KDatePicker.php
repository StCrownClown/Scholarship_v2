<?php
Yii::import('zii.widgets.jui.CJuiDatePicker');

class KDatePicker extends CJuiDatePicker  // v1.0
{
	public $_assetsUrl;
	
	public function init()
	{
		Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/js/jquery.ui.datepicker.offset.min.js');
		return parent::init();
	}
	
	private function getAssetsUrl()
	{
		if (isset($this->_assetsUrl))
			return $this->_assetsUrl;
		else
		{
			$assetsPath = Yii::getPathOfAlias('ext.KDatePicker.assets');
			$assetsUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, YII_DEBUG);
			return $this->_assetsUrl = $assetsUrl;
		}
	}
}