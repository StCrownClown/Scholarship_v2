<?php

class WorkflowData {
    
    public static $home = 'scholar/index';
    
    public static $useSkipWorkflow = array(
//        'stem' => array(
//            'professor' => array(
//                'main' => True
//            ),
//            'mentor' => array(
//                'main' => True
//            ),
//            'student' => array(
//                'main' => True
//            ),
//            'industrial' => array(
//                'main' => True
//            ),
//        ),
        
        'nuirc' => array(
            'professor' => array(
                'main' => True
            ),
            'mentor' => array(
                'main' => True
            ),
            'student' => array(
                'main' => True
            ),
            'industrial' => array(
                'main' => True
            ),
        ),
        
        'tgist' => array(
            'professor' => array(
                'main' => True
            ),
            'mentor' => array(
                'main' => True
            ),
            'student' => array(
                'main' => True
            ),
        ),
    );
    public static $listWorkflow = array(
        'stem' => array(
            'professor' => array(
                'main' => array(
                    '1' => 'site/miniuploadphotoprofile',
                    '2' => 'site/miniprofile',
                    '3' => 'site/minieducation',
                    '4' => 'stem/student',
                    '5' => 'stem/primaryproject',
                    '6' => 'stem/studentproject',
                    '7' => 'stem/company',
                    '8' => 'site/miniattachment',
                    '9' => 'stem/comment',
                    '10' => 'scholar/index',
                ),
            ),
            'mentor' => array(
                'main' => array(
                    '1' => 'site/miniuploadphotoprofile',
                    '2' => 'site/miniprofile',
                    '3' => 'site/minieducation',
                    '4' => 'stem/student',
                    '5' => 'stem/primaryproject',
                    '6' => 'stem/studentproject',
                    '7' => 'stem/company',
                    '8' => 'site/miniattachment',
                    '9' => 'stem/comment',
                    '10' => 'scholar/index',
                ),
            ),
            'student' => array(
                'main' => array(
                    '1' => 'site/miniuploadphotoprofile',
                    '2' => 'site/miniprofile',
                    '3' => 'site/address',
                    '4' => 'site/minieducation',
                    '5' => 'stem/history',
                    '6' => 'stem/experience',
                    '7' => 'stem/working',
                    '8' => 'site/miniattachment',
                    '9' => 'scholar/index',
                ),
            ),
            'industrial' => array(
                'main' => array(
                    '1' => 'site/company',
                    '2' => 'site/address',
                    '3' => 'site/miniattachment',
                    '4' => 'stem/recommendation',
                    '5' => 'scholar/index',
                ),
            ),
        ),


	'nuirc' => array(
            'student' => array(
                'main' => array(
                    '1' => 'site/miniuploadphotoprofile',
                    '2' => 'nuirc/miniprofile',
                    '3' => 'site/address',
                    '4' => 'site/minieducation',
                    '5'	=> 'nuirc/history',
                    '6'	=> 'nuirc/working',
                    '7'	=> 'nuirc/experience',
                    '8' => 'nuirc/professor',
                    '9' => 'nuirc/mentor',
                    '10' => 'nuirc/industrial',
                    '11' => 'nuirc/miniattachment',
                    '12' => 'scholar/index',
                ),
            ),
            'professor' => array(
                'main' => array(
                    '1' => 'site/miniuploadphotoprofile',
                    '2' => 'nuirc/miniprofile',
                    '3' => 'site/minieducation',
                    '4' => 'nuirc/primaryproject',
                    '5' => 'nuirc/miniattachment',
                    '6' => 'nuirc/verifystudent',
                    '7' => 'nuirc/comment',
                    '8' => 'scholar/index',
                ),
            ),
            'mentor' => array(
                'main' => array(
                    '1' => 'site/miniuploadphotoprofile',
                    '2' => 'nuirc/miniprofile',
                    '3' => 'site/minieducation',
                    '4' => 'nuirc/primaryproject',
                    '5' => 'nuirc/miniattachment',
                    '6' => 'nuirc/verifystudent',
                    '7' => 'nuirc/comment',
                    '8' => 'scholar/index',
                ),
            ),
            'industrial' => array(
                'main' => array(
                    '1' => 'site/miniuploadphotoprofile',
                    '2' => 'nuirc/miniprofile',
                    '3' => 'site/minieducation',
                    '4' => 'nuirc/primaryproject',
                    '5' => 'nuirc/miniattachment',
                    '6' => 'nuirc/verifystudent',
                    '7' => 'nuirc/comment',
                    '8' => 'scholar/index',
                ),
            ),
        ),
    		
	'tgist' => array(
            'student' => array(
                'main' => array(
                    '1' => 'site/miniuploadphotoprofile',
                    '2' => 'tgist/miniprofile',
                    '3' => 'site/address',
                    '4' => 'site/minieducation',
                    '5' => 'tgist/history',
                    '6' => 'tgist/studentproject',
                    '7'	=> 'tgist/working',
                    '8'	=> 'tgist/experience',
                    '9' => 'tgist/professor',
                    '10' => 'tgist/mentor',
                    '11' => 'tgist/miniattachment',
                    '12' => 'scholar/index',
                ),
            ),
            'professor' => array(
                'main' => array(
                    '1' => 'site/miniuploadphotoprofile',
                    '2' => 'tgist/miniprofile',
                    '3' => 'site/minieducation',
                    '4' => 'tgist/primaryproject',
                    '5' => 'tgist/miniattachment',
                    '6' => 'tgist/verifystudent',
                    '7' => 'tgist/comment',
                    '8' => 'scholar/index',
                ),
            ),
            'mentor' => array(
                'main' => array(
                    '1' => 'site/miniuploadphotoprofile',
                    '2' => 'tgist/miniprofile',
                    '3' => 'site/minieducation',
                    '4' => 'tgist/primaryproject',
                    '5' => 'tgist/miniattachment',
                    '6' => 'tgist/verifystudent',
                    '7' => 'tgist/comment',
                    '8' => 'scholar/index',
                ),
            ),
        ),
    );
    
