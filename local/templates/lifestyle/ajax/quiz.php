<? include($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
// require $_SERVER['DOCUMENT_ROOT'] . '/local/vendor/autoload.php';
?>
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

if (!$_REQUEST["back_id"]) {
    if ($_REQUEST["text"]) {
        $otvet = $_REQUEST["text"];
    } elseif ($_REQUEST["img"]) {
        $otvet = $_REQUEST["img"];
    } elseif ($_REQUEST["check"]) {
        $otvet = implode("|", $_REQUEST["check"]);
    }

    if ($_REQUEST["SUB"] == "Y") {
        $ATTEMPTS = $arUser["UF_ATTEMPTS_" . $_REQUEST["NUM"]] + 1;
        $result['status'] = "confirm";
        $result['url'] = 'ELEMENT_ID=' . $_REQUEST["NUM"];
    } else {
        $ATTEMPTS = $arUser["UF_ATTEMPTS_" . $_REQUEST["NUM"]];
        $result['status'] = "success";
        $result['url'] = 'ELEMENT_ID=' . $_REQUEST["NUM"];
    }

    $res = CIBlockElement::GetList(array(), array("IBLOCK_ID" => 2, "ID" => $_REQUEST["vopros_id"], "ACTIVE_DATE" => "Y", "ACTIVE" => "Y"), false, false, array("ID", "IBLOCK_ID", "NAME", 'IBLOCK_SECTION_ID', "DATE_ACTIVE_FROM", "PROPERTY_*"));
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        $arProps = $ob->GetProperties();
        if ($_REQUEST["text"]) {
            $res = CIBlockSection::GetByID($arFields["IBLOCK_SECTION_ID"]);
            if ($ar_res = $res->Fetch()) {
                $lesson = $ar_res['NAME'];
            }

            $names = $arProps["vopros_text"]["VALUE"];

            $testHTML = '🤖 ' . $arUser["NAME"] . ' ' . $arUser["LAST_NAME"] . " \r\n";
            $testHTML .= '📚 ' . $lesson . "\r\n";
            $testHTML .= "🔴Вопрос: " . $names . "\r\n\n";
            $testHTML .= "🟢Ответ: " . $_REQUEST["text"] . "\r\n";

            $section_id = $arUser["UF_SECTION"];
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


            //  $telegram = new  Telegram\Bot\Api('1761560957:AAGKUSXqzEQuaTcu59F8enksIrBlNDhcrqU');
            //  $response = $telegram->sendMessage(['chat_id' => '-1001155737636','text' =>  $testHTML]);

            //LSTESTBOT
            // $telegram = new  Telegram\Bot\Api($bot_id);
            // $response = $telegram->sendMessage(['chat_id' => $chat_id,'text' => $testHTML]);
            //LSTESTBOT
            $testHTML = urlencode($testHTML);
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.telegram.org/bot' . $bot_id . '/sendmessage?chat_id=' . $chat_id . '&text=' . $testHTML,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            $response = curl_exec($curl);
            $resultat = file_get_contents('https://api.telegram.org/bot' . $bot_id . '/sendmessage?chat_id=' . $chat_id . '&text=' . $testHTML);
            curl_close($curl);

            //  $telegram = new  Telegram\Bot\Api('6456329352:AAFKET0k7RNDcLSYfXE_kUEZUoUyAERMihg');
            //  $response = $telegram->sendMessage(['chat_id' => '-4069968381','text' =>  $testHTML]);
        } elseif ($_REQUEST["img"]) {
            $names = $arProps["vopros_radio"]["VALUE"];
        } elseif ($_REQUEST["check"]) {
            $names = $arProps["vopros_checkbox"]["VALUE"];
        }
        if ($names != "") {
            $answ = $arUser["UF_ANSWER_" . $_REQUEST["NUM"]] . '   #' . $arFields["ID"] . '# ' . 'Вопрос: ' . $names . '  Ответ: ' . '(' . $otvet . ')';
            $save_answ = $arUser['UF_SAVE_ANSWERS'] . $arFields["ID"] . '|' . $otvet . '#';
        }

        if ($_REQUEST["img"]) {
            if ($arProps["pr_otvet_radio"]["~VALUE"] == "Да") {
                if ($arProps["otvet_radio"]["~VALUE"] == $_REQUEST["img"]) {
                    $modradio = 1;
                } else {
                    $nomodradio = 1;
                }
            } else {
                $modradio = 1;
            }
        } elseif ($_REQUEST["check"]) {
            if ($arProps["pr_otvet_checkbox"]["VALUE"] == "Да") {
                $resul = array_merge(array_diff($arProps["otvet_checkbox"]["~VALUE"], $_REQUEST["check"]), array_diff($_REQUEST["check"], $arProps["otvet_checkbox"]["~VALUE"]));
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
        if ($modradio == 1) {
            $goit = $arUser["UF_EXT_" . $_REQUEST["NUM"]] + 1;
            $nogoit = $arUser["UF_NOEXT_" . $_REQUEST["NUM"]];
            $sdan = $arUser["UF_SDAN_" . $_REQUEST["NUM"]];
        } else {
            $nogoit = $arUser["UF_NOEXT_" . $_REQUEST["NUM"]] + 1;
            $goit = $arUser["UF_EXT_" . $_REQUEST["NUM"]];
            $sdan = $arUser["UF_SDAN_" . $_REQUEST["NUM"]];
        }
    }
    // $num_s = explode("_",$ATTEMPTS);
    // if($_REQUEST["SUB"] == "Y"){
    // 	$save_answ = ' ';
    // }

    // if ($num_s[1] == '') {
    // 	$num_s[1] = 1;
    // }


    if ($_REQUEST["SUB"] == "Y") {
        $list = CIBlockElement::GetList(array(), array("IBLOCK_ID" => 1, "PROPERTY_NUM_VALUE" => $_REQUEST["NUM"], "ACTIVE_DATE" => "Y", "ACTIVE" => "Y"), false, array(), array("ID", "IBLOCK_ID", "IBLOCK_SECTION_ID", "PROPERTY_name", "PROPERTY_num"));
        while ($ob = $list->GetNextElement()) {
            $el = $ob->GetFields();
            $arProps = $ob->GetProperties([], ["code" => "binding"]);
            $binding = count($arProps["binding"]["VALUE"]);
            $name = $el["PROPERTY_NAME_VALUE"];

        }


        $testHTML = '🤖 ' . $arUser["NAME"] . ' ' . $arUser["LAST_NAME"] . " \r\n";
        $testHTML .= '📚 ' . $name . "\r\n";
        $testHTML .= "🎚 Попыток: " . intval($arUser["UF_TRY"]) . "\r\n";
        $testHTML .= (($goit == $binding) ? "😃" : "😔") . " Результат теста: " . $goit . "/" . $binding . "\r\n";
        $testHTML .= "👽 Всего неверных попыток: " . $arUser["UF_TRY_ALL"] . "\r\n";

        $testHTML .= 'request_sub';

        // $telegram = new  Telegram\Bot\Api($bot_id);
        // $response = $telegram->sendMessage(['chat_id' => $chat_id,'text' => $testHTML]);

        //тестовый бот
        //LSTESTBOT
        // $telegram = new  Telegram\Bot\Api($bot_id);
        // $response = $telegram->sendMessage(['chat_id' => $chat_id,'text' => $testHTML]);
        //LSTESTBOT

    }


    $user = new CUser;


    $fields = array(
        "UF_SDAN_" . $_REQUEST["NUM"] => $sdan,
        "UF_EXT_" . $_REQUEST["NUM"] => $goit,
        "UF_NOEXT_" . $_REQUEST["NUM"] => $nogoit,
        "UF_ANSWER_" . $_REQUEST["NUM"] => trim($answ),
        "UF_ATTEMPTS_" . $_REQUEST["NUM"] => $arUser["UF_ATTEMPTS_" . $_REQUEST["NUM"]],
        "UF_NO_QUIZ" => $arUser["UF_NO_QUIZ"],
        "UF_SAVE_ANSWERS" => $save_answ,
    );
    $user->Update($USER->GetID(), $fields);
    $strError .= $user->LAST_ERROR;
    $result['USER'] = $arUser["UF_ATTEMPTS_" . $_REQUEST["NUM"]];

    $result['reqcheck'] = $_REQUEST["check"];
    $result['otvcheck'] = $arProps["otvet_checkbox"]["~VALUE"];
    echo json_encode($result);

} else {
    // Проверка правильности ответа на предыдущий вопрос
    $lastAnswer = $arUser["UF_ANSWER_" . $_REQUEST["count_id"]];
    $answerPattern = '/#' . $_REQUEST["back_id"] . '#.*?\)/';

    $balancedAnswer = extractBalancedAnswer($_REQUEST["back_id"], $lastAnswer);

    $result['last_answer'] = $balancedAnswer;

    $matchAnswer = extractBalancedContent($balancedAnswer);
    if (!empty($matchAnswer)) {
        $answer = $matchAnswer;
    } else {
        $answer = '';
    }
//    $otvet = explode("|", $answer);
//    if (count($otvet) == 1) {
//        $otvet = $otvet[0];
//    }

    $isCorrect = false;
    $res = CIBlockElement::GetList(array(), array("IBLOCK_ID" => 2, "ID" => $_REQUEST["back_id"], "ACTIVE_DATE" => "Y", "ACTIVE" => "Y"), false, false, array("ID", "IBLOCK_ID", "NAME", 'IBLOCK_SECTION_ID', "DATE_ACTIVE_FROM", "PROPERTY_*"));
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        $arProps = $ob->GetProperties();
        if ($arProps["pr_otvet_radio"]["VALUE"] == "Да") {
            $otvet = $answer;
            $isCorrect = $arProps["otvet_radio"]["~VALUE"] == $otvet;
        } elseif ($arProps["pr_otvet_checkbox"]["VALUE"] == "Да") {
            $otvet = explode("|", $answer);
            $isCorrect = !array_merge(array_diff($arProps["otvet_checkbox"]["~VALUE"], $otvet), array_diff($otvet, $arProps["otvet_checkbox"]["~VALUE"]));
        } else {
            $isCorrect = true;
        }
    }

    if ($isCorrect) {
        $answerRight = max(0, $arUser["UF_EXT_" . $_REQUEST["count_id"]] - 1);
        $answerWrong = $arUser["UF_NOEXT_" . $_REQUEST["count_id"]];
    } else {
        $answerRight = $arUser["UF_EXT_" . $_REQUEST["count_id"]];
        $answerWrong = max(0, $arUser["UF_NOEXT_" . $_REQUEST["count_id"]] - 1);
    }

    // Удаляем ответ из строки
    $lastPos = strrpos($lastAnswer, '#' . $_REQUEST["back_id"] . '#');
    $newAnswer = ($lastPos !== false) ? substr($lastAnswer, 0, $lastPos) : $lastAnswer;

    // Обновляем данные пользователя
    $user = new CUser;
    $fields = [
        "UF_EXT_" . $_REQUEST["count_id"] => $answerRight,
        "UF_NOEXT_" . $_REQUEST["count_id"] => $answerWrong,
        "UF_ANSWER_" . $_REQUEST["count_id"] => trim($newAnswer),
    ];
    $user->Update($USER->GetID(), $fields);

    // Формируем ответ
    $result['status'] = "success";
    $result['url'] = 'ELEMENT_ID=' . $_REQUEST["count_id"];
    $result['USER'] = $arUser;
    $result['isCorrect'] = $isCorrect;
    $result['otvet'] = $otvet;
    $result['ratio'] = $arProps["pr_otvet_radio"]["VALUE"];
    $result['ratioVal'] = $arProps["otvet_radio"]["~VALUE"];
    $result['checkbox'] = $arProps["pr_otvet_checkbox"]["VALUE"];
    $result['checkboxVal'] = $arProps["otvet_checkbox"]["~VALUE"];
    echo json_encode($result);
}

function extractBalancedContent($text) {
    $pattern = '/Ответ:\s*\((.+)\)/';
    if (preg_match($pattern, $text, $matches)) {
        $content = $matches[1];
        $level = 0;
        $result = '';
        for ($i = 0; $i < strlen($content); $i++) {
            if ($content[$i] === '(') {
                $level++;
            } elseif ($content[$i] === ')') {
                $level--;
                if ($level < 0) {
                    break;
                }
            }
            $result .= $content[$i];
        }
        return $result;
    }
    return null;
}
function extractBalancedAnswer($backId, $lastAnswer) {
    $pattern = '/#' . preg_quote($backId, '/') . '#(.*)/';
    if (preg_match($pattern, $lastAnswer, $matches)) {
        $content = $matches[1];
        $level = 0;
        $result = '';
        for ($i = 0; $i < strlen($content); $i++) {
            if ($content[$i] === '(') {
                $level++;
            } elseif ($content[$i] === ')') {
                $level--;
                if ($level < 0) {
                    break;
                }
            }
            $result .= $content[$i];
        }
        return $result;
    }
    return null;
}
?>
