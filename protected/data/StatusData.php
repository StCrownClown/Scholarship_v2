<?php

class StatusData {
    /**
    * BUTTON NAME
     * View = view
     * Edit = edit
     * Delete = delete
     * More = more
    */
    public static $configBtnInStatus = array(
        'stem' => array(
            'professor' => array(
                'main' => array(
                    'draft' => array('edit','delete'),
                    'pending_recommendations' => array('view', 'more'),
                    'confirm' => array('edit', 'more'),
                    'pending_scholarships' => array('view', 'more'),
                ),
            ),
            'mentor' => array(
                'main' => array(
                    'draft' => array('edit','delete'),
                    'pending_recommendations' => array('view', 'more'),
                    'confirm' => array('edit', 'more'),
                    'pending_scholarships' => array('view', 'more'),
                ),
            ),
            'student' => array(
                'comment' => array(
                    'draft' => array(),
                    'pending_recommendations' => array('edit'),
                    'confirm' => array('edit', 'view'),
                    'pending_scholarships' => array('view'),
//                    'draft' => array('edit',),
//                    'sent' => array('view'),
                ),
            ),
            'industrial' => array(
                'comment' => array(
                    'draft' => array(),
                    'pending_recommendations' => array('edit'),
                    'confirm' => array('edit', 'view'),
                    'pending_scholarships' => array('view'),
//                    'draft' => array('edit',),
//                    'sent' => array('view'),
                ),
            ),
        ),


        'nuirc' => array(
            'professor' => array(
                'comment' => array(
                    'draft' => array('view'),
                    'pending_recommendations' => array('edit', 'more'),
                    'confirm' => array('edit', 'more'),
                    'pending_scholarships' => array('view', 'more'),
                ),
            ),
            'mentor' => array(
                'comment' => array(
                    'draft' => array('view'),
                    'pending_recommendations' => array('edit', 'more'),
                    'confirm' => array('edit', 'more'),
                    'pending_scholarships' => array('view', 'more'),
                ),
            ),
            'student' => array(
                'main' => array(
                    'draft' => array('edit','delete'),
                    'pending_recommendations' => array('view', 'more'),
                    'confirm' => array('edit', 'more'),
                    'pending_scholarships' => array('view', 'more'),
                ),
            ),
            'industrial' => array(
                'comment' => array(
                    'draft' => array('view'),
                    'pending_recommendations' => array('edit', 'more'),
                    'confirm' => array('edit', 'view'),
                    'pending_scholarships' => array('view', 'more'),
//                    'sent' => array('view'),
                ),
            ),
        ),
        
        'tgist' => array(
            'professor' => array(
                'comment' => array(
                    'draft' => array('view'),
                    'pending_recommendations' => array('edit', 'more'),
                    'confirm' => array('edit', 'more'),
                    'pending_scholarships' => array('view', 'more'),
                ),
            ),
            'mentor' => array(
                'comment' => array(
                    'draft' => array('view'),
                    'pending_recommendations' => array('edit', 'more'),
                    'confirm' => array('edit', 'more'),
                    'pending_scholarships' => array('view', 'more'),
                ),
            ),
            'student' => array(
                'main' => array(
                    'draft' => array('edit','delete'),
                    'pending_recommendations' => array('view', 'more'),
                    'confirm' => array('edit', 'more'),
                    'pending_scholarships' => array('view', 'more'),
                ),
            ),
        ),
    );
    
    public function DisplayStatusBar($page='main'){
        $result=array();
        $scholar_type = ConfigWeb::getActiveScholarType();
        $person_type = ConfigWeb::getActivePersonType();
        if(Yii::app()->session['user_type'] == 'admin')
            $person_type = 'professor';
        if (array_key_exists($page, StatusData::$configBtnInStatus[$scholar_type][$person_type])) {
            $sdata = StatusData::$configBtnInStatus[$scholar_type][$person_type][$page];
            foreach ($sdata as $key=>$value) {
                array_push($result,$key);
            }
        }
        return $result;
    }
    
