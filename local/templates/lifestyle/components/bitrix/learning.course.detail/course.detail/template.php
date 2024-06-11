<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$res = CLearnLesson::GetList(["SORT" => "DESC"], ["COURSE_ID" => $arResult["COURSE"]['COURSE_ID']], ["UF_*"]);
if ($arUF = $res->GetNext()) {
	$videoSrc = $arUF["UF_VIDEO"];
    $youTube = $arUF["UF_UT"];
	$files = $arUF["UF_FILE"];
	$name = $arUF["UF_NAME"];
	$desc = $arUF["UF_DESC"];
}

//вынести функцию
function formatBytes($size, $precision = 2){
	$base = log($size, 1024);
	$suffixes = array('Bytes', 'Kb', 'Mb', 'Gb', 'Tb');
	return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
}
?>
	<div class="title"><span><?=$arResult["COURSE"]["NAME"]?></span>
		<h1><?=$name?></h1>
		<p><?=$desc?></p>
	</div>



        <?if($videoSrc){?>
            <div class="video">
                <video id="video-player" style="width:100%; margin:0 auto; display:block;" controls></video>
            </div>
        <?} elseif($youTube) {?>
            <div class="video">
                <iframe style="width:100%; height: 668px; margin:0 auto; display:block;" src="http://www.youtube.com/embed/<?=$youTube?>?rel=0&showinfo=0" frameborder="0" allowfullscreen="allowfullscreen"></iframe>
            </div>
        <?}?>

	<?if($files[0]) {?>
		<div class="material">
			<h2>Материалы для изучения</h2>
			<div class="material__grid">
				<?	foreach ($files as $aRitems) {
					$arFile = CFile::GetFileArray($aRitems);?>
					<a class="material__grid--item" download href="<?=$arFile["SRC"]?>">
						<div class="image"><img src="<?=SITE_TEMPLATE_PATH?>/public/assets/images/doc.svg"></div>
						<div class="text">
							<p><?=$arFile["ORIGINAL_NAME"]?></p><span><?=formatBytes($arFile["FILE_SIZE"])?></span>
						</div>
					</a>
				<?}?>
			</div>
		</div>
	<?}?>
    <script>var PLAYBACK_URL = "https://lstraning.s3.us-east-2.amazonaws.com/output/<?= $videoSrc;?>";</script>
