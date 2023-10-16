<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Смена пароля");
?><?$APPLICATION->IncludeComponent("bitrix:main.auth.changepasswd", "changepasswd", Array(
	"AUTH_AUTH_URL" => "/auth/",	// Страница для авторизации
		"AUTH_REGISTER_URL" => "/auth/register/",	// Страница для регистрации
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>