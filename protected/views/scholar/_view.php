<?php 
$scholar_type = Yii::app()->session['scholar_type'];
$person_type = Yii::app()->session['person_type']; 
?>

<?php ++$index; ?>
<tr class="<?php echo ($index & 1) ? 'odd' : 'even'; ?> pointer">
    <td class="" style="text-align: center;"><?php echo $index; ?> </td>
    <!--<td class="" style="text-align: center;"><?php // echo CHtml::encode($data['type']);          ?> </td>-->
    <td class="" style="white-space: nowrap;">
        <?php if($scholar_type == 'stem') { ?>
        <?php echo CHtml::encode($data['student']); ?> 
    </td>
        <?php } else if($scholar_type == 'nuirc' || $scholar_type == 'tgist') {?>
        <?php echo CHtml::encode($data['pro_men_ind']); ?> 
    </td>
    <td class="" style="text-align: center;">
        <button type="button" class="btn btn-<?php echo InitialData::STATUS_COLOR($data['status_comment']); ?> btn-xs">
        <?php echo InitialData::STATUS($data['status_comment']); ?>
        </button>
    </td>
        <?php } ?>
    <td class="" style="text-align: left;">
        <ul class="list-unstyled user_data">
            <li>
                <p style="font-size: small;"><?php echo InitialData::STATUS($data['status']); ?></p>
                <div class="progress progress_sm">
                    <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="<?php echo Yii::app()->params['StatusPersent'][$data['status']]; ?>" style="width: <?php echo Yii::app()->params['StatusPersent'][$data['status']]; ?>%;" aria-valuenow="19"></div>
                </div>
            </li>
        </ul>
    </td>
    <td class="" style="text-align: center;white-space: nowrap;">
        <?php echo CHtml::encode(date("d/m/Y H:i:s", strtotime($data['lastupdated']))); ?>
    </td>
    <td class="last" style="text-align: left;">
        <?php
        echo StatusData::getActionBar($this, $person_type, $data);
