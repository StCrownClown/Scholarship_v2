<?php
class kNavTab extends CWidget  // v1.4
{
	public $tab;
	public $actionTab = 1;
	public $classTab;
	public $groupColor = array();
	
	public $htmlOptions = array();
	
	public $_assetsUrl;
	
	public function init()
	{
		Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/js/navTab.js');
		
		Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrl().'/css/navTab.css');
		
		$this->_registerScript();
		return parent::init();
	}
	
	private function getAssetsUrl()
	{
		if (isset($this->_assetsUrl))
			return $this->_assetsUrl;
		else
		{
			$assetsPath = Yii::getPathOfAlias('ext.kNavTab.assets');
			$assetsUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, YII_DEBUG);
			return $this->_assetsUrl = $assetsUrl;
		}
	}
	
	public function run()
	{
		$tab = '<div>';
		$tab .= '<ul id="detail_tab" class="kNav kNav-tabs">';
		$i = 1;
		if(isset($this->groupColor) && is_array($this->groupColor)){
			$this->_createGroupColor();
		}
		
		foreach ($this->tab as $items){
			$tab .= '<li ';
			$tab .= (isset($items['tabColor']))?	"class='{$items['tabColor']}' " : " ";
			$tab .= '>';
			
			$tab .= '<a ';
			$tab .= "data-type='{$items['type']}' ";
			$tab .= ($items['type'] == 'goto')?	"href='{$items['url']}' " : "href='{$i}' ";
			$tab .= ($items['type'] == 'ajax')?	"data-ajax='{$items['url']}' " : " ";
			$tab .= (isset($items['htmlOptions'])) ? $this->_createOptions($items['htmlOptions']) : '';
			$tab .= ">{$items['name']}</a></li>";
			$i++;
		}
		$tab .= '</ul></div>';
		$tab .= '<div class="ktab-content">';
		
		$i = 1;
		foreach ($this->tab as $items){
			$tab .= "<div id='detail_tab_{$i}' class='ktab-pane ";
			$tab .= (isset($items['classTab']))?$items['classTab']:" ";
			$tab .= "'>";
			$tab .= ($items['type'] == 'text')? $items['detail'] : " ";
			$tab .= "</div>";
			$i++;
		}
		$tab .= '</div>';
		
		echo $tab;
		parent::run();
	}
	
	private function _createOptions($option)
	{
		if(is_array($option)){
			$tmp = '';
			foreach($option as $key => $value){
				$tmp .= $key.'="'.$value.'"';
			}
			return $tmp;
		}
		return '';
	}
	
	private function _registerScript()
	{
		$script = "
			jQuery(function(){
				actTab({$this->actionTab});
			});
			";
					
		Yii::app()->clientScript->registerScript('kNavTab',$script,CClientScript::POS_HEAD);
		
	}
	
	private function _createGroupColor()
	{
		$tmp = '';
		foreach($this->groupColor as $key => $value){
			$tmp .= ".kNav-tabs > .{$key} > a, .kNav-tabs > .{$key} > a:hover {
						background-color: {$value};
						}
					.kNav-tabs > .{$key}.active > a, .kNav-tabs > .{$key}.active > a:hover {
						background-color: #CEECF5;
						}";
		}
		Yii::app()->clientScript->registerCss('kNavTab',$tmp,CClientScript::POS_HEAD);
	}
	
}