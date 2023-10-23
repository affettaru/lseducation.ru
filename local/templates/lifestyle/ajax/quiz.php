<?include($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");
require $_SERVER['DOCUMENT_ROOT'] . '/local/vendor/autoload.php';?>
<?
//$_REQUEST["vopros_id"] id вопроса
//$_REQUEST["img"] ответ типа радио
//$_REQUEST["text"] текстовый вопрос
//$_REQUEST["check"] // ответ тип чекбокс
//$_REQUEST["quiz_sub"] // отправить на модерацию


	unset($answ);
	unset($ATTEMPTS);
	unset($arUser);
	
	global $USER;
	$USER->GetID();
	$arUser = CUser::GetByID($USER->GetID())->GetNext();

	if (!$_REQUEST["back_id"]){
	if($_REQUEST["text"]) {$otvet = $_REQUEST["text"];} elseif($_REQUEST["img"]) {$otvet = $_REQUEST["img"];} elseif($_REQUEST["check"]) {$otvet = implode("|", $_REQUEST["check"]);}
	
	if($_REQUEST["SUB"] == "Y") {
		$ATTEMPTS = $arUser["UF_ATTEMPTS_".$_REQUEST["NUM"]]+1; $result['status'] = "confirm"; $result['url']='ELEMENT_ID='.$_REQUEST["NUM"];
	} else {
		$ATTEMPTS = $arUser["UF_ATTEMPTS_".$_REQUEST["NUM"]]; $result['status'] = "success"; $result['url']='ELEMENT_ID='.$_REQUEST["NUM"];
	}
	
	$res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>2, "ID"=>$_REQUEST["vopros_id"], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y"), false, false, Array("ID", "IBLOCK_ID", "NAME", 'IBLOCK_SECTION_ID', "DATE_ACTIVE_FROM","PROPERTY_*"));
	while($ob = $res->GetNextElement()){ 
	 $arFields = $ob->GetFields();  
	 $arProps = $ob->GetProperties();
		 if($_REQUEST["text"]) {
			$res = CIBlockSection::GetByID($arFields["IBLOCK_SECTION_ID"]);
			if($ar_res = $res->Fetch()){
				$lesson = $ar_res['NAME'];
			}

			 $names = $arProps["vopros_text"]["VALUE"];

			 $testHTML = '🤖 '.$arUser["NAME"].' '.$arUser["LAST_NAME"] ." \r\n";
			 $testHTML .= '📚 '.$lesson. "\r\n";
			 $testHTML .= "Вопрос: ".$names."\r\n";
			 $testHTML .= "Ответ: ".$_REQUEST["text"]."\r\n";
	 
			 $telegram = new  Telegram\Bot\Api('1761560957:AAGKUSXqzEQuaTcu59F8enksIrBlNDhcrqU');
			 $response = $telegram->sendMessage(['chat_id' => '-1001155737636','text' =>  $testHTML]);
	 
			//  $telegram = new  Telegram\Bot\Api('6456329352:AAFKET0k7RNDcLSYfXE_kUEZUoUyAERMihg');
			//  $response = $telegram->sendMessage(['chat_id' => '-4069968381','text' =>  $testHTML]);
		 } 
		 elseif($_REQUEST["img"]) {
			 $names = $arProps["vopros_radio"]["VALUE"];
		 } 
		 elseif($_REQUEST["check"]) {
			 $names = $arProps["vopros_checkbox"]["VALUE"];
		 }
		 if($names!=""){
			 $answ = $arUser["UF_ANSWER_".$_REQUEST["NUM"]].'   #'.$arFields["ID"].'# '.'Вопрос: '.$names.'  Ответ: '.'('.$otvet.')';
			 $save_answ = $arUser['UF_SAVE_ANSWERS'].$arFields["ID"].'|'.$otvet.'#';
		 }
			
		if($_REQUEST["img"]) {
			if($arProps["pr_otvet_radio"]["~VALUE"]=="Да") {
				if($arProps["otvet_radio"]["~VALUE"]==$_REQUEST["img"]) {
					$modradio = 1;
				} else {
					$nomodradio = 1;
				} 
			} else {
				$modradio = 1;
			}
		} elseif($_REQUEST["check"]) {
			if($arProps["pr_otvet_checkbox"]["VALUE"]=="Да") {
				$resul= array_diff($arProps["otvet_checkbox"]["~VALUE"],$_REQUEST["check"]);
				if (!$resul) {
					$modradio = 1;
				} else {
					$nomodradio = 1;
				}
			} else {
					$modradio = 1;
			}
		} else {
				$modradio = 1;
		}
				if($modradio==1) {
					$goit = $arUser["UF_EXT_".$_REQUEST["NUM"]]+1; $nogoit = $arUser["UF_NOEXT_".$_REQUEST["NUM"]]; $sdan = $arUser["UF_SDAN_".$_REQUEST["NUM"]];
				} else {
					$nogoit = $arUser["UF_NOEXT_".$_REQUEST["NUM"]]+1; $goit = $arUser["UF_EXT_".$_REQUEST["NUM"]]; $sdan = $arUser["UF_SDAN_".$_REQUEST["NUM"]];
				}
	}
	$num_s = explode("_",$ATTEMPTS);
	if($_REQUEST["SUB"] == "Y"){
		$save_answ = ' ';
	}
	
	
	if($_REQUEST["SUB"] == "Y") {
		$list = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>1, "PROPERTY_NUM_VALUE"=>$_REQUEST["NUM"], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y"), false, Array(), Array("ID","IBLOCK_ID","PROPERTY_name","PROPERTY_num"));
		while($ob = $list->GetNextElement()){
			$el = $ob->GetFields();
			$arProps = $ob->GetProperties([],["code"=>"binding"]);
			$binding = count($arProps["binding"]["VALUE"]);
			$name = $el["PROPERTY_NAME_VALUE"];
		}
		$testHTML = '🤖 '.$arUser["NAME"].' '.$arUser["LAST_NAME"] ." \r\n";
		$testHTML .= '📚 '.$name . "\r\n";
		$testHTML .= "🎚 Попыток: ".$arUser["UF_TRY"]."\r\n";
		$testHTML .=   (($goit==$binding)?"😃":"😔")." Результат теста: " .$goit."/".$binding. "\r\n";
		$testHTML .= "👽 Всего неверных попыток: ".$arUser["UF_TRY_ALL"]."\r\n";

		// $telegram = new  Telegram\Bot\Api('1761560957:AAGKUSXqzEQuaTcu59F8enksIrBlNDhcrqU');
		// $response = $telegram->sendMessage(['chat_id' => '-1001155737636','text' =>  $testHTML]);

		$telegram = new  Telegram\Bot\Api('6456329352:AAFKET0k7RNDcLSYfXE_kUEZUoUyAERMihg');
		$response = $telegram->sendMessage(['chat_id' => '-4069968381','text' =>  $testHTML]);

	}
	
	
		$user = new CUser;
		$fields = Array(
		"UF_SDAN_".$_REQUEST["NUM"]=>$sdan, 
		"UF_EXT_".$_REQUEST["NUM"]=>$goit,
		"UF_NOEXT_".$_REQUEST["NUM"]=>$nogoit,
		"UF_ANSWER_".$_REQUEST["NUM"] =>trim($answ),
		"UF_ATTEMPTS_".$_REQUEST["NUM"] => $num_s[1],
		"UF_NO_QUIZ" => $arUser["UF_NO_QUIZ"],
		"UF_SAVE_ANSWERS" => $save_answ,
		); 
		$user->Update($USER->GetID(), $fields);
		$strError .= $user->LAST_ERROR;
		echo json_encode($result);
	
} else {

	if ($arUser["UF_EXT_".$_REQUEST["count_id"]]>1){
		$answerWright = $arUser["UF_EXT_".$_REQUEST["count_id"]] - 1;
	} else {
		$answerWright = '';
	}

	if ($arUser["UF_NOEXT_".$_REQUEST["count_id"]]>1){
		$answerWrong = $arUser["UF_NOEXT_".$_REQUEST["count_id"]] - 1;
	} else {
		$answerWrong = '';
	}

	$answer = $arUser["UF_ANSWER_".$_REQUEST["count_id"]];

	$string = $answer;
	$char = "#".$_REQUEST["back_id"]."#";
	$last_pos = strrpos($string, $char);
	$new_string = substr($string, 0, $last_pos);

	$pattern = '/#'.$_REQUEST["back_id"].'#.*?\)/';
	preg_match($pattern, $string, $matches);
	$result['last_answer'] = $matches[0];

	$user = new CUser;
	$fields = Array(
	"UF_EXT_".$_REQUEST["count_id"]=>$answerWright,
	"UF_NOEXT_".$_REQUEST["count_id"]=>$answerWrong,
	"UF_ANSWER_".$_REQUEST["count_id"] =>trim($new_string),
	); 
	$user->Update($USER->GetID(), $fields);
	$strError .= $user->LAST_ERROR;
	$result['status'] = "success"; 
	$result['url']='ELEMENT_ID='.$_REQUEST["count_id"];
	echo json_encode($result);
}

	?>
