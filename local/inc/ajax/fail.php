<?include($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");
	global $USER;
	$USER->GetID();
	$kurs = kurs($USER->GetID());
	$stack = array_pop($kurs);
	$arUser = CUser::GetByID($USER->GetID())->GetNext();

	$user = new CUser;
	$fields = Array(
	"UF_SDAN_".$stack =>$arUser["UF_SDAN_".$stack], 
	"UF_EXT_".$stack =>"",
	"UF_NOEXT_".$stack =>"",
	"UF_ANSWER_".$stack =>"",
	"UF_ATTEMPTS_".$stack =>$arUser["UF_ATTEMPTS_".$stack],
	"UF_NO_QUIZ" => $arUser["UF_NO_QUIZ"],
	); 
	$user->Update($USER->GetID(), $fields);
	$strError .= $user->LAST_ERROR;
?>