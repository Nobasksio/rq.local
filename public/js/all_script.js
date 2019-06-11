//create_team
//admin
$(function () {
    $(".name_user1").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "ajax/search_user.php",
                dataType: "json",
                data: {
                    term: request.term
                },

                success: function (data) {
                    response(data)
                }
            });
        },
        minLength: 2,
        select: function (event, ui) {
            $.ajax({
                url: "ajax/add_people_team.php",
                dataType: "json",
                data: {
                    id_new_man: ui.item.id,
                    type: 1
                },
                success: function () {
                    $("#admin").append(ui.item.label);
                    $(".name_user1").val("");
                }
            })
        }
    });
});
//povar
$(function () {
    $(".name_user2").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "ajax/search_user.php",
                dataType: "json",
                data: {
                    term: request.term
                },

                success: function (data) {
                    response(data)
                }
            });
        },
        minLength: 2,
        select: function (event, ui) {
            $.ajax({
                url: "ajax/add_people_team.php",
                dataType: "json",
                data: {
                    id_new_man: ui.item.id,
                    type: 2
                },
                success: function (data) {
                    $("#povar").append(ui.item.label);
                }
            })
        }
    });
});
//buh
$(function () {
    $(".name_user3").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "ajax/search_user.php",
                dataType: "json",
                data: {
                    term: request.term
                },

                success: function (data) {
                    response(data)
                }
            });
        },
        minLength: 2,
        select: function (event, ui) {
            $.ajax({
                url: "ajax/add_people_team.php",
                dataType: "json",
                data: {
                    id_new_man: ui.item.id,
                    type: 3
                },
                success: function (data) {
                    $("#bar").append(ui.item.label);
                }
            })
        }
    });
});
//marketolog
$(function () {
    $(".name_user4").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "ajax/search_user.php",
                dataType: "json",
                data: {
                    term: request.term
                },

                success: function (data) {
                    response(data)
                }
            });
        },
        minLength: 2,
        select: function (event, ui) {
            $.ajax({
                url: "ajax/add_people_team.php",
                dataType: "json",
                data: {
                    id_new_man: ui.item.id,
                    type: 4
                },
                success: function (data) {
                    $("#buh").append(ui.item.label);
                }
            })
        }
    });
});
//barmanager
$(function () {
    $(".name_user4").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "ajax/search_user.php",
                dataType: "json",
                data: {
                    term: request.term
                },

                success: function (data) {
                    response(data)
                }
            });
        },
        minLength: 2,
        select: function (event, ui) {
            $.ajax({
                url: "ajax/add_people_team.php",
                dataType: "json",
                data: {
                    id_new_man: ui.item.id,
                    type: 5
                },
                success: function (data) {
                    $("#marketolog").append(ui.item.label);
                }
            })
        }
    });
});


//create team
function creacte_team(roll) {
    var email = $("#roll" + roll).val();
    if (roll == 0) {
        var arr_team = '';
        $("[class ^= roll]").each(function (indx, element) {
            var email = $(element).val(),
                class_string = $(element).attr("class"),
                roll = class_string.substring(4, 5);
            if (email != '') {
                arr_team = arr_team + roll + ":" + email + "}";
            }
        });
    } else {

        $.ajax({
            url: 'ajax/save_team_aj.php',
            data: 'email=' + email + "&roll=" + roll,
            dataType: "text",
            success: function (data) {

                $("#ok_done2").show();
                $("#roll" + roll).val("");
                $("#all_people_roll" + roll).append(email);
            }
        })
    }
}
//create team
function add_people(roll) {
    creacte_team(roll);
    // $('#last'+roll).append("<div class='new_str_inpit'>"
    //     +"<input type='text' class='roll"+roll+" input_medium name_user1' placeholder='e-mail' value=''>"
    //     +"</div>");
}
function del_user(id_user, roll) {
    $.ajax({
        url: 'ajax/del_user.php',
        data: 'id_user=' + id_user + "&roll=" + roll,
        dataType: "text",
        success: function (data) {
            if (data == 0) {
                $("#user" + id_user + "roll" + roll).addClass("del_tov_tr del_tov_text");

            } else {
                $("#user" + id_user + "roll" + roll).removeClass("del_tov_tr del_tov_text");

            }
        }
    })
}
//create team
$("#nametype").autocomplete({
    source: function (request, response) {
        $.ajax({
            url: "ajax/search.php",
            dataType: "json",
            data: {
                term: request.term
            },
            success: function (data) {
                response(data);
            }
        });
    },

});

//table_king
$(function () {
    $("[type=radio]").checkboxradio({
        icon: false
    });
    $(".controlgroup").controlgroup();
    $(".controlgroup2").controlgroup();
});

//table_king
function swich_type(type) {
    $.ajax({
        url: 'ajax/swich_file_king_type.php',
        data: "type=" + type,
        dataType: "html",
        success: function (data) {
            $('#type_blc').html(data);

        }
    });
}
function swich_type_design(type,hash) {
    $.ajax({
        url: 'ajax/swich_file_design.php',
        data: "type=" + type+"&hash="+hash,
        dataType: "html",
        success: function (data) {
            $('#type_blc').html(data);

        }
    });
}

//table_king
function swich_cat_king(id_analit) {
    $.ajax({
        url: 'ajax/swich_file_king.php',
        data: "id_analiz=" + id_analit,
        dataType: "html",
        success: function (data) {
            $('#table').html(data);
        }
    });
}


//new_abc
$(function () {
    $(".date").datepicker({
        altFormat: "dd.mm.yy",
        dateFormat: "dd.mm.yy"
    });
});

//abc_result
function click_remake(project_id, old_product_id) {
    if (!$('#check_ch' + id_tov).is(':checked')) {
        if ($('#remake_ch' + id_tov).is(':checked')) {
            $('#remake_ch' + id_tov).prop('checked', false);
        }
    }
}

//abc_result
function click_move_to_menu(project_id, old_product_id) {
    $.ajax({
        url: '/ajax/move_to_menu/'+project_id+'/'+old_product_id,
        dataType: "html",
        success: function (data) {

        }
    });
}
function click_add(project_id, old_product_id) {
    if (!$('#check_ch' + old_product_id).is(':checked')) {
        $('#check_ch' + old_product_id).click();
    }
}

function hide_analitics(obj, project_id,analitic_id) {

    var elem;
    elem = $(obj).closest('tr');
    elem.addClass('bad_td');
    $.ajax({
        url: '/ajax/'+project_id+'/analitics/hide/'+analitic_id,
        dataType: "html",
        success: function (data) {


              elem.remove();

        }
    });
}

//abc_result
function del_cat(id_cat) {
    $.ajax({
        url: 'ajax/del_cat_aj.php',
        data: "id_cat=" + id_cat,
        dataType: "html",
        success: function (data) {
            if (data == 1) {
            }
            else if (data == 0) {
                $("#table").html("Группа успешно удалена");
                $("#but_del").html("ой. я случайно. востановите пожалуйста");
            }
        }
    });
}

//abc_result
function save_comment_any_where(id_tov, id_cat, wherecom) {
    let comment;

    if (id_tov === 0) {
        comment = $("#comment" + id_cat).val();
    } else {
        comment = $("#comment" + id_tov).val();
    }

    $.ajax({
        url: 'ajax/save_comment_aj.php',
        data: "id_tov=" + id_tov + "&comment=" + comment + "&wherecom=" + wherecom + "&id_cat=" + id_cat,
        dataType: "html",
        success: function (data) {
            if (id_tov === 0) {
                $("#comment_td" + id_cat).prepend(comment);
            } else {
                $("#comment_td" + id_tov).prepend(comment);
            }

            $("#comment" + id_tov).val("");
        }
    });
}

//abc_result
function download(hash) {
    $.ajax({
        url: 'ajax/table.php',
        data: "hash=" + hash,
        dataType: "html",
        success: function (data) {
            document.location.href = "/otch/" + data;
        }
    });
}


