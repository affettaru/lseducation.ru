<?include($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");
	global $USER;
	$USER->GetID();
	$arGroups = CUser::GetUserGroup($USER->GetID());
	$rsGroups = \CUser::GetUserGroupEx($USER->GetID());
	$arUser = CUser::GetByID($USER->GetID())->GetNext();
	if($arUser["UF_SECTION"]=='15'){
		$code = 'T_OZON_';
	}
	elseif($arUser["UF_SECTION"]=='16'){
		$code = 'T_WILDBERRIES_';
	}
	elseif($arUser["UF_SECTION"]=='20'){
		$code = 'T_YANDEX_';
	}
	while($arGroup = $rsGroups->GetNext()) {
		$exp = explode($code, $arGroup['STRING_ID']);
		if($exp[1]!="") {
			$kods = $exp[1];
		}	   
	}
	$kod = $kods+1;
	$implode_kod = $code.$kod;

   $rsG = CGroup::GetList ($by = "c_sort", $order = "asc", Array ("STRING_ID" => $implode_kod));
   while($group = $rsG->GetNext()) {
   	 $Groups = $group["ID"];
   }
	$asGroups[] = $Groups;

	$asGroups[] = $Groups;
	$newGroup = array_merge($arGroups,$asGroups);
	CUser::SetUserGroup($USER->GetID(), $newGroup);
?>