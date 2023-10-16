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
	echo Loc::getMessage('MAIN_AUTH_FORM_SUCCESS');
	return;
}
?>

<div class="login__form">

	<?/*if ($arResult['AUTH_SERVICES']):?>
		<?$APPLICATION->IncludeComponent('bitrix:socserv.auth.form',
			'flat',
			array(
				'AUTH_SERVICES' => $arResult['AUTH_SERVICES'],
				'AUTH_URL' => $arResult['CURR_URI']
	   		),
			$component,
			array('HIDE_ICONS' => 'Y')
		);
		?>
		<hr class="bxe-light">
	<?endif*/?>

	<form name="<?= $arResult['FORM_ID'];?>" method="post" target="_top" action="<?= POST_FORM_ACTION_URI;?>">
        <?if ($arResult['ERRORS']):?>
            <div class="alert alert-danger">
                <? foreach ($arResult['ERRORS'] as $error)
                {
                    echo $error;
                }
                ?>
            </div>
        <?endif;?>
        <h2 class="bx-title"><?= Loc::getMessage('MAIN_AUTH_FORM_HEADER');?></h2>
        <label>
            <input type="text" name="<?= $arResult['FIELDS']['login'];?>" value="<?= \htmlspecialcharsbx($arResult['LAST_LOGIN']);?>" placeholder="<?= Loc::getMessage('MAIN_AUTH_FORM_FIELD_LOGIN');?>">
        </label>
        <label class="pwd">
            <input type="password" name="<?= $arResult['FIELDS']['password'];?>" autocomplete="off" placeholder="<?= Loc::getMessage('MAIN_AUTH_FORM_FIELD_PASS');?>"><i class="js-type__toggle"> </i>
        </label>
        <?if ($arResult['STORE_PASSWORD'] == 'Y'):?>
            <label>
                <input type="checkbox" id="USER_REMEMBER" name="<?= $arResult['FIELDS']['remember'];?>" value="Y" />
                <p><?= Loc::getMessage('MAIN_AUTH_FORM_FIELD_REMEMBER');?></p>
            </label>
        <?endif?>
        <div class="btn__wrapper">
            <input type="submit" class="btn btn-secondary" name="<?= $arResult['FIELDS']['action'];?>" value="<?= Loc::getMessage('MAIN_AUTH_FORM_FIELD_SUBMIT');?>" />
            <a href="<?= $arResult['AUTH_FORGOT_PASSWORD_URL'];?>" rel="nofollow"><?= Loc::getMessage('MAIN_AUTH_FORM_URL_FORGOT_PASSWORD');?></a>
        </div>
        <hr>
        <p>У вас нет аккаунта? <a href="<?= $arResult['AUTH_REGISTER_URL'];?>" rel="nofollow"><?= Loc::getMessage('MAIN_AUTH_FORM_URL_REGISTER_URL');?></a></p>
    </form>
</div>

<script type="text/javascript">
	<?if ($arResult['LAST_LOGIN'] != ''):?>
	try{document.<?= $arResult['FORM_ID'];?>.USER_PASSWORD.focus();}catch(e){}
	<?else:?>
	try{document.<?= $arResult['FORM_ID'];?>.USER_LOGIN.focus();}catch(e){}
	<?endif?>
</script>