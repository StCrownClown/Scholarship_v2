<?php $scholar_type = Yii::app()->session['scholar_type']; ?>

<?php ++$index; ?>
<tr class="<?php echo ($index & 1) ? 'odd' : 'even'; ?> pointer">
    <td class="" style="text-align: center;"><?php echo $index; ?> </td>
    <td class="" style="white-space: nowrap;"><?php echo CHtml::encode($data['professor_mentor']); ?> </td>
    <td class="" style="white-space: nowrap;"><?php echo CHtml::encode($data['student']); ?> </td>
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
    <td class="last" style="text-align: left;white-space: nowrap;">
        <!--Word-->
        <?php
        if($data['status'] == 'pending_scholarships'){
            $addText = '<i class="fa fa-paperclip"></i>&nbsp;&nbsp;zip';
            $addUrl = Yii::app()->createUrl('scholar/word', array(
                'id' => $data['id'],
                'scholartype' => strtolower($data['type'])
            ));
            echo CHtml::link($addText, $addUrl, array(
                'class' => 'btn btn-primary btn-lg'
            ));
        }
        
        $this->widget('booster.widgets.TbButton', array(
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

        echo '<ul class="dropdown-menu" role="menu" style="display:none;" id="dropdown_' . $data['id'] . '">';
        // View Professor
        if(!empty($data['professor_id'])){
            $addText = '<i class="fa fa-user"></i> ข้อมูลอาจารย์ / Professor';
            $addUrl = Yii::app()->createUrl('scholar/readonly', array(
                'id' => $data['id'],
                'scholartype' => strtolower($data['type']),
                'utype' => 'professor',
                'pid' => $data['professor_id'],
            ));
            echo '<li>';
            echo CHtml::link($addText, $addUrl, array(
                'class' => 'btn',
                'style' => 'float: left;',
            ));
            echo '</li>';
			
			$addText = '<i class="fa fa-lock"></i> [Login แทน] อาจารย์ / Professor';
            $addUrl = Yii::app()->createUrl('/site/verifytoken', array(
                'token' => $data['pro_token'],
                'scholartype' => strtolower($data['type'])
            ));
            echo '<li>';
            echo CHtml::link($addText, $addUrl, array(
                'class' => 'btn',
                'style' => 'float: left;',
            ));
            echo '</li>';
			
        }
        // View Mentor
        if(!empty($data['mentor_id'])){
            $addText = '<i class="fa fa-user"></i> ข้อมูลนักวิจัย /Mentor';
            $addUrl = Yii::app()->createUrl('scholar/readonly', array(
                'id' => $data['id'],
                'scholartype' => strtolower($data['type']),
                'utype' => 'mentor',
                'pid' => $data['mentor_id'],
            ));
			
            echo '<li>';
            echo CHtml::link($addText, $addUrl, array(
                'class' => 'btn',
                'style' => 'float: left;',
            ));
            echo '</li>';
			
			$addText = '<i class="fa fa-lock"></i> [Login แทน] นักวิจัย/Mentor';
            $addUrl = Yii::app()->createUrl('/site/verifytoken', array(
                'token' => $data['men_token'],
                'scholartype' => strtolower($data['type'])
            ));
			
            echo '<li>';
            echo CHtml::link($addText, $addUrl, array(
                'class' => 'btn',
                'style' => 'float: left;',
            ));
            echo '</li>';
			
        }
        
        if($data['status'] != 'draft'){
            // View Student
            if(!empty($data['student_id'])){
                $addText = '<i class="fa fa-graduation-cap"></i> ข้อมูลนักเรียน/นักศึกษา / Student';
                $addUrl = Yii::app()->createUrl('scholar/readonly', array(
                    'id' => $data['id'],
                    'scholartype' => strtolower($data['type']),
                    'utype' => 'student',
                    'pid' => $data['student_id'],
                ));
				
                echo '<li>';
                echo CHtml::link($addText, $addUrl, array(
                    'class' => 'btn',
                    'style' => 'float: left;',
                ));
                echo '</li>';
				
				$addText = '<i class="fa fa-lock"></i> [Login แทน] นักเรียน/นักศึกษา / Student';
				$addUrl = Yii::app()->createUrl('/site/verifytoken', array(
					'token' => $data['std_token'],
					'scholartype' => strtolower($data['type'])
				));
				
				echo '<li>';
				echo CHtml::link($addText, $addUrl, array(
					'class' => 'btn',
					'style' => 'float: left;',
				));
				echo '</li>';
			
            }
            // View Industrial
            if(!empty($data['industrial_id'])){
                $addText = '<i class="fa fa-university"></i> ข้อมูลบริษัท/ภาคอุตสาหกรรม / Industrial';
                $addUrl = Yii::app()->createUrl('scholar/readonly', array(
                    'id' => $data['id'],
                    'scholartype' => strtolower($data['type']),
                    'utype' => 'industrial',
                    'pid' => $data['industrial_id'],
                ));
				
                echo '<li>';
                echo CHtml::link($addText, $addUrl, array(
                    'class' => 'btn',
                    'style' => 'float: left;',
                ));
                echo '</li>';
				
				$addText = '<i class="fa fa-lock"></i> [Login แทน] บริษัท/ภาคอุตสาหกรรม / Industrial';
				$addUrl = Yii::app()->createUrl('/site/verifytoken', array(
					'token' => $data['ind_token'],
					'scholartype' => strtolower($data['type'])
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
        
		if($data['status'] == 'pending_scholarships'){
			$addText = '<i class="fa fa-envelope"></i> ** สร้างเอกสารแนบใหม่';
            $addUrl = Yii::app()->createUrl('stem/word', array(
                'id' => $data['id']
            ));
            echo '<li>';
			echo CHtml::link($addText, $addUrl, array(
				'class' => 'btn',
				'style' => 'float: left;',
			));
			echo '</li>';
		}
        echo '</ul>';
        
        ?>
    </td>
</tr>
