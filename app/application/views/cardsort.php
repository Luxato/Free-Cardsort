<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cardsort</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/bootstrap.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500" rel="stylesheet">
    <style>
        html, body, .container {
            height: 100% !important;
        }

        .legit-cat h5.ui-widget-header {
            background: #fff;
            color: #000;
        }

        .legit-cat .gallery.ui-helper-reset {
            background: #A7B2B6;
            padding: 10px;
        }

        .legit-cat.ui-state-highlight .ui-helper-reset {
            background: #ffeacd !important;
        }

        .ui-helper-reset {
            padding: 3px 3px 0 3px;
            display: inline-block;
            width: 100%;
        }

        .category .ui-widget-content {
            background: #fff !important;
            margin: 3px 0;
        }

        .category {
            background: none;
        }

        .create-new h4 {
            color: gray;
        }

        .col-md-4 {
            font-family: 'Montserrat', sans-serif;
        }

        .fa-undo {
            position: relative;
            left: -3px;
        }

        .legit-cat h4.ui-widget-header {
            font-size: 1em;
            font-weight: 500;
            font-family: 'Montserrat', sans-serif;
            color: #b55c27;
            background: #D4DDDF;
            text-align: left;
        }

        h4 {
            padding: 4px 0 4px 7px;
        }

        .category .ui-widget-content, .category li h5 {
            display: inline-block !important;
        }

        .category li h5 {
            border: none;
        }

        .category .ui-widget-content {
            border: 1px solid lightgray;
            background: #E9E9E9;
        }

        .gallery.custom-state-active {
            background: #eee;
        }

        .ui-widget-content {
            border: none;
        }

        .gallery li {
            width: 100%;
            text-align: center;
        }

        .gallery li h5 {
            margin: 0 0 0.4em;
            cursor: move;
            font-family: 'Montserrat', sans-serif !important;
        }

        .gallery li a {
            float: right;
        }

        .gallery li a.ui-icon-zoomin {
            float: left;
        }

        .gallery li img {
            width: 100%;
            cursor: move;
        }

        #trash {
            height: 200px;
            z-index: -5;
        }

        #trash h4 {
            line-height: 16px;
            margin: 0 0 0.4em;
        }

        #trash h4 .ui-icon {
            float: left;
        }

        /*#trash .gallery h5 {
            display: none;
        }*/

        #trash2 {
            height: 200px;
            padding: 1%;
        }

        #trash2 h4 {
            line-height: 16px;
            margin: 0 0 0.4em;
        }

        #trash2 h4 .ui-icon {
            float: left;
        }

        #trash2 .gallery h5 {
            display: none;
        }

        .gallery li {
            z-index: 999999;
        }

        .ui-widget-header {
            font-weight: 400;
        }

        #gallery .ui-widget-header {
            background: #fff;
            padding: 4px;
        }

        .ui-state-highlight {
            background: #ffeacd !important;
        }

        h4.ui-widget-header {
            cursor: pointer;
        }
    </style>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        var languages = [];

        function tooltip() {
            $('[data-toggle="tooltip"]').tooltip();
        }
        function update_results() {
            var categories = $('.legit-cat');
            var result = {};
            var forgot_to_rename = false;
            for (var i = 0, max = categories.length; i < max; i++) {
                var catName = $(categories[i]).find('h4.ui-widget-header').text();
                var cards = $(categories[i]).find('li');
                var json = [];
                for (var j = 0; j < cards.length; j++) {
                    json.push($(cards[j]).attr('id'));
                }
                if(result.hasOwnProperty(catName)) {
                    forgot_to_rename = true;
                }
                result[catName] = json;
            }
            $('#data').val(JSON.stringify(result));
            if(forgot_to_rename) {
                return true;
            } else {
                return false;
            }
        }

        var height;
        $(function () {
            function reload() {
                var oriVal;
                $(".category").off();
                $(".category").on('click', 'h4', function () {
                    // If this is placeholder
                    if(!$(this).parent().hasClass('legit-cat')) {
                        return;
                    }
                    oriVal = $(this).html();
                    $(this).html("");
                    $("<input style='width: 100%;color: gray;' type='text'>")
                        .appendTo(this)
                        .focus()
                        .end()
                        .on('blur keyup', function (e) {
                            if (e.type == 'blur' || e.keyCode == '13') {
                                var $this = $(this);
                                var tmp = $this.val().replace(/\s\s+/g, ' ');
                                if  (tmp == ' ') {
                                    $this.parent().html(oriVal);
                                } else if ($this.val()) {
                                    $this.parent().html($this.val());
                                } else {
                                    $this.parent().html(oriVal);
                                }
                                $this.remove();
                            }
                        });
                });
                $("#category").on('focusout', function () {
                    var $this = $(this);
                    $this.parent().text($this.val() || oriVal);
                    $this.remove(); // Don't just hide, remove the element.
                });

                // There's the gallery and the trash
                var $gallery = $("#gallery"),
                    $trash2 = $(".category");

                // Let the gallery items be draggable
                $("li", $gallery).draggable({
                    cancel: "a.ui-icon", // clicking an icon won't initiate dragging
                    revert: "invalid", // when not dropped, the item will revert back to its initial position
                    containment: "document",
                    helper: "clone",
                    cursor: "move"
                });

                $trash2.droppable({
                    accept: "#gallery > li, #trash li, .category li",
                    classes: {
                        "ui-droppable-active": "ui-state-highlight"
                    },
                    drop: function (event, ui) {
                        deleteImage2(ui.draggable, $(this));
                        if ($(this).hasClass('create-new')) {
                            change_to_normal_state($(this));
                        }

                    }
                });
                $('.remove').off();
                $('.remove').on('click', function () {
                    $removeButton = $(this);
                    if(confirm('Are you sure you want to remove this group?')) {
                        var elements = $removeButton.parent().find('li');
                        for (var i = 0; i < elements.length; i++) {
                            $(elements[i]).fadeOut(function () {
                                $(this).remove();
                                $gallery.prepend($(this));
                                $(this).fadeIn();
                                reload();
                            });
                        }
                        $removeButton.parent().parent().fadeOut(function () {
                            $(this).remove();
                        });
                    }
                });

                // Accepting items from the trash.
                $gallery.droppable({
                    greedy: true,
                    accept: "#trash li, .category li",
                    classes: {
                        "ui-droppable-active": "custom-state-active"
                    },
                    drop: function (event, ui) {
                        recycleImage(ui.draggable);
                    }
                });

                // Image deletion function
                /*var recycle_icon = "<a class='_tooltip' href='link/to/recycle/script/when/we/have/js/off' data-toggle=\"tooltip\" title=\"Undo\" title='Recycle this image'><i class='fa fa-undo' aria-hidden='true'></i></a>";*/
                var recycle_icon = "";

                function updateItems($container) {

                }

                function deleteImage2($item, $container) {
                    $('#aside').height(height);
                    if ($('#guide').is(':visible')) {
                        $('#guide').hide();
                        $('#guide2').show();
                    }
                    $previous_container = $item.parent().parent();
                    if ($("ul", $previous_container).length) {
                        $list = $("ul", $previous_container);
                        var tmp = $previous_container.find('.counter').text().split(' ');
                        tmp[0]--;
                        $previous_container.find('.counter').text(tmp[0] + ' Items');
                    }

                    $item.fadeOut(function () {
                        if ($("ul", $container).length) {
                            $list = $("ul", $container);
                            var tmp = $container.find('.counter').text().split(' ');
                            tmp[0]++;
                            $container.find('.counter').text(tmp[0] + ' Items');
                        } else {
                            $list = $("<ul class='gallery ui-helper-reset'/>").appendTo($container);
                            $container.append("<div class=\"counter\" style=\"display: block;font-size: 13px;width: 100%; padding: 8px 0 8px 8px; background: #D4DDDF;\">1 Item</div>");
                        }

                        $item.find("a.ui-icon-trash").remove();
                        $item.append(recycle_icon).appendTo($list).fadeIn('fast', function () {
                            $('[data-toggle="tooltip"]').tooltip();
                        });
                    });
                }

                // Image recycle function
                var trash_icon = "<a href='link/to/trash/script/when/we/have/js/off' title='Delete this image' class='ui-icon ui-icon-trash'>Delete image</a>";

                function recycleImage($item) {
                    $container = $item.parent().parent();
                    if ($("ul", $container).length) {
                        $list = $("ul", $container);
                        var tmp = $container.find('.counter').text().split(' ');
                        tmp[0]--;
                        $container.find('.counter').text(tmp[0] + ' Items');
                    }
                    $item.fadeOut(function () {
                        $item.remove();
                        $gallery.prepend($item);
                        $item.fadeIn();
                        reload();
                    });
                }

                // Resolve the icons behavior with event delegation
                $("ul.gallery > li").on("click", function (event) {
                    var $item = $(this),
                        $target = $(event.target);
                    if ($target.is("a.ui-icon-trash")) {
                        deleteImage($item);
                    } else if ($target.is("a.ui-icon-zoomin")) {
                        viewLargerImage($target);
                    } else if ($target.is("a.ui-icon-refresh")) {
                        recycleImage($item);
                    }
                    return false;
                });
                /*update_results();*/
            }

            function change_to_normal_state($item) {
                var title = $item.parent().find('h4').parent();
                title.removeClass('create-new');
                title.addClass('legit-cat');
                title.css('opacity', '1');
                $item.parent().find('.temporary').parent().remove();
                $item.parent().find('.counter').show();
                $item.parent().find('h4').replaceWith('<h4 style="line-height: 39px; margin: 0; border: 0; padding-right: 7px;" class="ui-widget-header">Click to rename</h4><span style="display: inline-block;position: absolute;right: 20px;top: 17px;cursor:pointer;" class="remove"><i style="color: #89949b; margin-right: 5px;" class="fa fa-times pull-right" aria-hidden="true"></i></span>');
                // Create new placeholder
                $('#main').append('<div class="col-md-4">\n' +
                    '                    <div style="margin:10px 0;border-radius: 4px; border: 1px solid #b7c6c9;" class="category create-new ui-widget-content ui-state-default">\n' +
                    '                        <h4 style="font-size:17px;margin: 0; border: 0; padding-right: 7px;" class="ui-widget-header">Drag items inside this box</h4>\n' +
                    ' <div style="height: 100px;line-height: 20px;padding-top: 27px;color: gray;font-size: 12px; text-align: center;"> ' +
                    '                            <div class="temporary"><i class="fa fa-arrows" aria-hidden="true"></i></div>\n' +
                    '                            Drag items here to create new group</div>\n' +
                    '                    </div>\n' +
                    '                </div>');
                reload();
            }

            $.getJSON('cards.json', function (data) {
                //alert(JSON.stringify(data));
                var gallery = $('#gallery');
                for (index in data.cards) {
                    languages.push({
                        'en': data.cards[index].en,
                        'dk': data.cards[index].dk,
                        'sk': data.cards[index].sk,
                    });
                    gallery.append('<li id="' + index + '" class="ui-widget-content ui-corner-tr">\n' +
                        '                        <h5 class="ui-widget-header"><i style="font-size: 11px; position: relative; top: -2px;" class="fa fa-arrows" aria-hidden="true"></i> ' + data.cards[index].en + '</h5>\n' +
                        '                        <a style="display: none;" href="link/to/trash/script/when/we/have/js/off"\n' +
                        '                           title="Delete this image"\n' +
                        '                           class="ui-icon ui-icon-trash">Delete image</a>\n' +
                        '                    </li>');
                }
                reload();
            });
        });
    </script>
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a target="_blank" class="navbar-brand" href="https://github.com/Luxato/free-cardsort"><i
                        style="font-size: 30px;position: relative;left: -8px;bottom: 7px;float: left;"
                        class="fa fa-github" aria-hidden="true"></i> Cardsort</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <form id="submit" action="<?= base_url() ?>cardsort/process_results" method="POST">
                <button data-placement="bottom" type="submit" style="float: right;position: relative;top: 8px;"
                        data-toggle="tooltip" title="You can submit your results when you will be finished"
                        class="btn btn-md btn-success"><i class="fa fa-paper-plane-o" aria-hidden="true"></i> Send
                    results
                </button>
                <input id="cardLang" name="cardLang" type="hidden" value="">
                <input id="data" name="result" type="hidden" value="">
            </form>
        </div><!--/.nav-collapse -->
    </div>
