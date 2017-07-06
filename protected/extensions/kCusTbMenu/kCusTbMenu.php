<?php
Yii::import('bootstrap.widgets.TbMenu');

class kCusTbMenu extends TbMenu  // v1.0
{
    public $frameHeight = 500;
    public $colorMenuAct = '#FFFFFF';
    public $colorMenu = '#F8F8F8';

    public $_assetsUrl;

	public function init()
	{
//        Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/js/script.js');
//        Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrl().'/css/style.css');
        $this->_registerScript();
		return parent::init();
	}

    private function getAssetsUrl()
    {
        if (isset($this->_assetsUrl))
            return $this->_assetsUrl;
        else
        {
            $assetsPath = Yii::getPathOfAlias('ext.kCusTbMenu.assets');
            $assetsUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, YII_DEBUG);
            return $this->_assetsUrl = $assetsUrl;
        }
    }

	public function run()
	{

		parent::run();
        echo CHtml::openTag('div',array('id'=>'showFrameMenu_'.$this->id,'style'=>'height:'.$this->frameHeight.'px')).CHtml::closeTag('div');
	}

    private function _registerScript()
    {
        $script = "
                    $(function(){
                        $('#{$this->id} a').click(function(){
                            actMenuFrameTab($(this).attr('id'));
                            showIframeMenuTab($(this).attr('id-frame'));
                            return false;
                        });

                        $.each($('#{$this->id}').find('a'), function() {
                            var uID = uniqId();
                            $(this).attr({'id-frame':uID});
                            var divLoad = '<div id=\"load_{idFrame}\" class=\"iframeLoading loading\" style=\"display: none;\"></div>';

                            var frame = '<iframe src=\"{src}\" frameborder=\"0\" id=\"{idFrame}\" style=\"overflow:hidden;height:100%;width:100%;display:none;\" statusLoaging=\"wait\" width=\"100%\" height=\"100%\"></iframe>';

                            frame = frame.replace(\"{src}\", $(this).attr('href'));
                            frame = frame.replace(\"{idFrame}\", uID);
                            divLoad = divLoad.replace(\"{idFrame}\", uID);
                            $('#showFrameMenu_{$this->id}').append(divLoad);
                            $('#showFrameMenu_{$this->id}').append(frame);

                            $('#'+uID).on('load', function() {
                                $(this).attr({'statusLoaging':'done'})
                                $('#load_'+$(this).attr('id')).remove();
                            });
                        });

                        loadIframeMenuFirstTab();
                    });

                    function loadIframeMenuFirstTab(){
                        var li = $('#{$this->id}').find('.active');
                        if(li.hasClass('active')){
                           var frameID = li.find('a').attr('id-frame');
                            showIframeMenuTab(frameID);
                        }else{
                            var id = $('#{$this->id}').find('li').eq(0).find('a').attr('id');
                            actMenuFrameTab(id);
                        }
                    }

                    function showIframeMenuTab(id){
                        $('#showFrameMenu_{$this->id}').find('iframe').hide();
                        $('#'+id).show();

                        if($('#'+id).attr('statusLoaging') == 'wait'){
                            $('#load_'+id).show();
                        }else{
                            $('.iframeLoading').hide();
                        }
                    }

                    function actMenuFrameTab(id){
                        if(id != ''){
                            $('#{$this->id}').find('.active').removeClass('active');
                            $('#'+id).parent('li').addClass('active');
                            showIframeMenuTab($('#'+id).attr('id-frame'));
                        }
                    }

                    function reloadFrameTab(id){
                        var frame = $('#'+id).attr('id-frame');
                        $('#'+frame).attr( 'src', function ( i, val ) { return val; });
                    }
        ";
        Yii::app()->clientScript->registerScript(__class__.'#'.$this->id,$script,CClientScript::POS_HEAD);

        $css = "
                #{$this->id}>.active>a {
                    background-color: {$this->colorMenuAct};
                }
                #{$this->id}>li>a {
                    background-color: {$this->colorMenu};
                }
        ";
        Yii::app()->clientScript->registerCss(__class__.'#'.$this->id,$css);
    }
}