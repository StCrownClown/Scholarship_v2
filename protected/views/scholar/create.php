<?php
$this->breadcrumbs=array(
	'Scholars'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Scholar', 'url'=>array('index')),
	array('label'=>'Manage Scholar', 'url'=>array('admin')),
);
?>

<h1>Create Scholar</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>