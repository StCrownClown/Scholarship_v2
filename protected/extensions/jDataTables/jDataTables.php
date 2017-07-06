<?php
/*
 * DataTables-1.10.6
 * jDataTables v 1.4
 */
/*
$this->widget('ext.jDataTables.jDataTables',array(
    'table'=>$tableReport,
    'idTable'=>'example',
    'enExtension'=>array('TableTools'),
    'option'=>array(
        "scrollX" => "100%",
        'paging'=>false,
        'searching'=>false,
        'bInfo'=>false,
        "ordering" => false,
    ),
    'customsScript'=>"js:new $.fn.dataTable.FixedColumns( table, {leftColumns: 2   } );",
));
*/
class jDataTables extends CWidget
{
	public $_assetsUrl;
	public $_assetsUrlEx;

	public $enExtension;
	public $table;
    public $idTable;
	public $option;
	public $customsScript;

	public $style = 'bootstrapYii';

	public $toolSwf;

	private $script;

	public function init()
	{
        $this->table['class'] = '';
		Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/js/datatables.js');

		Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrl().'/css/dataTables.min.css');

        if($this->style == 'foundation'){
            Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/js/dataTables.foundation.min.js');
            Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrl().'/css/dataTables.foundation.min.css');
            Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrl().'/css/foundation.min.css');
        }else if($this->style == 'bootstrapYii'){
            $this->table['class'] = 'table table-bordered table-condensed';
            Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrl().'/css/boots.min.css');
        }

        if(is_array($this->enExtension)){
            foreach($this->enExtension as $value){
                if($value == 'FixedColumns'){
                    $value = 'FixedColumns-3.2.1';
                    Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrlEx().'/'.$value.'/js/dataTables.fixedColumns.min.js');

                    Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrlEx().'/'.$value.'/css/fixedColumns.dataTables.min.css');
                }
                else if($value == 'ColVis'){
                    Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrlEx().'/'.$value.'/js/dataTables.colVis.min.js');

                    Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrlEx().'/'.$value.'/css/dataTables.colVis.min.css');
                    Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrlEx().'/'.$value.'/css/dataTables.colvis.jqueryui.css');
                }
                else if($value == 'FixedHeader'){
                    $value = 'FixedHeader-3.1.1';
                    Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrlEx().'/'.$value.'/js/dataTables.fixedHeader.min.js');

                    Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrlEx().'/'.$value.'/css/fixedHeader.dataTables.min.css');
                }
                else if($value == 'Scroller'){
                    $value = 'Scroller-1.4.1';
                    Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrlEx().'/'.$value.'/js/dataTables.scroller.min.js');

                    Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrlEx().'/'.$value.'/css/scroller.dataTables.min.css');
                }
                else if($value == 'Buttons'){
                    $value = 'Buttons-1.1.2';
                    Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrlEx().'/'.$value.'/js/dataTables.buttons.min.js');
                    Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrlEx().'/'.$value.'/js/buttons.flash.min.js');
                    Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrlEx().'/'.$value.'/js/buttons.html5.js');
                    Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrlEx().'/'.$value.'/js/buttons.print.js');

                    $this->getJSzip();
                    $this->getPdfMake();

//                    Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrlEx().'/'.$value.'/css/dataTables.tableTools.min.css');

//                    $this->toolSwf = $this->getAssetsUrlEx().'/'.$value.'/swf/';
//
//                    $this->option['oTableTools']['sSwfPath'] = $this->toolSwf.'copy_csv_xls_pdf.swf';
                }

//                Buttons
            }
        }

        $this->idTable = ($this->idTable != '')?$this->idTable:$this->getId();
        $this->_registerScript();

        $this->table['idTable'] = $this->idTable;
		return parent::init();
	}

    public function getJSzip()
    {
        Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrlEx().'/JSZip-2.5.0/jszip.min.js');
    }

    public function getPdfMake()
    {
        Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrlEx().'/pdfmake-0.1.18/pdfmake.min.js');
        Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrlEx().'/pdfmake-0.1.18/vfs_fonts.js');
    }

	private function getAssetsUrl()
	{
		if (isset($this->_assetsUrl))
			return $this->_assetsUrl;
		else
		{
			$assetsPath = Yii::getPathOfAlias('ext.jDataTables.media');
			$assetsUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, YII_DEBUG);
			return $this->_assetsUrl = $assetsUrl;
		}
	}

    private function getAssetsUrlEx()
    {
        if (isset($this->_assetsUrlEx))
            return $this->_assetsUrlEx;
        else
        {
            $assetsPath = Yii::getPathOfAlias('ext.jDataTables.extensions');
            $assetsUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, YII_DEBUG);
            return $this->_assetsUrlEx = $assetsUrl;
        }
    }

    public function run()
    {
        echo CHtml::openTag('div',array('class'=>'grid-view'));
        echo ConfigWeb::createTableHtml($this->table);
        echo CHtml::closeTag('div');
    }

    private function _registerScript()
    {
        $this->customsScript = ($this->customsScript != '')?CJavaScript::encode($this->customsScript).';':'';
        $this->script = "
			$(function(){
				var table = $('#".$this->idTable."').DataTable(".CJavaScript::encode($this->option).");
                ".$this->customsScript."
			});
		";
        Yii::app()->clientScript->registerScript(__class__.'#'.$this->idTable,$this->script,CClientScript::POS_HEAD);
    }
}