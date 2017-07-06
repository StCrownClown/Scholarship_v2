<?php
class kNavDetail extends CWidget  // v1.4.1
{
    public $detail;
    public $showTop = false;
    public $showDetail = false;
    public $tagHead = 'h2';

    public $htmlOptions = array();

    public $_assetsUrl;

    public function init()
    {
        Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/js/kNavDetail.js');
        Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrl().'/css/kNavDetail.css');

        return parent::init();
    }

    private function getAssetsUrl()
    {
        if (isset($this->_assetsUrl))
            return $this->_assetsUrl;
        else
        {
            $assetsPath = Yii::getPathOfAlias('ext.kNavDetail.assets');
            $assetsUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, YII_DEBUG);
            return $this->_assetsUrl = $assetsUrl;
        }
    }

    public function run()
    {
        $detail = "<div id='kNavDetail'>";
        foreach ($this->detail as $items){
            $detail .= "<div id='{$items['id']}' ";
            $detail .= ($this->showDetail === false)?"style='display:none;'>":'>';
            $detail .= "<div><{$this->tagHead} ";
            $detail .= ($this->showTop === true)?"class='fLeft'":'';
            $detail .= ">{$items['name']}</{$this->tagHead}>";
            if($this->showTop === true){
                $detail .= "<div class='headGoToTop'>".CHtml::link("<i class='icon-circle-arrow-up'></i>Top",'#',array('onclick'=>'gotoTop();'))."</div>";
                $detail .= "<div class='clear'></div>";
            }
            $detail .= "</div>";
            $detail .= "<div class='kNavDetail-detail' ";
            if($items['type'] == 'data'){
                $detail .= '>';
                $detail .= (isset($items['data']))?$items['data']:'';
            }else if($items['type'] == 'ajax'){
                $detail .= " data-type='ajax' data-url='{$items['url']}' ";

                if(isset($items['cusHeight'])){
                    $detail .= " data-height='{$items['cusHeight']}' ";
                }

                $detail .= '>';
            }else{
                $detail .= '>';
            }

            $detail .= "</div>";

            $detail .= '</div>';
        }
        $detail .= "</div>";
        echo $detail;
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

}