//abc_result
function menu_fix() {
    var $table = $('table'),
        $header = $('#header'),
        $thead = $('thead');
    $thead.find('th').each(function () {
        var style_m = $(this).attr('class'),
            $newdiv;

        if (style_m != "") {
            $newdiv = $('<div />', {
                style: 'width:' + $(this).width() + 'px'
            });
        }
        else {
            $newdiv = $('<div />', {
                style: 'width:' + ($(this).width() - 20) + 'px'
            });
        }

        $newdiv.html($(this).html());
        $header.append($newdiv);

    });

    var $viewport = $(window);
    $viewport.scroll(function () {
        $header.css({
            left: -$(this).scrollLeft()
        });

    });
}

//abc_result
function move_to_menu() {
    var all_check = '',
        remake = '',
        kolv = 0;
    $("input:checked[class='check']").each(function (indx, element) {
        var substringArray = $(element).val().split(","),
            test = '';
        id_tov = substringArray[0];
        kolv++;
        if (all_check != "") {
            all_check = all_check + "," + id_tov;
        }
        else {
            all_check = id_tov;
        }
        if ($("#remake_ch" + id_tov).is(':checked')) {
            if (remake != "") {
                remake = remake + "," + id_tov;
            }
            else {
                remake = id_tov;
            }
        }

    });
    $.ajax({
        url: 'ajax/move_to_menu.php',
        data: 'ids_bl=' + all_check + '&remake=' + remake,
        dataType: "json",
        success: function (data) {
            var text = '';
            if (data['already'] > 0) {
                text = "<p>Успешно перенесено." + data['add'] + " блюд.<br> " + data['already'] + " уже были перенесены в меню раньше</p>";
            }
            else {
                text = "<p>Успешно перенесено." + data['add'] + " блюд.</p>";
            }
            $("#dialog-message").html(text);
            $(function () {
                $("#dialog-message").dialog({
                    modal: true,
                    buttons: {
                        Ok: function () {
                            $(this).dialog("close");
                        }
                    }
                });
            });
            $("input:checked").each(function (indx, element) {
                var substringArray = $(element).val().split(","),
                    id_tov2 = substringArray[0];
                $('#tr' + id_tov2).removeClass("select_tr");
                $('#tr' + id_tov2).addClass("good_td");
            });
        }
    })
}

//abc_result
function all_check() {
    $(".check").each(function (indx, element) {
        $(element).click();
    })
    $("#all_check_span").html("<span class='redact_grey_smal'>Отменить<br>выбор</span>");
    $("#all_check_span").attr("onClick", "all_uncheck()");
}

//abc_result
function all_uncheck() {
    $(".check").each(function (indx, element) {
        $(element).click();
    })
    $("#all_check_span").html("<span class='redact_grey_smal'>Выбрать<br>всё</span>");
    $("#all_check_span").attr("onClick", "all_check()");
}

//abc_result
function setLocation(curLoc) {
    try {
        history.pushState(null, null, curLoc);
        return;
    } catch (e) {
    }
    location.hash = '#' + curLoc;
}

//abc_result
function svodka(project_id,analitic_id) {
    $.ajax({
        type: "GET",
        url: '/ajax/svodka/'+analitic_id,
        dataType: "html",
        success: function (data) {
            if (data == 1) {
                $('#del').html("Текущая категория удалена. Обновите страницу");
            }
            $('#table').html(data);
            setLocation('/project/'+project_id+'/analitics/svodka/'+analitic_id);
        }
    });
}

//abc_result
$(function () {
    $("[type=radio]").checkboxradio({
        icon: false
    });
});

//abc_result
function swich_cat(project_id,analitic_id,old_category) {
    $('#table').html('');
    $.ajax({
        type: "GET",
        url: '/ajax/project/'+project_id+'/analitics/'+analitic_id+'/'+old_category,
        dataType: "html",
        success: function (data) {
            $('#table').html(data);
            $('#menu' + old_category).addClass('menuact');
            $('#del_cat_div').html("<div class='name_cat' onclick='del_cat(" + analitic_id + ")'>Удалить эту категорию</div>");
            setLocation('/project/'+project_id+'/analitics/'+analitic_id+'/'+old_category);
            //menu_fix();
        }
    });
}

//abc_result
function swich_cat_singl(id_tov, id_cat) {
    $('#group' + id_tov).attr("onclick", "");
    $.ajax({
        type: "GET",
        url: "spisok_cat.php",
        dataType: "html",
        // параметры запроса, передаваемые на сервер (последний - подстрока для поиска):
        data: 'id_cat=' + id_cat,

        // обработка успешного выполнения запроса
        success: function (data) {
            $('#group' + id_tov).html("<select class='input_medium2' onchange='select_swich_cat(" + id_tov + "," + id_cat + ")' id='sel" + id_tov + "'> " + data + "</select> ");
        }
    });
}

//abc_result
function select_swich_cat(id_tov, id_cat) {
    var new_cat = $("#sel" + id_tov).val();
    $.ajax({
        type: "GET",
        url: "ajax/group_change.php",
        dataType: "html",
        // параметры запроса, передаваемые на сервер (последний - подстрока для поиска):
        data: 'id_tov=' + id_tov + '&new_cat=' + new_cat + '&old_cat=' + id_cat,

        // обработка успешного выполнения запроса
        success: function (data) {
            if (data == 1) {
                //alert('Категория группы изменена. Обновите страницу');
            }
            else {
                //alert('Произошла ошибка. Свяжитесь с администратором');
            }
        }
    })
}
//abc_result

function click_disregard(id_tov) {
    var check = $("#disregard_ch" + id_tov).is(':checked');

    $.ajax({
        type: "GET",
        url: "ajax/disregard_aj.php",
        dataType: "html",
        // параметры запроса, передаваемые на сервер (последний - подстрока для поиска):
        data: 'id_tov=' + id_tov + '&check=' + check,

        // обработка успешного выполнения запроса
        success: function (data) {
            if (data == 1) {
                //alert('Категория группы изменена. Обновите страницу');
            }
            else {
                //alert('Произошла ошибка. Свяжитесь с администратором');
            }
        }
    })
    $("#refresh").show();
}


//ing_creat_cat

function type_cat(project_id, category_id, new_type) {
    $.ajax({
        url: '/ajax/'+project_id+'/inginiring/category/'+category_id+'/update_type',
        data: "new_type=" + new_type,
        dataType: "html",
        success: function (data) {

        }
    });

}

//ing_creat_cat
$(function () {
    $(".type_cat_class [type=radio]").checkboxradio({
        icon: false
    });
    $(".controlgroup").controlgroup(
    )

});

//ing_creat_cat
function done_cat() {
    $("#ok_done2").show();
}

//ing_creat_cat
function addcat(project_id) {
    var nameb = $("#nameb").val(),
        kol_blud = $(".tr_order3:last > td[id^='num'] ").text();
    if (nameb != "") {
        $.ajax({
            url: '/ajax/'+project_id+'/inginiring/category/new',
            data: 'category_name=' + nameb,
            dataType: "html",
            success: function (data) {
                kol_blud++
                $("#end_tr").before("<tr class='tr_order3'>"
                    + "<td class='td_ord_table' id='num" + data + "'>" + kol_blud + "</td>"
                    + "<td class='td_ord_group'id='name_tr" + data + "'>" + nameb + "</td>"
                    + "<td class='td_ord_table' id='name"+ data +"'>"
                        + "<div class='controlgroup'>"
                            + "<label for='kitch"+data+"' onclick='type_cat("+project_id+","+data+",1)'>К</label>"
                            + "<input type='radio' class='type_cat_class' name='type_cat"+data+"' checked id='kitch"+data+"'>"
                            + "<label for='bar"+data+"' onclick='type_cat("+project_id+","+data+",2)'>Б</label>"
                            + "<input type='radio' class='type_cat_class' name='type_cat"+data+"'  id='bar"+data+"'>"
                            + "<div class='help'></div>"
                        + "</div>"
                    + "</td>"
                    + "<td class='td_ord_table' id='edit_tr" + data + "' onclick='readactcat("+project_id+"," + data + ")' ><span class='redact_grey2_smal' id='edit" + data + "'>ред.</span></td>"
                    + "<td class='td_ord_table' id='del_tr" + data + "' onclick='delcat("+project_id+"," + data + ",2)'><span class='redact_grey2_smal' id='del" + data + "'>X</span></td>"
                    + "</tr>");
                $("#nameb").val("");

                    $(".type_cat_class").checkboxradio({
                        icon: false
                    });
                    $(".controlgroup").controlgroup(
                    )


            }
        })

    }

}

