$(document).ready(function() {
    var configId = $("#nova_ext_ordered_config_setting").val();
    showHideFields(configId);


    $(document).on("change", "#nova_ext_ordered_config_setting", function(e) {
        configId = $(this).val();
        showHideFields(configId);
    });

    function showHideFields(configId) {

        if (configId == 'day_time') {
            hideTimeLine();
            showDayTime();
            hideDateTime();
            hideStartDateTime();
        } else if (configId == 'date_time') {
            hideTimeLine();
            hideDayTime();
            hideStartDateTime();
            showDateTime();
        } else if (configId == 'startdate') {
            hideTimeLine();
            hideDayTime();
            hideDateTime();
            showStartDateTime();
        } else {
            showTimeLine();
            hideDayTime();
            hideDateTime();
            hideStartDateTime();
        }
    }

    function hideTimeLine() {
        $("#timeline").prev().css("display", "none");
        $("#timeline").css("display", "none");


        $('[name="post_timeline"]').prev().css("display", "none");
        $('[name="post_timeline"]').css("display", "none");

        $(".nova_ext_ordered_label_post_time").css("display", "block");
    }

    function showTimeLine() {
        $("#timeline").prev().css("display", "block");
        $("#timeline").css("display", "block");

        $('[name="post_timeline"]').prev().css("display", "block");
        $('[name="post_timeline"]').css("display", "block");
        $(".nova_ext_ordered_label_post_time").css("display", "none");
    }

    function hideDayTime() {
        $(".nova_ext_ordered_label_post_day").css("display", "none");
    }

    function showDayTime() {
        $(".nova_ext_ordered_label_post_day").css("display", "block");
    }

    function hideDateTime() {
        $(".nova_ext_ordered_label_post_date").css("display", "none");
    }

    function showDateTime() {
        $(".nova_ext_ordered_label_post_date").css("display", "block");
    }

    function hideStartDateTime() {
        $(".nova_ext_ordered_label_post_stardate").css("display", "none");
    }

    function showStartDateTime() {
        $(".nova_ext_ordered_label_post_stardate").css("display", "block");
    }
});