let colorSuccess = '#2ab27b';
let colorSuccessBorder = '#259d6d';
let colorDanger = '#bf5329';
let colorDangerBorder = '#aa4a24';
let colorBadge = 'rgb(30,203,182)';

let colorGary = '#aaabb1';

let currentAnwser = {
    'quiz_id': null,
    'result': null,
    'wrong_reason': null
};


$(document).ready(function(){

    // 答对按钮事件
    $("button.btn-success").on('click', function() {
        let divCurrent = $("div.current");
        let strQuiz_Id = divCurrent.data('quiz-id');
        let ihSelfCorrection = $('input[data-quiz-id="' + strQuiz_Id + '"]');

        currentAnwser.quiz_id = strQuiz_Id;
        currentAnwser.result = 1;

        $(this).siblings("button.btn-danger").attr(
            'style', 
            'background-color: ' + colorGary + ';border-color: ' + colorGary + ';'
        );
        $(this).attr('style', '');

        // console.log("btn-success clicked!" + strQuiz_Id);
    });

    // 答错按钮事件
    $("button.btn-danger").on('click', function() {
        let divCurrent = $("div.current");
        let strQuiz_Id = divCurrent.data('quiz-id');
        let ihSelfCorrection = $('input[data-quiz-id="' + strQuiz_Id + '"]');

        currentAnwser.quiz_id = strQuiz_Id;
        currentAnwser.result = -1;

        $(this).siblings("button.btn-success").attr(
            'style', 
            'background-color: ' + colorGary + ';border-color: ' + colorGary + ';'
        );
        $(this).attr('style', '');

        // console.log("btn-danger clicked!" + strQuiz_Id);
    });

    // 不认识按钮事件
    $("button.btn-primary").on('click', function() {
        let divCurrent = $("div.current");
        let strQuiz_Id = divCurrent.data('quiz-id');
        let ihSelfCorrection = $('input[data-quiz-id="' + strQuiz_Id + '"]');

        currentAnwser.quiz_id = strQuiz_Id;
        currentAnwser.wrong_reason = 'forget';

        $(this).siblings("button.btn-info").attr(
            'style', 
            'background-color: ' + colorGary + ';border-color: ' + colorGary + ';'
        );
        $(this).attr('style', '');

        // console.log("btn-unspell clicked!" + strQuiz_Id);
    });

    // 拼不出按钮事件
    $("button.btn-info").on('click', function() {
        let divCurrent = $("div.current");
        let strQuiz_Id = divCurrent.data('quiz-id');
        let ihSelfCorrection = $('input[data-quiz-id="' + strQuiz_Id + '"]');

        currentAnwser.quiz_id = strQuiz_Id;
        currentAnwser.wrong_reason = 'cannot_spell';

        $(this).siblings("button.btn-primary").attr(
            'style', 
            'background-color: ' + colorGary + ';border-color: ' + colorGary + ';'
        );
        $(this).attr('style', '');

        // console.log("btn-unknow clicked!" + strQuiz_Id);
    });

    // NEXT按钮事件
    $("a.btn-next").on('click', function() {
        if (!$("div.current").hasClass('last')) {
            if (currentAnwser.quiz_id === null || currentAnwser.result === null) {
                alert("不要留空选项！");
            } else if (currentAnwser.result === -1 && currentAnwser.wrong_reason === null) {
                alert("不要留空选项2！");
            } else {
                let divCurrent = $("div.current");
                let strQuiz_Id = divCurrent.data('quiz-id');
                let ihSelfCorrection = $('input[data-quiz-id="' + strQuiz_Id + '"]');
    
                save_anwser_to_hidden_field(ihSelfCorrection);
                // next div
                divCurrent.removeClass('current').addClass('hidden');
                divCurrent.next().removeClass('hidden').addClass('current');
                
                // init again
                divCurrent = $("div.current");
                strQuiz_Id = divCurrent.data('quiz-id');
                ihSelfCorrection = $('input[data-quiz-id="' + strQuiz_Id + '"]');
                load_anwser_from_hidden_field(ihSelfCorrection);
                init_button();
    
                // console.log("btn-next clicked!" + strQuiz_Id);
            }
        } else {
            if (currentAnwser.quiz_id === null || currentAnwser.result === null) {
                alert("不要留空选项！");
            } else if (currentAnwser.result === -1 && currentAnwser.wrong_reason === null) {
                alert("不要留空选项2！");
            } else {
                let divCurrent = $("div.current");
                let strQuiz_Id = divCurrent.data('quiz-id');
                let ihSelfCorrection = $('input[data-quiz-id="' + strQuiz_Id + '"]');
    
                save_anwser_to_hidden_field(ihSelfCorrection);

                if (ihSelfCorrection.val() !== '')
                {
                    alert("没有下一题了！请提交订正结果。");
                    submit_toggle();
                }
            }
        }
    });

    // PREV按钮事件
    $("a.btn-prev").on('click', function() {
        submit_toggle();
        if ($("div.current").prev()[0] !== undefined) {
            let divCurrent = $("div.current");

            // prev div
            divCurrent.removeClass('current').addClass('hidden');
            divCurrent.prev().removeClass('hidden').addClass('current');
            // init again
            divCurrent = $("div.current");
            strQuiz_Id = divCurrent.data('quiz-id');
            ihSelfCorrection = $('input[data-quiz-id="' + strQuiz_Id + '"]');
            load_anwser_from_hidden_field(ihSelfCorrection);
            init_button();

            // console.log("btn-prev clicked!" + strQuiz_Id);
        } else {
            alert("没有上一题了！");
        }
    });
});

