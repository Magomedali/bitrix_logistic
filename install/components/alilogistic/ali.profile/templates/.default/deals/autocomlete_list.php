<?php

$list = isset($arResult['list']) && is_array($arResult['list']) ? $arResult['list'] : array();
?>

<?php if(count($list)){?>
	<ul>
		<?php foreach ($list as $item) {?>
		<li><?php echo $item['ORGANISATION_DISTINCT'] ?></li>
		<?php } ?>
	</ul>
<?php } ?>