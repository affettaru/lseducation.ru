<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Lifestyle Education");
if (!$USER->IsAuthorized()) {LocalRedirect("/auth/register/");}
?>
<?$APPLICATION->IncludeComponent(
    "affetta:uniedit",
    "index",
    Array(
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "0",
        "CACHE_TYPE" => "N",
        "COMPONENT_TEMPLATE" => "index"
    )
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>