<?php
/**
 * @version     1.3
 * @see     	http://documentcloud.github.io/visualsearch/
 * Yii 1.1.14
 */
class VisualSearch extends CWidget
{
	public $id = 'search_box_container';
	public $options = array();  // array the options for the VisualSearch Javascript plugin.
	
	public $_assetsUrl;
	
	public function init()
	{
		Yii::app()->clientScript->registerCoreScript('jquery');
		Yii::app()->clientScript->registerCoreScript('jquery.ui');
		
	/*	Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/vendor/jquery.ui.core.js');
		Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/vendor/jquery.ui.widget.js');
		Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/vendor/jquery.ui.position.js');
		Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/vendor/jquery.ui.menu.js');
		Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/vendor/jquery.ui.autocomplete.js');*/
		Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/vendor/underscore-1.4.3.js');
		Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/vendor/backbone-0.9.10.js');
		
		Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/lib/js/visualsearch.js');
		Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/lib/js/views/search_box.js');
		Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/lib/js/views/search_facet.js');
		Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/lib/js/views/search_input.js');
		Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/lib/js/models/search_facets.js');
		Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/lib/js/models/search_query.js');
		Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/lib/js/utils/backbone_extensions.js');
		Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/lib/js/utils/hotkeys.js');
		Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/lib/js/utils/jquery_extensions.js');
		Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/lib/js/utils/search_parser.js');
		Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/lib/js/utils/inflector.js');
		Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/lib/js/templates/templates.js');

		Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/js/script.js');

		Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrl().'/lib/css/reset.css');
		Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrl().'/lib/css/icons.css');
		Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrl().'/lib/css/workspace.css');
		
		$this->_registerScript();
		return parent::init();
	}
	
	private function getAssetsUrl()
	{
		if (isset($this->_assetsUrl))
			return $this->_assetsUrl;
		else
		{
			$assetsPath = Yii::getPathOfAlias('ext.VisualSearch.assets');
			$assetsUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, YII_DEBUG);
			return $this->_assetsUrl = $assetsUrl;
		}
	}
	
	public function run()
	{
		$topMenu = "<div id='{$this->id}'></div>";
		echo $topMenu;
		parent::run();
	}
	
	private function _registerScript()
	{
		$this->options['container'] = "js:$('#{$this->id}')";
		$options = !empty($this->options) ? CJavaScript::encode($this->options) : '';
		$script = "
			$(document).ready(function() {
				window.visualSearch = VS.init({$options});
			  });
			";
		Yii::app()->clientScript->registerScript(__CLASS__,$script);

        Yii::app()->getClientScript()->registerCss(__class__,"
            #{$this->id} .ui-menu {
                width: 0px;
            }
        ");
	}
}