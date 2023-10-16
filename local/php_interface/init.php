<?
if(!CSite::InDir("/bitrix/admin/fileman_file_edit.php")) include($_SERVER['DOCUMENT_ROOT']."/local/inc/include.php");



AddEventHandler("main", "OnAfterUserUpdate", Array("SendLetterToActiveUser", "OnAfterUserUpdateHandler"));

class SendLetterToActiveUser
{
    public static function OnAfterUserUpdateHandler(&$arFields)
    {
        if($arFields["ACTIVE"] == "Y"){
            $arEventFields = [
                "NAME" => $arFields['NAME'],
                "EMAIL" => $arFields['EMAIL'],
                "LAST_NAME" => $arFields['LAST_NAME']
            ];
            CEvent::Send("ACTIVATE_USER", 's1', $arEventFields);
        }
    }
}

?>
