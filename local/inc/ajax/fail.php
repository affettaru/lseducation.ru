<? include($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
global $USER;
$USER->GetID();
$kurs = kurs($USER->GetID());
if (is_array($kurs)) $stack = array_pop($kurs);
$arUser = CUser::GetByID($USER->GetID())->GetNext();

$user = new CUser;
$fields = array(
    "UF_SDAN_" . $stack => $arUser["UF_SDAN_" . $stack],
    "UF_EXT_" . $stack => "",
    "UF_NOEXT_" . $stack => "",
    "UF_ANSWER_" . "",
    "UF_ATTEMPTS_" . $stack => $arUser["UF_ATTEMPTS_" . $stack] + 1,
    "UF_NO_QUIZ" => $arUser["UF_NO_QUIZ"],
    "UF_TRY_ALL" => $arUser["UF_TRY_ALL"] + 1,
    "UF_TRY" => $arUser["UF_TRY"] + 1,
    "UF_ANSWER_" . $stack => "",
);
$user->Update($USER->GetID(), $fields);


//LSTESTBOT
$ids = kurs($USER->GetID());
if (is_array($ids)) $stack = array_pop($ids);
$rsUser = CUser::GetByID($USER->GetID());
$arUser = $rsUser->Fetch();

$SECTION_ID = ($arUser["UF_SECTION"] ? $arUser["UF_SECTION"] : '15');

if ($_REQUEST["ELEMENT_ID"] == "") {
    $idv = $stack;
} else {
    if ($stack >= $_REQUEST["ELEMENT_ID"]) {
        $idv = $_REQUEST["ELEMENT_ID"];
    } else {
        $idv = $stack;
    }
}
$list = CIBlockElement::GetList(array(), array("IBLOCK_ID" => 1, "PROPERTY_NUM_VALUE" => $idv, "ACTIVE_DATE" => "Y", "ACTIVE" => "Y"), false, array(), array("ID", "PROPERTY_binding", "PROPERTY_num", "NAME"));
while ($el = $list->GetNext()) {
    $id = $el["ID"];
    $num = $el["PROPERTY_NUM_VALUE"];
    if ($el["PROPERTY_BINDING_VALUE"] != "") {
        $BINDING[] = $el["PROPERTY_BINDING_VALUE"];
    }
}


$list = CIBlockElement::GetList(array(), array("IBLOCK_ID" => 1, "PROPERTY_NUM_VALUE" => $num, "ACTIVE_DATE" => "Y", "ACTIVE" => "Y"), false, array(), array("ID", "IBLOCK_ID", "PROPERTY_name", "PROPERTY_num", "IBLOCK_SECTION_ID"));
while ($ob = $list->GetNextElement()) {
    $el = $ob->GetFields();
    $arProps = $ob->GetProperties([], ["code" => "binding"]);
    $binding = count($arProps["binding"]["VALUE"]);
    $name = $el["PROPERTY_NAME_VALUE"];
    $section_id = $el["IBLOCK_SECTION_ID"];
}


$goit = $arUser["UF_EXT_" . $num] + 1;
$testHTML = '🤖 ' . $arUser["NAME"] . ' ' . $arUser["LAST_NAME"] . " \r\n";
$testHTML .= '📚 ' . $name . "\r\n";
$testHTML .= "🎚 Попыток: " . (intval($arUser['UF_TRY'])) . "\r\n";
$testHTML .= (($goit == $binding) ? "😃" : "😔") . " Результат теста: " . $goit . "/" . $binding . "\r\n";
$testHTML .= "👽 Всего неверных попыток: " . (intval($arUser["UF_TRY_ALL"])) . "\r\n";
$testHTML = urlencode($testHTML);


$section_list = CIBlockSection::GetList(array(), array('IBLOCK_ID' => 1, 'ID' => $section_id), false, array('UF_*'));
if ($get_fields_item = $section_list->GetNext()) {
    $chat_id = $get_fields_item['UF_TG'];
    $bot_id = $get_fields_item['UF_BOT'];
}

if (!$chat_id) {
    $chat_id = '-1001155737636';
}

if (!$bot_id) {
    $bot_id = '1761560957:AAGKUSXqzEQuaTcu59F8enksIrBlNDhcrqU';
}

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.telegram.org/bot'.$bot_id.'/sendmessage?chat_id='.$chat_id.'&text=' . $testHTML,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
));
$response = curl_exec($curl);
curl_close($curl);

//LSTESTBOT

?>