//ing_creat_cat
function readactcat(project_id,category_id) {
    var name = $("#name_tr" + category_id).text();
    $("#name_tr" + category_id).html("<input type='text' class='input_min' id='name" + category_id + "' value='" + name + "'>");
    $("#edit" + category_id).text("сохр");
    $("#edit_tr" + category_id).attr('onclick', "save_redact_cat("+project_id+"," + category_id + ",3)");
}

//ing_creat_cat
function save_redact_cat(project_id,category_id, doing) {
    var name = $("#name" + category_id).val();
    $.ajax({
        url: '/ajax/'+project_id+'/inginiring/category/'+category_id+'/update_name',
        data: 'new_name=' + name,
        dataType: "html",
        success: function (data) {
            $("#name_tr" + category_id).html(name);
            $("#edit" + category_id).text("ред");
            $("#edit_tr" + category_id).attr('onclick', "readactcat("+project_id+"," + category_id + ")");
        }
    })
}

//ing_creat_cat
function delcat(project_id,category_id) {
    $.ajax({
        url: '/ajax/'+project_id+'/inginiring/category/'+category_id+'/hide',
        dataType: "html",
        success: function (data) {
            if (data == 1) {
                $("#tr" + category_id).removeClass('bad_td', 500);
            } else {
                $("#tr" + category_id).addClass('bad_td');
            }
        }
    })
}

//ing_dish_for_cat
function save_kol_blud(id_project) {
    var arr_kol = '';
    $("[id ^= kol]").each(function (indx, element) {
        var kol_all = $(element).val(),
            id_str = $(element).attr("id"),
            id_cat = id_str.substring(3);
        if (kol_all != '') {
            arr_kol = arr_kol + id_cat + ":" + kol_all + "}";
        }
    });
    $.ajax({
        url: '/ajax/project/'+id_project+'/inginiring/count_dish',
        data: 'arr_kol=' + arr_kol,
        dataType: "text",
        success: function (data) {
            $("#ok_done2").show();
        }
    })
}

//ing_ss_for_cat
function swich_type_ing_ss(type) {
    $.ajax({
        url: 'ajax/swich_ing_ss_for_cat_aj.php',
        data: "type=" + type,
        dataType: "html",
        success: function (data) {
            $('#swich_place').html(data);
            $('#but_save').attr("onclick", 'save_ss(' + type + ')');
        }
    });
}

//ing_ss_for_cat
$(function () {
    $(document).tooltip();
});

//ing_ss_for_cat 
function save_ss(type) {
    var arr_ss = '',
        new_ss = $("#new_ss").val();
    $("[id ^= new_ss_cat]").each(function (indx, element) {
        var ss_cat = $(element).val(),
            id_str = $(element).attr("id"),
            id_cat = id_str.substring(10);
        if (ss_cat != '') {
            arr_ss = arr_ss + id_cat + ":" + ss_cat + "}";
        }
    });

    $.ajax({
        url: 'ajax/save_ss_ing_aj.php',
        data: 'arr_ss=' + arr_ss + "&new_ss=" + new_ss + "&type=" + type,
        dataType: "text",
        success: function (data) {
            $("#ok_done2").show();
        }
    })
}

//ing_price_cat
function save_cat_price() {
    var id_activ = $('input:checked').val(),
        minprice = $("#min").val(),
        medprice1 = $("#medprice1").text(),
        medprice2 = $("#medprice2").text(),
        maxprice = $("#max").val(),
        kol_inexp = $("#amountmin").val(),
        kol_med_price = $("#amountmed").val(),
        kol_exp = $("#amountmax").val();
    $.ajax({
        url: 'ajax/save_price_cat_ing_aj.php',
        data: 'id_cat=' + id_activ +
        '&minprice=' + minprice +
        '&medprice1=' + medprice1 +
        '&medprice2=' + medprice2 +
        '&maxprice=' + maxprice +
        '&kol_inexp=' + kol_inexp +
        '&kol_med_price=' + kol_med_price +
        '&kol_exp=' + kol_exp,
        dataType: "text",
        success: function (data) {
            $("#ok_done2").show();
            if (!$("span").is("#done_cat" + id_activ)) (
                $("#but_cat" + id_activ).append("<span class='done_ing' id='done_cat" + id_activ + "'>готово</span>")
            )
        }
    })
}

//ing_price_cat
function swich_cat_price_ing(id_analit) {
    $.ajax({
        url: 'ajax/swich_file_ing_price_cat_aj.php',
        data: "id_analiz=" + id_analit,
        dataType: "html",
        beforeSend: function () {
            showb();
        },
        success: function (data) {
            $('#swich_part').html(data);

            hideb();
        }
    });
}

//ing_tz_cooker
function swich_type_tz_cooker(type, type_tz) {
    $.ajax({
        url: 'ajax/swich_ing_tz_aj.php',
        data: "type=" + type + "&type_tz=" + type_tz,
        dataType: "html",
        success: function (data) {
            $('#swich_place').html(data);
            creat_tz
            $('#savebut').attr("onclick", "creat_tz(" + type + ")");
        }
    });
}

//ing_tz_cooker
function save_comment() {
    var i = 1,
        arr_comment = '';
    $("[id ^=comment]").each(function () {
        var comment = $(this).val(),
            idattr = $(this).attr('id'),
            id_cat = idattr.slice(7);
        arr_comment = arr_comment + id_cat + ":" + comment + "}";
    });
    $.ajax({
        url: 'ajax/save_comment_tz_aj.php',
        data: 'arr_comment=' + arr_comment,
        dataType: "html",
        success: function (data) {
            $("#ok_done2").show();
        }
    })
};

//ing_tz_cooker
function creat_tz(type) {
    if (type != 0) {
        var date = $("#date").val(),
            discr = $("#tzcomment").val();
    }
    else {
        var date_k = $("#date_k").val(),
            date_b = $("#date_b").val(),
            tzcommvaent_k = $("#tzcomment_k").val(),
            tzcomment_b = $("#tzcomment_b").val(),
            date = date_k + "}" + date_b,
            discr = tzcommvaent_k + "}" + tzcomment_b
        ;
    }
    save_comment();
    $.ajax({
        url: 'ajax/creat_tz_aj.php',
        data: 'date=' + date +
        '&discr=' + discr + '&type=' + type,
        dataType: "html",
        success: function (data) {
            $("#ok_done2").show();
        }
    })
}


//ing_tz_cooker
function readact_cat_tz(id_cat) {
    var low_kol = $("#low_kol" + id_cat).text(),
        med_kol = $("#med_kol" + id_cat).text(),
        exp_kol = $("#exp_kol" + id_cat).text(),
        price1_low = $("#price1_low" + id_cat).text(),
        price2_low = $("#price2_low" + id_cat).text(),
        price1_med = $("#price1_med" + id_cat).text(),
        price2_med = $("#price2_med" + id_cat).text(),
        price1_max = $("#price1_max" + id_cat).text(),
        price2_max = $("#price2_max" + id_cat).text(),
        comment = $("#comment" + id_cat).val();

    $("#low_kol" + id_cat).html("<input type='text' class='input_td_30' value='" + low_kol + "' id='low_kol_inp" + id_cat + "'>");
    $("#med_kol" + id_cat).html("<input type='text' class='input_td_30' value='" + med_kol + "' id='med_kol_inp" + id_cat + "'>");
    $("#exp_kol" + id_cat).html("<input type='text' class='input_td_30' value='" + exp_kol + "' id='exp_kol_inp" + id_cat + "'>");

    $("#price1_low" + id_cat).html("<input type='text' class='input_td_30' value='" + price1_low + "' id='price1_low_inp" + id_cat + "'>");
    $("#price2_low" + id_cat).html("<input type='text' class='input_td_30' value='" + price2_low + "' id='price2_low_inp" + id_cat + "'>");

    $("#price1_med" + id_cat).html("<input type='text' class='input_td_30' value='" + price1_med + "' id='price1_med_inp" + id_cat + "'>");
    $("#price2_med" + id_cat).html("<input type='text' class='input_td_30' value='" + price2_med + "' id='price2_med_inp" + id_cat + "'>");

    $("#price1_max" + id_cat).html("<input type='text' class='input_td_30' value='" + price1_max + "' id='price1_max_inp" + id_cat + "'>");
    $("#price2_max" + id_cat).html("<input type='text' class='input_td_30' value='" + price2_max + "' id='price2_max_inp" + id_cat + "'>");

    $("#edit" + id_cat).text("сохр");
    $("#edit_tr" + id_cat).attr('onclick', "save_redact_tz(" + id_cat + ")");
}

