<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)
{
	die();
}

use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

\Bitrix\Main\Page\Asset::getInstance()->addCss(
	'/bitrix/css/main/system.auth/flat/style.css'
);
if ($arResult['AUTHORIZED'])
{
LocalRedirect("/");
}

?>
<?if($arResult['SUCCESS']) {?>
      <div class="login__form login__form--btn-small">
        <form>
          <h2>Восстановление пароля</h2>
          <p class="text-dark">На электронную почту <a href="/auth/"><?=$_REQUEST["USER_EMAIL"]?></a> мы отправили письмо с инструкцией и кодом восстановления</p>
          <a href="/auth/" class="btn btn-secondary"><span>Назад к авторизации</span></a>
        </form>
      </div>
<?} else {?>
    <div class="login__form">
    	<form name="bform" method="post" target="_top" action="<?= POST_FORM_ACTION_URI;?>">
            <h2><?= Loc::getMessage('MAIN_AUTH_PWD_HEADER');?></h2>
            <label>
                <input <?if ($arResult['ERRORS']):?>class="error"<?endif;?> type="text" name="<?= $arResult['FIELDS']['email'];?>" value="<?=$_REQUEST["USER_LOGIN"];?>" placeholder="Введите свой e-mail"><?if ($arResult['ERRORS']):?><span class="error__text">Указанный email не зарегистрирован в системе</span><?endif;?>
            </label>
            <input type="submit" class="btn btn-secondary" name="<?= $arResult['FIELDS']['action'];?>" value="<?= Loc::getMessage('MAIN_AUTH_PWD_FIELD_SUBMIT');?>" />
            <hr>
            <p class="centr"><a href="<?= $arResult['AUTH_AUTH_URL'];?>" rel="nofollow">Войти</a></p>
        </form>
    </div>
<?}?>
<script type="text/javascript">
	document.bform.<?= $arResult['FIELDS']['login'];?>.focus();
</script>
