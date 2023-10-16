<?include($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");

global $USER;
$ids = kurs($USER->GetID());
$stack = array_pop($ids);
$rsUser = CUser::GetByID($USER->GetID());
$arUser = $rsUser->Fetch();

if($_REQUEST["ELEMENT_ID"]=="") {
    $idv = $stack;
} else {
    if($stack>=$_REQUEST["ELEMENT_ID"]){ $idv = $_REQUEST["ELEMENT_ID"];} else { $idv = $stack;} 
}

$list = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>1, "PROPERTY_NUM_VALUE"=>$idv, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y"), false, false, Array("ID","PROPERTY_binding","PROPERTY_num"));
while($el = $list->GetNext()){
    $id = $el["ID"];
    $num = $el["PROPERTY_NUM_VALUE"];
    if($el["PROPERTY_BINDING_VALUE"]!=""){$BINDING[] = $el["PROPERTY_BINDING_VALUE"];}
}

if(preg_match_all("/#(.*?)#/", $arUser["UF_ANSWER_".$stack], $resId)){
	$result = array_diff($BINDING,$resId[1]);
}
?>
		<?
		$APPLICATION->IncludeComponent(
		    "affetta:uniedit",
		    "quiz",
		    Array(
				"RESTART" => $_REQUEST["restart"],
				"IS_BACK" => $_REQUEST["back_id"],
		        "CACHE_GROUPS" => "N",
		        "CACHE_TYPE" => "N",
		        "COMPONENT_TEMPLATE" => "quiz"
		    )
		);
	?>
      	<div class="uiz-ajax" <?if(!$result || $arUser["UF_SDAN_".$idv] == '1'):?>style="display:none;"<?endif;?>>
			<?if($result) {$bild = $result; $numVopros = count($BINDING)-count($result);} else {$bild = $BINDING;}
			global $arrFilter;
			$addFilter = Array("LOGIC"=>"OR","ID"=>0); 
		
			$backID = end($resId[1]);
			
			//dump($result);
			$lists = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>2, "ID"=>$bild, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y"), false, false, Array("ID","PROPERTY_binding"));
			while($els = $lists->GetNext()){
				$addFilter[] = Array("ID"=>$els["ID"]);
			}

			$arrFilter[] = $addFilter;
			
	
			$APPLICATION->IncludeComponent(
				"bitrix:news.list", 
				"vopros", 
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
					"CACHE_TIME" => "0",
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
					"FILTER_NAME" => "arrFilter",
					"HIDE_LINK_WHEN_NO_DETAIL" => "N",
					"IBLOCK_ID" => "2",
					"IBLOCK_TYPE" => "training",
					"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
					"INCLUDE_SUBSECTIONS" => "Y",
					"MESSAGE_404" => "",
					"NEWS_COUNT" => "100",
					"PAGER_BASE_LINK_ENABLE" => "N",
					"PAGER_DESC_NUMBERING" => "N",
					"PAGER_DESC_NUMBERING_CACHE_TIME" => "",
					"PAGER_SHOW_ALL" => "N",
					"PAGER_SHOW_ALWAYS" => "N",
					"PAGER_TEMPLATE" => ".default",
					"PAGER_TITLE" => "Новости",
					"PARENT_SECTION" => "",
					"PARENT_SECTION_CODE" => "",
					"NUM" => $num,
					"BINDING" => count($BINDING),
					"NUM_VOPROS" => $numVopros,
					"BACK_ID" => $backID,
					"ANSWERS" => $_REQUEST["answers"],
					"PREVIEW_TRUNCATE_LEN" => "",
					"PROPERTY_CODE" => array(
						0 => "vopros_text",
						1 => "otvet_text",
						2 => "vopros_checkbox",
						3 => "varianty_checkbox",
						4 => "otvet_checkbox",
						5 => "pr_otvet_checkbox",
						6 => "vopros_radio",
						7 => "otvet_radio",
						8 => "pr_otvet_radio",
						9 => "down_img_radio",
						10 => "pr_otvet_text",
						11 => "uvedomit",
						12 => "",
					),
					"SET_BROWSER_TITLE" => "N",
					"SET_LAST_MODIFIED" => "N",
					"SET_META_DESCRIPTION" => "N",
					"SET_META_KEYWORDS" => "N",
					"SET_STATUS_404" => "Y",
					"SET_TITLE" => "N",
					"SHOW_404" => "Y",
					"SORT_BY1" => "ACTIVE_FROM",
					"SORT_BY2" => "SORT",
					"SORT_ORDER1" => "DESC",
					"SORT_ORDER2" => "ASC",
					"STRICT_SECTION_CHECK" => "N",
					"COMPONENT_TEMPLATE" => "vopros",
					"FILE_404" => ""
				),
				false
			);?>
		</div>