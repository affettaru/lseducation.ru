<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $cont;
$list = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>3, "ID"=>12, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y"), false, Array(), Array("ID","PROPERTY_politica","PROPERTY_soglasie"));
while($el = $list->GetNext()){
	$policy = $el["~PROPERTY_POLITICA_VALUE"]["TEXT"];
	$user = $el["~PROPERTY_SOGLASIE_VALUE"]["TEXT"];
}
?>

	<?if($arParams["VID"]=="policy") {?>
		<?=$policy?>
	<?} elseif($arParams["VID"]=="user") {?>
		<?=$user?>
	<?}?>
