<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Регистрация");
if ($USER->IsAuthorized()) {LocalRedirect("/");}
?>
    <div class="login__form">
        <form action="<?=SITE_TEMPLATE_PATH?>/ajax/reg.php" class="regform">
            <h2>Регистрация в системе</h2>
            <p class="error"></p>
            <label>
                <input type="text" name="mail" placeholder="E-mail*">
            </label>
            <label>
                <input type="text" name="name" placeholder="Имя">
            </label>
            <label>
                <input type="text" name="last_name" placeholder="Фамилия">
            </label>

            <label class="pwd">
                <input type="password" name='password' placeholder="Пароль*">

            </label>
            <label class="pwd">
                <input type="password" name='confirm_password' placeholder="Повторите пароль*">

            </label>
          <div class="login__form-courses login__form-courses_mod1">
            <div class="login__form-courses-title">Выберите курс</div>
            <div class="login__form-courses-inner">
              <div class="login__form-courses-item">
                <input type="radio" id="ozon" name="courses" value="15" checked="checked">
                <label for="ozon"><img class="login__form-courses-item-logo login__form-courses-item-logo-ozon" src="<?=SITE_TEMPLATE_PATH?>/public/assets/images/logo/logo_Ozon_new_h50.png" alt="Ozon" title="Ozon"></label>
              </div>
              <div class="login__form-courses-item">
                <input type="radio" id="wildberries" name="courses" value="16">
                <label for="wildberries"><img class="login__form-courses-item-logo login__form-courses-item-logo-wildberries" src="<?=SITE_TEMPLATE_PATH?>/public/assets/images/logo/Wildberries_Logo_h50.png" alt="Wildberries" title="Wildberries"></label>
              </div>
              <div class="login__form-courses-item">
                <input type="radio" id="yandex" name="courses" value="20">
                <label for="yandex"><img class="login__form-courses-item-logo login__form-courses-item-logo-yandex" src="<?=SITE_TEMPLATE_PATH?>/public/assets/images/logo/yandex_market_logo2_h50.png" alt="Яндекс.Маркет" title="Яндекс.Маркет"></label>
              </div>
            </div>
          </div>
            <label>
                <input name="checkbox" type="checkbox">
                <p>Подтверждаю согласие c <a href="#" data-modal data-target="#user">Пользовательским соглашением</a> и <a href="#" data-modal data-target="#policy">Политикой конфиденциальности</a></p>
            </label>
            <button class="btn btn-secondary"><span>Зарегистрироваться</span></button>
            <hr>
            <p>Уже зарегистрировались? <a href="/auth/">Перейти к авторизации</a></p>
        </form>
    </div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>