//ing_tz_cooker
function save_redact_tz(id_cat) {
    var low_kol = $("#low_kol_inp" + id_cat).val(),
        med_kol = $("#med_kol_inp" + id_cat).val(),
        exp_kol = $("#exp_kol_inp" + id_cat).val(),
        price1_low = $("#price1_low_inp" + id_cat).val(),
        price2_low = $("#price2_low_inp" + id_cat).val(),
        price1_med = $("#price1_med_inp" + id_cat).val(),
        price2_med = $("#price2_med_inp" + id_cat).val(),
        price1_max = $("#price1_max_inp" + id_cat).val(),
        price2_max = $("#price2_max_inp" + id_cat).val(),
        comment = $("#comment" + id_cat).val();
    $.ajax({
        url: 'ajax/save_red_tz_aj.php',
        data: 'low_kol=' + low_kol +
        '&med_kol=' + med_kol +
        '&exp_kol=' + exp_kol +
        '&price1_low=' + price1_low +
        '&price2_low=' + price2_low +
        '&price1_med=' + price1_med +
        '&price2_med=' + price2_med +
        '&price1_max=' + price1_max +
        '&price2_max=' + price2_max +
        '&comment=' + comment +
        '&id_cat=' + id_cat,
        dataType: "html",
        success: function (data) {
            low_kol = $("#low_kol" + id_cat).text(low_kol),
                med_kol = $("#med_kol" + id_cat).text(med_kol),
                exp_kol = $("#exp_kol" + id_cat).text(exp_kol),
                price1_low = $("#price1_low" + id_cat).text(price1_low),
                price2_low = $("#price2_low" + id_cat).text(price2_low),
                price1_med = $("#price1_med" + id_cat).text(price1_med),
                price2_med = $("#price2_med" + id_cat).text(price2_med),
                price1_max = $("#price1_max" + id_cat).text(price1_max),
                price2_max = $("#price2_max" + id_cat).text(price2_max),
                comment = $("#comment" + id_cat).val(comment);
            $("#edit" + id_cat).text("ред");
            $("#edit_tr" + id_cat).attr('onclick', "readact_cat_tz(" + id_cat + ")");
            $("tr" + id_cat).addClass('good_td');
            $("tr" + id_cat).removeClass('good_td', 500);
        }
    })
}
//_single_degust
$("#bludo_swich").change(function () {
    let id_tov = $("#bludo_swich").val(),
        size = $('#select option').size(),
        i = -1;

    $('#bludo_swich option').each(function () {
        i++;
        if ($(this).val() == id_tov) {
            slider.goToSlide(i);

        }

    });
    // data_rate();
});

//single_degust
function raitdo(type, rait) {
    var id_tov = $("#bludo_swich").val(),
        price = $("#price" + id_tov).val(),
        comment = $("#comment" + id_tov).val();

    $.ajax({
        url: 'save_rate.php',
        data: 'rait=' + rait +
        '&bludo=' + id_tov +
        '&type=' + type +
        '&price=' + price +
        '&comment=' + comment,
        dataType: "text",
        success: function (data) {
            showb();
            if (type == 1) {
                $('[name ^= "taste"]').removeClass("actbutdeg");
                $('[name = "taste' + rait + '"]').addClass("actbutdeg");
            }
            else if (type == 2) {
                $('[name ^= "view"]').removeClass("actbutdeg");
                $('[name = "view' + rait + '"]').addClass("actbutdeg");
            }
            else if (type == 3) {
                $('[name ^= "concept"]').removeClass("actbutdeg");
                $('[name = "concept' + rait + '"]').addClass("actbutdeg");
            }
            else {
                $('#price').val(price);
                $('#comment').val(comment);
                $("#bludo_swich option:selected").append(" — готово");

            }
            $("#ok_done3").show();
            $("#ok_done3").hide("fade", 2000);
            $("#bludo_swich option:selected").addClass('good_td');

            hideb();
        }
    })
}
//single_degust
function data_rate() {
    var id_tov = $("#bludo_swich").val();
    $.ajax({
        url: 'ajax/creat_rate.php',
        data: 'bludo=' + id_tov,
        dataType: "json",
        success: function (data) {
            showb();
            if (data['taste'] != "") {
                $('[name ^= "taste"]').removeClass("actbutdeg");
                $('[name = "taste' + data['taste'] + '"]').addClass("actbutdeg");
            }
            if (data['view'] != "") {
                $('[name ^= "view"]').removeClass("actbutdeg");
                $('[name = "view' + data['view'] + '"]').addClass("actbutdeg");
            }
            if (data['concept'] != "") {
                $('[name ^= "concept"]').removeClass("actbutdeg");
                $('[name = "concept' + data['concept'] + '"]').addClass("actbutdeg");
            }


            if (data['photo'] == 1) {
                $("#foto").text("Фото уже есть");
                $("#foto").addClass("buton_nonact");
            } else {
                $("#foto").text("Сфотать");
                $("#foto").removeClass("buton_nonact");
            }
            $('#consist').html(data['consist']);
            $('#price' + id_tov).val(data['price']);
            $('#comment' + id_tov).val("");
            $('#comment' + id_tov).val(data['comment']);
            hideb();
        }
    })

}
//single_degust
function addbludo_fast(id_tov) {
    $.ajax({
        url: 'ajax/save_bludo_fast.php',
        data: 'id_degust=' + id_degust,
        dataType: "html",
        success: function (data) {
            $("#ok_done2").show();
            $("#add_buut").attr('onclick', '');
            $("#add_buut").removeClass('buton_reg2');
            $("#add_buut").addClass('buton_grey');

        }
    })
}
//degust_result
$("#what_do").change(function () {
    let hash_deg = $("#hash_degust").val();
    if ($("#what_do").val() == 1) {
        $("#but_what_do").attr('onclick', "good_work('" + hash_deg + "',2)");
    }
    else if ($("#what_do").val() == 2) {
        $("#but_what_do").attr('onclick', "good_work('" + hash_deg + "',1)");
    }
    else {
        $(".transfer-degust-blc").show();
        $("#but_what_do").attr('onclick', "another_day()");
    }
});

//degust_result
function download_deg(hash) {
    $.ajax({
        url: 'ajax/down_deg.php',
        data: "hash=" + hash,
        dataType: "html",
        success: function (data) {
            document.location.href = "/otch/" + data;
        }
    });
}
//degust_result table_king
//degust_result table_king

//table_king
function foto_on(id_tov) {
    $("#id_upload").val(id_tov)
    document.getElementById('fileupload_king').click();

}
//table_king
//degust_result
function another_day() {
    var all_check = '',
        trans_deg = $("#transfer-degust").val(),
        kolv = 0;
    $("input:checked").each(function (indx, element) {
        var substringArray = $(element).val().split(","),
            id_tov = substringArray[0];
        kolv++
        if (all_check != "") {
            all_check = all_check + "," + id_tov;
        }
        else {
            all_check = id_tov;
        }
        $("#tr" + id_tov).hide();
    });
    $.ajax({
        url: 'ajax/another_day.php',
        data: 'ids_bl=' + all_check +
        '&trans_deg=' + trans_deg,
        dataType: "html",
        success: function (data) {


            $("#dialog-message").html("<p>" + kolv + " блюд успешно перенесено на следующую дегустацию. <br>"
                + "Посмотреть её можно <a href=\'/degust_result.php?hash=" + data + "\'>здесь</a> или в общем перечне дегустаций</p>");
            $(function () {
                $("#dialog-message").dialog({
                    modal: true,
                    buttons: {
                        Ok: function () {
                            $(this).dialog("close");
                        }
                    }
                });
            });
            $('input[type=checkbox]').removeAttr("checked");
            countChecked();
        }
    })
}

