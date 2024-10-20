<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
IncludeTemplateLangFile(__FILE__);
global $cont;
?>
</main>
<footer>
  <p>© 2014–<?=date ( 'Y' );?> «Lifestyle Education»</p>
  <div class="ft">
    <a href="#" data-modal data-target="#policy">Политика конфиденциальности и обработки персональных данных</a>
    <p>Все материалы сайта защищены авторским правом и являются объектом коммерческой тайны</p>
  </div>
  <!--<a href="#" data-modal data-target="#user">Пользовательское соглашение</a>-->
  <a target="blank"></a>
</footer>
    <div class="quiz__modal--overlay" id="user">
      <div class="quiz__modal">
        <div class="close">&#215;</div>
        <div class="quiz__modal--wrapper active">
          <div class="quiz__modal--header">
            <h3>Пользовательское соглашение</h3>
          </div>
          <div class="quiz__modal--body text">
			<?$APPLICATION->IncludeComponent(
			    "affetta:uniedit",
			    "text",
			    Array(
			        "CACHE_GROUPS" => "Y",
			        "VID" => "user",
			        "CACHE_TIME" => "36000000",
			        "CACHE_TYPE" => "N",
			        "COMPONENT_TEMPLATE" => "text"
			    )
			);?>
          </div>
        </div>
      </div>
    </div>
    <div class="quiz__modal--overlay" id="policy">
      <div class="quiz__modal">
        <div class="close">&#215;</div>
        <div class="quiz__modal--wrapper active">
          <div class="quiz__modal--header">
            <h3>Политика конфиденциальности и обработки персональных данных</h3>
          </div>
          <div class="quiz__modal--body text">
			<?$APPLICATION->IncludeComponent(
			    "affetta:uniedit",
			    "text",
			    Array(
			        "CACHE_GROUPS" => "Y",
			        "VID" => "policy",
			        "CACHE_TIME" => "36000000",
			        "CACHE_TYPE" => "N",
			        "COMPONENT_TEMPLATE" => "text"
			    )
			);?>
          </div>
        </div>
      </div>
    </div>

    <div class="quiz__modal--overlay" id="reg">
        <div class="quiz__modal quiz__modal--reg">
            <div class="close">&#215;</div>
            <div class="quiz__modal--wrapper active">
                <div class="quiz__modal--header">
                    <h3>Спасибо за регистрацию.</h3>
                </div>
                <div class="quiz__modal--body text">
                    Скоро администратор подтвердит вашу учетную запись и вы сможете приступить к обучению. Вам придет письмо на указанную при регистрации электронную почту.
                </div>
                <img src="<?=SITE_TEMPLATE_PATH?>/public/assets/images/reg_new.svg" class="quiz__decore-image" alt="">
            </div>
        </div>
    </div>

</body>
</html>