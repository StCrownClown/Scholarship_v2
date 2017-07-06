<?php
/*
 * Class kAttachmentNew
 * Version 3.2.6
 * Modify -> bootstrap.widgets.TbBox -> change line 136 (h3 -> h4) and bootstrap-box.css > line 79 (h3 -> h4)
 *
 * Requirement
 *
 *
 * Ex:
$this->widget('ext.kAttachmentNew.kAttachmentNew', array(
    'box'=>true,
    'boxOptions'=>array('title'=>'title','headerIcon'=>'icon','htmlOptions'=>array()),
    'btnLabel'=>'upload',
    'btnUploadOption'=>array('dialogID'=>'asf','dialogTitle'=>'title'),
    'showDetail'=>true,
    'model'=>'KR_DOC_ATTRACH',
    'modelFileName'=>'FILE_NAME',
    'modelTypeName'=>'FILE_DESC',
    'modelFileIDSend'=>'ID_ATTACHMENT',
    'data'=>array(
        array('name'=>'aaa','loadUrl'=>'uu','desc'=>'asf','idDel'=>'asfasf'),
        array('name'=>'bbb','loadUrl'=>'uu','desc'=>'asf','idDel'=>'asfasf'),
    ),
    'typeData'=>array('1'=>'aaa','2'=>'bbb'),
    'urlImageView'=>Yii::app()->createUrl('attament/imageView',array('code'=>'')),
    'aJaxUploadUrl'=>Yii::app()->createUrl('attament/upload',array('desc'=>'')),
    'aJaxUploadDeleteUrl'=>Yii::app()->createUrl('attament/del'),
    'help'=>array('label'=>'?','type'=>'info','size'=>'small','htmlOptions'=>array('class'=>'helpTip','head-title'=>'title','title'=>'text' )),
));

 *
 */

class kAttachmentNew extends CWidget
{
	public $id;
    public $box = false;
    public $boxOptions = array();

    public $showBtnUpload = true;
    public $btnUploadOption = array();
    public $btnLabel = 'Upload';

    public $data = array();

    public $showDetail = false;
    public $showDelete = true;

    public $model;
    public $modelFileName;
    public $modelTypeName;
    public $modelFileIDSend;
    public $fileType = '';  // image/*,application/pdf
    public $fileMaxSize = '10m';

    public $typeData = array();
    public $typeOther = false;

    public $aJaxUploadUrl;
    public $aJaxUploadDeleteUrl;

    public $urlImageView='';

    public $help = array(); // TbButton

    public $hits = '';

    public $required = false;

    private $_assetsUrl;
    private $script = '';

