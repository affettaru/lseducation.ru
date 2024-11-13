<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Регистрация");
if ($USER->IsAuthorized()) {LocalRedirect("/");}
$arFilter = array('IBLOCK_ID' => 1);
$arSelect = array('ID', 'NAME', 'CODE');
$rsSections = CIBlockSection::GetList(array(), $arFilter, false, $arSelect);
$Curses = [];
while ($arSection = $rsSections->GetNext()) {
  $Curses[] = [
    'NAME' => $arSection['NAME'],
    'CODE' => $arSection['CODE'],
    'ID' => $arSection['ID']
  ]; 
}
?>
    <div class="login__form">
        <form action="<?=SITE_TEMPLATE_PATH?>/ajax/reg.php" class="regform">
            <h2>Регистрация в системе</h2>
            <p class="error"></p>
            <label>
                <input type="text" name="mail" placeholder="E-mail*">
            </label>
            <label>
                <input type="text" name="name" placeholder="Имя*">
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
            <select name="courses" class="select" id="Curses">
                <option value="">Выберите курс</option>
                <?foreach ($Curses as $key => $Curs) {?>
                  <option value="<?=$Curs['ID']?>"><?=$Curs['NAME']?></option>
                <?}?>
            </select>
            <label>
                <input name="checkbox" type="checkbox">
                <p>Подтверждаю согласие c <a href="#" data-modal data-target="#user">Пользовательским соглашением</a> и <a href="#" data-modal data-target="#policy">Политикой конфиденциальности</a></p>
            </label>
            <button class="btn btn-secondary"><span>Зарегистрироваться</span></button>
            <hr>
            <p>Уже зарегистрировались? <a href="/auth/">Войти</a></p>
        </form>
    </div>
<script>
    $(".select").each(function () {
        const _this = $(this),
            selectOption = _this.find("option"),
            selectOptionLength = selectOption.length,
            selectedOption = selectOption.filter(":selected"),
            duration = 450; // длительность анимации

        _this.hide();
        _this.wrap("<div id='Curses' class='select'></div>");
        $("<div>", {
            class: "new-select",
            text: "Выберите курс"
        }).insertAfter(_this);

        const selectHead = _this.next(".new-select");
        $("<div>", {
            class: "new-select__list"
        }).insertAfter(selectHead);

        const selectList = selectHead.next(".new-select__list");
        for (let i = 1; i < selectOptionLength; i++) {
            $("<div>", {
                class: "new-select__item",
                html: $("<span>", {
                    text: selectOption.eq(i).text()
                })
            })
                .attr("data-value", selectOption.eq(i).val())
                .appendTo(selectList);
        }

        const selectItem = selectList.find(".new-select__item");
        selectList.slideUp(0);
        selectHead.on("click", function () {
            if (!$(this).hasClass("on")) {
                $(this).addClass("on");
                selectList.slideDown(duration);

                selectItem.on("click", function () {
                    let chooseItem = $(this).data("value");

                    $("select").val(chooseItem).attr("selected", "selected");
                    selectHead.text($(this).find("span").text());

                    selectList.slideUp(duration);
                    selectHead.removeClass("on");
                });

            } else {
                $(this).removeClass("on");
                selectList.slideUp(duration);
            }
        });
    });
</script>
<style>
    .select {
        display: block;
        width: 100%;
        position: relative;
    }
    .new-select {
        position: relative;
        cursor: pointer;
        user-select: none;
        padding: 19px 25px;
    }
    .new-select__list {
        position: absolute;
        top: 65px;
        left: 0;
        border: 1px solid transparent;
        border-radius: 10px;
        cursor: pointer;
        width: 100%;
        z-index: 5;
        background: #F9F9F9;
        user-select: none;
    }
    .new-select__list.on {
        display: block;
    }
    .new-select__item span {
        display: block;
        padding: 10px 15px;
    }
    .new-select__item span:hover {
        color: #E8B98B;
    }
    .new-select:after {
        content: "";
        display: block;
        width: 25px;
        height: 25px;
        position: absolute;
        right: 9px;
        top: 9px;
        background: url("path-to-image") no-repeat right center / cover;
        opacity: 0.6;
        transition: all .27s ease-in-out;
        transform: rotate(0deg);
    }
    .new-select.on:after {
        transform: rotate(180deg);
    }
</style>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>