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
<?if($arParams["PARENT_SECTION"]){
	$Section = CIBlockSection::GetByID($arParams["PARENT_SECTION"])->GetNext();?>
	<div  class="oz">Курс по <?=$Section["NAME"];?></div>
	<?if($arResult["ITEMS"]) {?>
		<ul>
		    <?foreach ($arResult["ITEMS"] as $key => $value) {
		    	$sdan = 'UF_SDAN_'.$value["PROPERTIES"]["num"]["VALUE"];
		    	$Groups = CGroup::GetList ($by = "c_sort", $order = "asc", Array ("STRING_ID" => 'T_'.$value["PROPERTIES"]["num"]["VALUE"]))->Fetch();?>
		        <li><a class="lessons_item_link <?if(!$arParams["USER"][$sdan] && in_array($Groups["ID"], CUser::GetUserGroup($arParams["USER"]["ID"]))):?>current<?elseif(preg_replace("/[^0-9]/", "", $arParams["STACK"])>preg_replace("/[^0-9]/", "", $value["PROPERTIES"]["num"]["VALUE"]) || $GLOBALS['proc']==100):?>done<?elseif($arParams["STACK"]==$value["PROPERTIES"]["num"]["VALUE"]):?>current<?endif;?><?=($value["PROPERTIES"]["num"]["VALUE"]==$arParams["IDV"] ? ' active' : '');?>" <?if(preg_replace("/[^0-9]/", "", $value["PROPERTIES"]["num"]["VALUE"])>preg_replace("/[^0-9]/", "", $arParams["STACK"]) || $value["PROPERTIES"]["num"]["VALUE"]==$arParams["IDV"]):?><?else:?>href="/?ELEMENT_ID=<?=$value["PROPERTIES"]["num"]["VALUE"]?>"<?endif;?>>
		            <span><?=$value["NAME"]?></span>
		            <p><?=$value["PROPERTIES"]["name"]["VALUE"]?></p></a>
		        </li>
		    <?}?>
		</ul>
	<?}?>
<?}?>

<?
echo '<pre>';
print_r($GLOBALS['proc']);
echo '</pre>';
?>