//degust_result
function good_work(hash_in, status) {
    var all_check = '',
        kolv = 0;
    $("input:checked").each(function (indx, element) {
        var substringArray = $(element).val().split(","),
            id_tov = substringArray[0];
        kolv++
        if (all_check != "") {
            all_check = all_check + "," + id_tov;
        }
        else {
            all_check = id_tov;
        }
    });
    $.ajax({
        url: 'ajax/good_work.php',
        data: 'ids_bl=' + all_check + '&hash_in=' + hash_in + '&status=' + status,
        dataType: "html",
        success: function (data) {
            if (status == 2) {
                $("#dialog-message").html("<p>" + kolv + " блюд успешно отобрано. <br>"
                    + "Все отобранные блюда можно увидеть на общей странице дегустаций <a href=\'/degust_all.php\'>здесь</a></p>");
                $(function () {
                    $("#dialog-message").dialog({
                        modal: true,
                        buttons: {
                            Ok: function () {
                                $(this).dialog("close");
                            }
                        }
                    });
                });

                $("input:checked").each(function (indx, element) {
                    var substringArray = $(element).val().split(","),
                        id_tov2 = substringArray[0];
                    $('#tr' + id_tov2).removeClass("select_tr");
                    $('#tr' + id_tov2).addClass("good_td");
                });
            }
            else {
                $("input:checked").each(function (indx, element) {
                    var substringArray = $(element).val().split(","),
                        id_tov2 = substringArray[0];
                    $('#tr' + id_tov2).removeClass("select_tr");
                    $('#tr' + id_tov2).removeClass("good_td");
                });
            }
            $('input[type=checkbox]').removeAttr("checked");
            $("#line_ot").hide();
        }
    })
}

//degust_result


//degust_result
function get_line_save(value) {
    var lines,
        str3 = '';
    if (value.indexOf("\t") > -1) {
        lines = value.split(/[\n\r]+/);
        str3 = lines.join('{');
    }
    else {
        lines = value.split(/[\n\r\t]+/);
        str3 = lines.join('{');
    }
    return str3;
}

//degust_result
function get_line_print(value) {
    var lines,
        str3 = '';
    if (value.indexOf("\t") > -1) {
        lines = value.split(/[\n\r]+/);
        str3 = lines.join('<br>');
    }
    else {
        lines = value.split(/[\n\r\t]+/);
        str3 = lines.join('<br>');
    }
    return str3;
}

//degust_result
$(".time").mask("99:99");

//degust_result
function delbludo(id_tov) {
    $.ajax({
        url: 'ajax/del_bludo.php',
        data: 'id_tov=' + id_tov,
        dataType: "html",
        success: function (data) {
            if (data == 1) {
                $("#tr" + id_tov).removeClass('del_tov_text del_tov_tr');
            } else {
                $("#tr" + id_tov).addClass('del_tov_text del_tov_tr');
            }

        }
    })
}

//degust_result
function creat_degust(hash_in) {
    var date = $("#date").val(),
        time = $("#time").val(),
        name_degust = $("#name_degust").val(),
        place = $("#place").val(),
        discr = $("#comment").val();
    $.ajax({
        url: 'ajax/creat_degust.php',
        data: '&date=' + date +
        '&name_degust=' + name_degust +
        '&time=' + time +
        '&place=' + place +
        '&discr=' + discr +
        '&hash_deg=' + hash_in,
        dataType: "html",
        success: function (data) {
            $("#ok_done2").html("Дегустация успешно сохранена."
                + "<br>Перейти на <A href='degust_result.php?hash=" + data + "'>страницу результатов</A>");
            $("#ok_done2").show();
        }
    })
}
//degust_result
function showAllComment(id_tov, doing) {
    if (doing == 1) {
        $('#all_comment' + id_tov).show();
        $('#showhideComment_all' + id_tov).attr('onclick', 'showAllComment(' + id_tov + ',2)');
        $('#showhideComment_all' + id_tov).text('скрыть');
    }
    else {
        $('#all_comment' + id_tov).hide();
        $('#showhideComment_all' + id_tov).attr('onclick', 'showAllComment(' + id_tov + ',1)');
        $('#showhideComment_all' + id_tov).text('показать все');
    }
}

function showAllPhoto(id_tov, doing) {
    if (doing == 1) {
        $('#all_photo' + id_tov).show();
        $('#showhide_all' + id_tov).attr('onclick', 'showAllPhoto(' + id_tov + ',2)');
        $('#showhide_all' + id_tov).text('показать все');
    }
    else {
        $('#all_photo' + id_tov).hide();
        $('#showhide_all' + id_tov).attr('onclick', 'showAllPhoto(' + id_tov + ',1)');
        $('#showhide_all' + id_tov).text('скрыть');
    }
}

function getLink() {
    href = document.location.pathname,
        href_list = href.split('/'),
        index = href_list.length - 1;
    return href_list[index];
}
//new_degust, degust result

function addbludo(hash_deg, id_bl) {
    var nameb = $("#nameb").val(),
        name_degust = $("#name_degust").val(),
        date = $("#date").val(),
        time = $("#time").val(),
        place = $("#place").val(),
        sostav = get_line_print($("#sostav").val()),
        sostav_save = get_line_save($("#sostav").val()),
        ss = $("#ss").val(),
        cat = $("#cat").val(),
        ves = $("#ves").val(),
        povar = $("#povar").val(),
        remake = $("#remake_id").val(),
        kol_blud = $(".tr_order2:last > td[id^='num'] ").text(),
        link = getLink();

    if (nameb != "") {

        if (kol_blud > 15) {

            $("#advice").show("bounce", 1000);
        }


        $.ajax({
            url: 'ajax/save_bludo.php',
            data: 'nameb=' + nameb +
            '&sostav=' + sostav_save +
            '&name_degust=' + name_degust +
            '&date=' + date +
            '&time=' + time +
            '&place=' + place +
            '&ss=' + ss +
            '&cat=' + cat +
            '&ves=' + ves +
            '&povar=' + povar +
            '&remake=' + remake,
            dataType: "json",
            success: function (data) {
                for (var i = 0; i < arr.length; i++) {
                    if (cat == arr_id[i]) {
                        num_cat = i;
                    }
                }
                if (link == 'new_degust.php') {
                    $("#end_tr").before("<tr class='tr_order2' id='tr" + data.new_id_tov + "'>"
                        + "<td class='td_ord_table' id='num_tr" + data.new_id_tov + "'>" + kol_blud + "</td>"
                        + "<td class='td_ord_table' id='name_tr" + data.new_id_tov + "'>" + nameb + "</td>"
                        + "<td class='td_ord_table' id='sostav_tr" + data.new_id_tov + "'>" + sostav + "</td>"
                        + "<td class='td_ord_table' id='cat_tr" + data.new_id_tov + "'>" + arr[num_cat] + "</td>"
                        + "<td class='td_ord_table' id='ves_tr" + data.new_id_tov + "'>" + ves + "</td>"
                        + "<td class='td_ord_table' id='ss_tr" + data.new_id_tov + "'>" + ss + "</td>"
                        + "<td class='td_ord_table' id='povar_tr" + data.new_id_tov + "'>" + povar + "</td>"
                        + "<td class='td_ord_table' id='edit_tr" + data.new_id_tov + "' onclick='readactbl(" + data.new_id_tov + ")' ><span class='redact' id='edit" + data.new_id_tov + "'>ред</span></td>"
                        + "</tr>");

                } else {
                    $("#end_tr").before("<tr class='tr_order2' id='tr" + data.new_id_tov + "'>"
                        + "<td class='td_ord_table left' id='num_tr" + data.new_id_tov + "'>" + kol_blud + "</td>"
                        + "<td class='td_ord_table left' id='name_tr" + data.new_id_tov + "'>" + nameb + "</td>"
                        + "<td class='td_ord_table left' id='sostav_tr" + data.new_id_tov + "'>" + sostav + "</td>"
                        + "<td class='td_ord_table' id='img_tr" + data.new_id_tov + "'>нет фото</td>"
                        + "<td class='td_ord_table' id='cat_tr" + data.new_id_tov + "'>" + arr[num_cat] + "</td>"
                        + "<td class='td_ord_table' id='ves_tr" + data.new_id_tov + "'>" + ves + "</td>"
                        + "<td class='td_ord_table' id='ss_tr" + data.new_id_tov + "'>" + ss + "</td>"
                        + "<td class='td_ord_table' id='povar_tr" + data.new_id_tov + "'></td>"
                        + "<td class='td_ord_table'></td>"
                        + "<td class='td_ord_table'></td>"
                        + "<td class='td_ord_table'></td>"
                        + "<td class='td_ord_table'></td>"
                        + "<td class='td_ord_table'></td>"
                        + "<td class='td_ord_table' id='checkbox" + data.new_id_tov + "'><input type='checkbox' value='" + data.new_id_tov + "," + cat + "'></td>"
                        + "<td class='td_ord_table' id='edit_tr" + data.new_id_tov + "' onclick='readactbl(" + data.new_id_tov + ")' ><span class='redact' id='edit" + data.new_id_tov + "'>ред</span></td>"
                        + "<td class='td_ord_table' id='del_tr" + data.new_id_tov + "' onclick='delbludo(" + data.new_id_tov + ")'><span class='redact' id='del" + data.new_id_tov + "'>X</span></td>"
                        + "</tr>");

                }
                kol_blud++;
                $("#addbl").attr('onclick', "addbludo('" + data.hash + "','0')");
                $("#but_gl").attr('onclick', "creat_degust('" + data.hash + "')");
                $("#nameb").val("");
                $("#ss").val("");
                //$("#cat").val("1");
                $("#sostav").val("");
                $("#ves").val("");
                $("#povar").val("");
                $("#remake_id").val("");

                $("#num0").html(kol_blud);
            }
        })

    }

}

