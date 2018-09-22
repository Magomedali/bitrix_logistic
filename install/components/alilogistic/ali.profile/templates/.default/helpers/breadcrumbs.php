<?php

use Ali\Logistic\Helpers\Html;
$breadcrumbs = is_array($arResult['breadcrumbs']) && count($arResult['breadcrumbs']) ? $arResult['breadcrumbs'] : null;

$pageTitle = isset($arResult['pageTitle'])? $arResult['pageTitle'] : "Мой профиль";

?>

<div class="breacrumbs" style="position: relative;">
	<ol class="breadcrumb" itemscope="" itemtype="https://schema.org/BreadcrumbList">
		<li itemprop="itemListElement" itemscope="" itemtype="https://schema.org/ListItem">	
			<a href="/" title="Главная" itemprop="url">
				<span itemprop="name">Главная<meta itemprop="position" content="0"></span>
			</a>
		</li>
		<?php 
			if($breadcrumbs && count($breadcrumbs)){ 
		?>
			<li itemprop="child" itemscope="" itemtype="https://schema.org/ListItem">
				<a href="/alk/" title="Мой профиль" itemprop="url">
					<span itemprop="name">Мой профиль<meta itemprop="position" content="1"></span>
				</a>
			</li>

			<?php foreach ($breadcrumbs as $br) { 
				if($br['active']){
					$pageTitle = $br['title'];
					?>
						<li class="active" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"><span itemprop="name"><?php echo $br['title'];?><meta itemprop="position" content="1"></span></li>
					<?php
				}else{?>
					<li itemprop="child" itemscope="" itemtype="https://schema.org/ListItem">
						<span itemprop="name">
						<a href="<?php echo $component->getUrl($br['url'])?>"><?php echo $br['title']?></a>
						</span>
					</li>
			<?php }
			} 
			?>
		<?php }else{ 
			?>
			<li itemprop="child" itemscope="" itemtype="https://schema.org/ListItem">
				<span itemprop="name"><?php echo $pageTitle?><meta itemprop="position" content="1"></span>
			</li>
		<?php } ?>
	</ol>
	<h1 class="line-behind text-left emm"><?php echo $pageTitle;?></h1>
	<?php
		echo Html::a('Создать заявку',$component->getUrl("dealform"),['class'=>'btn btn-primary','id'=>'btnNewDeal']);
	?>
</div>