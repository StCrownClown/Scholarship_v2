<?php
Yii::import('bootstrap.widgets.TbMenu');
/****
 * Class kMenuList
 *  v1.5
 * Ex:
$this->createWidget('ext.kMenuList.kMenuList',array(
    'type'=>'list',
    'id'=>'listItemLeft',
    'appendTo'=>'contentRight',
    'mode'=>'frame',
    'urlHome'=>Yii::app()->createUrl($this->id.'/'.$this->action->id),
    'actMenu'=>Yii::app()->input->get('menu'),
    'items'=>array(
        array('label'=>'Menu1', 'url'=>Yii::app()->createUrl('site/menu1'), 'linkOptions'=>array('badge'=>0,'id'=>'menu1')),
        array('label'=>'Menu2', 'url'=>Yii::app()->createUrl('site/menu1'), 'linkOptions'=>array('badge'=>0,'id'=>'menu2')),
    ),
));
 *
 */
class kMenuList extends TbMenu
{
    public $mode = 'link'; // link , frame

    /* ใช้เฉพาะ mode => frame  */
    public $frameAutoLoad = false;
    public $appendTo; // id ที่จะนำข้อมูลไปใส
    public $urlHome; // get -> menu
    public $actMenu = '';
    public $sendLogUrl = '';

	public function init()
	{
        if($this->mode == 'frame'){
            $this->_registerScript();
        }else{
            if(!empty($this->actMenu)){
                $this->_registerScriptActMenu();
            }
        }

        if(!empty($this->sendLogUrl)){
            $this->_registerScriptLog();
        }

		return parent::init();
	}

	public function run()
	{
		parent::run();
	}

    private function _registerScriptActMenu()
    {
        $script="
            $(function(){
                $('#".$this->actMenu."').parent('li').addClass('active');
             });
             ";
        Yii::app()->clientScript->registerScript(__class__.'#'.$this->id,$script,CClientScript::POS_HEAD);
    }

    private function _registerScript()
    {
        $script = "
                    window.onpopstate = function(e) {
                        if(e.state){
                            actMenuFrame(e.state.menu);
                        }

                    };
                    $(function(){
                        $('#{$this->id} a').click(function(){
                            var id = $(this).attr('id');
                            actMenuFrame(id);
                            return false;
                        });

                    ";
        if(!empty($this->actMenu)){
            $script .= "actMenuFrame('{$this->actMenu}');";
        }
        if($this->frameAutoLoad === true){
            $script .= "
                        $.each($('#listItemLeft').find('a'), function() {
                            createFrame($(this).attr('id'));
                        });
                    ";
        }

        $script .= "
                        loadIframeMenuFirst();
                    });

                    function createFrame(id){
                        var uID = uniqId();
                        $('#'+id).attr({'id-frame':uID});
                         var divLoad = '<div id=\"load_{idFrame}\" class=\"iframeLoading loading\" style=\"display: none;\"></div>';

                        var frame = '<iframe src=\"{src}\" frameborder=\"0\" id=\"{idFrame}\" style=\"overflow:hidden;height:100%;width:100%;display:none;\" statusLoaging=\"wait\" width=\"100%\" height=\"100%\"></iframe>';

                        frame = frame.replace(\"{src}\", $('#'+id).attr('href'));
                        frame = frame.replace(\"{idFrame}\", uID);
                        divLoad = divLoad.replace(\"{idFrame}\", uID);
                        $('#{$this->appendTo}').append(divLoad);
                        $('#{$this->appendTo}').append(frame);

                        $('#'+uID).on('load', function() {
                            $(this).attr({'statusLoaging':'done'})
                            $('#load_'+$(this).attr('id')).remove();
                        });

                        return uID;
                    }

                    function loadIframeMenuFirst(){
                        var li = $('#{$this->id}').find('.active');
                        if(li.hasClass('active')){
                           var id = li.find('a').attr('id');
                            actMenuFrame(id);
                        }else{
                            var id = $('#{$this->id}').find('li').eq(0).find('a').attr('id');
                            actMenuFrame(id);
                        }
                    }

                    function showIframeMenu(id){
                        $('#contentRight').find('iframe').hide();
                        $('#'+id).show();

                        if($('#'+id).attr('statusLoaging') == 'wait'){
                            $('#load_'+id).show();
                        }else{
                            $('.iframeLoading').hide();
                        }
                    }

                    function actMenuFrame(id){
                        if(id != ''){
                            $('#{$this->id}').find('.active').removeClass('active');
                            $('#'+id).parent('li').addClass('active');
                            var frame = $('#'+id).attr('id-frame');
                            if(frame === undefined){
                                createFrame(id);
                                actMenuFrame(id);
                            }else{
                                window.history.pushState({'menu':id}, id, '{$this->urlHome}'+id);
                                showIframeMenu(frame);
                            }


                        }
                    }

                    function reloadFrame(id){
                        var frame = $('#'+id).attr('id-frame');
                        $('#'+frame).attr( 'src', function ( i, val ) { return val; });
                    }
        ";


        Yii::app()->clientScript->registerScript(__class__.'#'.$this->id,$script,CClientScript::POS_HEAD);
    }

    private function _registerScriptLog()
    {
        $script = "
                        jQuery(function(){
                             $('ul#{$this->id} li a').click(function(){
                                var text = $(this).text();
                                var ba = $(this).attr('badge');
                                if(ba != '0'){
                                    text = text.replace(ba, '');
                                }

                                $.ajax({
                                    type: 'POST',
                                    url: '".$this->sendLogUrl."',
                                    data: {name: text},
                                    success: function(msg){
                                        return true;
                                    }
                                });
                            });
                        });
                        ";
        Yii::app()->clientScript->registerScript(__class__.'#log_'.$this->id,$script,CClientScript::POS_HEAD);
    }
}