<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);?>
<?if($arResult["ITEMS"]) {?>
    <h3>Уроки</h3>
    <div class="lessons__slick">
    	<?foreach ($arResult["ITEMS"] as $key => $value) {
    	$crop = crop($value["PROPERTIES"]["preview"]["VALUE"],"250","130",1);?>
	    	<a class="lessons__slick-item <?if($value["PROPERTIES"]["num"]["VALUE"]==$arParams["IDV"]):?>current slick-current 11<?endif;?><?if(preg_replace("/[^0-9]/", "", $value["PROPERTIES"]["num"]["VALUE"])>preg_replace("/[^0-9]/", "", $arParams["STACK"])):?>lock<?endif;?>" style="background-image: url('<?=$crop?>')" <?if(preg_replace("/[^0-9]/", "", $value["PROPERTIES"]["num"]["VALUE"])>preg_replace("/[^0-9]/", "", $arParams["STACK"]) || $value["PROPERTIES"]["num"]["VALUE"]==$arParams["IDV"]):?><?else:?>href="/?ELEMENT_ID=<?=$value["PROPERTIES"]["num"]["VALUE"]?>"<?endif;?>>
	            <div><span><?=$value["NAME"]?></span>
	                <p><?=$value["PROPERTIES"]["name"]["VALUE"]?></p>
	            </div>
	        </a>
    	<?}?>
    </div>
<?}?>
