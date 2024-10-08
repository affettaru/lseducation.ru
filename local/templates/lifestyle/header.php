<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
global $cont;
global $USER;
$rsUser = CUser::GetByID($USER->GetID());
$arUser = $rsUser->Fetch();
$ids = kurs($USER->GetID());
$stack = array_pop($ids);

$arFilter = Array("IBLOCK_ID"=>1, "ACTIVE"=>"Y");
$res_count = CIBlockElement::GetList(Array(), $arFilter, Array(), false, Array());

if($stack==$res_count) {$sdan = $stack;} else {$sdan = $stack-1;}

$proc = (100 / $res_count) * $sdan;?>
<!DOCTYPE html>
<html lang="<?=LANGUAGE_ID?>">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <?$APPLICATION->ShowHead();?>

    <meta name="viewport" content="width=max-width, initial-scale=1.0, maximum-scale=2.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="HandheldFriendly" content="true">
    <meta http-equiv="cleartype" content="on">
    <meta http-equiv="msthemecompatible" content="no">
    <meta name="format-detection" content="telephone=no">
    <link rel="shortcut icon" type="image/x-icon" href="<?=SITE_TEMPLATE_PATH?>/public/favicon.ico">
    <title><?=$APPLICATION->ShowTitle()?></title>

    <?use \Bitrix\Main\Page\Asset;
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/public/vendors/malihu-scrollbar/jquery.mCustomScrollbar.css");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/public/vendors/slick/slick.css");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/public/vendors/slick/slick-theme.css");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/public/assets/css/app.css");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/public/assets/css/affetta.css");

    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/public/vendors/jquery/dist/jquery.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/public/vendors/jquery-ui/jquery-ui.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/public/vendors/malihu-scrollbar/jquery.mCustomScrollbar.concat.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/public/vendors/slick/slick.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/public/vendors/parallax/js/simpleParallax.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/public/assets/js/app.js?v=3");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/public/assets/js/affetta.js?v=35");
    ?>
    <script>var SITE_DIR = "<?=SITE_DIR?>";</script>
</head>
<body>

<? //$APPLICATION->ShowPanel(); ?>

<?
if($_GET["logout"]=="Y") {
    $USER->Logout();
}
?>
<!-- <aside>
    <a class="logo" <?=(!CSite::InDir('/index.php'))?'href="/"':'';?>>
        <img src="<?=SITE_TEMPLATE_PATH?>/public/assets/images/logo.png">
    </a>
    <img class="lg" src="<?=SITE_TEMPLATE_PATH?>/public/assets/images/edu.svg">
    <img class="xs" src="<?=SITE_TEMPLATE_PATH?>/public/assets/images/edu-line.svg">
    <a class="nav js-toogle-nav" href="#"></a>
