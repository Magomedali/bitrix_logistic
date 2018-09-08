<?php require("top.php"); ?>

<?php

use \Bitrix\Main\Application;
use Ali\Logistic\Schemas\ContractorsSchemaTable;
use Ali\Logistic\Helpers\Html;
use Ali\Logistic\Helpers\ArrayHelper;
use Bitrix\Main\UserTable;
use Bitrix\Main\Result;
use Bitrix\Main\Error;
use Ali\Logistic\User;
use Ali\Logistic\Companies;


$context = Application::getInstance()->getContext();
$request = $context->getRequest();
$title = "Настройка свободных контрагентов";


$APPLICATION->SetTitle($title);
// $self_url = $_SERVER['HTTP_REFERER'];
$self_url = "/bitrix/admin/ali.logistic_settingscustomers.php";

if($request->isAjaxRequest()){
	
	$s = false;
	$e = "";
	if(isset($request['inn'])){
		$c = ContractorsSchemaTable::getList([
			'select'=>['*'],
			'filter'=>['=INN'=>$request['inn'],'=COMPANY_ID'=>0]
		])->fetchAll();

		$APPLICATION->RestartBuffer();
		echo json_encode([
			'results'=>$c,
		]); 
		exit;
	}

	if(isset($request['email'])){
		$u = UserTable::getList([
			'select'=>['*'],
			'filter'=>['EMAIL'=>$request['email'].'%']
		])->fetchAll();

		$APPLICATION->RestartBuffer();
		echo json_encode([
			'users'=>$u,
		]); 
		exit;
	}

	$APPLICATION->RestartBuffer();
	echo json_encode(
		[
			'result'=>"Не правильный запрос!"
		]
	);
	exit;
}

$result = new Result;
$errors = array();
$success = array();
if(isset($request['torel']) && isset($request['C_ID']) && (int)$request['C_ID'] && isset($request['U_ID']) && (int)$request['U_ID']){

	$user_id = (int)$request['U_ID'];
	$c_id = (int)$request['C_ID'];

	$contractor = ContractorsSchemaTable::getRowById($c_id);
	if(!isset($contractor['ID'])){
		$result->addError(new Error("Контрагент не найден",404));
	}

	$user = UserTable::getRowById($user_id);
	if(!isset($user['ID'])){
		$result->addError(new Error("Пользователь не найден",404));
	}

	if($result->isSuccess()){

		// 1) Определить компанию пользователя
		$companies = User::getUserCompanies($user_id);
		$company_id = null;
		if(is_array($companies) && count($companies)){
			$company_id = reset($companies);
		}else{
			// 2) Если нет компании, то создать его
			$company_id = Companies::createCompanyForUser($user_id);
		}
		if(!$company_id){
			$result->addError(new Error("Не удалось создать группу для контрагентов пользователя!",404));
		}else{
			// 3) Прикрепить компанию к контрагенту
			$result = ContractorsSchemaTable::update($c_id,['COMPANY_ID'=>$company_id]);
		}
		
		if($result->isSuccess()){
			$success = ['Контрагент '.$contractor['NAME']." привязан к пользователю с e-mail ".$user['EMAIL']];
		}else{
			$errors = $result->getErrorMessages();
		}
		
	}else{
		$errors = $result->getErrorMessages();
	}
}


?>
<?php if($errors){?>
	<div class="row">
		<div class="col-xs-6">
			<?php foreach ($errors as $key => $e) { ?>
				<div class="alert alert-warning">
					<div>
						<?php echo $e;?>
					</div>
				</div>
			<?php }?>
		</div>
	</div>
<?php } ?>

<?php if($success){?>
	<div class="row">
		<div class="col-xs-6">
			<?php foreach ($success as $key => $e) { ?>
				<div class="alert alert-success">
					<div>
						<?php echo $e;?>
					</div>
				</div>
			<?php }?>
		</div>
	</div>
<?php } ?>
<div class="row">
	<div class="col-xs-12">
		<form action="" method="POST">
			<div class="row">
				<div class="col-xs-3">
					<label>Поиск контрагентов</label>
					<?php echo Html::input("text",'inn',null,['id'=>'c_inn','class'=>'form-control','placeholder'=>'Введите инн контрагента']);?>
					<div id="result_contractor" class="results_search">
						<ul></ul>
					</div>
				</div>
				<div class="col-xs-3">
					<label>Поиск пользователей(Email)</label>
					<?php echo Html::input("text",'email',null,['id'=>'u_email','class'=>'form-control','placeholder'=>'Введите email пользователя']);?>
					<div id="result_users" class="results_search">
						<ul></ul>
					</div>
				</div>
				<div class="col-xs-3">
					<?php echo Html::hiddenInput("C_ID",null,['id'=>'contractor_id_input']);?>
					<?php echo Html::hiddenInput("U_ID",null,['id'=>'user_id_input']);?>
					<?php echo Html::submitButton("Связать",['name'=>'torel','class'=>'btn btn-success']);?>
				</div>
			</div>
		</form>
	</div>
</div>

<script type="text/javascript">
	$(function(){

		var req_send = 0;
		$("#c_inn").keyup(function(event){

			var inn = $(this).val();
			if(!req_send && inn.length >= 10){
				$.ajax({
					url:'<?php echo $self_url;?>',
					data:{
						inn:inn
					},
					type:"GET",
					dataType:'json',
					beforeSend:function(){
						req_send = 1;
					},
					success:function(r){
						console.log(r);
						var html = '<li class="def">Выберите контрагента из списка</li>';
						if(r.hasOwnProperty('results')){

							if(r.results.length){
								r.results.forEach(function(c,i){
									html+="<li data-id='"+c.ID+"'>"+c.NAME+"</li>";
								});
							}else{
								html = "<li>Контрагентов не обнаружено!</li>";
							}
							
						}else{
							html = "<li>Контрагентов не обнаружено!</li>";
						}
						$("#result_contractor ul").html(html);
					},
					error:function(e){
						console.log(e);
					},
					complete:function(){
						req_send = 0;
					}

				});
			}
		})

		$("#u_email").keyup(function(event){

			var email = $(this).val();
			if(!req_send && email.length >= 3){
				$.ajax({
					url:'<?php echo $self_url;?>',
					data:{
						email:email
					},
					type:"GET",
					dataType:'json',
					beforeSend:function(){
						req_send = 1;
					},
					success:function(r){
						console.log(r);
						var html = '<li class="def">Выберите пользователя из списка</li>';
						if(r.hasOwnProperty("users") && r.users.length){
							r.users.forEach(function(user,i){
								html+="<li data-id='"+user.ID+"'>"+user.NAME+"-"+user.EMAIL+"</li>";
							});
							
						}else{
							html = "<li>Пользователя не обнаружено!</li>";
						}

						$("#result_users ul").html(html);
					},
					error:function(e){
						console.log(e);
					},
					complete:function(){
						req_send = 0;
					}

				});
			}
		})


		$("body").on("click","#result_contractor ul li",function(){
			var id = $(this).data("id");
			if(id){
				$("#result_contractor ul li").removeClass("selected");
				$(this).addClass("selected");
				$("#contractor_id_input").val(id);
			}
		});

		$("body").on("click","#result_users ul li",function(){
			var id = $(this).data("id");
			if(id){
				$("#result_users ul li").removeClass("selected");
				$(this).addClass("selected");
				$("#user_id_input").val(id);
			}
		});

	});
</script>
<? require("bottom.php"); ?>