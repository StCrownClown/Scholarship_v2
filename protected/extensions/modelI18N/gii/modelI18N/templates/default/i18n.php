<?php echo "<?php\n"; ?>
return array(
	<?php foreach($labels as $name=>$label): ?>
			<?php echo "'$name' => '$name',\n"; ?>
	<?php endforeach; ?>
);