    public function getActionBar($form, $urlBack, $isNext = True, $attrBack = NULL, $attrNext = NULL, $name = 'main') {
        $scholar_type = ConfigWeb::getActiveScholarType();
        $person_type = ConfigWeb::getActivePersonType();
        
        $linkBack = Yii::app()->createUrl($urlBack);
        $currentUrl = WorkflowData::WorkflowUrlNext($urlBack);
        if(empty($attrBack)){
            $attrBack = array(
                'class' => 'btn btn-default',
                'style' => 'float: left;',
            );
        }
        
        if(empty($attrNext)){
            $attrNext = array(
                'label' => 'ถัดไป / Next →',
                'buttonType' => 'submit',
                'htmlOptions' => array(
                    'name' => 'next',
                    'class' => 'btn btn-success',
                    'style' => 'float: right;',
                ),
            );
        }
        
        echo CHtml::link('← ย้อนกลับ / Back', $linkBack, $attrBack);
        if(!WorkflowData::isLastStep($currentUrl) && $isNext){
            $form->widget('booster.widgets.TbButton', $attrNext);
        }
    }
    
    public function isLastStep($current = NULL, $name = 'main') {
        $scholar_type = ConfigWeb::getActiveScholarType();
        $person_type = ConfigWeb::getActivePersonType();
        $data = "";
        if (WorkflowData::$home != $current &&  !empty($scholar_type) && !empty($person_type)) {
            $wf = WorkflowData::getWorkflow($name);
            $max = sizeof($wf)-1;
            $i = array_search($current, $wf);
            if ($i != FALSE) {
                if ($i == $max) {
                    return True;
                } else {
                    return False;
                }
            }
        }else{
            return False;
        }
        return False;
    }
    
    public function ShowBreadcrumbName($current = NULL, $name = 'main') {
        $scholar_type = ConfigWeb::getActiveScholarType();
        $person_type = ConfigWeb::getActivePersonType();
        $data = "";
        if (WorkflowData::$home != $current &&  !empty($scholar_type) && !empty($person_type)) {
            $wf = WorkflowData::getWorkflow($name);
            $i = array_search($current, $wf);
            if ($i != FALSE) {
                $data = InitialData::PageName($wf[$i]);
            }
        }
        return $data;
    }
    