function addbludo_king(id_cat) {
    var nameb = $("#nameb").val(),
        name_degust = $("#name_degust").val(),
        sostav = get_line_print($("#sostav").val()),
        sostav_save = get_line_save($("#sostav").val()),
        ss = $("#ss").val(),
        cat = $("#cat").val(),
        ves = $("#ves").val(),
        kol_blud = $(".tr_order2:last > td[id^='num'] ").text();

    if (nameb != "") {

        $.ajax({
            url: 'ajax/save_bludo.php',
            data: 'nameb=' + nameb +
            '&sostav=' + sostav_save +
            '&ss=' + ss +
            '&cat=' + id_cat +
            '&king=1'  +
            '&ves=' + ves,
            dataType: "json",
            success: function (data) {

                    $("#end_tr").before("<tr class='tr_order2' id='tr" + data.new_id_tov + "'>"
                        + "<td class='td_ord_table' id='num" + data.new_id_tov + "'>" + kol_blud + "</td>"
                        + "<td class='td_ord_table' id='name_tr" + data.new_id_tov + "'>" + nameb + "</td>"
                        + "<td class='td_ord_table' id='name_menu_tr" + data.new_id_tov + "'></td>"
                        + "<td class='td_ord_table' id='img_tr" + data.new_id_tov + "'></td>"
                        + "<td class='td_ord_table' id='sostav_tr" + data.new_id_tov + "'>" + sostav + "</td>"
                        + "<td class='td_ord_table' id='discription_menu_tr" + data.new_id_tov + "'></td>"
                        + "<td class='td_ord_table' id='ves_tr" + data.new_id_tov + "'>" + ves + "</td>"
                        + "<td class='td_ord_table' id='ss_tr" + data.new_id_tov + "'>" + ss + "</td>"
                        + "<td class='td_ord_table' id='price_tr" + data.new_id_tov + "'></td>"
                        + "<td class='td_ord_table' id='comment_td" + data.new_id_tov + "'></td>"
                        + "</tr>");


                kol_blud++;
                $("#nameb").val("");
                $("#ss").val("");
                //$("#cat").val("1");
                $("#sostav").val("");
                $("#ves").val("");
                $("#povar").val("");
                $("#num0").html(kol_blud);
            }
        })

    }

}

//degust result

function readactbl(id_bl) {
    let name = $("#name_tr" + id_bl).text(),
        sostav = $("#sostav_tr" + id_bl).html(),
        ss = $("#ss_tr" + id_bl).text(),
        cat = $("#cat_tr" + id_bl).text(),
        ves = $("#ves_tr" + id_bl).text(),
        povar = $("#povar_tr" + id_bl).text(),
        arr_opt = [],
        link = getLink(),
        group_str = "<select class='input_td_med' id='cat" + id_bl + "'>";
    for (var i = 0; i < arr.length; i++) {
        var dl = arr_opt.length;
        if (cat == arr[i]) {
            arr_opt[dl] = "selected";
        }
        else {
            arr_opt[dl] = "";
        }
        group_str = group_str + "<option value='" + arr_id[i] + "' " + arr_opt[i] + ">" + arr[i] + "</option>";

    }
    group_str = group_str + "</select>";
    $("#name_tr" + id_bl).html("<input type='text' class='input_medium2' id='name" + id_bl + "' value='" + name + "'>");
    $("#sostav_tr" + id_bl).html("<textarea class='input_medium' id='sostav" + id_bl + "'>" + sostav + "</textarea>");
    $("#ss_tr" + id_bl).html("<input type='text' class='input_medium2' id='ss" + id_bl + "' value='" + ss + "'>");
    $("#cat_tr" + id_bl).html(group_str);
    $("#ves_tr" + id_bl).html("<input type='text' class='input_medium2' id='ves" + id_bl + "' value='" + ves + "'>");
    $("#povar_tr" + id_bl).html("<input type='text' class='input_medium2' id='povar" + id_bl + "' value='" + povar + "'>");
    $("#edit" + id_bl).text("сохр");
    $("#edit_tr" + id_bl).attr('onclick', "save_redact_bl(" + id_bl + ")");
}
function save_redact_bl(id_bl) {
    var name = $("#name" + id_bl).val(),
        sostav = get_line_print($("#sostav" + id_bl).val(), 1),
        sostav_save = get_line_save($("#sostav" + id_bl).val(), 1),
        ss = $("#ss" + id_bl).val(),
        cat = $("#cat" + id_bl).val(),
        cat_r = cat,
        ves = $("#ves" + id_bl).val(),
        povar = $("#povar" + id_bl).val();
    $.ajax({
        url: 'ajax/save_redbludo.php',
        data: 'nameb=' + name +
        '&id_bl=' + id_bl +
        '&sostav=' + sostav_save +
        '&ss=' + ss +
        '&cat=' + cat +
        '&ves=' + ves +
        '&povar=' + povar,
        dataType: "html",
        success: function (data) {
            for (var i = 0; i < arr.length; i++) {
                if (cat_r == arr_id[i]) {
                    num_cat = i;
                }
            }

            $("#name_tr" + id_bl).html(name);
            $("#sostav_tr" + id_bl).html(sostav);
            $("#ss_tr" + id_bl).html(ss);
            $("#cat_tr" + id_bl).html(arr[num_cat]);
            $("#ves_tr" + id_bl).html(ves);
            $("#povar_tr" + id_bl).html(povar);
            $("#edit" + id_bl).text("ред");
            $("#edit_tr" + id_bl).attr('onclick', "readactbl(" + id_bl + ")");
        }
    })
}

function del_degust(hash_in) {
    $.ajax({
        url: 'ajax/del_deg_aj.php',
        data: 'hash_in=' + hash_in,
        dataType: "json",
        success: function (data) {
            if (data == 0) {
                $("#block_telo").hide();
                $("#message").html("Дегустация удалена");
                $("#but_del").html("ой. Я криворук. Верните всё назад");
            } else {
                $("#block_telo").show();
                $("#message").html("");
                $("#but_del").html("Удалить дегустацию");
            }
        }
    })
}


//discription_make

function showb() {
//Задаем прозрачность и блокируем дисплей
//элемента "b"
    $("#b").show();
    $("#loader").show();
}

function hideb() {
    $("#b").hide();
    $("#loader").hide();
}

//Plate_choice

