<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $cont;
if (CModule::IncludeModule("learning")) {
    $curs = CCourse::GetList(Array("SORT"=>"ASC"),Array());
    while ($arCourse = $curs->GetNext())
    {
    $cId[] = $arCourse["COURSE_ID"];
    sort($cId, SORT_NATURAL | SORT_FLAG_CASE);
    }
    }
    if($_REQUEST["COURSE_ID"]==""){
    $COURSE_ID = $cId[0];
    } else {
    $COURSE_ID = $_REQUEST["COURSE_ID"];
    }?>
<section>
    <div class="container">
        <?$APPLICATION->IncludeComponent(
            "bitrix:learning.course.detail",
            "course.detail",
            Array(
                "CACHE_TIME" => "3600",
                "CACHE_TYPE" => "A",
                "CHECK_PERMISSIONS" => "Y",
                "COMPONENT_TEMPLATE" => ".default",
                "COURSE_ID" => $COURSE_ID,
                "SET_TITLE" => "Y"
            )
        );?>
        <div class="quiz__start">
            <div class="quiz__start--header">
                <h3>Тест по теме «Обучение стажёров для поиска оптовых клиентов»</h3>
            </div>
            <div class="quiz__start--body">
                <div class="quiz__start--info">
                    <div><strong>Без ограничений</strong>
                        <p>Ограничение по времени: </p>
                    </div>
                    <div><strong>10 <i title="Tooltip">?</i></strong>
                        <p>Вопросов: </p>
                    </div>
                    <div><strong>7</strong>
                        <p>Количество попыток: </p>
                    </div>
                </div>
                <div class="quiz__start--btn"><a class="js-quiz btn btn-primary" href="#"><span>Пройти тест</span></a></div>
            </div>
        </div>
        <div class="lessons">
            <h3>Уроки</h3>
            <div class="lessons__slick"><a class="lessons__slick-item" style="background-image: url('assets/images/lsn.jpg')" href="#">
                    <div><span>Урок 1</span>
                        <p>Как искать лидов</p>
                    </div></a><a class="lessons__slick-item current" style="background-image: url('assets/images/lsn.jpg')" href="#">
                    <div><span>Урок 2</span>
                        <p>Как искать лидов</p>
                    </div></a><a class="lessons__slick-item lock" style="background-image: url('assets/images/lsn.jpg')" href="#">
                    <div><span>Урок 3</span>
                        <p>Как искать лидов</p>
                    </div></a><a class="lessons__slick-item lock" style="background-image: url('assets/images/lsn.jpg')" href="#">
                    <div><span>Урок 4</span>
                        <p>Как искать лидов</p>
                    </div></a><a class="lessons__slick-item lock" style="background-image: url('assets/images/lsn.jpg')" href="#">
                    <div><span>Урок 5</span>
                        <p>Как искать лидов</p>
                    </div></a><a class="lessons__slick-item lock" style="background-image: url('assets/images/lsn.jpg')" href="#">
                    <div><span>Урок 6</span>
                        <p>Как искать лидов</p>
                    </div></a><a class="lessons__slick-item lock" style="background-image: url('assets/images/lsn.jpg')" href="#">
                    <div><span>Урок 7</span>
                        <p>Как искать лидов</p>
                    </div></a><a class="lessons__slick-item lock" style="background-image: url('assets/images/lsn.jpg')" href="#">
                    <div><span>Урок 8</span>
                        <p>Как искать лидов</p>
                    </div></a></div>
        </div>
    </div>
    <nav><a class="collapse" href="#"></a>
        <ul>
            <li><a class="done" href="#"><span>Урок 1</span>
                    <p>Обучение стажеров для поиска оптовых клиентов</p></a></li>
            <li><a class="current" href="#"><span>Урок 2</span>
                    <p>Введение в CRM</p></a></li>
            <li><a href="#"><span>Урок 3</span>
                    <p>Как искать лидов</p></a></li>
            <li><a href="#"><span>Урок 4</span>
                    <p>Коммерческое предложение</p></a></li>
            <li><a href="#"><span>Урок 5</span>
                    <p>Отправка клиента</p></a></li>
            <li><a href="#"><span>Урок 6</span>
                    <p>Экзамен</p></a></li>
            <li><a href="#"><span>Урок 7</span>
                    <p>Проверочный курс</p></a></li>
            <li><a href="#"><span>Урок 8</span>
                    <p>Два ответа</p></a></li>
        </ul>
    </nav>
</section>