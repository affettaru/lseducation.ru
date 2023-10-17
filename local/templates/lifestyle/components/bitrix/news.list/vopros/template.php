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
<?

#431# Вопрос:  Выберите способы загрузки изображений на Ozon  Ответ: (Редактировать товар,Редактировать медиа,Импорт изображений,Загрузка изображений по API)
$start_pos = strpos($arParams["ANSWERS"], "(") + 1;
$end_pos = strpos($arParams["ANSWERS"], ")", $start_pos);
$answers = substr($arParams["ANSWERS"], $start_pos, $end_pos - $start_pos);

$answers = explode('|', $answers);


?>
<?$Item = array();
foreach($arResult["ITEMS"] as $key => $arItem):?>
<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
if($arItem["PROPERTIES"]["vopros_text"]["VALUE"]) {
	$Item[$arItem["PROPERTIES"]["sort"]["VALUE"]]["ID"] = $arItem["ID"];
	$Item[$arItem["PROPERTIES"]["sort"]["VALUE"]]["type"] = "text";
	$Item[$arItem["PROPERTIES"]["sort"]["VALUE"]]["sort"] = $arItem["PROPERTIES"]["sort"]["VALUE"];
	$Item[$arItem["PROPERTIES"]["sort"]["VALUE"]]["vopros"] = $arItem["PROPERTIES"]["vopros_text"]["VALUE"];
} elseif($arItem["PROPERTIES"]["varianty_checkbox"]["VALUE"]) {
	$Item[$arItem["PROPERTIES"]["sort"]["VALUE"]]["ID"] = $arItem["ID"];
	$Item[$arItem["PROPERTIES"]["sort"]["VALUE"]]["type"] = "checkbox";
	$Item[$arItem["PROPERTIES"]["sort"]["VALUE"]]["sort"] = $arItem["PROPERTIES"]["sort"]["VALUE"];
	$Item[$arItem["PROPERTIES"]["sort"]["VALUE"]]["vopros"] = $arItem["PROPERTIES"]["vopros_checkbox"]["VALUE"];
	$Item[$arItem["PROPERTIES"]["sort"]["VALUE"]]["photo_checkbox"] = $arItem["PROPERTIES"]["photo_checkbox"]["VALUE"];
	$Item[$arItem["PROPERTIES"]["sort"]["VALUE"]]["varianty_checkbox"] = $arItem["PROPERTIES"]["varianty_checkbox"]["VALUE"];
} elseif($arItem["PROPERTIES"]["vopros_radio"]["VALUE"]) {
	$Item[$arItem["PROPERTIES"]["sort"]["VALUE"]]["ID"] = $arItem["ID"];
	$Item[$arItem["PROPERTIES"]["sort"]["VALUE"]]["type"] = "radio";
	$Item[$arItem["PROPERTIES"]["sort"]["VALUE"]]["sort"] = $arItem["PROPERTIES"]["sort"]["VALUE"];
	$Item[$arItem["PROPERTIES"]["sort"]["VALUE"]]["vopros"] = $arItem["PROPERTIES"]["vopros_radio"]["VALUE"];
	$Item[$arItem["PROPERTIES"]["sort"]["VALUE"]]["photo_radio"] = $arItem["PROPERTIES"]["photo_radio"]["VALUE"];
	$Item[$arItem["PROPERTIES"]["sort"]["VALUE"]]["down_img_radio"] = $arItem["PROPERTIES"]["down_img_radio"]["VALUE"];
}
$curSection = $arItem['IBLOCK_SECTION_ID'];
endforeach;
ksort($Item);
if(count($Item)<count($arResult["ITEMS"])){
	if($arParams["NUM_VOPROS"]){$i=$arParams["NUM_VOPROS"]+1;} else {$i = count($arResult["ITEMS"]) - count($Item) + 1;}
	$n[] = $i;
} else {
	if($arParams["NUM_VOPROS"]){$i=$arParams["NUM_VOPROS"]+1;} else {$i = 1;}
	$n[0] = $i;
}
?>
<?
//Получаем количество вопросов текущего теста
$activeElements = CIBlockSection::GetSectionElementsCount($curSection, Array("CNT_ACTIVE"=>"Y"));
//Получаем код текущего теста и создаем название UF из пользователя
$curTestUF = 'UF_EXT_'.$arParams['NUM'];
//Получаем количество верных ответов пользователя для текущего пользователя
$rsUser = CUser::GetByID($USER->GetID());
$arUser = $rsUser->Fetch();
$goodAnswers = $arUser[$curTestUF]; 
$saveAnswers = $arUser['UF_SAVE_ANSWERS']; 
$saveAnswers = explode('#', $arUser['UF_SAVE_ANSWERS']);

foreach ($saveAnswers as $key => $value) {
	if ($value != ''){
		$saveAnswers[$key] = explode('|', $value);
		$saveAnswers[$saveAnswers[$key][0]] = $saveAnswers[$key];
		unset($saveAnswers[$key]);
	} else {
		unset($saveAnswers[$key]);
	}
}

foreach ($saveAnswers as $key => $value) {
	unset($saveAnswers[$key][0]);
}




