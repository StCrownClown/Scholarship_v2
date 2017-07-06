<?php
Yii::import('bootstrap.widgets.TbMenu');

/***
 * Class kFilterMenu
 * v 2.0.1
 *
 *
 *
 *
$this->createWidget('ext.kFilterMenu.kFilterMenu',array(
    'type'=>'list',
    'id'=>'listItemFilterLeft',
    'items'=>KR_FILTERS::loadFilter('ipmDoing'),
    'showSearch'=>'#showAvSearch',
    'btnSubmitSearch'=>'#submitAvSearch',
    'btnResetSearch'=>'#resetAvSearch',
    'filterType'=>'ipmDoing',
    'filterUrlAdd'=>Yii::app()->createUrl('filter/add'),
    'filterUrlLoad'=>Yii::app()->createUrl('filter/load'),
    'filterUrlDelete'=>Yii::app()->createUrl('filter/delete'),
));
 */
class kFilterMenu extends TbMenu
{
    public $id;
    public $showSearch;
    public $btnSubmitSearch;
    public $btnResetSearch;
    public $filterType;
    public $filterUrlAdd;
    public $filterUrlLoad;
    public $filterUrlDelete;

	public function init()
	{
        $this->id = (empty($this->id))?$this->getId():$this->id;

        $this->_registerScript();
		return parent::init();
	}

