<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Ali\Logistic\Helpers\Html;
use Ali\Logistic\Dictionary\DealStates;
use Ali\Logistic\Dictionary\DealFileType;


$total = isset($arResult['total']) ? $arResult['total'] : 0;
$page = isset($arResult['page']) ? $arResult['page'] : 1;
$limit = isset($arResult['limit']) ? $arResult['limit'] : 20;
$pageName = isset($arResult['pageName']) ? $arResult['pageName'] : null;
?>


			<div class="pagination-block">
				<?php if($pageName && $page && $total && $total > $limit){
					$pageCounts = ($total % $limit) + 1;
					?>
					<ul class="pagination">
						<?php if($page > 1){
							$filters['page']=$page - 1;
						?>
							<li><?php echo Html::a("&laquo;", $page != $i ? $component->getUrl('deals',$filters):null);?></li>
						<?php } ?>
						
						<?php
							for ($i=1; $i <= $pageCounts; $i++) { 
								$filters['page']=$i;
						?>
								<li class="<?php echo $page == $i ? 'active' : ''?>"><?php echo Html::a($i, $page != $i ? $component->getUrl('deals',$filters):null);?></li>
								<?php
							}
						?>

						<?php if($page < $pageCounts){
							$filters['page']=$page + 1;
						?>
							<li><?php echo Html::a("&raquo;", $page != $pageCounts ? $component->getUrl('deals',$filters):null);?></li>
						<?php } ?>
					</ul>
				<?php }?>
			</div>
