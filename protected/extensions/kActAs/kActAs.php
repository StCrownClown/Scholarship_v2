<?php
class kActAs extends CWidget  // v1.3
{
	public $roleAs = array();
	public $valueRoleAs;
	public $id;

	public $fnShowName = 'showActAs';

    public $iFrameUrl;

    public $reload = true;

	public function init()
	{
        $this->id = (empty($this->id))?$this->getId():$this->id;

        if($this->checkRole()){
            $this->_registerScript();
        }

		return parent::init();
	}

	public function run()
	{
        if($this->checkRole()){
            $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
                'id'=>$this->id,
                'options'=>array(
                    'title'=>'Act As',
                    'open'=>'js:function( event, ui ) {$(".overlayA").show();}',
                    'close'=>'js:function( event, ui ) {$(".overlayA").hide();}',
                    'minWidth'=>'800',
                    'minHeight'=>'600',
                    'autoOpen'=>false,
                    'buttons' => array(
                        array('text'=>'Close','click'=> 'js:function(){$(this).dialog("close");}','class'=>'btn btn-danger'),
                    ),
                ),
            ));
            echo CHtml::openTag('div',array('id'=>'actAsFrame','style'=>'height:600px;'));
            echo CHtml::closeTag('div');
            $this->endWidget('zii.widgets.jui.CJuiDialog');
        }


		parent::run();
	}

	public function checkRole()
    {
        $groupAdmin = LoginForm::getGroupAdmin();
        foreach($groupAdmin as $key => $value){
            $this->roleAs[] = $key;
        }

        if(in_array($this->valueRoleAs,$this->roleAs))
            return true;
        return false;
    }

    public function _registerScript()
    {
        if($this->reload === true){
            $goto = 'window.location.reload();';
        }else{
            $goto = "window.location = ".CJavaScript::encode($this->reload);
        }
        $script = "
                    function {$this->fnShowName}(){
                        $('#{$this->id}').dialog('open');

                        var iframe = ".CJavaScript::encode(CHtml::openTag('iframe',array('src'=>$this->iFrameUrl,'id'=>$this->id.'_iframe','frameborder'=>0,'style'=>'overflow:hidden;height:100%;width:100%','width'=>'100%','height'=>'100%')).CHtml::closeTag('iframe')).";
                        $('#actAsFrame').html(iframe);
                    }

                    function closeActAs(){
                        $('#{$this->id}').dialog('close');
                        {$goto}
                    }
                ";
        Yii::app()->clientScript->registerScript(__class__.'#'.$this->id,$script,CClientScript::POS_HEAD);
    }
}