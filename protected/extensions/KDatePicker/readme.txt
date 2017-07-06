$this->widget('ext.KDatePicker.KDatePicker', array(
                        'name' => get_class($model) . '[M_CT_WORK_CREATED]',
                        'language' => Yii::app()->language,
                        'value' => $model->M_CT_WORK_CREATED,
                        'options' => array(
                            'showAnim' => 'fold',
                            'dateFormat' => 'dd/mm/yy',
                            'yearOffset' => 543,
                            'changeMonth' => true,
                            'changeYear' => true,
                            'yearRange' => 'c-10:c+5',
                            'showOn' => "both",
                            'buttonImage' => Yii::app()->request->baseUrl . '/images/calendar.gif',
                        ),
                        'htmlOptions' => array(
                            'style' => 'height:20px;',
                        ),
                    ));