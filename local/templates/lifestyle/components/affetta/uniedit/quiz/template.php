<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arFilter = Array("IBLOCK_ID"=>1, "ACTIVE"=>"Y");
$res_count = CIBlockElement::GetList(Array(), $arFilter, Array(), false, Array());

global $USER;
$ids = kurs($USER->GetID());
$stack = array_pop($ids);
$rsUser = CUser::GetByID($USER->GetID());
$arUser = $rsUser->Fetch();
if(!$_REQUEST["ELEMENT_ID"]) {
    $idv = $stack;
} else {
    if($stack>=$_REQUEST["ELEMENT_ID"]){ $idv = $_REQUEST["ELEMENT_ID"];} else { $idv = $stack;} 
}

$list = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>1, "PROPERTY_NUM_VALUE"=>$idv, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y"), false, false, Array("ID","NAME","PROPERTY_binding","PROPERTY_num"));
while($el = $list->GetNext()){
    $id = $el["ID"];
    $name = $el["NAME"];
    $num = $el["PROPERTY_NUM_VALUE"];
    if($el["PROPERTY_BINDING_VALUE"]!=""){$BINDING[] = $el["PROPERTY_BINDING_VALUE"];}
}

if(preg_match_all("/#(.*?)#/", $arUser["UF_ANSWER_".$stack], $resId))
$result = array_diff($BINDING,$resId[1]);
?>
	<?if($arUser["UF_SDAN_".$idv]=="1") {?>
        <div class="quiz__start quiz__block">
          <div class="quiz__block--wrapper">
            <h3>Поздравляем! <br/>Вы сдали <?if($stack == $res_count):?>экзамен<?else:?>тест<?endif;?>!</h3>
            <p>Вы отлично справились с тестированием. <br/>Ваши результаты будут отправлены в чат участников.</p><?if($stack==$num) {?><a class="gold-as button-secondary button btn" href="#"><span>Завершить <?if($stack == $res_count):?>экзамен<?else:?>тест<?endif;?></span></a><?}?>
          </div><p class="gold_as--yes"></p><img src="<?=SITE_TEMPLATE_PATH?>/public/assets/images/success.svg">
        </div>
    <?} elseif($arUser["UF_SDAN_".$idv]=="0" && $arUser["UF_ATTEMPTS_".$stack]>0 && !$result) {?>
          <div class="quiz__start quiz__block">
            <div class="quiz__block--wrapper">
              <h3>Упс! Нам очень жаль. <br/>Вы не сдали <?if($stack == $res_count):?>экзамен<?else:?>тест<?endif;?>.</h3>
              <p>У вас есть возможность пройти <?if($stack == $res_count):?>экзамен<?else:?>тест<?endif;?> заново. <br/>Ваши результаты будут отправлены в чат участников.</p><a class="button-secondary button btn fail"><span>Пройти еще раз</span></a>
            </div> <p class="fails"></p><img src="<?=SITE_TEMPLATE_PATH?>/public/assets/images/fail.svg">
          </div>
    <?} else {
            if(preg_match_all("/#(.*?)#/", $arUser["UF_ANSWER_".$stack], $resId))
            $result = array_diff($BINDING,$resId[1]);?>
        <?if(count($BINDING)>0 && !$result):?>

            <div class="quiz__start" <?=$arParams['IS_BACK'] > 0?'style="display:none"':''?> <?=$arParams['RESTART'] == "Y"?'style="display:none"':''?>>
                <div class="quiz__start--header">
                    <h3><?if($stack == 8):?>Экзамен<?else:?>Тест<?endif;?> по теме «<?=$name?>»</h3>
                </div>
                <div class="quiz__start--body">
                    <div class="quiz__start--info">
                        <div><strong>Без ограничений</strong>
                            <p>Ограничение по времени: </p>
                        </div>
                        <div><strong><?=count($BINDING)?> <i title="Tooltip">?</i></strong>
                            <p><?=sklon(count($BINDING),"Вопросов","Вопрос","Вопроса")?>: </p>
                        </div>
                        <div><strong>Без ограничений</strong>
                            <p>Количество попыток: </p>
                        </div>
                    </div>
                    <div class="quiz__start--btn"><a class="btn btn-primary"><span>Пройти <?if($stack == $res_count):?>экзамен<?else:?>тест<?endif;?></span></a></div>
                    <!--<div class="quiz__start--btn"><a class="btn btn-primary" data-modal data-target="#quiz"><span>Пройти <?if($stack == 8):?>экзамен<?else:?>тест<?endif;?></span></a></div>-->
                </div>
            </div>
        <?endif;?>
    <?}?>