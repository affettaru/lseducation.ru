var tooltip = 0;

$(function () {
    $(".regform").submit(function (event) {
        var form = $(this);
        $.post($(this).attr('action'), $(this).serialize(), function (data) {
            var result = $.parseJSON(data);
            if (result.status == "success") {
                $('#reg').toggleClass('active');
                $('body').toggleClass('active');
                setTimeout(() => {
                    window.location.href = "/auth/";
                }, 4500);

            } else {
                if (result.status == "confirm") {

                } else {
                    form.find(".error").html(result.message);
                }
            }
        });
        event.preventDefault();
    });

    $(document).on('click', '.quiz__start--btn', function () {
        $('.quiz__start').hide();

        $('.uiz-ajax').show();
    });

    $(document).on('click', '.button-back', function () {
        let back_id = $(this)[0].id
        let countId = $(this).attr('data_id')

        $.post("/local/templates/lifestyle/ajax/quiz.php", {'back_id': back_id, 'count_id': countId}, function (data) {
                var result = $.parseJSON(data);

                if (result.status == "success") {

                    let answers = result.last_answer;


                    $(".uiz_block-ajax").load('/local/templates/lifestyle/ajax/theme.php', {
                        'ajax': 'Y',
                        'back_id': back_id,
                        'count_id': countId,
                        'answers': answers
                    });
                    setTimeout(() => {
                        $(".quiz__start--btn").click()

                    }, 300);

                }
            }
        );


    })

    $(document).on('submit', '.quiz_sub_form', function (event) {
        //$(".quiz_sub_form").submit(function (event){
        var form = $(this);
        form.find('.btn').attr("disabled", "disabled");
        $.post($(this).attr('action'), $(this).serialize(), function (data) {
            var result = $.parseJSON(data);
            if (result.status == "success") {
                $(".quiz__start--btn").click();

                function addParameterToURL(param) {
                    _url = location.href;
                    _url += (_url.split('?')[1] ? '&' : '?') + result.url;
                    return _url;
                }

                let restart = 'N'
                if (result.restart == 'Y') {
                    restart = 'Y'
                }

                $(".uiz_block-ajax").load('/local/templates/lifestyle/ajax/theme.php', {
                    'ajax': 'Y',
                    'restart': restart
                });

                setTimeout(() => {
                    $(".quiz__start--btn").click();

                }, 300);
            } else {
                if (result.status == "confirm") {
                    setTimeout(() => {
                        location.reload()
                    }, 400);
                } else {
                    form.find(".error").html(result.message);
                }
            }
        });
        event.preventDefault();
    });

    // //Вы не прошли тест
    $(document).on('click', '.fail', function () {
        $('.fails').load('/local/inc/ajax/fail.php', {});
        $('.quiz__block').hide();
        console.log()
        if ($('#reload').length) {
            location.reload()
        } else {
            $('.uiz-ajax').show();
        }
    });

    //Вы прошли тест
    $(document).on('click', '.gold-as', function () {
        $('.gold_as--yes').load('/local/inc/ajax/success.php', {'name': $(this).val()});
        setTimeout(() => {
            //location.reload(); 
            window.location.href = '/';
        }, 300);
    });

    //Скрыть первый квиз 
    $(".quiz__as__no").click(function () {
        setTimeout(() => {
            location.reload()
        }, 300);
        $('.as-no-quizs').load('/local/inc/ajax/no_quiz.php', {'no_quiz': "Y"});
    });

    //Загружаем видео
    $(".load_video").click(function () {
        $('.as-load-video').load('/local/templates/lifestyle/ajax/video.php', {
            'tip': $(this).attr('tip'),
            'video': $(this).attr('data-rel')
        });
    });

    //AMAZON
    //     var PLAYBACK_URL =  'https://lstraning.s3.us-east-2.amazonaws.com/output/'+P_URL;
    //     if (IVSPlayer.isPlayerSupported) {
    //         const player = IVSPlayer.create();
    //         player.attachHTMLVideoElement(document.getElementById('video-player'));
    //         player.load(PLAYBACK_URL);
    //         player.play();
    //     }
//     дает ошибку
});