    public function ShowBreadcrumbShort($current = NULL, $name = 'main') {
        $scholar_type = ConfigWeb::getActiveScholarType();
        $person_type = ConfigWeb::getActivePersonType();
        $data = "";
        if (WorkflowData::$home != $current &&  !empty($scholar_type) && !empty($person_type)) {
            $wf = WorkflowData::getWorkflow($name);
            $i = array_search($current, $wf);
            if ($i != FALSE) {
                $numItems = count($wf);
                $data = $i . "/".($numItems-1);
            }
        }
        return $data;
    }
    
    public function ShowBreadcrumb($current = NULL, $name = 'main') {
        $scholar_type = ConfigWeb::getActiveScholarType();
        $person_type = ConfigWeb::getActivePersonType();
        if (WorkflowData::$home != $current &&  !empty($scholar_type) && !empty($person_type)) {
            $wf = WorkflowData::getWorkflow($name);
            $i = array_search($current, $wf);
            if ($i != FALSE) {
                $numItems = count($wf);
                $cnt = 0;
                $data = '<div class="form_wizard wizard_horizontal">'
                        . '<ul class="wizard_steps anchor" style="padding-right: 40px;">'
                        . '<li>'
                        . '<a href="'.Yii::app()->createUrl(WorkflowData::$home).'"  style="cursor: pointer;"  class="done" isdone="0" rel="0">'
                        . '<span class="step_no" style="background:#EDEDED;">&nbsp;<i class="fa fa-home" style="color:#5A738E;"></i>&nbsp;</span>'
                        . '<span class="step_descr">Home</span>'
                        . '</a>'
                        . '</li>';
                if(!WorkflowData::getUseSkipWorkflow($name)){
                    foreach ($wf as $key => $value) {
                        if (++$cnt !== $numItems) {
                            $data .= '<li>';
                            $url = '#';
                            $style = '';
                            $color = '';
                            $check = '';
                            if( WorkflowData::getMaxPage() >= $key || Yii::app()->session['tmpReadOnly']){
                                $url = Yii::app()->createUrl($wf[$key]);
                                $style = 'cursor: pointer;';
                                if (WorkflowData::getMaxPage() >= $key) {
                                    $check = '<i class="fa fa-check" style="color: #1ABB9C;"></i>&nbsp;';
                                }
                            }
                            if ($i > $key) {
                                $data .= '<a href="'.Yii::app()->createUrl($wf[$key]).'" style="cursor: pointer;" class="done" isdone="1" rel="' . $key . '">';
                                $data .= '<span class="step_no">' . $key . '</span>';
                            } else if ($value == $current) {
                                $data .= '<a href="#" class="selected" isdone="0" rel="' . $key . '">';
                                $data .= '<span class="step_no">' . $key . '</span>';
                            } else {
//                                $check = '<i class="fa fa-lock"></i>&nbsp;';
                                $data .= '<a href="'.$url.'" style="'.$style.'" class="disabled" isdone="0" rel="' . $key . '">';
                                $data .= '<span class="step_no" style="'.$color.'">' . $key . '</span>';
                            }
                            $data .= '<span class="step_descr">' . $check
                                    . InitialData::PageName($value)
                                    . '</span>'
                                    . '</a>'
                                    . '</li>';
                        }
                    }
                }else{
                    $steppage = WorkflowData::getMaxPage();
                    $wf = WorkflowData::getWorkflow($name);
                    $max = count($wf);

                    $ac = '';
                    for($i=0;$i<$max;$i++){
                        if($steppage & pow(2, $i))
                            $ac .=  ''+'1';
                        else
                            $ac .=  ''+'0';
                    }
                    
                    $i = 0;
                    foreach ($wf as $key => $value) {
                        if($i < $max-1){
                            $check = '';
                            $color = '';
                            $data .= '<li>';
                            $url = Yii::app()->createUrl($wf[$key]);
                            if($ac[$i++] == '1'){
                                $check = '<i class="fa fa-check" style="color: #1ABB9C;"></i>&nbsp;';
                                if ($value == $current) {
                                    $data .= '<a href="#" class="selected" isdone="0" rel="' . $key . '">';
                                    $data .= '<span class="step_no">' . $key . '</span>';
                                }else{
                                    $data .= '<a href="'.Yii::app()->createUrl($wf[$key]).'" style="cursor: pointer;" class="done" isdone="1" rel="' . $key . '">';
                                    $data .= '<span class="step_no">' . $key . '</span>';
                                }
                            }else{
                                if ($value == $current) {
                                    $data .= '<a href="#" class="selected" isdone="0" rel="' . $key . '">';
                                    $data .= '<span class="step_no">' . $key . '</span>';
                                }else{
                                    $data .= '<a href="'.$url.'"  style="cursor: pointer;"  class="disabled" isdone="0" rel="' . $key . '">';
                                    $data .= '<span class="step_no" style="'.$color.'">' . $key .  '</span>';
                                } 
                            }
                            $data .= '<span class="step_descr">' . $check
                                . InitialData::PageName($value)
                                . '</span>'
                                . '</a>'
                                . '</li>';
                        }
                    }
                }
                $data .= '</ul>'
                        . '</div>';
                return $data;
            }
        }

        return '';
    }
    