</nav>
<div style="margin-top: 60px;" class="ui-widget ui-helper-clearfix">

    <div class="container-fluid">
        <div class="row">
            <div id="aside" class="col-md-3"
                 style="padding-top:13px;word-wrap: break-word;border-right: 1px dashed lightgray; background: #A7B2B6;">
                <div class="pull-right">
                    <img class="lang" id="en" style="position: relative;left: -3px; cursor: pointer;"
                         src="<?= base_url() ?>assets/enus_22.png" data-placement="bottom" data-toggle="tooltip"
                         title="Switch to English" alt="Danish">
                    <img class="lang" id="dk" style="position: relative;left: -3px; cursor: pointer;"
                         src="<?= base_url() ?>assets/dk_22.png" data-placement="top" data-toggle="tooltip"
                         title="Switch to Danish" alt="Danish">
                    <img class="lang" id="sk" style="position: relative;left: -3px; cursor: pointer;"
                         src="<?= base_url() ?>assets/sk_22.png" data-placement="bottom" data-toggle="tooltip"
                         title="Switch to Slovak" alt="Slovak">
                </div>
                <ul id="gallery" class="gallery ui-helper-reset ui-helper-clearfix">
                </ul>
            </div>
            <div style="margin-top: 25px;" id="main" class="col-md-9">
                <div id="guide"
                     style="color:#4B555B;background: #E3EFF8; border: 2px solid #90BFE5; border-radius: 4px;"
                     class="col-md-4">
                    <h3>Step 1</h3>
                    <p>Take a quick look at the list of items to the left.</p>
                    <p>We'd like you to sort them into groups that make sense to you.</p>
                    <p>There is no right or wrong answer, just do what comes naturally.</p>
                    <h3>Step 2</h3>
                    <p>When you're ready, drag an item from the left to create your first group.</p>
                </div>
                <!--<div class="col-md-3">
                    <div style="border:1px solid #c0c0c0;" class="category legit-cat ui-widget-content ui-state-default">
                        <h4 style="margin: 0; border: 0; padding-right: 7px;" class="ui-widget-header">Sample category <i style="color: #da1111; margin-right: 5px;" class="fa fa-times pull-right" aria-hidden="true"></i>
                        </h4>
                        Drag items here.
                    </div>
                </div>-->
                <div class="col-md-4">
                    <div style="margin:10px 0;border-radius:4px;border: 1px solid #c0c0c0; opacity: 0.8;"
                         class="category create-new ui-widget-content ui-state-default">
                        <h4 style="font-size:17px;margin: 0; border: 0; padding-right: 7px;" class="ui-widget-header">Drag items inside this box</h4>
                        <div style="height: 100px;line-height: 20px;padding-top: 27px;color: gray;font-size: 12px; text-align: center;">
                            <div class="temporary"><i class="fa fa-arrows" aria-hidden="true"></i></div>
                            Drag items here to create new group
                        </div>
                    </div>
                </div>


            </div>
            <div class="container">
                <div class="row">
                    <div id="guide2"
                         style="float: right;margin-bottom: 25px;display: none;margin-left:15px;color:#4B555B;background: #E3EFF8; border: 2px solid #90BFE5; border-radius: 4px;"
                         class="col-md-4">
                        <h3>Step 3</h3>
                        <p>If you already know what you'd like to call this group, click the title to rename it now. If
                            not, you can do this later.</p>
                        <h3>Step 4</h3>
                        <p>When you're done, click "Finished" (top right).</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
