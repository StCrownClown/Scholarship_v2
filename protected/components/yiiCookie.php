<?php
class yiiCookie // 1.2
{
	public function setCookie($name,$value,$expire=null)
	{
		$cookie = new CHttpCookie($name, $value);
		if(!is_null($expire) && !empty($expire)){
			list($type,$i) = explode("_",$expire);
			if(!isset($i)) $i = 1;
			
			if($type == 'min')
				$times = 60*$i;
			else if($type == 'hour')
				$times = 3600*$i;
			else if($type == 'day')
				$times = 86400*$i;
			else if($type == 'week')
				$times = 604800*$i;
			else if($type == 'month')
				$times = 2592000*$i;
			
			$cookie->expire = time()+$times;
		}
			//$cookie->expire = time()+60*60*24*180; 
		Yii::app()->request->cookies[$name] = $cookie;
	}
	
	public function getCookie($name)
	{
		return isset(Yii::app()->request->cookies[$name]) ? Yii::app()->request->cookies[$name]->value : '';
	}
	
	public function delCookie($name)
	{
		if(isset(Yii::app()->request->cookies[$name])) unset(Yii::app()->request->cookies[$name]);
	}
	
	public function delAllCookie()
	{
		Yii::app()->request->cookies->clear();
	}
	
	public function checkCookie($name)
	{
		return isset(Yii::app()->request->cookies[$name]) ? true : false;
	}
	
	// JS
	public function js_regFile()
	{
		Yii::app()->clientScript->registerCoreScript('cookie');
	}
	
	public function js_setCookie($name,$value,$exp='',$path='')
	{
		$option = false;
		$tmp = '';
		if(!empty($exp)){
			$exp = 'expires: '.CJavaScript::encode($exp);
			$option = true;
		}
		if(!empty($path)){
			$path = 'path: '.CJavaScript::encode($path);
			$option = true;
		}
		if($option){
			if(!empty($exp) && !empty($path)){
				$tmp = ", { {$exp}, {$path} }";
			}else if(!empty($exp)){
				$tmp = ", { {$exp} }";
			}else if(!empty($path)){
				$tmp = ", { {$path} }";
			}
		}
		
		$name = str_replace(array('#','#'), array("'","'"), $name);
		$value = str_replace(array('#','#'), array("'","'"), $value);
		return "$.cookie({$name}, {$value} {$tmp});";
	}
	
	
	public function js_getCookie($name)
	{
		return "$.cookie('{$name}');";
	}
	
	public function js_delCookie($name)
	{
		return "$.removeCookie('{$name}');";
	}
}