    public function getUseSkipWorkflow($name = 'main') {
        $scholar_type = ConfigWeb::getActiveScholarType();
        $person_type = ConfigWeb::getActivePersonType();
        if (array_key_exists($scholar_type, WorkflowData::$useSkipWorkflow)) {
            if (array_key_exists($person_type, WorkflowData::$useSkipWorkflow[$scholar_type])) {
                if (array_key_exists($name, WorkflowData::$useSkipWorkflow[$scholar_type][$person_type])) {
                    return WorkflowData::$useSkipWorkflow[$scholar_type][$person_type][$name];
                }
            }
        }
        return False;
    }
    
    public function getWorkflow($name = 'main') {
        $scholar_type = ConfigWeb::getActiveScholarType();
        $person_type = ConfigWeb::getActivePersonType();
        return WorkflowData::$listWorkflow[$scholar_type][$person_type][$name];
    }

    public function WorkflowUrlStart($name = 'main') {
        $wf = WorkflowData::getWorkflow($name);
        if(Yii::app()->session['tmpReadOnly']){
            return $wf[strval(1)];
        } else if (array_key_exists(strval(WorkflowData::getMaxPage()), $wf)) {
            return $wf[strval(WorkflowData::getMaxPage())];
        } else if(WorkflowData::getMaxPage() == 0){
            return $wf[strval(1)];
        }else {
            return WorkflowData::$home;
        }
    }
    
    public function UpdateMaxPage($key = 0, $name = 'main') {
        $scholar_type = ConfigWeb::getActiveScholarType();
        $person_type = ConfigWeb::getActivePersonType();
        $person_id = ConfigWeb::getActivePersonId();
        $scholar_id = ConfigWeb::getActiveScholarId();
        $steppage = WorkflowData::getMaxPage();
        
        $criteria = new CDbCriteria;
        if(Yii::app()->session['LoginByToken']){
            $criteria->condition = "scholar_id = " . $scholar_id
                    . " and person_id = " . $person_id;
            $criteria->limit = 1;
            $Comment = Comment::model()->find($criteria);
            
            $Comment->first_created = $Comment->first_created;
            $Comment->last_updated = date("Y-m-d H:i:s");
            $Comment->setIsNewRecord(FALSE);
            if(!WorkflowData::getUseSkipWorkflow($name)){
                if($key > $Comment->steppage){
                    $Comment->steppage = $key;
                    $Comment->update();
                }
            }else{
                if($steppage & pow(2, $key-1))
                    $Comment->update();
                else{
                    $Comment->steppage = $Comment->steppage + pow(2,$key-1);
                    $Comment->update();
                }
            }
        } else if(($scholar_type == 'nuirc' || $scholar_type == 'tgist') && $person_type == 'mentor'){
            $criteria->condition = "scholar_id = " . $scholar_id
                    . " and person_id = " . $person_id;
            $criteria->limit = 1;
            $Comment = Comment::model()->find($criteria);
            
            $Comment->first_created = $Comment->first_created;
            $Comment->last_updated = date("Y-m-d H:i:s");
            $Comment->setIsNewRecord(FALSE);
            if(!WorkflowData::getUseSkipWorkflow($name)){
                if($key > $Comment->steppage){
                    $Comment->steppage = $key;
                    $Comment->update();
                }
            }else{
                if($steppage & pow(2, $key-1))
                    $Comment->update();
                else{
                    $Comment->steppage = $Comment->steppage + pow(2,$key-1);
                    $Comment->update();
                }
            }
        } else {
            $criteria->condition = "id = " . $scholar_id . " and " . strtolower($person_type) . '_id = ' .$person_id;
            $criteria->limit = 1;
            $Scholar = Scholar::model()->find($criteria);
            $Scholar->first_created = $Scholar->first_created;
            $Scholar->last_updated = date("Y-m-d H:i:s");
            $Scholar->setIsNewRecord(FALSE);
            if(!WorkflowData::getUseSkipWorkflow($name)){
                if($key > $Scholar->steppage){
                    $Scholar->steppage = $key;
                    $Scholar->update();
                }
            }else{  
                if($steppage & pow(2, $key-1)){
                    $Scholar->update();
                }
                else{
                    $Scholar->steppage = $Scholar->steppage + pow(2,$key-1);
                    $Scholar->update();
                }
            }
        }
    }
    
