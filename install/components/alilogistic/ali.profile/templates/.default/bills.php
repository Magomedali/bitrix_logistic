<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Ali\Logistic\Dictionary\DealFileType;
use Ali\Logistic\Helpers\Html;

$files = is_array($arResult['files']) && count($arResult['files']) ? $arResult['files'] : null;
$type = isset($arResult['type']) ? $arResult['type'] : null;

$file_path = DealFileType::getFilePath($type);
?>


<div class="organisations-page">
	<div class="organisations">
		<table class="table table-bordered  table-hover">
			<thead>
				<tr>
					<th>Дата</th>
					<th>Номер</th>
					<th>Файл</th>
				</tr>
			</thead>
			<tbody>
				<?php
					if($files){
						foreach ($files as $f) {
							?>

							<tr>
								<td><?php echo date("d.m.Y",strtotime($f['FILE_DATE']));?></td>
								<td><?php echo $f['FILE_NUMBER'];?></td>
								<td>
									<?php 
										if(file_exists($file_path.$f['FILE'])){
											echo Html::a("Посмотреть",$component->getActionUrl("downloadFile",['f'=>$f['ID']]),['target'=>"_blank"]);
										}else{
											echo "Файл не найден";
										}
										
									?>
								</td>
							</tr>

							<?php
						}
					}
				?>
			</tbody>
			<tfoot>
				
			</tfoot>
		</table>
	</div>
</div>