	public function run()
	{
        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
            'id'=>'showDialogFrameFilter',
            'options'=>array(
                'title'=>'Filter',
                'close'=>'js:function( event, ui ) {$(".overlayA").hide();}',
                'minWidth'=>'420',
                'height'=>'170',
                'autoOpen'=>false,
                'buttons' => array(
                    array('text'=>'Save','class'=>'btn btn-success','click'=> 'js:function(){sendFilterMenu();}'),
                    array('text'=>'Close','class'=>'btn btn-danger','click'=> 'js:function(){$(this).dialog("close");}'),
                ),
            ),
        ));
        echo CHtml::openTag('div',array('class'=>'form-horizontal'));
        echo CHtml::openTag('div',array('class'=>'control-group'));
        echo CHtml::label('Filter Name','',array('class'=>'control-label'));
        echo CHtml::openTag('div',array('class'=>'controls'));
        echo CHtml::textField('filterName','');
        echo CHtml::closeTag('div');
        echo CHtml::closeTag('div');

        echo CHtml::closeTag('div');

        $this->endWidget('zii.widgets.jui.CJuiDialog');

		parent::run();
	}

    private function _registerScript()
    {
        $script = "
                    var tmpData = '';
                    $(function(){
                        checkFilter();
                        $('#{$this->id}').find('.active').find('a div').click();
                    });

                    function addFilterMenu(data){
                        tmpData = data;
                        $('.overlayA').show();
                        $('#showDialogFrameFilter').dialog('open');
                    }

                    function sendFilterMenu(){
                        if($('#filterName').val() == ''){
                            bootbox.alert('กรุณาใส่ Filter Name');
                            return false;
                        }

                        $('#showDialogFrameFilter').dialog('close');
                        var name = $('#filterName').val();
                        var data = tmpData;
                        $('.loadajax').show();
                        $('.overlayA').show();
                        $.ajax({
                            type: 'POST',
                            url: '{$this->filterUrlAdd}',
                            data: {'name': name,'data': data,'type':'{$this->filterType}'},
                            dataType: 'json',
                            success: function(msg){
                                $('.loadajax').hide();
                                $('.overlayA').hide();
                                if(msg.err == 0){
                                    addFilter(msg.code,msg.text);
                                }else{
                                    alert('ระบบไม่สามารถทำงานได้ กรุณาลองใหม่อีกครั้ง หรือติดต่อผู้ดูแลระบบ');
                                }
                            },
                            error: function (xhr, text_status, error_thrown) {
                                        if (text_status != 'abort') {
                                            alert('ระบบไม่สามารถทำงานได้ กรุณาลองใหม่อีกครั้ง หรือติดต่อผู้ดูแลระบบ');
                                            window.location.reload();
                                        }
                                    }
                        });
                    }

                    function addFilter(code,text){
                        var li = \"<li><a href='#' filter='{code}'>{text}</a></li>\";
                        li = li.replace(\"{code}\", code);
                        li = li.replace(\"{text}\", text);
                        $('#{$this->id}').append(li);
                        checkFilter();
                    }

                    function checkFilter(){
                        $.each($('#{$this->id}').find('a'), function() {
                            var str = $(this).attr('filter');
                            if(str != ''){
                                $(this).find('i').remove();
                                var text = $(this).text();
                                text = '<div style=\"display: inline-block;width:140px;\">'+ text + '</div>' + '<i class=\"icon-remove ckFilterRemove\" style=\"float:right;margin-top:2px;\"></i>';
                                $(this).html(text);
                            }
                        });

                        $('#{$this->id} a div').click(function(){ // load
                            $('#{$this->id}').find('li').removeClass('active');
                            var input = $(this).parent('a');
                            input.parent('li').addClass('active');

                            var id = input.attr('filter');
                            var text = input.text();
                            $(\".overlayA\").show();
                            $(\".loadajax\").show();
                            $.ajax({
                                type: 'POST',
                                url: '{$this->filterUrlLoad}',
                                data: {'code':id},
                                dataType: 'json',
                                success: function(msg){
                                    if(msg.err == 0){
                                        $(\".overlayA\").hide();
                                        $(\".loadajax\").hide();
                                        $('{$this->showSearch}').show();
                                        $('{$this->btnResetSearch}').click();

                                        var json = $.parseJSON(msg.data);
                                        $.each(json, function(key,value){
                                            var substr = value.split(' : ');
                                            if(substr[0].indexOf(\"#\") >= 0){
                                                var co = substr[0].split('#');
                                                if(co[1] == 'select2'){
                                                    if(substr[1].indexOf(\",\") >= 0){
                                                        var dataSelect2 = substr[1].split(',');
                                                        $('#'+co[0]).select2('val',dataSelect2);
                                                    }else{
                                                        $('#'+co[0]).select2('val',substr[1]);
                                                    }
                                                }
                                                else if(co[1] == 'select'){
                                                    $('#'+co[0]).val(substr[1]);
                                                }
                                                else if(co[1] == 'radio'){
                                                    $('input:radio[name='+co[0]+']').filter('[value='+substr[1]+']').prop('checked', true);
                                                }
                                                else if(co[1] == 'lookup'){
                                                    var look = substr[1].split('#');
                                                    $('#'+co[0]).val(look[0]);
                                                    $('#'+look[1]).text(look[2]);
                                                }
                                            }else{
                                                $('#'+substr[0]).val(substr[1]);
                                            }


                                        });
                                        $('{$this->btnSubmitSearch}').click();
                                    }else{
                                        alert('ระบบไม่สามารถทำงานได้ กรุณาลองใหม่อีกครั้ง หรือติดต่อผู้ดูแลระบบ');
                                        window.location.reload();
                                    }

                                },

                                error: function (xhr, text_status, error_thrown) {
                                    if (text_status != 'abort') {
                                        alert('ระบบไม่สามารถทำงานได้ กรุณาลองใหม่อีกครั้ง หรือติดต่อผู้ดูแลระบบ');
                                        window.location.reload();
                                    }
                                }
                            });
                        });

                        $('.ckFilterRemove').click(function(){ // delete
                            var id = $(this).parent('a').attr('filter');
                            var text = $(this).parent('a').text();
                            var input = $(this).parent('a').parent('li');
                            bootbox.confirm(\"คุณต้องการลบ \"+text+\" หรือไม่\",
                                function(con){
                                    if(con){
                                        $(\".overlayA\").show();
                                        $(\".loadajax\").show();
                                        $.ajax({
                                            type: 'POST',
                                            url: '{$this->filterUrlDelete}',
                                            data: {'code':id},
                                            dataType: 'json',
                                            success: function(data){
                                                $(\".overlayA\").hide();
                                                $(\".loadajax\").hide();
                                                if(data.err == 0){
                                                    input.remove();
                                                }else{
                                                    bootbox.alert('ไม่สามารถลบข้อมูลได้');
                                                }
                                            },

                                            error: function (xhr, text_status, error_thrown) {
                                                if (text_status != 'abort') {
                                                    alert('ระบบไม่สามารถทำงานได้ กรุณาลองใหม่อีกครั้ง หรือติดต่อผู้ดูแลระบบ');
                                                    window.location.reload();
                                                }
                                            }
                                        });
                                    }
                                });
                        });
                    }
        ";
        Yii::app()->clientScript->registerScript(__class__.'#'.$this->id,$script,CClientScript::POS_HEAD);

        $css = "
                .ckFilterNew{cursor: pointer;}
                .ckFilterColumns, .popoverFilter .popover-title{cursor: pointer;}
                #filterShow label{display: inline !important;}
        ";
        Yii::app()->clientScript->registerCss(__class__.'#'.$this->id,$css);
    }
}