<?
//require_once $_SERVER['DOCUMENT_ROOT'] . '/local/vendor/autoload.php';
//defaults
foreach ($_POST as $key => $val) if ($val == $_POST[$key . '_default_value']) $_POST[$key] = '';
// db for backup
$DB->Query("SET wait_timeout=28800");

CModule::IncludeModule('iblock');
CModule::IncludeModule('catalog');
CModule::IncludeModule('sale');

use Bitrix\Main\Loader;
use Bitrix\Iblock\PropertyEnumerationTable;

//include($_SERVER['DOCUMENT_ROOT']."/local/inc/function.php");

global $cont;
$cont = array();
$iblock = CIBlock::GetList(array(), array("CODE" => 'index_text'))->GetNext();
if ($iblock) {
    $cs = CIBlockElement::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => $iblock["ID"], "ACTIVE" => "Y", "ACTIVE_DATE" => "Y"), false, array(), array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_*"));
    while ($ob = $cs->GetNextElement()) {
        $arFields = $ob->GetFields();
        $arProps = $ob->GetProperties();
        if (is_array($c)) $c = GetIBlockElement($c['ID']);
        if (function_exists("t_item")) $c = t_item($c);
        $can['auth_text'] = $arProps["auth_text"]["~VALUE"]["TEXT"];
        $can['privet'] = $arProps["privet"]["~VALUE"]["TEXT"];
        $cont[] = $can;
    }
}

$dir = explode('/', $APPLICATION->GetCurDir());
global $face;
$face = array();
$iblockFace = CIBlock::GetList(array(), array("CODE" => 'arcticles'))->GetNext();
if ($iblockFace) {
    $facebook = CIBlockElement::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => $iblockFace["ID"], "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", "CODE" => $dir[2]));
    while ($fb = $facebook->GetNext()) {
        $fb = GetIBlockElement($fb['ID']);
        $fb["URL"] = $dir;
        $fb["TITLE"] = $fb["NAME"];
        $fb["DESCRIPTION"] = $fb["DETAIL_TEXT"];
        $fb["IMG"] = $_SERVER["HTTP_HOST"] . '' . crop($fb["DETAIL_PICTURE"], "250", "250", 1);
        $face[] = $fb;
    }
}
global $news;
$news = array();
$iblockNews = CIBlock::GetList(array(), array("CODE" => 'news'))->GetNext();
if ($iblockNews) {
    $newsob = CIBlockElement::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => $iblockFace["ID"], "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", "CODE" => $dir[2]));
    while ($nw = $newsob->GetNext()) {
        $nw = GetIBlockElement($nw['ID']);
        $nw["URL"] = $dir;
        $nw["TITLE"] = $nw["NAME"];
        $nw["DESCRIPTION"] = $nw["DETAIL_TEXT"];
        $nw["IMG"] = $_SERVER["HTTP_HOST"] . '' . crop($nw["DETAIL_PICTURE"], "250", "250", 1);
        $news[] = $nw;
    }
}

// Уберем дурацкое quot из названия
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "renameQuot");
AddEventHandler("iblock", "OnBeforeIBlockElementAdd", "renameQuot");
// нам дано сделать адскую жесть - собрать все свойства в одно и привесить это все элементу
function renameQuot(&$arFields)
{
    if ($arFields['NAME'] != "") $arFields['NAME'] = unhtmlentities($arFields['NAME']);
}