function load_anwser_from_hidden_field(objInput) {
    let strAnwser = objInput.val();

    if (strAnwser === '') {
        currentAnwser.quiz_id = null;
        currentAnwser.result = null;
        currentAnwser.wrong_reason = null;
    } else {
        let objAnswer = strAnwser.split('::');
        currentAnwser.quiz_id = objAnswer[0];
        currentAnwser.result = objAnswer[1];
        currentAnwser.wrong_reason = objAnswer[2];
    }

    //console.log(strAnwser);
}

function save_anwser_to_hidden_field(objInput) {
    let strAnwser = currentAnwser.quiz_id + '::' + currentAnwser.result + '::' + currentAnwser.wrong_reason;

    //console.log(strAnwser);
    objInput.val(strAnwser);
}

function init_button() {
    $("button").attr("style", ' ');

    if (currentAnwser.result*1 === 1) {
        $("div.current").find("button.btn-danger").attr(
            'style', 
            'background-color: ' + colorGary + ';border-color: ' + colorGary + ';'
        );

        //$('div.current').find('button.btn-success').click();
    } else if (currentAnwser.result*-1 === 1) {
        $("div.current").find("button.btn-success").attr(
            'style', 
            'background-color: ' + colorGary + ';border-color: ' + colorGary + ';'
        );

        // $('div.current').find('button.btn-danger').click();
    }

    if (currentAnwser.wrong_reason === 'forget') {
        $("button.btn-info").attr(
            'style', 
            'background-color: ' + colorGary + ';border-color: ' + colorGary + ';'
        );

        // $("button.btn-primary").click();
    } else if (currentAnwser.wrong_reason === 'cannot_spell') {
        $("button.btn-primary").attr(
            'style', 
            'background-color: ' + colorGary + ';border-color: ' + colorGary + ';'
        );

        // $("button.btn-info").click();
    }
}

function submit_toggle() {
    if ($('div[data-quiz-id="'+currentAnwser.quiz_id+'"]').hasClass('last')) {
        $("div#submit_interface").removeClass("hidden");
    } else {
        $("div#submit_interface").removeClass("hidden").addClass("hidden");
    }
}