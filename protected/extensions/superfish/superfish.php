<?php
/**
 * @version     2.1
 * superfish version 1.7.5
 */
class superfish extends CWidget
{
	public $items;
	public $minWidth = 12;
	public $maxWidth = 27;
	public $actionMenu;
	
	public $filterMenu = false;
	public $filterMenuList;
	
	public $_assetsUrl;

	public $sendLogUrl = '';

	public function init()
	{
		Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/js/hoverIntent.js');
		Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/js/superfish.js');
		Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/js/supersubs.js');
		Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/js/superMenu.js');
		
		Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrl().'/css/superfish.css');
		
		$this->_registerScript();
		return parent::init();
	}
	
	private function getAssetsUrl()
	{
		if (isset($this->_assetsUrl))
			return $this->_assetsUrl;
		else
		{
			$assetsPath = Yii::getPathOfAlias('ext.superfish.assets');
			$assetsUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, YII_DEBUG);
			return $this->_assetsUrl = $assetsUrl;
		}
	}
	
	public function run()
	{
		$topMenu = "<ul class='sf-menu' >";
		foreach ($this->items as $items){
			if($this->filterMenu){
				if (in_array($items['id'], $this->filterMenuList)) {
					$topMenu .= "<li>";
					$topMenu .= $this->_checkLink($items);
					$topMenu .= "</li>";
				}
			}else{
				$topMenu .= "<li>";
				$topMenu .= $this->_checkLink($items);
				$topMenu .= "</li>";
			}
		}

		$topMenu .= "</ul>";
		echo $topMenu;
		parent::run();
	}
	
	private function _checkLink($items)
	{
		if(!is_array($items['link'])){
			if(isset($items['optionLink']) && is_array($items['optionLink']))
				$menu = $this->createLink($items['name'],$items['link'],$items['optionLink'],'',$items['id']);
			else
				$menu = $this->createLink($items['name'],$items['link'],array(),'',$items['id']);
		}else{
			$menu = $this->createLink($items['name'],'#',array(),'',$items['id']);
			$menu .= $this->_createSub($items['link']);
		}
		return $menu;
	}
	
	private function _createSub($items)
	{
		$menu = "<ul>";
		foreach ($items as $key => $sub){
			$menu .= "<li>";
			if(!is_array($sub['link'])){
				if(isset($items['optionLink']) && is_array($items['optionLink']))
					$menu .= $this->createLink($sub['name'],$sub['link'],$sub['optionLink'],'sub_'.$key);
				else
					$menu .= $this->createLink($sub['name'],$sub['link'],array(),'sub_'.$key);
			}else{
				$menu .= $this->createLink($sub['name'],'#',array(),'sub_'.$key);
				$menu .= $this->_createMenuSub($sub['link']);
			}
			
			$menu .= "</li>";
		}
		$menu .= "</ul>";
		return $menu;
	}
	
	private function _createMenuSub($items)
	{
		$menu = "<ul>";
		foreach ($items as $key => $sub){
			$menu .= "<li>";
			if(is_array($sub['optionLink']))
				$menu .= $this->createLink($sub['name'],$sub['link'],$sub['optionLink'],'menusub_'.$key);
			else
				$menu .= $this->createLink($sub['name'],$sub['link'],array(),'menusub_'.$key);

			$menu .= "</li>";
		}
		$menu .= "</ul>";
		return $menu;
	}

    private function createLink($name,$url,$option = array(),$class = '', $id = ''){
        if(strpos($url, 'js:') === false){
            return CHtml::link($name,Yii::app()->createUrl($url,$option),array('class'=>$class,'id'=>$id));
        }else{
            $url = str_replace("js:", "", $url);
            return CHtml::link($name,'#',array('onClick'=>$url.';return false;','class'=>$class,'id'=>$id));
        }
    }
	
	private function _registerScript()
	{
		$script = "
			jQuery(function(){
				jQuery('ul.sf-menu').supersubs({ 
					minWidth:    {$this->minWidth},
					maxWidth:    {$this->maxWidth},
					extraWidth:  1  
				}).superfish(); 
				
				actionMenuTop({$this->actionMenu});
			});
			";

            if(!empty($this->sendLogUrl)){
                $script .= "

                        jQuery(function(){
                             $('#mainMenu ul li a').click(function(){
                                $.ajax({
                                    type: 'POST',
                                    url: '".$this->sendLogUrl."',
                                    data: {name: $(this).text()},
                                    success: function(msg){
                                        return true;
                                    }
                                });
                            });
                        });
                        ";
            }
					
		Yii::app()->clientScript->registerScript('superfish',$script,CClientScript::POS_HEAD);
		
	}
}