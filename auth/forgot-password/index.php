<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Восстановление пароля");
?><?$APPLICATION->IncludeComponent("bitrix:main.auth.forgotpasswd", "forgot_password", Array(
	"AUTH_AUTH_URL" => "/auth/",	// Страница для авторизации
		"AUTH_REGISTER_URL" => "/auth/reg/",	// Страница для регистрации
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>