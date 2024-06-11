<?include($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");
if($_REQUEST["no_quiz"]=="Y"){
	global $USER;
	$USER->GetID();
	$kurs = kurs($USER->GetID());
	if(is_array($kurs))$stack = array_pop($kurs);
	$arUser = CUser::GetByID($USER->GetID())->GetNext();

	$user = new CUser;
	$fields = Array(
	"UF_SDAN_".$stack =>$arUser["UF_SDAN_".$stack], 
	"UF_EXT_".$stack =>$arUser["UF_EXT_".$stack],
	"UF_NOEXT_".$stack =>$arUser["UF_NOEXT_".$stack],
	"UF_ANSWER_".$stack =>$arUser["UF_ANSWER_".$stack],
	"UF_ATTEMPTS_".$stack =>$arUser["UF_ATTEMPTS_".$stack],
	"UF_NO_QUIZ" => 1,
	); 
	$user->Update($USER->GetID(), $fields);
	$strError .= $user->LAST_ERROR;
}
?>
