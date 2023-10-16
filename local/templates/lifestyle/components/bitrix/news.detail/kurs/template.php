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
$this->setFrameMode(true);
	global $USER;
	$USER->GetID();
	$kurs = kurs($USER->GetID());
	$stack = array_pop($kurs);
	$arUser = CUser::GetByID($USER->GetID())->GetNext();
	if(preg_match_all("/#(.*?)#/", $arUser["UF_ANSWER".$stack], $resId))
	$result = array_diff($arResult["PROPERTIES"]["binding"]["VALUE"],$resId[1]);
    if($_REQUEST["ELEMENT_ID"]=="") {$ids = $stack;} else {$ids = $_REQUEST["ELEMENT_ID"];}
    $crop = crop($arResult["PROPERTIES"]["preview"]["VALUE"],"1200","570",1);
?>
	<div class="title"><span><?=$arResult["NAME"]?></span>
		<h1><?=$arResult["PROPERTIES"]["name"]["VALUE"]?></h1>
		<?if($arResult["PROPERTIES"]["description"]["~VALUE"]["TEXT"]):?><p><?=$arResult["PROPERTIES"]["description"]["~VALUE"]["TEXT"]?></p><?endif;?>
	</div>
	<?if($arResult["PROPERTIES"]["amazon"]["VALUE"]){?>
	    <div class="video as-load-video">
        <a href="<?=$arResult["PROPERTIES"]["amazon"]["VALUE"]?>" class="video-gr" data-fancybox></a>
	    	<video class="load_video" tip="amazon" data-rel="<?=$arResult["PROPERTIES"]["amazon"]["VALUE"]?>" poster="<?=$crop?>"></video>
	    </div>
	<?} elseif($arResult["PROPERTIES"]["youtube"]["VALUE"]) {?>
	    <div class="video as-load-video">
		<iframe width="100%" height="580" src="<?=$arResult["PROPERTIES"]["youtube"]["VALUE"]?>?rel=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

        <!-- <a href="https://youtu.be/<?=$arResult["PROPERTIES"]["youtube"]["VALUE"]?>" class="video-gr" data-fancybox></a>
	    	<video class="load_video" tip="youtube" data-rel="<?=$arResult["PROPERTIES"]["youtube"]["VALUE"]?>" poster="<?=$crop?>"></video> -->
	    </div>
	<?}
	else{?>
	    <div class="video as-load-video video_img">
	    	<img src="<?=$crop?>" alt="">
	    </div>
	<?}?>
	<?if($arResult["PROPERTIES"]["file"]["VALUE"] || $arResult["PROPERTIES"]["file_src"]["VALUE"] ) {?>
		<div class="material">
			<h2>Материалы для изучения</h2>
			<div class="material__grid">
				<?	foreach ($arResult["PROPERTIES"]["file"]["VALUE"] as $f=>$aRitems) {
					$arFile = CFile::GetFileArray($aRitems);?>
					<a class="material__grid--item" download href="<?=$arFile["SRC"]?>">
						<div class="image"><img src="<?=SITE_TEMPLATE_PATH?>/public/assets/images/doc.svg"></div>
						<div class="text">
							<p><?=($arResult["PROPERTIES"]["file"]["DESCRIPTION"][$f] ? $arResult["PROPERTIES"]["file"]["DESCRIPTION"][$f] : $arFile["ORIGINAL_NAME"])?></p><span><?=formatBytes($arFile["FILE_SIZE"])?></span>
						</div>
					</a>
				<?}?>
				<?	foreach ($arResult["PROPERTIES"]["file_src"]["VALUE"] as $fc=>$srcFile) {
					$arFile = CFile::GetFileArray($aRitems);?>
					<a class="material__grid--item" href="<?=$srcFile?>" target="_blank">
						<div class="image"><img src="<?=SITE_TEMPLATE_PATH?>/public/assets/images/link.svg"></div>
						<div class="text">
							<p><?=($arResult["PROPERTIES"]["file_src"]["DESCRIPTION"][$fc] ? $arResult["PROPERTIES"]["file_src"]["DESCRIPTION"][$fc] : $srcFile)?></p>
						</div>
					</a>
				<?}?>
			</div>
		</div>
	<?}?>
<script src="https://player.live-video.net/1.7.0/amazon-ivs-player.min.js"></script>


