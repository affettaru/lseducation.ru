<? include($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
global $USER;
$USER->GetID();
$arGroups = CUser::GetUserGroup($USER->GetID());
$rsGroups = \CUser::GetUserGroupEx($USER->GetID());
$arUser = CUser::GetByID($USER->GetID())->GetNext();

$kurs = kurs($USER->GetID());
if (is_array($kurs)) $stack = array_pop($kurs);


if ($arUser["UF_SECTION"] == '15') {
    $code = 'T_OZON';
} elseif ($arUser["UF_SECTION"] == '16') {
    $code = 'T_WILDBERRIES';
} elseif ($arUser["UF_SECTION"] == '20') {
    $code = 'T_YANDEX';
}

$exp = [];
while ($arGroup = $rsGroups->GetNext()) {
    if (!preg_match("/T_\D+/", $arGroup['STRING_ID'], $matches)) continue;
    if (!$code) $code = $matches[0];

    $testNum = intval(preg_replace("/T_\D+?_/", "", $arGroup['STRING_ID']));
    $exp = explode($code, $arGroup['STRING_ID']);
    if ($testNum) {
        $kods = $testNum;
    } else {
        $kods = 1;
    }
}


$user = new CUser;
$fields = array(
    "UF_SDAN_" . $stack => $arUser["UF_SDAN_" . $stack],
    "UF_TRY" => $arUser["UF_TRY"] + 1,
);
$user->Update($USER->GetID(), $fields);

$code .= "_";
$kod = $kods + 1;
$implode_kod = $code . $kod;

$rsG = CGroup::GetList($by = "c_sort", $order = "asc", array("STRING_ID" => $implode_kod));
while ($group = $rsG->GetNext()) {
    $Groups = $group["ID"];
}
$asGroups[] = $Groups;

$asGroups[] = $Groups;
$newGroup = array_merge($arGroups, $asGroups);
file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/test.txt", json_encode($newGroup));
CUser::SetUserGroup($USER->GetID(), $newGroup);
?>