<div id="submitModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div id="changable" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-info" data-dismiss="modal">Ok</button>
            </div>
        </div>

    </div>
</div>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
<script>
    $(function () {
        tooltip();
        var tmp = 0;
        $('#cardLang').val('en');
        $('.lang').on('click', function () {
            var elements = $('#gallery').find('li');
            lang = $(this).attr('id');
            $('#cardLang').val(lang);
            for (var i = 0, max = elements.length; i < max; i++) {
                var id = $(elements[i]).attr('id');
                $(elements[i]).find('h5').html('<i style="font-size: 11px; position: relative; top: -2px;" class="fa fa-arrows" aria-hidden="true"></i> ' + languages[id][lang]);
            }
            var elements = $('ul.gallery.ui-helper-reset');
            for (i = 0; i < elements.length; i++) {
                if (i == 0) continue;
                for (var l = 0, max = elements[i].children.length; l < max; l++) {
                    var id = $(elements[i].children[l]).attr('id');
                    $(elements[i].children[l]).find('h5').html('<i style="font-size: 11px; position: relative; top: -2px;" class="fa fa-arrows" aria-hidden="true"></i> ' + languages[id][lang]);
                }
            }
            tmp++;
        });
        $('#submit').on('submit', function (e) {
            if (update_results()) {
                e.preventDefault();
                $('#changable').html('<p><strong>You forgot to name all the groups.</strong></p>\n' +
                    '                <p><strong>Click the group title to change it.</strong></p>');
                $('#submitModal').modal('show');
            }
            if ($('#gallery li').length > 0) {
                e.preventDefault();
                $('#changable').html('<p><strong>You must sort all the cards and name all the groups before you can finish.</strong></p>\n' +
                    '                <p><strong>Click the group title to change it.</strong></p>');
                $('#submitModal').modal('show');
            }
        });
    });
</script>
</body>
</html>