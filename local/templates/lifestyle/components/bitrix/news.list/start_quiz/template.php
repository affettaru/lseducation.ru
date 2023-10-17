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
?>
    <div class="start__quiz">
		<?$i=1;
		foreach($arResult["ITEMS"] as $arItem):?>
			<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			$pict = CFile::GetPath($arItem["PROPERTIES"]["pic"]["VALUE"]);
			?>
	      <div class="start__quiz--page active" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
	        <div class="start__quiz--image"><img class="move wave" src="<?=SITE_TEMPLATE_PATH?>/public/assets/images/roud.webp"><img class="move start" src="<?=$pict?>"></div>
	        <div class="start__quiz--text">
	          <?if($i==1):?>
	          	<h2>Здравствуйте, <?=$arParams["NAME"]?></h2><?else:?><h2><?=$arItem["NAME"]?></h2><?endif;?>
	          	<?=$arItem["PROPERTIES"]["text"]["~VALUE"]["TEXT"]?>
	          <!-- <div class="btn__wrapper">
	            <?if($i == count($arResult["ITEMS"])):?>
	            	<div>
	            		<a class="btn btn-primary js-close quiz__as__no" data-quiz="Y" href="#"><span>Закрыть</span></a>
	            		<span><?=$i?> из <?=count($arResult["ITEMS"])?></span>
	            		<a class="btn btn-secondary js-prev" href="#"><span>Назад</span></a>
	            	</div>
	            <?elseif($i == 1):?>
	            	<div>
	            		<a class="btn btn-primary js-next" href="#"><span>Далее</span></a>
	            		<span><?=$i?> из <?=count($arResult["ITEMS"]) ?></span>
	            	</div>
	            	<a class="js-close quiz__as__no" data-quiz="Y" href="#">Пропустить</a>
	            <?else:?>
	            	<div>
	            		<a class="btn btn-primary js-next" href="#"><span>Далее</span></a>
	            		<span><?=$i?> из <?=count($arResult["ITEMS"]) ?></span>
	            		<a class="btn btn-secondary js-prev" href="#"><span>Назад</span></a>
	            	</div>
	            	<a class="js-close quiz__as__no" data-quiz="Y" href="#">Пропустить</a>
	            <?endif;?>
	          </div> -->


			  <div class="btn__wrapper">
	            <?if($i == count($arResult["ITEMS"])):?>
	            	<div style="margin-left:auto">
						<a class="btn btn-secondary js-prev" href="#"><span>Назад</span></a>
	            		
	            		<span><?=$i?> из <?=count($arResult["ITEMS"])?></span>
						<a class="btn btn-primary js-close quiz__as__no" data-quiz="Y" href="#"><span>Закрыть</span></a>
	            		
	            	</div>
	            <?elseif($i == 1):?>
					<a class="js-close btn btn-secondary quiz__as__no" data-quiz="Y" href="#">Пропустить</a>
	            	<div>
	            		
	            		<span><?=$i?> из <?=count($arResult["ITEMS"]) ?></span>
						<a class="btn btn-primary js-next" href="#"><span>Далее</span></a>
	            	</div>
	            	
	            <?else:?>
					<a class="js-close btn btn-secondary quiz__as__no" data-quiz="Y" href="#">Пропустить</a>
	            	<div>
						<a class="btn btn-secondary js-prev" href="#"><span>Назад</span></a>
	            		<span><?=$i?> из <?=count($arResult["ITEMS"]) ?></span>
						<a class="btn btn-primary js-next" href="#"><span>Далее</span></a>
	            		
	            	</div>
	            	
	            <?endif;?>
	          </div>


	        </div>
	      </div>
		<?$i++;
	endforeach;?>
    </div>
    <p class="as-no-quizs"></p>