AddEventHandler("main", "OnAfterUserAdd", "OnAfterUserRegisterHandler");
AddEventHandler("main", "OnAfterUserRegister", "OnAfterUserRegisterHandler");
function OnAfterUserRegisterHandler(&$arFields)
{
    if (intval($arFields["ID"]) > 0) {
        $toSend = array();
        $toSend["PASSWORD"] = $arFields["CONFIRM_PASSWORD"];
        $toSend["EMAIL"] = $arFields["EMAIL"];
        $toSend["USER_ID"] = $arFields["ID"];
        $toSend["USER_IP"] = $arFields["USER_IP"];
        $toSend["USER_HOST"] = $arFields["USER_HOST"];
        $toSend["LOGIN"] = $arFields["LOGIN"];
        $toSend["NAME"] = (trim($arFields["NAME"]) == "") ? $toSend["NAME"] = htmlspecialchars('<Не указано>') : $arFields["NAME"];
        $toSend["LAST_NAME"] = (trim($arFields["LAST_NAME"]) == "") ? $toSend["LAST_NAME"] = htmlspecialchars('<Не указано>') : $arFields["LAST_NAME"];
        CEvent::SendImmediate("MY_NEW_USER", SITE_ID, $toSend);
    }
    return $arFields;
}


function kurs(&$idUser)
{
    $rsGroups = \CUser::GetUserGroupEx($idUser);
    while ($arGroup = $rsGroups->GetNext()) {
        $exp = explode("T_", $arGroup['STRING_ID']);
        if ($exp[1] != "") {
            $urok[] = $exp[1];
        }
    }
    return $urok;
}

//событие изменения элемента
AddEventHandler("iblock", "OnBeforeIBlockElementAdd", "addLessonNumber");
function addLessonNumber(&$arFields)
{
    if (!function_exists('str_contains')) {
        function str_contains($haystack, $needle)
        {
            return $needle !== '' && mb_strpos($haystack, $needle) !== false;
        }
    }

    if ($arFields['IBLOCK_ID'] == 1) {


        $res = CIBlockSection::GetByID($arFields['IBLOCK_SECTION']['0']);
        if ($ar_res = $res->GetNext()) {
            if (!str_contains(strtolower($arFields['CODE']), strtolower($ar_res['CODE']))) {
                global $APPLICATION;
                $APPLICATION->ThrowException("Название урока должно содержать название курса. Перед номером урока нужен пробел. Например, 'Курс по вышивке урок 1'");
                return false;
            }
        }


        $rsElement = CIBlockElement::GetList(
            array(),
            array(
                "CODE" => str_replace('_UROK', '', $arFields['CODE']),
                'IBLOCK_ID' => 1,
            ),
            false,
            false,
            array("ID")
        );
        if ($arElement = $rsElement->fetch()) {
            if ($arElement['ID'] > 0) {
                global $APPLICATION;
                $APPLICATION->ThrowException("Элемент с CODE '{$arFields['CODE']}' уже существует!");
                return false;
            }
        }


        $arFields['CODE'] = str_replace('_UROK', '', $arFields['CODE']);
        $enum = CIBlockPropertyEnum::Add(array('PROPERTY_ID' => 21, 'VALUE' => $arFields['CODE'], 'XML_ID' => md5($arFields['CODE']), 'SORT' => 2400, 'DEF' => 'N'));

        $arFields['PROPERTY_VALUES']['num'] = $enum;

        $group_id = $arFields['CODE'];

        //попытки
        $attempt = 'UF_ATTEMPTS_' . $group_id;
        addUserProperty($attempt, '2', "string", "Попытки сдачи теста " . $group_id . "й экзамен");
        //dump($id); die();

        //ответы
        $answer = 'UF_ANSWER_' . $group_id;
        addUserProperty($answer, $group_id . '0', "string", "Ответы квиза " . $group_id . "й экзамен");

        //кол-во правильных
        $ext = 'UF_EXT_' . $group_id;
        addUserProperty($ext, $group_id . '0', "string", "Кол-во правильных ответов " . $group_id . "й экзамен");

        //кол-во неправильных
        $noext = 'UF_NOEXT_' . $group_id;
        addUserProperty($noext, $group_id . '0', "string", "Кол-во неправильных ответов " . $group_id . "й экзамен");

        //экзамен сдан
        $sdan = 'UF_SDAN_' . $group_id;
        addUserProperty($sdan, $group_id . '0', "boolean", $group_id . "й Экзамен сдан?");

        //создаем группу
        addGroup($group_id);
    }
}