    public function getMaxPage() {
        $scholar_type = ConfigWeb::getActiveScholarType();
        $person_type = ConfigWeb::getActivePersonType();
        $person_id = ConfigWeb::getActivePersonId();
        $scholar_id = ConfigWeb::getActiveScholarId();
        $is_loginByToken = !empty(Yii::app()->session['LoginByToken']);
        
        $criteria = new CDbCriteria;
        if ($scholar_type == 'stem') {
            if($person_type == 'student' || $person_type == 'industrial'){
        //if((!$is_loginByToken && Yii::app()->session['tmpReadOnly']) || $is_loginByToken){
            $criteria->condition = "scholar_id = " . $scholar_id
                    . " and person_id = " . $person_id;
            $criteria->limit = 1;
            $Comment = Comment::model()->find($criteria);
            return $Comment->steppage;
        }else if($person_type == 'professor' || $person_type == 'mentor'){
        //}else if(!Yii::app()->session['tmpReadOnly'] || !$is_loginByToken){
            $criteria->condition = "id = " . $scholar_id . " and " . strtolower($person_type) . '_id = ' .$person_id;
            $criteria->limit = 1;
            $Scholar = Scholar::model()->find($criteria);
            if(!empty($Scholar)){
                return $Scholar->steppage;
            }else{
                throw new CHttpException(404, 'The requested page does not exist.');
                }
            }
        }
        

        else if ($scholar_type == 'nuirc' || $scholar_type == 'tgist') {
            if($person_type != 'student') {
                $criteria->condition = "scholar_id = " . $scholar_id
                        . " and person_id = " . $person_id;
                $criteria->limit = 1;
                $Comment = Comment::model()->find($criteria);
                if(!empty($Comment)){
                    return $Comment->steppage;
                }else{
                    throw new CHttpException(404, 'The requested page does not exist.');
                }
            } else if($person_type == 'student') {
                $criteria->condition = "id = " . $scholar_id . " and student_id = " .$person_id;
                $criteria->limit = 1;
                $Scholar = Scholar::model()->find($criteria);
                if(!empty($Scholar)){
                    return $Scholar->steppage;
                }else{
                    throw new CHttpException(404, 'The requested page does not exist.');
                }
            }
        }
    }
    
    public function WorkflowUrlNext($current = NULL, $setMax = False ,$name = 'main') {
        $scholar_type = ConfigWeb::getActiveScholarType();
        $person_type = ConfigWeb::getActivePersonType();
        if (WorkflowData::$home != $current) {
            $wf = WorkflowData::getWorkflow($name);
            $key = array_search($current, $wf);
            if ($key != FALSE) {
                if($setMax){
                    WorkflowData::UpdateMaxPage($key, $name);
                }
                
                if (array_key_exists(strval($key + 1), $wf)) {
                    return $wf[strval($key + 1)];
                } else {
                    return WorkflowData::$home;
                }
            }
        } else {
            return WorkflowData::$home;
        }
    }

    public function WorkflowUrlBack($current = NULL, $name = 'main') {
        $scholar_type = ConfigWeb::getActiveScholarType();
        $person_type = ConfigWeb::getActivePersonType();
        if (WorkflowData::$home != $current) {
            $wf = WorkflowData::getWorkflow($name);
            $key = array_search($current, $wf);
            if ($key != FALSE) {
                if (array_key_exists(strval($key - 1), $wf)) {
                    return $wf[strval($key - 1)];
                } else {
                    return WorkflowData::$home;
                }
            }
        } else {
            return WorkflowData::$home;
        }
    }
}
