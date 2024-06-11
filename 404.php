<?
define('ERROR_404','Y');
header("HTTP/1.0 404 Not Found");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("404 — страница не найдена");

if(preg_match("#/([0-9])0([0-9]+)$#", $_SERVER['REQUEST_URI'], $mat) || preg_match("#/([0-9])0([0-9]+)/$#", $_SERVER['REQUEST_URI'], $mat)){
	if($mat[1] == 1) $utm = "?utm_source=vk&utm_campaign=group";
	elseif($mat[1] == 2) $utm = "?utm_source=inst&utm_campaign=group";

	$good = CIBlockElement::GetById($mat[2])->GetNext();
	$url = "http://".$_SERVER['SERVER_NAME'].$good['DETAIL_PAGE_URL'].$utm;

	LocalRedirect($url);
}
?>

      <section>
        <div class="container">
          <div class="error__wrapper"><img src="<?=SITE_TEMPLATE_PATH?>/public/assets/images/e404.svg">
            <h3>Страница не найдена</h3><a class="btn btn-primary" href="/"><span>Вернуться на главную</span></a>
          </div>
        </div>
    <nav><a class="collapse" href="/"></a>
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
    "SET_STATUS_404" => "Y",
    "SET_TITLE" => "Y",
    "SHOW_404" => "Y",
    "SORT_BY1" => "PROPERTY_num_VALUE",
    "SORT_BY2" => "SORT",
    "SORT_ORDER1" => "ASC",
    "SORT_ORDER2" => "ASC",
    "STRICT_SECTION_CHECK" => "N",
    "COMPONENT_TEMPLATE" => "right_curs",
    "FILE_404" => ""
  ),
  false
);?>
    </nav>
      </section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>