    public function init()
    {
        Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/js/ajaxfileupload.js');
        Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/js/file-validator.js');

        Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrl().'/css/style.css');
        Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrl().'/css/file-validator.css');

        $this->id = (empty($this->id))?$this->getId():$this->id;

        isset($this->btnUploadOption['dialogID'])?$this->btnUploadOption['dialogID']:'dialogAtta';
        isset($this->btnUploadOption['dialogTitle'])?$this->btnUploadOption['dialogTitle']:'Attachment';

        if($this->typeOther === true){
            $this->typeData['other'] = Yii::t('web','other');
        }

        if(!empty($this->model)){
            eval("\$this->model = new \$this->model;");
        }

        if($this->showDetail === true){
            $this->script .= "
                    function descMore(that){
                        that.parents('.attaList').css({'height':'auto','overflow':'auto'});
                    }
                    $(function(){
                        overDesc_{$this->id}();
                    });

                    function overDesc_{$this->id}(){
                        $('#attachmentList_{$this->id} .attaList .desc').mouseenter(function() {
                            $(this).css({'height':'auto','overflow':'auto','min-height':'20px'});
                        }).mouseleave(function() {
                            $(this).css({'height':'20px','overflow':'hidden'});
                        });
                    }";

//            $this->_registerCss();
        }

        if($this->box === true){
            $this->boxOptions['title'] = (isset($this->boxOptions['title']))?$this->boxOptions['title']:'title';
            $this->boxOptions['headerIcon'] = (isset($this->boxOptions['headerIcon']))?$this->boxOptions['headerIcon']:'';

            if(isset($this->boxOptions['htmlOptions']['style'])){
                $this->boxOptions['htmlOptions']['style'] .= 'width:800px;margin-left:100px';
            }else{
                $this->boxOptions['htmlOptions']['style'] = 'width:800px;margin-left:100px';
            }
        }

        $this->boxOptions['title'] = ($this->required === false)?$this->boxOptions['title']:$this->boxOptions['title'].' <span style="color:red;">*</span>';
        return parent::init();
    }
	
	private function getAssetsUrl()
	{
		if (isset($this->_assetsUrl))
			return $this->_assetsUrl;
		else
		{
			$assetsPath = Yii::getPathOfAlias('ext.'.__CLASS__.'.assets');
			$assetsUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, YII_DEBUG);
			return $this->_assetsUrl = $assetsUrl;
		}
	}
	
	public function run()
	{
        $buttons = array();
        if($this->box === false){
            if($this->showBtnUpload === true){
                $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'button','type'=>'primary', 'size'=>'mini', 'label'=>$this->btnLabel, 'htmlOptions'=>array('onclick'=>"showDialogAtta_{$this->id}();")));
                $this->_registerDialog();
                $this->_registerScriptDialog();
            }
            echo CHtml::openTag('div',array('class'=>'clear')).CHtml::closeTag('div');
        }

        $tmp = CHtml::openTag('div',array('id'=>'attachmentList_'.$this->id,'style'=>'width:100%;'));
        if(is_array($this->data)){
            foreach($this->data as $values){
                $tmp .= CHtml::openTag('div',array('class'=>'attaList'));

                if($this->showDelete === true){
                    $tmp .= "<div class='del' onclick=\"deleteAtta_{$this->id}($(this));\" data-id='".$values['idDel']."'><i class='icon-remove'></i></div>";
                }

                if ($this->hasModel()) {
                    $typeFile = ConfigWeb::getTypeFile($values['name']);
                    $typeFile = ConfigWeb::getTypeIcon($typeFile);
                    if($typeFile == 'image'){
                        $tmp .= CHtml::openTag('div',array('class'=>'fileIcon'));
                        $tmp .= CHtml::link(CHtml::image($this->urlImageView.$values['idDel']),$values['loadUrl'],array('target'=>'_blank'));
                        $tmp .= CHtml::closeTag('div');
                    }else{
                        $tmp .= CHtml::openTag('div',array('class'=>'fileIcon'));
                        $tmp .= CHtml::link('<div class="iconSize '.$typeFile.'"></div>',$values['loadUrl'],array('target'=>'_blank'));
                        $tmp .= CHtml::closeTag('div');
                    }
                }
                $tmp .= CHtml::openTag('div',array('class'=>'fileName'));
                $tmp .= CHtml::link($values['name'],$values['loadUrl'],array('target'=>'_blank'));
                $tmp .= CHtml::closeTag('div');

                $tmp .= CHtml::openTag('div',array('class'=>'clear')).CHtml::closeTag('div');
                $tmp .= ($this->showDetail === true)?CHtml::openTag('div',array('class'=>'desc')).$values['desc'].CHtml::closeTag('div'):'';

                if(isset($values['hidden'])){
                    if ($this->hasModel()) {
                        $fileIDName = get_class($this->model).'['.$this->modelFileIDSend.']';
                    }else{
                        $fileIDName = $this->modelFileIDSend;
                    }
                    $tmp .= CHtml::hiddenField($fileIDName.'[]',$values['hidden']);
                }

//                $tmp .= CHtml::closeTag('div');
                $tmp .= CHtml::closeTag('div');
            }
            $tmp .= CHtml::openTag('div',array('class'=>'clear')).CHtml::closeTag('div');
        }
        $tmp .= $this->hits;
        $tmp .= CHtml::closeTag('div');