</aside> -->
    <nav class="float js-nav">
      <div class="float__lessons"><strong>Уроки</strong>
        <?$APPLICATION->IncludeComponent(
          "bitrix:news.list",
          "right_curs",
          array(
            "ACTIVE_DATE_FORMAT" => "d.m.Y",
            "ADD_SECTIONS_CHAIN" => "Y",
            "AJAX_MODE" => "N",
            "AJAX_OPTION_ADDITIONAL" => "",
            "AJAX_OPTION_HISTORY" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "CACHE_FILTER" => "N",
            "CACHE_GROUPS" => "Y",
            "CACHE_TIME" => "36000000",
            "CACHE_TYPE" => "A",
            "CHECK_DATES" => "Y",
            "DETAIL_URL" => "",
            "DISPLAY_BOTTOM_PAGER" => "Y",
            "DISPLAY_DATE" => "Y",
            "DISPLAY_NAME" => "Y",
            "DISPLAY_PICTURE" => "Y",
            "DISPLAY_PREVIEW_TEXT" => "Y",
            "DISPLAY_TOP_PAGER" => "N",
            "FIELD_CODE" => array(
              0 => "",
              1 => "",
            ),
            "FILTER_NAME" => "",
            "IDV" => $idv,
            "STACK" => $stack,
            "HIDE_LINK_WHEN_NO_DETAIL" => "N",
            "IBLOCK_ID" => "1",
            "IBLOCK_TYPE" => "training",
            "INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
            "INCLUDE_SUBSECTIONS" => "Y",
            "MESSAGE_404" => "",
            "NEWS_COUNT" => "8",
            "PAGER_BASE_LINK_ENABLE" => "N",
            "PAGER_DESC_NUMBERING" => "N",
            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
            "PAGER_SHOW_ALL" => "N",
            "PAGER_SHOW_ALWAYS" => "N",
            "PAGER_TEMPLATE" => ".default",
            "PAGER_TITLE" => "Новости",
            "PARENT_SECTION" => "",
            "PARENT_SECTION_CODE" => "",
            "PREVIEW_TRUNCATE_LEN" => "",
            "PROPERTY_CODE" => array(
              0 => "name",
              1 => "description",
              2 => "youtube",
              3 => "amazon",
              4 => "num",
              5 => "",
            ),
            "SET_BROWSER_TITLE" => "Y",
            "SET_LAST_MODIFIED" => "N",
            "SET_META_DESCRIPTION" => "Y",
            "SET_META_KEYWORDS" => "Y",
            "SET_STATUS_404" => "N",
            "SET_TITLE" => "Y",
            "SHOW_404" => "N",
            "SORT_BY1" => "PROPERTY_num_VALUE",
            "SORT_BY2" => "SORT",
            "SORT_ORDER1" => "ASC",
            "SORT_ORDER2" => "ASC",
            "STRICT_SECTION_CHECK" => "N",
            "COMPONENT_TEMPLATE" => "right_curs"
          ),
          false
        );?>
      </div>
      <div class="float__profile">
        <div class="login">
          <div class="login__user">
            <div class="login__user--avatar"><?=mb_substr($arUser["NAME"],0,1,"UTF-8")?><?=mb_substr($arUser["LAST_NAME"],0,1,"UTF-8")?></div>
            <p><?=$arUser["NAME"]?> <?=$arUser["LAST_NAME"]?></p>
          </div>
          <div class="login__btn"><a href="?logout=Y">Выйти из системы</a></div>
        </div>
      </div>
    </nav>
  <?if(CSite::InDir('/index.php') && $arUser["UF_NO_QUIZ"]!=="1") {?>
    <?$APPLICATION->IncludeComponent(
        "affetta:uniedit",
        "start_quiz",
        Array(
            "CACHE_GROUPS" => "Y",
            "NAME" => $arUser["NAME"],
            "CACHE_TIME" => "36000000",
            "CACHE_TYPE" => "N",
            "COMPONENT_TEMPLATE" => "start_quiz"
        )
    );?>
  <?}?>

<main <?if(CSite::InDir("/auth/")):?>class="login"<?endif;?>>
    <?if(!CSite::InDir("/auth/")):?>
        <header>
            <div class="progress">
                <div class="logo">
                    <a href="/">
                        <img src="/local/templates/lifestyle/public/assets/images/logo.png" alt="">
                    </a>
                </div>
                <div class="container">
                    <span class="progress__text">Ваш прогресс</span>
                    <div class="progress__bar">
                        <div class="progress__bar--title"><strong><?=round($proc)?>% материала изучено</strong>
                            <p><?=$sdan?> из <?=$res_count?> уроков пройдено</p>
                        </div>
                        <div class="progress__meter">
                            <div class="progress__meter--bar" style="width: calc((100% / <?=$res_count?>) * <?=$sdan?>)"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="login"><a class="login__user">
                <div class="login__user--avatar"><?=mb_substr($arUser["NAME"],0,1,"UTF-8")?><?=mb_substr($arUser["LAST_NAME"],0,1,"UTF-8")?></div>
                <p><?=$arUser["NAME"]?> <?=$arUser["LAST_NAME"]?></p></a>
            <div class="login__btn"><a href="?logout=Y"></a></div>
            </div>
        </header>
    <?else:?>
        <div class="login__image">
            <div class="login__image--image">
                <div class="sphere sphere--big">
                </div>
                <div class="sphere sphere--small">
                </div>
                <img src="/local/templates/lifestyle/public/assets/images/auth.svg" class="move">
            </div>
          <?=$cont[0]["auth_text"]?>
        </div>
    <?endif;?>



