<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$bann = CIBlockElement::GetList(Array(),Array("IBLOCK_ID"=>IBLOCK_ABOUT,"ACTIVE"=>"Y"))->GetNext();
$bann = GetIBlockElement($bann['ID']);?> 
	<?if($bann["PREVIEW_TEXT"]):?>
			<section class="about">
				<div class="section-container">
					<div class="section-content">
						<div class="about-header">
							<div class="about__text"><?=$bann["~PREVIEW_TEXT"];?></div>
							<?if($bann["PREVIEW_PICTURE"]):?><div class="about__img"><img src="<?=res($bann["PREVIEW_PICTURE"],543,361,1);?>"></div><?endif;?>
						</div>
					</div>
				</div>
			</section>
	<?endif;?>
	<?
	$APPLICATION->IncludeComponent(
		"bitrix:news.list",
		"advantages",
		array(
			"ACTIVE_DATE_FORMAT" => "d.m.Y",
			"ADD_SECTIONS_CHAIN" => "N",
			"AJAX_MODE" => "N",
			"AJAX_OPTION_ADDITIONAL" => "",
			"AJAX_OPTION_HISTORY" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "N",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "N",
			"CACHE_TIME" => "36000000",
			"CACHE_TYPE" => "A",
			"CHECK_DATES" => "Y",
			"DETAIL_URL" => "",
			"DISPLAY_BOTTOM_PAGER" => "N",
			"DISPLAY_DATE" => "N",
			"DISPLAY_NAME" => "Y",
			"DISPLAY_PICTURE" => "N",
			"DISPLAY_PREVIEW_TEXT" => "N",
			"DISPLAY_TOP_PAGER" => "N",
			"FIELD_CODE" => array(
				0 => "",
				1 => "",
			),
			"FILTER_NAME" => "",
			"HIDE_LINK_WHEN_NO_DETAIL" => "N",
			"IBLOCK_ID" => "10",
			"IBLOCK_TYPE" => "shop",
			"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
			"INCLUDE_SUBSECTIONS" => "N",
			"MESSAGE_404" => "",
			"NEWS_COUNT" => "3",
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
				0 => "descript",
				1 => "index",
				2 => "",
			),
			"SET_BROWSER_TITLE" => "N",
			"SET_LAST_MODIFIED" => "N",
			"SET_META_DESCRIPTION" => "N",
			"SET_META_KEYWORDS" => "N",
			"SET_STATUS_404" => "N",
			"SET_TITLE" => "N",
			"SHOW_404" => "N",
			"SORT_BY1" => "SORT",
			"SORT_BY2" => "ID",
			"SORT_ORDER1" => "ASC",
			"SORT_ORDER2" => "DESC",
			"STRICT_SECTION_CHECK" => "N",
			"COMPONENT_TEMPLATE" => "advantages"
		),
		false
	);?>
			<section class="about">
				<div class="section-container">
					<div class="section-content">
						<div class="about-main">
							<?if($bann["PROPERTIES"]["baner"]["VALUE"]):?><div class="about-main__img"><img src="<?=res($bann["PROPERTIES"]["baner"]["VALUE"],1201,445,1);?>"></div><?endif;?>
							<div class="about-main-info">
								<?if($bann["PROPERTIES"]["one"]["VALUE"]):?>
									<div class="about-main-info__col">
										<div class="about-main-number__list">
											<?foreach($bann["PROPERTIES"]["one"]["VALUE"] as $n=>$one):?>
												<div class="about-main-number__item">
													<div class="about-main-number__value"><?=$one;?></div>
													<div class="about-main-number__text"><?=$bann["PROPERTIES"]["one"]["DESCRIPTION"][$n];?></div>
												</div>
											<?endforeach;?>
										</div>
									</div>
								<?endif;?>
								<?if($bann["PROPERTIES"]["text"]["VALUE"]):?>
									<div class="about-main-info__col">
										<div class="about-main-info__text">
											<?=$bann["PROPERTIES"]["text"]["~VALUE"]["TEXT"];?>
										</div>
									</div>
								<?endif;?>
							</div>
						</div>
					</div>
				</div>
			</section>
			<?if($bann["PROPERTIES"]["his_text"]["VALUE"] || $bann["PROPERTIES"]["his_img"]["VALUE"]):?>
				<section class="about">
					<div class="section-container">
						<div class="section-content">
							<div class="about-footer">
								<?if($bann["PROPERTIES"]["his_img"]["VALUE"]):?><div class="about__img"><img src="<?=res($bann["PROPERTIES"]["his_img"]["VALUE"],542,384,1);?>"></div><?endif;?>
								<div class="about__text">
									<div class="about__text--title">
										<h3>История успеха</h3>
									</div>
									<div class="about__text--excerpt">
										<p><?=$bann["PROPERTIES"]["his_text"]["~VALUE"]["TEXT"];?></p>
									</div>
									<?if($bann["PROPERTIES"]["his_url"]["VALUE"]):?><div class="about__text--btn"><a class="button button__arrow" href="<?=$bann["PROPERTIES"]["his_url"]["VALUE"];?>">Подробнее</a></div><?endif;?>
								</div>
							</div>
						</div>
					</div>
				</section>
			<?endif;?>
