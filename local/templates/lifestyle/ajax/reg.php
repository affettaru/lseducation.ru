<?include($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");?>
<?php
    if(!isset($_REQUEST['checkbox']) && $_REQUEST['checkbox'] !== "Y") {
        $result['status'] = "error";
        $result['message'] = "Необходимо подтвердить согласие с Пользовательским соглашением и Политикой конфиденциальности";
    }
    if($_REQUEST['password'] !== $_REQUEST['confirm_password']){
        $result['status'] = "error";
        $result['message'] = "Пароли не совпадают";
    }
    if($_REQUEST['confirm_password']==""){
        $result['status'] = "error";
        $result['message'] = "Необходимо повторить пароль";
    }
    if($_REQUEST['password']==""){
        $result['status'] = "error";
        $result['message'] = "Заполните пароль";
    }
    if($_REQUEST['name']==""){
        $result['status'] = "error";
        $result['message'] = "Введите имя";
    }

        if(check_email($_REQUEST['mail'])) {
            $_REQUEST['login_name'] = $_REQUEST['mail'];

            $sql = CUser::GetList(($by="id"), ($order="desc"), Array("=EMAIL" => $_REQUEST['mail']));
            if($sql->NavNext(true, "f_"))
            {
                $id_user = $f_ID;
                if($id_user) {
                    $result['status'] = "error";
                    $result['message'] = "Вы уже зарегистрированы";
                }
            }
        } else {
            $result['status'] = "error";
            $result['message'] = "Неверный Email";
        }
        if($_REQUEST['mail']=="") {
            $result['status'] = "error";
            $result['message'] = "Заполните Email";
        }



        if($_REQUEST["courses"]=="15"){
            $sdan = "UF_SDAN_OZON_1";
        }
        elseif($_REQUEST["courses"]=="16"){
            $sdan = "UF_SDAN_WILDBERRIES_1";
        }
        elseif($_REQUEST["courses"]=="20"){
            $sdan = "UF_SDAN_YANDEX_1";
        } elseif ($_REQUEST["courses"]=="") {
            $result['status'] = "error";
            $result['message'] = "Выберите курс";
        } else {

            $arFilter = array('IBLOCK_ID' => 1);
            $arSelect = array('ID', 'NAME', 'CODE');
            $rsSections = CIBlockSection::GetList(array(), $arFilter, false, $arSelect);
            if ($arSection = $rsSections->GetNext()) {
                $sdan = "UF_SDAN_".$arSection['CODE']."_1";
            }

        }

        // echo '<pre>';
        // print_r($_REQUEST);
        // echo '</pre>';

        global $USER;
        if(empty($result['status'])) {
            $user = new CUser;
            $res = $user->add(array("ACTIVE" => "N","NAME" => $_REQUEST['name'], "LAST_NAME" => $_REQUEST['last_name'], "LOGIN" => $_REQUEST['login_name'], "EMAIL" => $_REQUEST['mail'],"PASSWORD" => $_REQUEST['password'], "CONFIRM_PASSWORD" => $_REQUEST['confirm_password'],"UF_SECTION"=>$_REQUEST["courses"], $sdan=>'0'));
            if($res) {
                if($_REQUEST["courses"]=="15"){
                    $arrGroups_new = array(3,4,5); // в какие группы хотим добавить
                }
                elseif($_REQUEST["courses"]=="16"){
                    $arrGroups_new = array(3,4,36); // в какие группы хотим добавить
                }
                elseif($_REQUEST["courses"]=="20"){
                    $arrGroups_new = array(3,4,37); // в какие группы хотим добавить
                } else {

                    $arFilter = array('IBLOCK_ID' => 1, 'SECTION_ID' => $_REQUEST["courses"]);
                    $arSelect = array('ID', 'NAME', 'CODE');
                    $rsEls = CIBlockElement::GetList(array(), $arFilter, false, $arSelect);
                    if ($rsEl = $rsEls->GetNext()) {
                        $Groups = CGroup::GetList(($by="c_sort"), ($order="desc"), array("STRING_ID"=>"T_".$rsEl['CODE']))->NavNext(true, "f_");
                        $arrGroups_new = array(3,4, $Groups["ID"]);
                    }
                    
        
                }
                
                $arrGroups_old = $USER->GetUserGroupArray(); // получим текущие группы
                $arrGroups = array_unique(array_merge($arrGroups_old, $arrGroups_new)); // объединим два массива и удалим дубли
                $USER->Update($res, array("GROUP_ID" => $arrGroups)); // обновим профайл пользователя в базе
                $USER->Authorize($res);
                $result['status'] = "success";

                $arEventFields = [
                    "LAST_NAME" => $_REQUEST['last_name'],
                    "NAME" => $_REQUEST['name'],
                    "PASSWORD" => $_REQUEST['password'],
                    "EMAIL" => $_REQUEST['mail'],
                ];

                CEvent::Send("USER_INFO", 's1', $arEventFields);
                CEvent::Send("NEW_USER", 's1', $arEventFields);
               
            } else {
                $result['status'] = "error";
                $result['message'] = $user->LAST_ERROR;
            }
        }
echo json_encode($result);
?>