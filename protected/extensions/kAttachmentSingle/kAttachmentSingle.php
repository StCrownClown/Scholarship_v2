<?php
/*
 * Class kAttachmentSingle
 * Version 1.3
 *
 *
 * Ex:
 $this->widget('ext.kAttachmentSingle.kAttachmentSingle', array(
        'model'=>'KR_DOC_ATTRACH',
        'modelFileName'=>'FILE_NAME',
        'modelFileIDSend'=>'ID_ATTACHMENT',
        'num'=>1, // max 4
        'fileType'=>'',
        'data'=>array(
            array('name'=>'aaa','loadUrl'=>'uu','desc'=>'asf','idDel'=>'asfasf'),
        ),
        'urlImageView'=>Yii::app()->createUrl('attament/imageView',array('code'=>'')),
        'aJaxUploadUrl'=>Yii::app()->createUrl('attament/upload',array('desc'=>'')),
        'aJaxUploadDeleteUrl'=>Yii::app()->createUrl('attament/del'),
    ));
 *
 */

class kAttachmentSingle extends CWidget
{
	public $id;

    public $data = array();

    public $showDelete = true;

    public $model;
    public $modelFileName;
//    public $modelTypeName;
    public $modelFileIDSend;

    public $num;

    public $aJaxUploadUrl;
    public $aJaxUploadDeleteUrl;

    public $urlImageView;

    public $overlay = true;

    public $fileType = ''; // image/*,application/pdf
    public $fileMaxSize = '10m';

    private $_assetsUrl;
    private $script = '';
    private $showDetail = false;

    public function init()
    {
        Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/js/ajaxfileupload.js');
        Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/js/file-validator.js');

        Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrl().'/css/style.css');
        Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrl().'/css/file-validator.css');

        $this->id = (empty($this->id))?$this->getId():$this->id;

        if(!empty($this->model)){
            eval("\$this->model = new \$this->model;");
        }
        $this->num = (empty($this->num))?1:$this->num;

        $this->modelFileName .= '_'.$this->num;

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
        $i = 0;
        $tmp = CHtml::openTag('div',array('id'=>'attachmentList_'.$this->id,'style'=>'width:100%;'));
        if(is_array($this->data)){
            if ($this->hasModel()) {
                $fileName = get_class($this->model).'['.$this->modelFileIDSend.']';
            }else{
                $fileName = 'fileID_'.$this->id;
            }

            foreach($this->data as $values){
                $i++;
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
                        $tmp .= CHtml::link('<div class="iconSize '.$typeFile.'">',$values['loadUrl'],array('target'=>'_blank'));
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

                $tmp .= CHtml::closeTag('div');
            }
            $tmp .= CHtml::openTag('div',array('class'=>'clear')).CHtml::closeTag('div');
        }
        $tmp .= CHtml::closeTag('div');

        if ($this->hasModel()) {
            echo CHtml::activeFileField($this->model,$this->modelFileName,array('style'=>($i!=0)?'display:none;':'','accept'=>$this->fileType,'data-max-size'=>$this->fileMaxSize));
        }else{
            $this->modelFileName = (!empty($this->modelFileName))?$this->modelFileName:'fileName_'.$this->id;
            echo CHtml::fileField($this->modelFileName,'',array('style'=>($i!=0)?'display:none;':'','accept'=>$this->fileType,'data-max-size'=>$this->fileMaxSize));
        }
        if (!$this->hasModel()) {
            $this->modelFileIDSend = (!empty($this->modelFileIDSend))?$this->modelFileIDSend:'fileID_'.$this->id;
        }

        echo $tmp;

        $this->_registerScript();
		parent::run();
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
        if ($this->hasModel()) {
            $fileName = get_class($this->model).'_'.$this->modelFileName;
            $fileID = get_class($this->model).'_'.$this->modelFileIDSend;
            $fileIDName = get_class($this->model).'['.$this->modelFileIDSend.']';
        }else{
            $fileName = $this->modelFileName;
            $fileID = $this->modelFileIDSend;
            $fileIDName = $this->modelFileIDSend;
        }
        $ovShow = ($this->overlay === true)?"$('.overlayA').show();":'';
        $ovHide = ($this->overlay === true)?"$('.overlayA').hide();":'';

        $loadScript = '';
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

            $loadScript .= "
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
        $loadScript .= "$(function(){
                    $('#{$fileName}').change(function(){
                        saveAs_{$this->id}();
                    });
                });";

        $this->script .= $loadScript;
        $this->script .= "
                function saveAs_{$this->id}(){
				    if($('#{$fileName}').val() != ''){
					    var url = '{$this->aJaxUploadUrl}';
					    url += '&num={$this->num}';
					    {$ovShow}
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
									{$ovHide}
								    if(data.err === true){
											bootbox.alert(data.msg);
									}else{
                                        var atta = createList_{$this->id}(data.code,data.filename,data.url,data.desc,data.typeFile);
									    $('#attachmentList_{$this->id}').prepend(atta);
									    $('#{$fileName}').hide();
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

        if($this->showDelete === true){
            $this->script .= "
                    function deleteAtta_{$this->id}(that){
                        bootbox.confirm('".Yii::t('web','Please confirm Delete.')."',function(confirmed){
                            if(confirmed){
                                var id = that.attr('data-id');
                                {$ovShow}
                                $('.loadajax').show();
                                $.ajax({
                                    type: 'POST',
                                    url: '".$this->aJaxUploadDeleteUrl."',
                                    data: {'code':id},
                                    dataType: 'json',
                                    success: function(data){
                                        {$ovHide}
                                        $('.loadajax').hide();
                                        that.parents('.attaList').remove();
                                        $('#{$fileName}').show();
                                        {$loadScript}
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
//                    width:230px;
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