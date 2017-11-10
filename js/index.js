$(document).ready(function() {
    $(".day .closer").click(function() {
        var div = $(this).parent().find(".body");

        if (div.height() === 0) {
            div.animateToAutoHeight({
                height: "auto"
            }, 300);
        } else {
            div.animate({
                height: "0px"
            }, 300);
        }
    });

    $(".todolist_btn div.btn").click(function() {
        var el = $(".todolist");
        if (el.width() > 0) {
            $(".todolist").stop();
            $(".todolist span").fadeOut(150, function() {
                $(".todolist").animate({
                    width: "0px",
                    height: "0px"
                }, 300);
            });

        } else {
            $(".todolist").stop();
            $(".todolist").animate({
                //width: 324px; height: 230px;
                width: "324px",
                height: "218px"
            }, 500, function() {
                $(".todolist span").fadeIn(200);
            });
        }
    });

    $(".day .header").click(function() {
        var elem = $(this).parent();
        var topP = $(this).offset().top - 62;
        var leftP = $(this).offset().left;

        $("#originalMysql").val(elem.data("content"));
        $("#de_events").val(elem.data("content"));
        $("#dayEventDay").val(elem.data("date"));

        $("#dayEvent").css({
            top: topP + "px",
            left: leftP + "px"
        }).fadeIn(500);
    });

    $("#dayEvent .close").click(function() {
        $("#dayEvent").fadeOut(500);
        $("#originalMysql").val("");
        $("#de_events").val("");
        $("#dayEventDay").val("");
    });


    $("#de_events").keypress(function(event) {
        if (event.which == 13) {
            event.preventDefault();

            var date = $("#dayEventDay").val();
            var originalMysql = $("#originalMysql").val();
            var mysql = $("#de_events").val();

            var request = $.ajax({
                url: "actions.php",
                type: "POST",
                data: {
                    type: "dayModify",
                    date: date,
                    originalMysql: originalMysql,
                    mysql: mysql
                },
                success: function(msg) {

                },
                error: function() {
                    console.log("Source was not found!");
                }
            });
        }
    });
    
    $(".day .header").mouseover(function(){
        if(!($(this).parent().hasClass("today"))){
            $(this).stop().animate({
                backgroundColor: "#3C85B3"
            }, 500);
        }
    }).mouseout(function(){
        if(!($(this).parent().hasClass("today"))){
            $(this).stop().animate({
                backgroundColor: "#4B4B4B"
            }, 500);
        }
    });
    
    /*$(".day .closer").mouseover(function(){
        $(this).stop().animate({
            backgroundColor: "#E3DBC8"
        }, 500);
    }).mouseout(function(){
        $(this).stop().animate({
            backgroundColor: "#B5A98D"
        }, 500);
    });*/

});