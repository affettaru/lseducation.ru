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
if($_REQUEST["text"]) {$otvet = $_REQUEST["text"];} elseif($_REQUEST["img"]) {$otvet = $_REQUEST["img"];} elseif($_REQUEST["check"]) {$otvet = implode(",", $_REQUEST["check"]);}

if($_REQUEST["SUB"] == "Y") {
	$ATTEMPTS = $arUser["UF_ATTEMPTS_".$_REQUEST["NUM"]]+1; $result['status'] = "confirm"; $result['url']='ELEMENT_ID='.$_REQUEST["NUM"];
} else {
	$ATTEMPTS = $arUser["UF_ATTEMPTS_".$_REQUEST["NUM"]]; $result['status'] = "success"; $result['url']='ELEMENT_ID='.$_REQUEST["NUM"];
}

$res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>2, "ID"=>$_REQUEST["vopros_id"], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y"), false, Array("nPageSize"=>50), Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_*"));
while($ob = $res->GetNextElement()){ 
 $arFields = $ob->GetFields();  
 $arProps = $ob->GetProperties();
	 if($_REQUEST["text"]) {$names = $arProps["vopros_text"]["VALUE"];} elseif($_REQUEST["img"]) {$names = $arProps["vopros_radio"]["VALUE"];} elseif($_REQUEST["check"]) {$names = $arProps["vopros_checkbox"]["VALUE"];}
	 if($names!=""){$answ = $arUser["UF_ANSWER_".$_REQUEST["NUM"]].'   #'.$arFields["ID"].'# '.'Вопрос: '.$names.'  Ответ: '.'('.$otvet.')';}
		
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
			if($arProps["pr_otvet_checkbox"]["~VALUE"]=="Да") {
				$resul= array_diff($arProps["otvet_checkbox"]["~VALUE"],$_REQUEST["check"]);
				if ($resul[0]=="") {
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
				$goit = $arUser["UF_EXT".$_REQUEST["NUM"]]+1; $nogoit = $arUser["UF_NOEXT_".$_REQUEST["NUM"]]; $sdan = $arUser["UF_SDAN_".$_REQUEST["NUM"]];
			} else {
				$nogoit = $arUser["UF_NOEXT".$_REQUEST["NUM"]]+1; $goit = $arUser["UF_EXT_".$_REQUEST["NUM"]]; $sdan = $arUser["UF_SDAN_".$_REQUEST["NUM"]];
			}
}
$num_s = explode("_",$ATTEMPTS);

if($_REQUEST["SUB"] == "Y") {
	$list = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>1, "PROPERTY_NUM_VALUE"=>$_REQUEST["NUM"], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y"), false, Array(), Array("ID","PROPERTY_name","PROPERTY_num"));
	while($el = $list->GetNext()){
	    $name = $el["PROPERTY_NAME_VALUE"];
	}
    $testHTML = '🤖 '.$arUser["NAME"].' '.$arUser["LAST_NAME"] ." \r\n";
    $testHTML .= '📚 '.$name . "\r\n";
    $testHTML .= "🎚 Попыток: ".$num_s[1]."\r\n";
    $testHTML .=   (($goit==$_REQUEST["COUNT"])?"😃":"😔")." Результат теста: " .$goit."/".$_REQUEST["COUNT"]. "\r\n";
    $testHTML .= "👽 Всего попыток: ".$num_s[1]."\r\n";
    $telegram = new  Telegram\Bot\Api('1761560957:AAGKUSXqzEQuaTcu59F8enksIrBlNDhcrqU');
    $response = $telegram->sendMessage(['chat_id' => '-1001155737636','text' =>  $testHTML]);

	$telegram1 = new  Telegram\Bot\Api('6414218348:AAEMfgFWspk5hHtq-XjsNWtbHbeMfpGVyw0');
    $response1 = $telegram1->sendMessage(['chat_id' => '-1074625886','text' =>  $testHTML]);
}

	$user = new CUser;
	$fields = Array(
	"UF_SDAN_".$_REQUEST["NUM"]=>$sdan, 
	"UF_EXT_".$_REQUEST["NUM"]=>$goit,
	"UF_NOEXT_".$_REQUEST["NUM"]=>$nogoit,
	"UF_ANSWER_".$_REQUEST["NUM"] =>$answ,
	"UF_ATTEMPTS_".$_REQUEST["NUM"] => $num_s[1],
	"UF_NO_QUIZ_" => $arUser["UF_NO_QUIZ"],
	); 
	$user->Update($USER->GetID(), $fields);
	$strError .= $user->LAST_ERROR;
	echo json_encode($result);
	?>