function swich_cat_plate(type) {
    $.ajax({
        url: 'ajax/swich_plate_type_aj.php',
        data: "type=" + type,
        dataType: "html",
        success: function (data) {
            $('#swich_body').html(data);
            //setLocation("plate_choice.php?type=1");
        }
    });
}
//Plate_choice
function bookmark(id_plate, doing) {
    $.ajax({
        url: 'ajax/bookmarks_aj.php',
        data: 'id_plate=' + id_plate + '&doing=' + doing,
        dataType: "json",
        success: function (data) {
            $("#plate" + id_plate).hide('slide', 1000);
        }
    });
}
//Plate_choice
$(".blc_plate").draggable({
    helper: function () {
        var id_str = $(this).attr("id"),
            id_plate = id_str.substring(5),
            htm = $("#img" + id_plate).clone();
        return htm;
    }
});
//Plate_choice
$(function () {
    $(".droppable_group").droppable({
        classes: {
            "ui-droppable-active": "active_plate",
            "ui-droppable-hover": "hover_plate"
        },
        drop: function (event, ui) {

            var id_str_plate = ui.draggable.attr("id"),
                id_plate = id_str_plate.substring(5),
                id_str_tov = $(this).attr("id"),
                id_cat = id_str_tov.substring(6),
                obj = $(this),
                htm = $("#img" + id_plate).clone();

            $.ajax({
                url: 'ajax/plate_for_tov_aj.php',
                data: 'id_plate=' + id_plate + '&id_tov=0&id_cat=' + id_cat,
                dataType: "json",
                success: function (data) {
                    for (i = 0; i < data.length; ++i) {
                        htm = $("#img" + id_plate).clone();
                        //alert(data[i]);
                        $("#plate_tov" + data[i]).html(htm);
                    }
                    obj.addClass("good_td");
                }
            });
        }
    });
});

//Plate_choice
$(function () {
    $(".droppable").droppable({
        classes: {
            "ui-droppable-active": "active_plate",
            "ui-droppable-hover": "hover_plate"
        },
        drop: function (event, ui) {

            var id_str_plate = ui.draggable.attr("id"),
                id_plate = id_str_plate.substring(5),
                id_str_tov = $(this).attr("id"),
                id_tov = id_str_tov.substring(9),
                obj = $(this),
                htm = $("#img" + id_plate).clone();
            obj.html(htm);
            $.ajax({
                url: 'ajax/plate_for_tov_aj.php',
                data: 'id_plate=' + id_plate + '&id_tov=' + id_tov + '&id_cat=0',
                dataType: "text",
                success: function (data) {
                    obj.addClass("good_td");
                }
            });
        }
    });
});

//Plate_choice
$(function () {
    $("#tabs").tabs();
});

function save_plate(id_plate){
    var name_plate = $("#name_plate" + id_plate).val(),
        amount = $("#amount" + id_plate).val(),
        kolvo = $("#kolvo" + id_plate).val();
    $.ajax({
        url: 'ajax/save_redbludo.php',
        data: 'name_plate=' + name_plate +
        '&amount=' + amount +
        '&kolvo=' + kolvo +
        '&id_plate=' + id_plate,
        dataType: "html",
        success: function (data) {

        }
    })
}

//Plate_choice
function add_plate(id_tov) {
    $("#id_upload").val(id_tov)
    document.getElementById('fileupload_plate').click();
}

//Plate_choice
$(function () {
    var i = 40
    $('#fileupload_plate').fileupload({
        dataType: 'json',
        formAcceptCharset: 'utf-8',

        progressall: function (e, data) {
            showb();
        },
        done: function (e, data) {
            $.each(data.result.files, function (index, file) {

                var name_file = file.name,
                    urlf = file.url,
                    previewurl = file.thumbnailUrl,
                    id_tov = $('#id_upload').val(),
                    id_photoset = $('#id_photoset').val();
                $.ajax({
                    url: 'ajax/safe_plate_aj.php',
                    data: 'urlf=' + urlf + '&previewurl=' + previewurl,
                    dataType: "text",
                    success: function (data) {


                        $('#plate_pole').append("<div class='blc_plate' name='plate" + data + "' id='plate" + data + "'>"
                            + "<img src='" + previewurl + "' class='image_plate' id='img" + data + "'>"
                            + "<div class='flex_plate'>"
                            + "<div class='name_plate'>"
                            + "	Добавить название"
                            + "</div>"
                            + "<div class='tags_plate'>"
                            + "Тэги<br>"
                            + "<span class='tag_plate'>Добавить тэги</span>"
                            + "</div>"
                            + "<div class='remove_bookmark'>"
                            + "убрать закладку"
                            + "</div>"
                            + "</div>"
                            + "<div class='help'></div>"
                            + "</div>");

                        hideb();
                        document.location.href = "#plate" + data + "";
                        $("#plate" + data).addClass("blc_plate_new");
                        $("#plate" + data).removeClass("blc_plate_new", 2000);
                        $('html,body').stop().animate({scrollTop: $("#plate" + data).offset().top}, 1000);
                        $(".blc_plate").draggable({
                            helper: function () {
                                var id_str = $(this).attr("id"),
                                    id_plate = id_str.substring(5),
                                    htm = $("#img" + id_plate).clone();
                                return htm;
                            }
                        });
                    }
                });

            });

        },
        previewMinHeight: 150,

    });

});

//check_buh

function check_data(roll) {
    var all_check = '',
        all_comment = '',
        kolv = 0;
    $("input:checked").each(function (indx, element) {
        var substringArray = $(element).val().split(","),
            id_tov = substringArray[0];
        kolv++
        if (all_check != "") {
            all_check = all_check + "," + id_tov;
            all_comment = all_comment + "{" + $("#comment" + id_tov).val();
        }
        else {
            all_check = id_tov;
            all_comment = $("#comment" + id_tov).val();
        }
    });
    $.ajax({
        url: 'ajax/check_buh_aj.php',
        data: 'ids_bl=' + all_check + '&all_comment=' + all_comment + '&roll=' + roll,
        dataType: "html",
        success: function (data) {
            var text = "<p>Успешно проверено " + kolv + " блюд. </p>";

            $("#dialog-message").html(text);
            $(function () {
                $("#dialog-message").dialog({
                    modal: true,
                    buttons: {
                        Ok: function () {
                            $(this).dialog("close");
                        }
                    }
                });
            });

            $("input:checked").each(function (indx, element) {
                var substringArray = $(element).val().split(","),
                    id_tov2 = substringArray[0];
                $('#tr' + id_tov2).removeClass("select_tr");
                $('#tr' + id_tov2).addClass("good_td");
            });
        }
    })
}

//check_buh
function redactbl_buh(id_bl) {
    var name = $("#name_tr" + id_bl).text(),
        sostav = $("#consist_tr" + id_bl).text(),
        ss = $("#new_ss_tr" + id_bl).text(),
        ves = $("#ves_tr" + id_bl).text();


    $("#consist_tr" + id_bl).html("<input type='text' class='input_medium' id='consist" + id_bl + "' value='" + sostav + "'>");
    $("#new_ss_tr" + id_bl).html("<input type='text' class='input_medium2' id='new_ss" + id_bl + "' value='" + ss + "'>");
    $("#ves_tr" + id_bl).html("<input type='text' class='input_medium2' id='ves" + id_bl + "' value='" + ves + "'>");
    $("#edit" + id_bl).text("сохр");
    $("#edit_tr" + id_bl).attr('onclick', "save_redact_buh(" + id_bl + ")");
}


//check_buh

function getStyle(item, data) {
    var style = '';
    if (data === "") {
        style = "bad_td";
        item.addClass(style);
    }
    else {
        style = "bad_td";
        item.removeClass(style);
    }
}


