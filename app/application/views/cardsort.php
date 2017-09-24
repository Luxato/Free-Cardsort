<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cardsort</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://bootswatch.com/flatly/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .legit-cat h5.ui-widget-header {
            background: #fff;
            color: #000;
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
        .create-new h4{
            color: gray;
        }
        .legit-cat .ui-widget-header {
            color: #fff;
            background: #333;
        }
        h4 {
            padding: 4px 0 4px 7px;
        }
        .category .ui-widget-content, .category li h5 {
            display: inline-block !important;
            float: left !important;
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
    </style>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(function () {
            function reload() {
                var oriVal;
                $(".category").on('click', 'h4', function () {
                    oriVal = $(this).text();
                    $(this).text("");
                    $("<input style='width: 100%;color: gray;' type='text'>")
                        .appendTo(this)
                        .focus()
                        .end()
                        .on('blur keyup', function (e) {
                            if (e.type == 'blur' || e.keyCode == '13') {
                                var $this = $(this);
                                $this.parent().text($this.val() || oriVal);
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
                    start: function () {
                        console.log('zacinam tahat');
                    },
                    cancel: "a.ui-icon", // clicking an icon won't initiate dragging
                    revert: "invalid", // when not dropped, the item will revert back to its initial position
                    containment: "document",
                    helper: "clone",
                    cursor: "move"
                });

                $trash2.droppable({
                    accept: "#gallery > li",
                    classes: {
                        "ui-droppable-active": "ui-state-highlight"
                    },
                    drop: function (event, ui) {
                        console.log($(this).hasClass('.legit-cat'));
                        deleteImage2(ui.draggable, $(this));
                        if ($(this).hasClass('create-new')) {
                            change_to_normal_state($(this));
                        }
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
                        //recycleImage(ui.draggable);
                    }
                });

                // Image deletion function
                var recycle_icon = "<a href='link/to/recycle/script/when/we/have/js/off' title='Recycle this image'><i class='fa fa-trash' aria-hidden='true'></i></a>";

                function deleteImage2($item, $container) {
                    $item.fadeOut(function () {
                        var $list = $("ul", $container).length ?
                            $("ul", $container) :
                            $("<ul class='gallery ui-helper-reset'/>").appendTo($container);
                        $item.find("a.ui-icon-trash").remove();
                        $item.append(recycle_icon).appendTo($list).fadeIn('fast',function () {

                        });
                    });
                }

                // Image recycle function
                var trash_icon = "<a href='link/to/trash/script/when/we/have/js/off' title='Delete this image' class='ui-icon ui-icon-trash'>Delete image</a>";

                function recycleImage($item) {
                    $item.fadeOut(function () {
                        $item
                            .find("a.ui-icon-refresh")
                            .remove()
                            .end()
                            .append(trash_icon)
                            .end()
                            .appendTo($gallery)
                            .fadeIn();
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
            }

            function change_to_normal_state($item) {
                var title = $item.parent().find('h4').parent();
                title.removeClass('create-new');
                title.addClass('legit-cat');
                title.css('opacity','1');
                $item.parent().find('.temporary').parent().remove();
                $item.parent().find('h4').replaceWith('<h4 style="margin: 0; border: 0; padding-right: 7px;" class="ui-widget-header">Sample category <i style="color: #da1111; margin-right: 5px;" class="fa fa-times pull-right" aria-hidden="true"></i>');
                $('#main').append('<div class="col-md-3">\n' +
                    '                    <div style="border: 1px solid #c0c0c0; opacity: 0.8;" class="category create-new ui-widget-content ui-state-default">\n' +
                    '                        <h4 style="margin: 0; border: 0; padding-right: 7px;" class="ui-widget-header">Double-click to edit group name</h4>\n' +
                    '                        <div style="color: gray;font-size: 12px; text-align: center;">\n' +
                    '                            <div class="temporary"><i class="fa fa-arrows" aria-hidden="true"></i></div>\n' +
                    '                            Drag items here to create new group</div>\n' +
                    '                    </div>\n' +
                    '                </div>');
                reload();
            }

            $.getJSON('cards.json',function(data){
                //alert(JSON.stringify(data));
                var gallery = $('#gallery');
                for (index in data.cards) {
                    gallery.append('<li id="'+index+'" class="ui-widget-content ui-corner-tr">\n' +
                        '                        <h5 class="ui-widget-header"><i style="font-size: 11px; position: relative; top: -2px;" class="fa fa-arrows" aria-hidden="true"></i> '+data.cards[index].en+'</h5>\n' +
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

<div class="ui-widget ui-helper-clearfix">

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2" style="border-right: 1px dashed lightgray; background: #ededed;">
                <h2 style="text-align: center;">Cards</h2>
                <ul id="gallery" class="gallery ui-helper-reset ui-helper-clearfix">
                </ul>
            </div>
            <div id="main" class="col-md-10">
                <h2 style="text-align: center;">Categories</h2>
                <!--<div class="col-md-3">
                    <div style="border:1px solid #c0c0c0;" class="category legit-cat ui-widget-content ui-state-default">
                        <h4 style="margin: 0; border: 0; padding-right: 7px;" class="ui-widget-header">Sample category <i style="color: #da1111; margin-right: 5px;" class="fa fa-times pull-right" aria-hidden="true"></i>
                        </h4>
                        Drag items here.
                    </div>
                </div>-->
                <div class="col-md-3">
                    <div style="border: 1px solid #c0c0c0; opacity: 0.8;" class="category create-new ui-widget-content ui-state-default">
                        <h4 style="margin: 0; border: 0; padding-right: 7px;" class="ui-widget-header">Double-click to edit group name</h4>
                        <div style="color: gray;font-size: 12px; text-align: center;">
                            <div class="temporary"><i class="fa fa-arrows" aria-hidden="true"></i></div>
                            Drag items here to create new group</div>
                    </div>
                </div>


            </div>
        </div>
    </div>


</div>


</body>
</html>