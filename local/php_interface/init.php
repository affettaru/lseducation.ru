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


\Bitrix\Main\EventManager::getInstance()->addEventHandlerCompatible( 

    'main', 

    'OnBeforeUserChangePassword', 

    '\SendPassword::onBeforeUserChangePassword'

); 



\Bitrix\Main\EventManager::getInstance()->addEventHandlerCompatible( 

    'main', 

    'OnBeforeEventAdd', 

    '\SendPassword::onBeforeEventAdd'

); 



class SendPassword 

{

    public static function onBeforeUserChangePassword($arParams)

    {

        self::singleton(true,$arParams["PASSWORD"]);

    }



    public static function onBeforeEventAdd(&$event, &$lid, &$arFields, &$message_id, &$files)

    {

        if($event=="USER_PASS_CHANGED")

            $arFields["PASSWORD"] = self::singleton();

    }

    

    private static function singleton($write=false,$newValue=false)

    {

        static $value;

        if($write)

            $value = $newValue;

        return $value;

    }

}

?>
