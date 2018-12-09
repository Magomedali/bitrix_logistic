<?php

$list = isset($arResult['list']) && is_array($arResult['list']) ? $arResult['list'] : array();

?>

<?php if(count($list)){?>
	<ul>
		<?php foreach ($list as $item) {?>
		<li data-phone='<?php echo $item['PHONE']?>' data-comment='<?php echo $item['COMMENT']?>'><?php echo $item['PERSON']; ?></li>
		<?php } ?>
	</ul>
<?php } ?>
