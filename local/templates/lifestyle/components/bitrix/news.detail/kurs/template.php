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
	if(is_array($kurs))$stack = array_pop($kurs);
	$arUser = CUser::GetByID($USER->GetID())->GetNext();
	if(preg_match_all("/#(.*?)#/", $arUser["UF_ANSWER".$stack], $resId))
	$result = array_diff($arResult["PROPERTIES"]["binding"]["VALUE"],$resId[1]);
    if($_REQUEST["ELEMENT_ID"]=="") {$ids = $stack;} else {$ids = $_REQUEST["ELEMENT_ID"];}
    $crop = crop($arResult["PROPERTIES"]["preview"]["VALUE"],"1200","570",1);
?>
	<div class="title"><span><?=$arResult["NAME"]?></span>
		<h1><?=$arResult["PROPERTIES"]["name"]["VALUE"]?></h1>
		<?if($arResult["PROPERTIES"]["description"]["VALUE"] && $arResult["PROPERTIES"]["description"]["VALUE"]["TEXT"]):?><p><?=html_entity_decode($arResult["PROPERTIES"]["description"]["VALUE"]["TEXT"])?></p><?endif;?>
	</div>
    <?if($arResult["PROPERTIES"]["rutube"]["~VALUE"]){?>
        <div class="video as-load-video">
            <video class="load_video" tip="rutube" data-rel="<?=preg_match('/src="([^"]+)"/', $arResult["PROPERTIES"]["rutube"]["~VALUE"], $match) ? $match[1] : ''?>" poster="<?=$crop?>"></video>
        </div>
	<?} elseif($arResult["PROPERTIES"]["amazon"]["VALUE"]){?>
	    <div class="video as-load-video">
            <a href="<?=$arResult["PROPERTIES"]["amazon"]["VALUE"]?>" class="video-gr" data-fancybox></a>
	    	<video class="load_video" tip="amazon" data-rel="<?=$arResult["PROPERTIES"]["amazon"]["VALUE"]?>" poster="<?=$crop?>"></video>
	    </div>
	<?} elseif($arResult["PROPERTIES"]["youtube"]["VALUE"]) {?>
	    <div class="video as-load-video">
		<img src="<?=$crop?>" alt="">
		<!-- <iframe id="myframe" class="youtube_iframe" src="<?=$arResult["PROPERTIES"]["youtube"]["VALUE"]?>?rel=0&autoplay=1" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> -->
	    </div>

	<?} else{?>
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

<style>
.video { position: relative; padding-bottom: 56.25%; /* 16:9 */ height: 0; }
.video img { position: absolute; display: block; top: 0; left: 0; width: 100%; z-index: 20; cursor: pointer; }
.video:after { content: ""; position: absolute; display: block;
    top: 45%; left: 45%; width: 46px; height: 36px; z-index: 30; cursor: pointer; }
.video iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }

/* image poster clicked, player class added using js */
.video.player img { display: none; }
.video.player:after { display: none; }
</style>

<script>
$(function() {
    var videos  = $(".video");

        videos.on("click", function(){
            var elm = $(this),
                conts   = elm.contents(),
                le      = conts.length,
                ifr     = null;

            for(var i = 0; i<le; i++){
              if(conts[i].nodeType == 8) ifr = conts[i].textContent;
            }

            elm.addClass("player").html(ifr);
            elm.off("click");
        });
});
		</script>