// //событие изменения элемента
// AddEventHandler("iblock", "OnAfterIBlockElementAdd", "goodProps");
// function goodProps(&$arFields){
// 	if($arFields['IBLOCK_ID']==1){
// 			$enum = CIBlockPropertyEnum::GetList(Array(),Array("IBLOCK_ID"=>1,"PROPERTY_ID"=>21,"ID"=>$arFields["PROPERTY_VALUES"][21][0]["VALUE"]))->GetNext();

// 			$group_id = $enum["VALUE"];

// 			//попытки
// 			$attempt = 'UF_ATTEMPTS_'.$group_id;
// 			addUserProperty($attempt, '2', "string", "Попытки сдачи теста ".$group_id."й экзамен");
// 			//dump($id); die();

// 			//ответы
// 			$answer = 'UF_ANSWER_'.$group_id;
// 			addUserProperty($answer, $group_id.'0', "string", "Ответы квиза ".$group_id."й экзамен");

// 			//кол-во правильных
// 			$ext = 'UF_EXT_'.$group_id;
// 			addUserProperty($ext, $group_id.'0', "string", "Кол-во правильных ответов ".$group_id."й экзамен");

// 			//кол-во неправильных
// 			$noext = 'UF_NOEXT_'.$group_id;
// 			addUserProperty($noext, $group_id.'0', "string", "Кол-во неправильных ответов ".$group_id."й экзамен");

// 			//экзамен сдан
// 			$sdan = 'UF_SDAN_'.$group_id;
// 			addUserProperty($sdan, $group_id.'0', "boolean", $group_id."й Экзамен сдан?");

// 			//создаем группу
// 			addGroup($group_id);
// 	}
// }

//событие удаления элемента
AddEventHandler("iblock", "OnBeforeIBlockElementDelete", "OnIBlockElementDelete");
function OnIBlockElementDelete($ID)
{
    $stacks = CIBlockElement::GetList(array(), array("IBLOCK_ID" => 1, "ID" => $ID), false, false, array("ID", "IBLOCK_ID", "PROPERTY_num"))->GetNext();
    $result = CIBlockPropertyEnum::Delete($stacks['PROPERTY_NUM_ENUM_ID']);

    echo '<pre>';
    print_r($stacks);
    echo '</pre>';
    if ($stacks["ID"]) {

        //удалим группу
        deleteGroup($stacks["PROPERTY_NUM_VALUE"]);

        //удалим свойства
        //попытки
        $attempt = 'UF_ATTEMPTS_' . $stacks["PROPERTY_NUM_VALUE"];
        deleteUserProperty($attempt);

        //ответы
        $answer = 'UF_ANSWER_' . $stacks["PROPERTY_NUM_VALUE"];
        deleteUserProperty($answer);

        //кол-во правильных
        $ext = 'UF_EXT_' . $stacks["PROPERTY_NUM_VALUE"];
        deleteUserProperty($ext);

        //кол-во неправильных
        $noext = 'UF_NOEXT_' . $stacks["PROPERTY_NUM_VALUE"];
        deleteUserProperty($noext);

        //экзамен сдан
        $sdan = 'UF_SDAN_' . $stacks["PROPERTY_NUM_VALUE"];
        deleteUserProperty($sdan);

    }

}


\Bitrix\Main\EventManager::getInstance()->addEventHandlerCompatible(
    'sale',
    'OnSaleComponentOrderProperties',
    'SaleOrderEvents::fillLocation'
);

