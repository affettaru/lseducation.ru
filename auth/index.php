<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Авторизация");
if ($USER->IsAuthorized()) {LocalRedirect("/");}
?><?$APPLICATION->IncludeComponent(
	"bitrix:main.auth.form",
	"auth",
	Array(
		"AUTH_FORGOT_PASSWORD_URL" => "/auth/forgot-password/",
		"AUTH_REGISTER_URL" => "/auth/register/",
		"AUTH_SUCCESS_URL" => "/"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>