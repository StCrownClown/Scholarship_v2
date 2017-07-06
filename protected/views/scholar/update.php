<?php
$this->breadcrumbs=array(
	'Scholars'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Scholar', 'url'=>array('index')),
	array('label'=>'Create Scholar', 'url'=>array('create')),
	array('label'=>'View Scholar', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Scholar', 'url'=>array('admin')),
);
?>

<h1>Update Scholar <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>