class SaleOrderEvents
{
    public static function fillLocation(&$arUserResult, $request, &$arParams, &$arResult)
    {
        $registry = \Bitrix\Sale\Registry::getInstance(\Bitrix\Sale\Registry::REGISTRY_TYPE_ORDER);
        $orderClassName = $registry->getOrderClassName();
        $order = $orderClassName::create(\Bitrix\Main\Application::getInstance()->getContext()->getSite());
        $propertyCollection = $order->getPropertyCollection();

        foreach ($propertyCollection as $property) {
            if ($property->isUtil())
                continue;
            $arProperty = $property->getProperty();
            if (
                $arProperty['TYPE'] === 'LOCATION'
                && array_key_exists($arProperty['ID'], $arUserResult["ORDER_PROP"])
                && !$request->getPost("ORDER_PROP_" . $arProperty['ID'])
                && (
                    !is_array($arOrder = $request->getPost("order"))
                    || !$arOrder["ORDER_PROP_" . $arProperty['ID']]
                )
            ) {
                $arUserResult["ORDER_PROP"][$arProperty['ID']] = CURRENT_CITY_CODE;
            }
        }
    }
}

function fsize($path)
{
    $fp = fopen($path, "r");
    $inf = stream_get_meta_data($fp);
    fclose($fp);
    foreach ($inf["wrapper_data"] as $v)
        if (stristr($v, "content-length")) {
            $v = explode(":", $v);
            return trim($v[1]);
        }
}

function formatBytes($size, $precision = 2)
{
    $base = log($size, 1024);
    $suffixes = array('Bytes', 'Kb', 'Mb', 'Gb', 'Tb');
    return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
}

AddEventHandler("main", "OnBeforeUserUpdate", "OnBeforeUserUpdateHandler");

function OnBeforeUserUpdateHandler(&$arFields)
{
    global $USER;
    $USER->GetID();
    $arUser = CUser::GetByID($USER->GetID())->GetNext();
    $ids = kurs($USER->GetID());
    if (is_array($ids)) $stack = array_pop($ids);
    $stacks = CIBlockElement::GetList(array(), array("IBLOCK_ID" => 1, "PROPERTY_NUM_VALUE" => $stack, "ACTIVE_DATE" => "Y", "ACTIVE" => "Y"), false, array("nPageSize" => 50), array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_binding"));
    //Посчитаем кол-во вопросов
    while ($obStack = $stacks->GetNext()) {
        $ob[] = $obStack;
        if ($obStack["PROPERTY_BINDING_VALUE"] != "") {
            $ascount[] = $obStack["PROPERTY_BINDING_VALUE"];
        }
    }
    //Проверим сколько вопросов ответил пользователь и сравним с изначальным кл-ом
    $numOtvet = intval($arFields["UF_EXT_" . $stack]) + intval($arFields["UF_NOEXT_" . $stack]);
    if ($numOtvet >= count($ascount)) {
        if ($arFields["UF_NOEXT" . $stack] > 0 || $arFields["UF_EXT_" . $stack] == "") {
            $arFields["UF_SDAN_" . $stack] = 0;
        } else {
            if ($arFields["UF_EXT_" . $stack] > 0) {
                $arFields["UF_SDAN_" . $stack] = 1;

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
                $testHTML .= "🎚 Попыток: " . (intval($arUser["UF_ATTEMPTS_" . $stack]) + 1) . "\r\n";
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

            } else {
                $arFields["UF_SDAN_" . $stack] = $arFields["UF_SDAN_" . $stack];
            }

        }
    }
}

AddEventHandler("main", "OnAfterUserLogin", "OnAfterUserLoginHandler");

function OnAfterUserLoginHandler(&$arFields)
{

    //$Users = CUser::GetList(($by="personal_country"), ($order="desc"), array("ID"=>$arFields["USER_ID"]))->Fetch();
    $Users = CUser::GetByID($arFields["USER_ID"])->Fetch();

    $arGroupAvalaible = array('1');
    $arGroups = CUser::GetUserGroup($Users["ID"]);
    $result_intersect = array_intersect($arGroupAvalaible, $arGroups);
    if (!$result_intersect && $Users["UF_DATE"] != "1") {
        $date = strtotime(date("d.m.Y H:i:s", strtotime("-1 month")));
        $reg_date = strtotime($Users["DATE_REGISTER"]);

        if ($date > $reg_date) {
            $user = new CUser;
            $user->Update($Users["ID"], array("BLOCKED" => "Y"));
            $user->Logout();
        }
    }

}

?>