//check_buh
function save_redact_buh(id_bl) {
    var sostav = $("#consist" + id_bl).val(),
        ss = $("#new_ss" + id_bl).val(),
        ves = $("#ves" + id_bl).val();
    $('#positionable2').show();
    $.ajax({
        url: 'ajax/save_redbludo_buh_aj.php',
        data: 'id_bl=' + id_bl +
        '&sostav=' + sostav +
        '&ss=' + ss +
        '&ves=' + ves,
        dataType: "html",
        success: function (data) {
            $("#consist_tr" + id_bl).html(sostav);
            $("#new_ss_tr" + id_bl).html(ss);
            $("#ves_tr" + id_bl).html(ves);

            $("#edit" + id_bl).text("редакт.");
            $("#edit_tr" + id_bl).attr('onclick', "redactbl_buh(" + id_bl + ")");
            if ($('#checkbox' + id_bl).is(':checked')) {

                $('#checkbox' + id_bl).prop('checked', false);

            } else {

            }
            $("#tr" + id_bl + ">td").removeClass('good_td');

            getStyle($("#consist_tr" + id_bl), sostav);
            getStyle($("#new_ss_tr" + id_bl), ss);
            getStyle($("#ves_tr" + id_bl), ves);

            if (sostav != '' && ss != '' && ves != '') {
                $('#checkbox' + id_bl).removeAttr('disabled');
                $('#td_checkbox' + id_bl).removeAttr('title');

            } else {
                $('#checkbox' + id_bl).attr('disabled', 'disabled');
                $('#td_checkbox' + id_bl).attr('title', 'Нельзя подтвердить данные если не все поля заполнены');
            }

            $(function () {
                function position() {
                    $(".positionable").position({
                        of: $('#checkbox' + id_bl),
                        my: "left+60",
                        at: "center"
                    });
                }

                position();
            });

            $('.positionable').hide("fade", 3000);

        }
    })
}
//discription_make
function save_redact_disc(id_bl) {
    var name_menu = $("#name_menu" + id_bl).val(),
        sostav = $("#discription_menu" + id_bl).val();
    $.ajax({
        url: 'ajax/save_discr.php',
        data: 'id_bl=' + id_bl +
        '&sostav=' + sostav +
        '&name_menu=' + name_menu,
        dataType: "json",
        success: function (data) {
            if (data === 1) {
                $("#tr" + id_bl).addClass('good_td');
                if (sostav != '' && name_menu != '') {

                } else {
                    $("#tr" + id_bl).removeClass('good_td', 1000);
                }
                getStyle($("#discription_menu_td" + id_bl), sostav);
                getStyle($("#name_menu_td" + id_bl), name_menu);
            }
            else {
                $("#tr" + id_bl).addClass('bad_td');
                $("#tr" + id_bl).removeClass('bad_td', 1000);
            }

        }
    })
}
//discription_make
function safe_discr() {
    var i = 1;
    $("[id ^=discription_menu]").each(function () {
        var sostav = $(this).val(),
            idattr = $(this).attr('id'),
            id_val = idattr.slice(16),
            name_menu = $("#name_menu" + id_val).val();
        $.ajax({
            url: 'ajax/save_discr.php',
            data: 'sostav=' + sostav +
            '&id_bl=' + id_val +
            '&name_menu=' + name_menu,
            dataType: "html",
            success: function (data) {
                if (data == 1) {

                    $("#ok_done2").show();
                    $("#ok_done2").hide("fade", 2000);
                    getStyle($("#discription_menu_td" + id_bl), sostav);
                    getStyle($("#name_menu_td" + id_bl), name_menu);
                }
                else {
                    $("#eror").show();
                    $("#eror").hide("fade", 2000);
                }
            }
        })
    })
}

//discription_make
function creat_good(action) {
    var i = 1,
        action_swich = $("#" + action).prop("checked");
    $("[id ^=discription_menu]").each(function () {
        var sostav = $(this).val(),
            idattr = $(this).attr('id'),
            id_tov = idattr.slice(16);
        $("#tip" + id_tov).html("<div class='center'><img src='img/loaderm2.gif' class='center'></div>");
        $.ajax({
            url: 'creat_good.php',
            data: 'sostav=' + sostav +
            '&action=' + action +
            '&id_tov=' + id_tov +
            '&action_swich=' + action_swich,
            dataType: "text",
            success: function (data) {
                if (action === 'speller') {
                    $("#tip" + id_tov).html(data);
                }
                else {
                    $("#tip" + id_tov).html("");
                    $("#discription_menu" + id_tov).val(data);
                }
                //creat_good2(sostav);
            }
        })
    })
};

function fix_mistake(word, id_tov, id_eror) {
    let descr = $("#discription_menu" + id_tov).val(),
        new_word = $("#select" + id_tov + "id_eror" + id_eror).val(), new_descr = '';
    new_descr = descr.replace(word, new_word);
    $("#discription_menu" + id_tov).val(new_descr);
    $("#select" + id_tov + "id_eror" + id_eror).attr("onchange", "fix_mistake(\"" + new_word + "\"," + id_tov + "," + id_eror + ")");
}
$(function () {
    $(".widget input[type=submit], .widget a, .widget button").button();
});
//discription_make
$(function () {
    $(".checkbox_but").checkboxradio({
        icon: false
    });
});


//new_price
function swich_cat_price(id_analit) {
    $.ajax({
        url: 'ajax/swich_file_new_price_aj.php',
        data: "&id_analiz=" + id_analit,
        dataType: "html",
        beforeSend: function () {
            showb();
        },
        success: function (data) {
            $('#swich_part').html(data);

            hideb();
        }
    });
}
//new_price
function save_price() {
    var arr_price = '';
    $("[id ^= new_price]").each(function (indx, element) {
        var price_tov = $(element).val(),
            id_str = $(element).attr("id"),
            id_tov = id_str.substring(9);

        arr_price = arr_price + id_tov + ":" + price_tov + "}";

    });

    $.ajax({
        url: 'ajax/save_new_price_aj.php',
        data: 'arr_price=' + arr_price,
        dataType: "text",
        success: function (data) {
            $("#ok_done2").show();
        }
    })
}
//new_price
function fill_rec() {
    var i = 1;
    $("[id ^=rec_price]").each(function () {
        var price = $(this).text(),
            idattr = $(this).attr('id'),
            id_val = idattr.slice(9);
        $("#new_price" + id_val).val(price);
    })
    refresh_group_ss();
};
//new_price
function refresh_group_ss() {
    var group_ss = 0,
        new_ss_gr = 0,
        i = 0;
    $("[id ^=new_ss_per]").each(function () {
        var per_ss = parseFloat($(this).val());
        group_ss = group_ss + (per_ss);
        i++;
    })
    new_ss_gr = group_ss / i;
    $("#new_ss").val(new_ss_gr);

}
//new_price

$("[id ^=new_price]").keyup(function () {
    var new_price = parseFloat($(this).val()),
        idattr = $(this).attr('id'),
        id_val = idattr.slice(9),
        ss_rud = parseFloat($("#ss" + id_val).text()),
        new_ss_per = parseFloat(Math.round(ss_rud / (new_price / 100)));
    $("#new_ss_per" + id_val).val(new_ss_per);
    refresh_group_ss()
});
//new_price

$("[id ^=new_ss_per]").keyup(function () {
    var new_ss_per = $(this).val(),
        idattr = $(this).attr('id'),
        id_val = idattr.slice(10),
        ss_rud = parseFloat($("#ss" + id_val).text()),
        new_price;
    if (ss_rud != "") {
        new_price = Math.round(ss_rud * 100 / new_ss_per);
        $("#rec_price" + id_val).text(new_price);
    }
});

function complex_check(type,id_project_parent, id_project_child, id_tov) {
    var status_parent,
        status_child;
    if ($('#child_check' + id_tov).is(':checked')) {
        status_child=1;
    } else {
        status_child=0;
    }

    if ($('#parent_check' + id_tov).is(':checked')) {
        status_parent=1;
    } else {
        status_parent=0;
    }

    $.ajax({
        url: 'ajax/complex_project_check.php',
        data: 'type=' + type +
        '&id_project_parent=' + id_project_parent +
        '&id_project_child=' + id_project_child +
        '&status_child=' + status_child +
        '&status_parent=' + status_parent +
        '&id_tov=' + id_tov,
        dataType: "text",
        success: function (data) {

        }
    })
};

function swich_complex_cat(id_tov,id_project_parent, id_project_child,) {
    var new_cat = $("#cat" + id_tov).val();
    $.ajax({
        type: "GET",
        url: "ajax/complex_group_change.php",
        dataType: "html",
        // параметры запроса, передаваемые на сервер (последний - подстрока для поиска):
        data: 'id_tov=' + id_tov +
        '&new_cat=' + new_cat+
        '&id_project_parent=' + id_project_parent+
        '&id_project_child=' + id_project_child,
        // обработка успешного выполнения запроса
        success: function (data) {
            if (data == 1) {
                //alert('Категория группы изменена. Обновите страницу');
            }
            else {
                //alert('Произошла ошибка. Свяжитесь с администратором');
            }
        }
    })
}