?>
<?foreach ($Item as $key => $value) {

// echo '<pre>';
// print_r($value["ID"]);
// echo '</pre>';
			if((count($arResult["ITEMS"])=='1') && (($activeElements - 1) > $goodAnswers)){?>
<div class="uiz-ajax">


    <div class="quiz__modal--wrapper active">
        <div class="quiz__modal--header flex flex-sb">
			<div>
				<h3>Упс! Нам очень жаль. <br />Вы не сдали экзамен.</h3>
				<p>У вас есть возможность пройти тест заново. <br />Ваши результаты будут отправлены в чат участников.</p>
			</div>
			<img class="fail-img" src="/local/templates/lifestyle/public/assets/images/fail.svg">
        </div>

        <div class="quiz__modal--body">
            <form class="quiz__form quiz_sub_form" id="form" action="/local/templates/lifestyle/ajax/quiz_restart.php">
                <input type="hidden" name="vopros_id" value="<?=$value["ID"]?>">
                <input type="hidden" name="COUNT" value="<?=count($arResult["ITEMS"])?>">
                <input type="hidden" name="NUM" value="<?=$arParams["NUM"]?>">
                <input type="hidden" name="user" value="<?=$USER->GetID()?>">
                <input type="hidden" name="test" value="<?=$arParams['NUM']?>">
                <input type="hidden" name="restart" value="Y">
                <button id="<?=$value["ID"]?>" data_id="<?=$arParams["NUM_VOPROS"];?>" type="submit"
                    class="button-secondary button quiz__modal-button btn">
                    <span>
                        Пройти тест заново<picture>
                            <source srcset="/local/templates/lifestyle/public/assets/images/svg/arrow-bottom-right.svg"
                                type="image/webp"><img
                                src="/local/templates/lifestyle/public/assets/images/svg/arrow-bottom-right.svg" alt="">
                        </picture>
                    </span>
                </button>
            </form>
        </div>
    </div>
</div>

<?} else {
			
			if($value["type"]=='radio') {$fieldset = 'image';} elseif($value["type"]=='text') {$fieldset = 'text';} elseif($value["type"]=='checkbox') {$fieldset = 'checkbox';}?>
<div class="quiz__modal--wrapper <?if($n[0] == $i):?>active<?endif;?>">
    <div class="quiz__modal--header"><span>вопрос <?=$i?> / <?=$arParams["BINDING"]?></span>
        <h3><?=$value["vopros"]?></h3>
        <?if($value["type"]=='checkbox'): $photo = crop($value["photo_checkbox"],820,300,1);?>
        <?if($photo){?>
        <div class="quiz__modal--header-imm">
            <img src="<?=$photo?>">
        </div>
        <?}?>
        <?endif;?>
    </div>
    <div class="quiz__modal--body">
        <form class="quiz__form quiz_sub_form" id="form" action="<?=SITE_TEMPLATE_PATH?>/ajax/quiz.php">
            <input type="hidden" name="vopros_id" value="<?=$value["ID"]?>">
            <input type="hidden" name="NUM" value="<?=$arParams["NUM"]?>">
            <?if(count($arResult["ITEMS"])=='1'):?><input type="hidden" name="SUB" value="Y">
            <?endif;?>
            <input type="hidden" name="COUNT" value="<?=count($arResult["ITEMS"])?>">
            <fieldset class="quiz__form--<?=$fieldset?>">
                <?if($value["type"]=='radio') {
	              		foreach ($value["photo_radio"] as $photo) {
              			$arFile = CFile::GetFileArray($photo);
	              		$URL = crop($photo,500,500,1);
						?>
                <label>
                    <input type="radio" name="img" value="<?=$arFile["DESCRIPTION"]?>"
                        <?=in_array($arFile["DESCRIPTION"], $saveAnswers[$value["ID"]])?'checked':''?>>
                    <div class="image">
                        <span></span>
                        <img src="<?=$URL?>">
                        <p>
                            <?=$arFile["DESCRIPTION"]?>
                            <?if($value["down_img_radio"]=='Да'){?></br><a class="big-img" href="<?=$arFile["SRC"];?>"
                                download>Скачать исходник</a>
                            <?}?>
                        </p>
                    </div>
                </label>
                <?	}
	            	} elseif($value["type"]=='text') {?>
                <label>
                    <input type="text" name="text" placeholder="Введите вариант ответа">
                </label>
                <?} elseif($value["type"]=='checkbox') {
	            		$check=0;
	            		foreach ($value["varianty_checkbox"] as $checkbox) {?>
                <label>
                    <input type="checkbox" name="check[<?=$check?>]" value="<?=$checkbox?>"
                        <?=in_array($checkbox, $saveAnswers[$value["ID"]])?'checked':''?>>
                    <p><?=$checkbox?></p>
                </label>
                <?	$check++;}
	            	}?>
            </fieldset>
            <div class="button_wrapper">
                <?if(count($arResult["ITEMS"])!=$activeElements){?>
                <button type="button" id="<?=$arParams['BACK_ID']?>" data_id="<?=$arParams["NUM"];?>"
                    class="button-secondary button quiz__modal-button btn button-back">
                    <span>
                        Предыдущий вопрос
                    </span>
                </button>
                <?}?>

                <button <?if(count($arResult["ITEMS"])==$activeElements){?>style="margin-left:auto"
                    <?}?>type="submit" id="<?=$value["ID"]?>" data_id="<?=$arParams["NUM_VOPROS"];?>"
                    class="button-secondary button quiz__modal-button btn"
                    <?=is_array($saveAnswers[$value["ID"]])!=''?'':'disabled="disabled"'?>>
                    <span>
                        <?if(count($arResult["ITEMS"])=='1'):?>Завершить
                        <?else:?>Следующий вопрос
                        <?endif;?>
                        <picture>
                            <source srcset="/local/templates/lifestyle/public/assets/images/svg/arrow-bottom-right.svg"
                                type="image/webp"><img
                                src="/local/templates/lifestyle/public/assets/images/svg/arrow-bottom-right.svg" alt="">
                        </picture>
                    </span>
                </button>
            </div>
            <?/*
	              	<input type="submit" id="as-click<?=$value["ID"]?>" name="submit" value="
            <?if($i == count($arResult["ITEMS"])):?>Завершить
            <?else:?>Отправить
            <?endif;?>">
            */?>
        </form>
    </div>
</div>
<?$i++;
		} }?>