//        $tmp .= CHtml::closeTag('div');

        if($this->box === true){
            if($this->showBtnUpload === true){
                $buttons = array(
                    'class' => 'bootstrap.widgets.TbButtonGroup',
                    'size'=>'mini',
                    'buttons' => array(
                        array('buttonType'=>'button','type'=>'primary', 'label'=>$this->btnLabel, 'htmlOptions'=>array('onclick'=>"showDialogAtta_{$this->id}();"))
                    ),
                );

                if(is_array($this->help) && count($this->help) > 0){
                    $buttons['buttons'][] = $this->help;
                }

                $this->_registerDialog();
                $this->_registerScriptDialog();
            }else{
                if(is_array($this->help) && count($this->help) > 0){
                    $buttons = $this->help;
                }

            }
            $this->widget(
                'bootstrap.widgets.TbBox',
                array(
                    'title' => $this->boxOptions['title'],
                    'headerIcon' => $this->boxOptions['headerIcon'],
                    'id'=>$this->id,
                    'htmlOptions'=>$this->boxOptions['htmlOptions'],
                    'headerButtons' => array(
                        $buttons
                    ),
                    'content'=>$tmp,
                )
            );
        }


        if($this->box === false){
            echo $tmp;
        }

        $this->_registerScript();
		parent::run();
	}

    private function _registerDialog()
    {
        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
            'id'=>$this->btnUploadOption['dialogID'],
            'options'=>array(
                'title'=>$this->btnUploadOption['dialogTitle'],
                'open'=>'js:function( event, ui ) {$(".overlayA").show();}',
                'close'=>'js:function( event, ui ) {$(".overlayA").hide();}',
                'minWidth'=>'650',
                'autoOpen'=>false,
                'buttons' => array(
                    array('text'=>'Save','class'=>'btn btn-success','id'=>'btnAttachmentSave','click'=> 'js:function(){saveAtta_'.$this->id.'();}'),
                    array('text'=>'Close','class'=>'btn btn-danger','id'=>'btnAttachmentClose','click'=> 'js:function(){closeAtta_'.$this->id.'();}'),
                ),
            ),
        ));

        echo CHtml::openTag('div',array('class'=>'form-horizontal'));

        // File
        echo CHtml::openTag('div',array('class'=>'control-group'));
        if ($this->hasModel()) {
            echo CHtml::activeLabel($this->model,$this->modelFileName,array('class'=>'control-label'));
        }else{
            echo CHtml::label(Yii::t('web','file'),'',array('class'=>'control-label'));
        }

        echo CHtml::openTag('div',array('class'=>'controls'));
        $reqFile = array();
        $reqFile['class'] = 'required';
        if($this->fileType != ''){
            $reqFile['accept'] = $this->fileType;
        }
        if($this->fileMaxSize != ''){
            $reqFile['data-max-size'] = $this->fileMaxSize;
        }
        if ($this->hasModel()) {
//            echo "<input class='required' accept='{$this->fileType}' data-max-size='{$this->fileMaxSize}' name='{$this->model}[{$this->modelFileName}] id='{$this->model}_{$this->modelFileName}'' type='file'>";
            echo CHtml::activeFileField($this->model,$this->modelFileName,$reqFile);
        }else{
            $this->modelFileName = 'fileName_'.$this->id;
            echo CHtml::fileField($this->modelFileName,'',$reqFile);
        }
        echo CHtml::closeTag('div');
        echo CHtml::closeTag('div');

        // ประเภท
        echo CHtml::openTag('div',array('class'=>'control-group'));
        if ($this->hasModel()) {
            echo CHtml::activeLabel($this->model,$this->modelTypeName,array('class'=>'control-label'));
        }else{
            echo CHtml::label(Yii::t('web','type'),'',array('class'=>'control-label'));
        }

        echo CHtml::openTag('div',array('class'=>'controls'));
        if ($this->hasModel()) {
            echo CHtml::activeDropDownList($this->model,$this->modelTypeName,$this->typeData,array('class'=>'required'));
        }else{
            $this->modelTypeName = 'dropType_'.$this->id;
            echo CHtml::dropDownList($this->modelTypeName,$this->modelTypeName,$this->typeData,array('class'=>'required'));
        }
        echo CHtml::textField('typeOther_'.$this->id,'',array('style'=>'display:none;float:right;'));

        echo CHtml::closeTag('div');
        echo CHtml::closeTag('div');

        echo CHtml::closeTag('div');

        $this->endWidget('zii.widgets.jui.CJuiDialog');

        if (!$this->hasModel()) {
            $this->modelFileIDSend = 'fileID_'.$this->id;
        }
    }

    private function _registerScriptDialog()
    {
        if ($this->hasModel()) {
            $fileName = get_class($this->model).'_'.$this->modelFileName;
            $typeName = get_class($this->model).'_'.$this->modelTypeName;
            $fileID = get_class($this->model).'_'.$this->modelFileIDSend;
            $fileIDName = get_class($this->model).'['.$this->modelFileIDSend.']';
        }else{
            $fileName = $this->modelFileName;
            $typeName = $this->modelTypeName;
            $fileID = $this->modelFileIDSend;
            $fileIDName = $this->modelFileIDSend;
        }

        if(!empty($this->fileType)){
            $pos = strpos($this->fileType, ',');
            if ($pos === false) {
                $tmpType = array($this->fileType);
            }else{
                $tmpType = explode(",",$this->fileType);
            }
            $tmpArr = array();
            foreach($tmpType as $value){
                if($value == 'image/*'){
                    $tmpArr[] = 'image/jpeg';
                    $tmpArr[] = 'image/png';
                    $tmpArr[] = 'image/gif';
                }else{
                    $tmpArr[] = $value;
                }
            }

            $this->script .= "
                $(function(){
                    $('#{$fileName}').fileValidator({
                        onValidation: function(files){ $(this).attr('class','');},
                        onInvalid:    function(type, file){ $(this).val(null);$(this).addClass('invalid '+type); },
                        type: function(type){
                                var typeCheck = ".CJavaScript::encode($tmpArr).";
                                if(jQuery.inArray(type, typeCheck ) == -1){
                                    return false;
                                }else{
                                    return true;
                                }
                              }
                    });
                });
                ";
        }
        $this->script .= "
                $(function(){
                    $('#{$typeName}').change(function(){
                        if($(this).val() == 'other'){
                           $('#typeOther_$this->id').show();
                        }else{
                            $('#typeOther_$this->id').hide();
                        }
                    });

                });

                function showDialogAtta_{$this->id}(){
                    $('#{$this->btnUploadOption['dialogID']}').dialog('open');
                    $('#{$this->btnUploadOption['dialogID']}').find('.error').removeClass('error');

                    $('#{$typeName}').change();
                }

				function closeAtta_{$this->id}(){
					$('#{$this->btnUploadOption['dialogID']}').dialog('close');
					$('#{$fileName}').val('');

					$('#{$typeName} option:first-child').attr('selected','selected');
					$('#typeOther_$this->id').val('');
					$('#typeOther_$this->id').hide();
				}

				function saveAtta_{$this->id}(){
				    if(checkRequiredData_{$this->id}() === false){
				        saveAs_{$this->id}();
				    }else{
				        bootbox.alert('".Yii::t('web','Please select file.')."');
				    }

				}

				function checkRequiredData_{$this->id}(){
				    $('#{$this->btnUploadOption['dialogID']}').find('.error').removeClass('error');
				    var err = false;
                    $.each($('#{$this->btnUploadOption['dialogID']}').find('.required'), function() {
                        if($(this).val() == ''){
                            err = true;
                            $(this).addClass('error');
                            $(this).parent('.controls').prev().addClass('error');
                        }
                    });
                    return err;
				}

				function saveAs_{$this->id}(){
				    if($('#{$fileName}').val() != ''){
				        var typeVal = $('#{$typeName}').val();
				        if(typeVal == 'other'){
				            typeVal = $('#typeOther_$this->id').val();
				        }

					    var url = '{$this->aJaxUploadUrl}'+typeVal;
					    $('#{$this->btnUploadOption['dialogID']}').dialog('close');
					    $('.overlayA').show();
					    $('.loadajax').show();
						$.ajaxFileUpload
						(
							{
								url: url,
								secureuri:false,
								fileElementId:'{$fileName}',
								dataType: 'json',
								success: function (data, status)
								{
								    $('.loadajax').hide();
									$('.overlayA').hide();
								    if(data.err === true){
											bootbox.alert(data.msg);
									}else{
                                        var atta = createList_{$this->id}(data.code,data.filename,data.url,data.desc,data.typeFile);
									    $('#attachmentList_{$this->id}').prepend(atta);
									    closeAtta_{$this->id}();
									    overDesc_{$this->id}();
								    }

								},
								error: function (data, status, e)
								{
									alert(e);
								}
							}
						);
					}else{
						bootbox.alert('".Yii::t('web','Please select file.')."');
					}
				}

                function createList_{$this->id}(code,filename,url,desc,typeFile){
                    var atta = '<div class=\"attaList\">';
                     atta += '<div class=\"del\" onclick=\"deleteAtta_{$this->id}($(this));\" data-id=\"'+code+'\"><i class=\"icon-remove\"></i></div>';
                    if(typeFile == 'image'){
                        atta += '<div class=\"fileIcon\"><a href='+url+' target=\"_blank\"><img src=\"{$this->urlImageView}'+code+'\" ></a></div>';
                    }else{
                        atta += '<div class=\"fileIcon\"><a href='+url+' target=\"_blank\"><div class=\"iconSize '+typeFile+'\" ></div></a>';
                    }
                    atta += '<div class=\"clear\"></div>';
                    atta += '<div class=\"fileName\"><a href='+url+' target=\"_blank\">'+filename+'</a></div>';
                    atta += '<div class=\"clear\"></div>';
                    atta += '<div class=\"desc\" style=\"height: 20px; overflow: hidden;\">'+desc+'</div>';
                    atta += '<div class=\"clear\"></div>';
                    atta += '<input type=\"hidden\" name=\"{$fileIDName}[]\" value=\"'+code+'\">';
                    atta += '</div>';
                    return atta;
                }


				";

    }

    /**
     * @return boolean whether this widget is associated with a data model.
     */
    protected function hasModel()
    {
        return (get_class($this->model) === false)?false:true;
    }

    private function _registerScript()
    {
        if($this->showDelete === true){
            $this->script .= "
                    function deleteAtta_{$this->id}(that){
                        bootbox.confirm('".Yii::t('web','Please confirm Delete.')."',function(confirmed){
                            if(confirmed){
                                var id = that.attr('data-id');
                                $('.overlayA').show();
                                $('.loadajax').show();
                                $.ajax({
                                    type: 'POST',
                                    url: '".$this->aJaxUploadDeleteUrl."',
                                    data: {'code':id},
                                    //dataType: 'json',
                                    success: function(data){
                                        $('.overlayA').hide();
                                        $('.loadajax').hide();
                                        that.parents('.attaList').remove();
                                    },
                                    error: function(){
                                        bootbox.alert('".Yii::t('web','report Admin.')."');
                                        window.location.reload();
                                    }
                                });

                            }
                        });
                    }
                    ";
        }
        Yii::app()->clientScript->registerScript(__class__.'#'.$this->id,$this->script,CClientScript::POS_HEAD);
    }

//    private function _registerCss()
//    {
//        $css = "
//                .attaList{
//                    margin:2px 0px 0px 5px;
//                    padding: 2px 2px;
//                    width:180px;
//                    height:20px;
//                    border: 1px solid #E6E6E6;
//                    float:left;
//                    overflow:hidden;
//                }
//                .attaList .fileName{
//                    float:left;
//                }
//                .attaList .del{
//                    float:right;
//                    cursor:pointer;
//                    color:#FF0000;
//                }
//                .attaList .more{
//                    float:right;
//                    cursor:pointer;
//                    color:#0000FF;
//                    margin-top:-5px;
//                }";
//        Yii::app()->clientScript->registerCss(__class__.'#'.$this->id,$css);
//    }
}