<?php $scholar_type = Yii::app()->session['scholar_type']; ?>
<?php ++$index; ?>
<tr class="<?php echo ($index & 1) ? 'odd' : 'even'; ?> pointer">
    <td class="" style="text-align: center;"><?php echo $index; ?> </td>
    <td class="" style="white-space: nowrap;"><?php echo CHtml::encode($data['education']); ?> </td>
    <td class="" style="white-space: nowrap;"><?php echo CHtml::encode($data['name']); ?> </td>
    <td class="" style="text-align: center;white-space: nowrap;">
        <?php echo CHtml::encode(date("d/m/Y", strtotime($data['begin']))); ?>
    </td>
    <?php if (empty(Yii::app()->session['tmpReadOnly'])) { ?>
    <td class="last" style="text-align: left;white-space: nowrap;">
        <!--Detail-->
        <?php
        $addText = '<i class="fa fa-search-plus"></i>';
        $addUrl = Yii::app()->createUrl('nuirc/viewhistory', array(
            'id' => $data['id']
        ));
        echo CHtml::link($addText, $addUrl, array(
            'class' => 'btn btn-primary'
        ));
        ?>
        <!--Edit-->
        <?php
        $addText = '<i class="fa fa-pencil"></i>';
        $addUrl = Yii::app()->createUrl('nuirc/edithistory', array(
            'id' => $data['id']
        ));
        echo CHtml::link($addText, $addUrl, array(
            'class' => 'btn btn-info'
        ));
        ?>
        <!--Delete-->
        <?php
        $addText = '<i class="fa fa-trash-o"></i>';
        $addUrl = Yii::app()->createUrl('nuirc/deletehistory', array(
            'id' => $data['id']
        ));
        echo CHtml::link($addText, $addUrl, array(
            'class' => 'btn btn-danger',
            'onclick' => 'js:return confirm("ยืนยันการลบข้อมูล / Confirm to delete ?");'
        ));

        ?>
    </td>
    <?php } ?>
</tr>