//        if (in_array($data['status'], array(
//                    Yii::app()->params['Status']['PendingRecommendations'],
//                    Yii::app()->params['Status']['PendingScholarships'],
//                    Yii::app()->params['Status']['Reject'],
//                    Yii::app()->params['Status']['Pass']
//                ))) {
//            $addUrl = Yii::app()->createUrl('scholar/view', array(
//                'id' => $data['id'],
//                'scholartype' => strtolower($data['type'])
//            ));
//            $this->widget('booster.widgets.TbButton', array(
//                'label' => '',
//                'icon' => 'search',
//                'buttonType' => 'button',
//                'htmlOptions' => array(
//                    'class' => 'btn btn-primary btn-lg',
//                    'onclick' => 'window.location="' . $addUrl . '"',
//                ),
//            ));
//        }
//
//        if (in_array($data['status'], array(
//                    Yii::app()->params['Status']['Draft'],
//                    Yii::app()->params['Status']['Confirm']
//                ))) {
//            $addUrl = Yii::app()->createUrl('scholar/edit', array(
//                'id' => $data['id'],
//                'scholartype' => strtolower($data['type'])
//            ));
//            $this->widget('booster.widgets.TbButton', array(
//                'label' => '',
//                'icon' => 'pencil',
//                'buttonType' => 'button',
//                'htmlOptions' => array(
//                    'class' => 'btn btn-info btn-lg ',
//                    'onclick' => 'window.location="' . $addUrl . '"',
//                ),
//            ));
//        }
//        if (in_array($data['status'], array(
//                    Yii::app()->params['Status']['Draft']
//                ))) {
//            $addUrl = Yii::app()->createUrl('scholar/delete', array(
//                'id' => $data['id'],
//                'scholartype' => strtolower($data['type'])
//            ));
//            $this->widget('booster.widgets.TbButton', array(
//                'label' => '',
//                'icon' => 'trash',
//                'buttonType' => 'button',
//                'htmlOptions' => array(
//                    'class' => 'btn btn-danger btn-lg',
//                    'confirm' => "คุณต้องการลบรายการ หรือไม่?"
//                    . "\nDo you want to delete?",
//                    'onclick' => 'window.location="' . $addUrl . '"',
//                ),
//            ));
//        }
//
//        if ($scholar_type == 'stem') {
//            if (in_array($data['status'], array(
//                        Yii::app()->params['Status']['PendingRecommendations'],
//                    ))) {
//                $this->widget('booster.widgets.TbButton', array(
//                    'label' => '',
//                    'icon' => 'cog',
//                    'buttonType' => 'button',
//                    'htmlOptions' => array(
//                        'id' => 'btn_dropdown_' . $data['id'],
//                        'class' => 'btn btn-default btn-lg',
//                        'data-toggle' => 'dropdown',
//                        'aria-expanded' => 'false',
//                        'onclick' => ''
//                        . 'var offset_l = 38;'
//                        . 'var offset_t = 30;'
//                        . 'if($("#datatable-responsive").hasClass("collapsed")){'
//                        . 'offset_l = -150;'
//                        . 'offset_t = 35;'
//                        . '}'
//                        . 'var offset_left = $(this).find("span.glyphicon-cog").position().left - $("#dropdown_' . $data['id'] . '").width() + offset_l;'
//                        . 'var offset_top = $(this).find("span.glyphicon-cog").position().top + offset_t;'
//                        . 'if($( "ul#dropdown_' . $data['id'] . '" ).css("display") == "none"){'
//                        . '$( "ul#dropdown_' . $data['id'] . '" ).css("left", offset_left);'
//                        . '$( "ul#dropdown_' . $data['id'] . '" ).css("top", offset_top);'
//                        . '$( "ul#dropdown_' . $data['id'] . '" ).show();'
//                        . '}else{'
//                        . '$( "ul#dropdown_' . $data['id'] . '" ).hide();'
//                        . '}'
//                    ),
//                ));
//
//                echo '<ul class="dropdown-menu" role="menu" style="display:none;" id="dropdown_' . $data['id'] . '">';
//                // View Student
//                $addText = '<i class="fa fa-graduation-cap"></i> ข้อมูลนักเรียน/นักศึกษา / Student';
//                $addUrl = Yii::app()->createUrl('scholar/readonly', array(
//                    'id' => $data['id'],
//                    'scholartype' => strtolower($data['type']),
//                    'utype' => 'student',
//                ));
//                echo '<li>';
//                echo CHtml::link($addText, $addUrl, array(
//                    'class' => 'btn',
//                    'style' => 'float: left;',
//                ));
//                echo '</li>';
//                
//                // View Student
//                $addText = '<i class="fa fa-university"></i> ข้อมูลบริษัท/ภาคอุตสาหกรรม / Industrial';
//                $addUrl = Yii::app()->createUrl('scholar/readonly', array(
//                    'id' => $data['id'],
//                    'scholartype' => strtolower($data['type']),
//                    'utype' => 'industrial',
//                ));
//                echo '<li>';
//                echo CHtml::link($addText, $addUrl, array(
//                    'class' => 'btn',
//                    'style' => 'float: left;',
//                ));
//                echo '</li>';
//                
//                // ส่ง Email ไปยังนักเรียน/นักศึกษา Send mail to student
//                $addText = '<i class="fa fa-envelope"></i> ส่ง Email ไปยังนักเรียน/นักศึกษา Send mail to student';
//                $addUrl = Yii::app()->createUrl('scholar/act', array(
//                    'id' => $data['id'],
//                    'scholartype' => strtolower($data['type']),
//                    'action' => 'resend',
//                    'utype' => 'student',
//                ));
//                echo '<li>';
//                echo CHtml::link($addText, $addUrl, array(
//                    'class' => 'btn',
//                    'style' => 'float: left;',
//                    'confirm' => "คุณต้องส่ง Email แจ้ง นักเรียน/นักศึกษา(อีกครั้ง) หรือไม่?"
//                    . "\nDo you want to send email (again)?",
//                ));
//                echo '</li>';
//                
//                // ส่ง Email ไปยังบริษัท/ภาคอุตสาหกรรม Send mail to industrial
//                $addText = '<i class="fa fa-envelope"></i> ส่ง Email ไปยังบริษัท/ภาคอุตสาหกรรม Send mail to industrial';
//                $addUrl = Yii::app()->createUrl('scholar/act', array(
//                    'id' => $data['id'],
//                    'scholartype' => strtolower($data['type']),
//                    'action' => 'resend',
//                    'utype' => 'industrial',
//                ));
//                echo '<li>';
//                echo CHtml::link($addText, $addUrl, array(
//                    'class' => 'btn',
//                    'style' => 'float: left;',
//                    'confirm' => "คุณต้องส่ง Email แจ้ง บริษัท/ภาคอุตสาหกรรม(อีกครั้ง) หรือไม่?"
//                    . "\nDo you want to send email (again)?",
//                ));
//                echo '</li>';
//                echo '</ul>';
//            }
//        }
        ?>
    </td>
</tr>