    public function getBtnList($scholartype, $persontype, $status, $page='main') {
        if (array_key_exists($scholartype, StatusData::$configBtnInStatus)) {
            if (array_key_exists($persontype, StatusData::$configBtnInStatus[$scholartype])) {
                if (array_key_exists($page, StatusData::$configBtnInStatus[$scholartype][$persontype])) {
                    if (array_key_exists($status, StatusData::$configBtnInStatus[$scholartype][$persontype][$page])) {
                        return StatusData::$configBtnInStatus[$scholartype][$persontype][$page][$status];
                    }
                }
            }
        }
        return array();
        
    }
    
    public function getActionBar($form, $persontype, $data, $page='main') {
        $btnList = StatusData::getBtnList(strtolower($data['type']), $persontype, $data['status'], $page);
        foreach ($btnList as &$value) {
            switch ($value) {
                case "view":
                    $addUrl = Yii::app()->createUrl('scholar/view', array(
                        'id' => $data['id'],
                        'scholartype' => strtolower($data['type'])
                    ));
                    $form->widget('booster.widgets.TbButton', array(
                        'label' => '',
                        'icon' => 'search',
                        'buttonType' => 'button',
                        'htmlOptions' => array(
                            'class' => 'btn btn-primary btn-lg',
                            'onclick' => 'window.location="' . $addUrl . '"',
                        ),
                    ));
                    break;
                case "edit":
                    $addUrl = Yii::app()->createUrl('scholar/edit', array(
                        'id' => $data['id'],
                        'scholartype' => strtolower($data['type'])
                    ));
                    $form->widget('booster.widgets.TbButton', array(
                        'label' => '',
                        'icon' => 'pencil',
                        'buttonType' => 'button',
                        'htmlOptions' => array(
                            'class' => 'btn btn-info btn-lg ',
                            'onclick' => 'window.location="' . $addUrl . '"',
                        ),
                    ));
                    break;
                case "delete":
                    $addUrl = Yii::app()->createUrl('scholar/delete', array(
                        'id' => $data['id'],
                        'scholartype' => strtolower($data['type'])
                    ));
                    $form->widget('booster.widgets.TbButton', array(
                        'label' => '',
                        'icon' => 'trash',
                        'buttonType' => 'button',
                        'htmlOptions' => array(
                            'class' => 'btn btn-danger btn-lg',
                            'confirm' => "คุณต้องการลบรายการ หรือไม่?"
                            . "\nDo you want to delete?",
                            'onclick' => 'window.location="' . $addUrl . '"',
                        ),
                    ));
                    break;
                case "more":
                    $form->widget('booster.widgets.TbButton', array(
                        'label' => '',
                        'icon' => 'cog',
                        'buttonType' => 'button',
                        'htmlOptions' => array(
                            'id' => 'btn_dropdown_' . $data['id'],
                            'class' => 'btn btn-default btn-lg',
                            'data-toggle' => 'dropdown',
                            'aria-expanded' => 'false',
                            'onclick' => ''
                            . 'var offset_l = 38;'
                            . 'var offset_t = 30;'
                            . 'if($("#datatable-responsive").hasClass("collapsed")){'
                            . 'offset_l = -150;'
                            . 'offset_t = 35;'
                            . '}'
                            . 'var offset_left = $(this).find("span.glyphicon-cog").position().left - $("#dropdown_' . $data['id'] . '").width() + offset_l;'
                            . 'var offset_top = $(this).find("span.glyphicon-cog").position().top + offset_t;'
                            . 'if($( "ul#dropdown_' . $data['id'] . '" ).css("display") == "none"){'
                            . '$( "ul[id^=dropdown_]" ).hide();'
                            . '$( "ul#dropdown_' . $data['id'] . '" ).css("left", offset_left);'
                            . '$( "ul#dropdown_' . $data['id'] . '" ).css("top", offset_top);'
                            . '$( "ul#dropdown_' . $data['id'] . '" ).show();'
                            . '}else{'
                            . '$( "ul#dropdown_' . $data['id'] . '" ).hide();'
                            . '}'
                        ),
                    ));
                    
                    //More Menu
                    if($data['type'] == 'stem'){
                        echo '<ul class="dropdown-menu" role="menu" style="display:none;" id="dropdown_' . $data['id'] . '">';
                        if($data['status'] != 'draft'){
                            // View Student
                            $addText = '<i class="fa fa-graduation-cap"></i> ข้อมูลนักเรียน/นักศึกษา / Student';
                            $addUrl = Yii::app()->createUrl('scholar/readonly', array(
                                'id' => $data['id'],
                                'scholartype' => strtolower($data['type']),
                                'utype' => 'student',
                            ));
                            echo '<li>';
                            echo CHtml::link($addText, $addUrl, array(
                                'class' => 'btn',
                                'style' => 'float: left;',
                            ));
                            echo '</li>';

                            // View Student
                            $addText = '<i class="fa fa-university"></i> ข้อมูลบริษัท/ภาคอุตสาหกรรม / Industrial';
                            $addUrl = Yii::app()->createUrl('scholar/readonly', array(
                                'id' => $data['id'],
                                'scholartype' => strtolower($data['type']),
                                'utype' => 'industrial',
                            ));
                            echo '<li>';
                            echo CHtml::link($addText, $addUrl, array(
                                'class' => 'btn',
                                'style' => 'float: left;',
                            ));
                            echo '</li>';
                        }
                        
                        if($data['status'] == 'pending_recommendations' || $data['status'] == 'confirm'){
                            // เปลี่ยน Email นักเรียน/นักศึกษา
                            $addText = '<i class="fa fa-repeat"></i> เปลี่ยน Email นักเรียน/นักศึกษา';
                            $addUrl = Yii::app()->createUrl('scholar/act', array(
                                'id' => $data['id'],
                                'scholartype' => strtolower($data['type']),
                                'action' => 'changeemail',
                                'utype' => 'student',
                            ));
                            echo '<li>';
                            echo CHtml::link($addText, $addUrl, array(
                                'class' => 'btn',
                                'style' => 'float: left;',
                            ));
                            echo '</li>';
                            
                            // เปลี่ยน Email บริษัท/ภาคอุตสาหกรรม
                            $addText = '<i class="fa fa-repeat"></i> เปลี่ยน Email บริษัท/ภาคอุตสาหกรรม';
                            $addUrl = Yii::app()->createUrl('scholar/act', array(
                                'id' => $data['id'],
                                'scholartype' => strtolower($data['type']),
                                'action' => 'changeemail',
                                'utype' => 'industrial',
                            ));
                            echo '<li>';
                            echo CHtml::link($addText, $addUrl, array(
                                'class' => 'btn',
                                'style' => 'float: left;',
                            ));
                            echo '</li>';
                            
                            // ส่ง Email ไปยังนักเรียน/นักศึกษา Send mail to student
                            $addText = '<i class="fa fa-envelope"></i> ส่ง Email ไปยังนักเรียน/นักศึกษา / Send mail to student';
                            $addUrl = Yii::app()->createUrl('scholar/act', array(
                                'id' => $data['id'],
                                'scholartype' => strtolower($data['type']),
                                'action' => 'resend',
                                'utype' => 'student',
                            ));
                            echo '<li>';
                            echo CHtml::link($addText, $addUrl, array(
                                'class' => 'btn',
                                'style' => 'float: left;',
                                'confirm' => "คุณต้องส่ง Email แจ้ง นักเรียน/นักศึกษา(อีกครั้ง) หรือไม่?"
                                . "\nDo you want to send email (again)?",
                            ));
                            echo '</li>';

                            // ส่ง Email ไปยังภาคอุตสาหกรรม Send mail to industrial
                            $addText = '<i class="fa fa-envelope"></i> ส่ง Email ไปยังบริษัท/ภาคอุตสาหกรรม / Send mail to industrial';
                            $addUrl = Yii::app()->createUrl('scholar/act', array(
                                'id' => $data['id'],
                                'scholartype' => strtolower($data['type']),
                                'action' => 'resend',
                                'utype' => 'industrial',
                            ));
                            echo '<li>';
                            echo CHtml::link($addText, $addUrl, array(
                                'class' => 'btn',
                                'style' => 'float: left;',
                                'confirm' => "คุณต้องส่ง Email แจ้ง บริษัท/ภาคอุตสาหกรรม(อีกครั้ง) หรือไม่?"
                                . "\nDo you want to send email (again)?",
                            ));
                            echo '</li>';
                        }
                        echo '</ul>';
                    }
                    else if ($data['type'] == 'nuirc' || $data['type'] == 'tgist') {
                        echo '<ul class="dropdown-menu" role="menu" style="display:none;" id="dropdown_' . $data['id'] . '">';
                        if($data['status'] != 'draft'){
                            
                            // View Student
                            if (ConfigWeb::getActivePersonType() != 'student') {
                                $addText = '<i class="fa fa-graduation-cap"></i> ข้อมูลนักเรียน/นักศึกษา / Student';
                                $addUrl = Yii::app()->createUrl('scholar/readonly', array(
                                    'id' => $data['id'],
                                    'scholartype' => strtolower($data['type']),
                                    'utype' => 'student',
                                ));
                                echo '<li>';
                                echo CHtml::link($addText, $addUrl, array(
                                    'class' => 'btn',
                                    'style' => 'float: left;',
                                ));
                                echo '</li>';
                            }
                            
                            // View Professor
                            if (ConfigWeb::getActivePersonType() != 'professor') {
                                $addText = '<i class="fa fa-university"></i> ข้อมูลอาจารย์ที่ปรึกษา / Professor';
                                $addUrl = Yii::app()->createUrl('scholar/readonly', array(
                                    'id' => $data['id'],
                                    'scholartype' => strtolower($data['type']),
                                    'utype' => 'professor',
                                ));
                                echo '<li>';
                                echo CHtml::link($addText, $addUrl, array(
                                    'class' => 'btn',
                                    'style' => 'float: left;',
                                ));
                                echo '</li>';
                            }
                            
                            // View Mentor
                            if (ConfigWeb::getActivePersonType() != 'mentor') {
                                $addText = '<i class="fa fa-graduation-cap"></i> ข้อมูลนักวิจัยสวทช. / Mentor';
                                $addUrl = Yii::app()->createUrl('scholar/readonly', array(
                                    'id' => $data['id'],
                                    'scholartype' => strtolower($data['type']),
                                    'utype' => 'mentor',
                                ));
                                echo '<li>';
                                echo CHtml::link($addText, $addUrl, array(
                                    'class' => 'btn',
                                    'style' => 'float: left;',
                                ));
                                echo '</li>';
                            }
                            
                            // View Industrial
                            if (ConfigWeb::getActivePersonType() != 'industrial' && $data['type'] == 'nuirc') {
                                $addText = '<i class="fa fa-graduation-cap"></i> ข้อมูลบริษัท/ภาคอุตสาหกรรม / Industrial';
                                $addUrl = Yii::app()->createUrl('scholar/readonly', array(
                                    'id' => $data['id'],
                                    'scholartype' => strtolower($data['type']),
                                    'utype' => 'industrial',
                                ));
                                echo '<li>';
                                echo CHtml::link($addText, $addUrl, array(
                                    'class' => 'btn',
                                    'style' => 'float: left;',
                                ));
                                echo '</li>';
                            }
                        }
                        
                        if($data['status'] == 'pending_recommendations' || $data['status'] == 'confirm'){
                            
                            if (ConfigWeb::getActivePersonType() == 'student') {
                                
                                // เปลี่ยน Email อาจารย์ที่ปรึกษา
                                $addText = '<i class="fa fa-repeat"></i> เปลี่ยน Email อาจารย์ที่ปรึกษา';
                                $addUrl = Yii::app()->createUrl('scholar/act', array(
                                    'id' => $data['id'],
                                    'scholartype' => strtolower($data['type']),
                                    'action' => 'changeemail',
                                    'utype' => 'professor',
                                ));
                                echo '<li>';
                                echo CHtml::link($addText, $addUrl, array(
                                    'class' => 'btn',
                                    'style' => 'float: left;',
                                ));
                                echo '</li>';

                                // เปลี่ยน Email นักวิจัยสวทช.
                                $addText = '<i class="fa fa-repeat"></i> เปลี่ยน Email นักวิจัยสวทช.';
                                $addUrl = Yii::app()->createUrl('scholar/act', array(
                                    'id' => $data['id'],
                                    'scholartype' => strtolower($data['type']),
                                    'action' => 'changeemail',
                                    'utype' => 'mentor',
                                ));
                                echo '<li>';
                                echo CHtml::link($addText, $addUrl, array(
                                    'class' => 'btn',
                                    'style' => 'float: left;',
                                ));
                                echo '</li>';

                                // เปลี่ยน Email บริษัท/ภาคอุตสาหกรรม
                                $addText = '<i class="fa fa-repeat"></i> เปลี่ยน Email บริษัท/ภาคอุตสาหกรรม';
                                $addUrl = Yii::app()->createUrl('scholar/act', array(
                                    'id' => $data['id'],
                                    'scholartype' => strtolower($data['type']),
                                    'action' => 'changeemail',
                                    'utype' => 'industrial',
                                ));
                                echo '<li>';
                                echo CHtml::link($addText, $addUrl, array(
                                    'class' => 'btn',
                                    'style' => 'float: left;',
                                ));
                                echo '</li>';

                                // ส่ง Email ไปยังอาจารย์ที่ปรึกษา Send mail to professor
                                $addText = '<i class="fa fa-envelope"></i> ส่ง Email ไปยังอาจารย์ที่ปรึกษา / Send mail to professor';
                                $addUrl = Yii::app()->createUrl('scholar/act', array(
                                    'id' => $data['id'],
                                    'scholartype' => strtolower($data['type']),
                                    'action' => 'resend',
                                    'utype' => 'professor',
                                ));
                                echo '<li>';
                                echo CHtml::link($addText, $addUrl, array(
                                    'class' => 'btn',
                                    'style' => 'float: left;',
                                    'confirm' => "คุณต้องส่ง Email แจ้ง อาจารย์ที่ปรึกษา(อีกครั้ง) หรือไม่?"
                                    . "\nDo you want to send email (again)?",
                                ));
                                echo '</li>';

                                // ส่ง Email ไปยังนักวิจัยสวทช. Send mail to mentor
                                $addText = '<i class="fa fa-envelope"></i> ส่ง Email ไปยังนักวิจัยสวทช. / Send mail to mentor';
                                $addUrl = Yii::app()->createUrl('scholar/act', array(
                                    'id' => $data['id'],
                                    'scholartype' => strtolower($data['type']),
                                    'action' => 'resend',
                                    'utype' => 'mentor',
                                ));
                                echo '<li>';
                                echo CHtml::link($addText, $addUrl, array(
                                    'class' => 'btn',
                                    'style' => 'float: left;',
                                    'confirm' => "คุณต้องส่ง Email แจ้ง ไปยังนักวิจัยสวทช.(อีกครั้ง) หรือไม่?"
                                    . "\nDo you want to send email (again)?",
                                ));
                                echo '</li>';

                                // ส่ง Email ไปยังภาคอุตสาหกรรม Send mail to industrial
                                $addText = '<i class="fa fa-envelope"></i> ส่ง Email ไปยังบริษัท/ภาคอุตสาหกรรม / Send mail to industrial';
                                $addUrl = Yii::app()->createUrl('scholar/act', array(
                                    'id' => $data['id'],
                                    'scholartype' => strtolower($data['type']),
                                    'action' => 'resend',
                                    'utype' => 'industrial',
                                ));
                                echo '<li>';
                                echo CHtml::link($addText, $addUrl, array(
                                    'class' => 'btn',
                                    'style' => 'float: left;',
                                    'confirm' => "คุณต้องส่ง Email แจ้ง บริษัท/ภาคอุตสาหกรรม(อีกครั้ง) หรือไม่?"
                                    . "\nDo you want to send email (again)?",
                                ));
                                echo '</li>';
                            }
                        }
                        echo '</ul>';
                    }
                    break;
                default:
                    echo "Default";
            }
        }
    }

}
