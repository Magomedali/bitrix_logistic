<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Ali\Logistic\Dictionary\DealFileType;
use Ali\Logistic\Helpers\Html;

$contract_nds = is_array($arResult['contract_nds']) && count($arResult['contract_nds']) ? $arResult['contract_nds'] : null;
$contract_nonds = is_array($arResult['contract_nonds']) && count($arResult['contract_nonds']) ? $arResult['contract_nonds'] : null;

$file_path = isset($arResult['path'])  ? $arResult['path'] : ALI_CONTRACT_PATH;
?>

<?php $this->getComponent()->includeComponentTemplate("helpers/breadcrumbs"); ?>
<div id="alilogistic" class="row docs-page">
	<div class="col-md-12 docs">
		<?php 
			if($contract_nds && isset($contract_nds['VALUE']) && file_exists($file_path.$contract_nds['VALUE'])){
				?>
				<div>
					<?php echo Html::a("Скачать типовой договор с НДС (в формате Word)",$component->getActionUrl("docs",['d'=>$contract_nds['ID']]),['target'=>"_blank"]); ?>
				</div>
			<?php }
			
			if($contract_nonds && isset($contract_nonds['VALUE']) && file_exists($file_path.$contract_nonds['VALUE'])){
				?>
				<div>
					<?php echo Html::a("Скачать типовой договор без НДС (в формате Word)",$component->getActionUrl("docs",['d'=>$contract_nonds['ID']]),['target'=>"_blank"]); ?>
				</div>
			<?php 
			}						
		?>
	</div>
</div>