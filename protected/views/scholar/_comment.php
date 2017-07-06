<?php 
$scholar_type = Yii::app()->session['scholar_type'];
$person_type = Yii::app()->session['person_type']; 
?>

<?php ++$index; ?>
<tr class="<?php echo ($index & 1) ? 'odd' : 'even'; ?> pointer">
    <td class="" style="text-align: center;"><?php echo $index; ?> </td>
    <td class="" style="text-align: center;white-space: nowrap;">
        <?php echo CHtml::encode(date("d-m-Y H:i:s", strtotime($data['firstcreated']))); ?>
    </td>
    <!--<td class="" style="text-align: center;"><?php // echo CHtml::encode($data['type']); ?> </td>-->
    <td class="" style="white-space: nowrap;"><?php echo CHtml::encode($data['student']); ?> </td>
    <td class="" style="text-align: left;">
        <button type="button" class="btn btn-<?php echo InitialData::STATUS_COLOR($data['status_comment']); ?> btn-xs">
            <?php echo InitialData::STATUS($data['status_comment']); ?> / <?php echo InitialData::STATUS($data['status']); ?>
        </button>
    </td>
    <td class="" style="text-align: center;white-space: nowrap;">
        <?php echo CHtml::encode(date("d-m-Y H:i:s", strtotime($data['lastupdated']))); ?>
    </td>
    <td class="last" style="text-align: left;white-space: nowrap;">
        <?php
        echo StatusData::getActionBar($this, $person_type, $data, 'comment');
        
//        if (in_array($data['status'], array(
//                    Yii::app()->params['Status']['Send']
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
//                    Yii::app()->params['Status']['Draft']
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
//                    'class' => 'btn btn-info btn-lg',
//                    'onclick' => 'window.location="' . $addUrl . '"',
//                ),
//            ));
//        }
        ?>
    </td>
</tr>
