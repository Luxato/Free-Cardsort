<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cardsort</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://bootswatch.com/flatly/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500" rel="stylesheet">
    <style>
        .legit-cat h5.ui-widget-header {
            background: #fff;
            color: #000;
        }
        .legit-cat .gallery.ui-helper-reset {
            background: #A7B2B6;
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
        .col-md-4 {
            font-family: 'Montserrat', sans-serif;
        }
        .legit-cat .ui-widget-header {
            font-size: 1em;
            font-weight: 500;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            text-decoration: underline;
            color: #b55c27;
            background: #D4DDDF;
            height: 50px;
            text-align: center;
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
    </style>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        function tooltip() {
            $('[data-toggle="tooltip"]').tooltip();
        }
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
                var recycle_icon = "<a class='_tooltip' href='link/to/recycle/script/when/we/have/js/off' data-toggle=\"tooltip\" title=\"Undo\" title='Recycle this image'><i class='fa fa-undo' aria-hidden='true'></i></a>";

                function deleteImage2($item, $container) {
                    $item.fadeOut(function () {
                        console.log($container);
                        var $list = $("ul", $container).length ?
                            $("ul", $container) :
                            $("<ul class='gallery ui-helper-reset'/>").appendTo($container);
                            $container.append("<div class=\"counter\" style=\"display: block;font-size: 13px;width: 100%; padding: 8px 0 8px 8px; background: #D4DDDF;\">2 items</div>");
                        $item.find("a.ui-icon-trash").remove();
                        $item.append(recycle_icon).appendTo($list).fadeIn('fast',function () {
                            $('[data-toggle="tooltip"]').tooltip();
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
                $item.parent().find('.counter').show();
                $item.parent().find('h4').replaceWith('<h4 style="line-height: 39px; margin: 0; border: 0; padding-right: 7px;" class="ui-widget-header">Click to rename <i style="color: #da1111; margin-right: 5px;" class="fa fa-times pull-right" aria-hidden="true"></i>');
                // Create new placeholder
                $('#main').append('<div class="col-md-4">\n' +
                    '                    <div style="border-radius: 4px; border: 1px solid #b7c6c9; opacity: 0.8;" class="category create-new ui-widget-content ui-state-default">\n' +
                    '                        <h4 style="margin: 0; border: 0; padding-right: 7px;" class="ui-widget-header">Click to rename</h4>\n' +
                    ' <div style="height: 100px;line-height: 20px;padding-top: 27px;color: gray;font-size: 12px; text-align: center;"> ' +
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
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a target="_blank" class="navbar-brand" href="https://github.com/Luxato/free-cardsort"><i style="font-size: 30px;position: relative;left: -8px;bottom: 7px;float: left;" class="fa fa-github" aria-hidden="true"></i> Cardsort</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <form action="<?= base_url() ?>" method="POST">
                <button data-placement="bottom" type="submit" style="float: right;position: relative;top: 8px;" data-toggle="tooltip" title="You can submit your results when you will be finished" class="btn btn-md btn-success"><i class="fa fa-paper-plane-o" aria-hidden="true"></i> Send results</button>
            </form>
        </div><!--/.nav-collapse -->
    </div>
</nav>
<div style="margin-top: 60px;" class="ui-widget ui-helper-clearfix">

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3" style="padding-top:13px;word-wrap: break-word;border-right: 1px dashed lightgray; background: #A7B2B6;">
                <!--<img src="<?/*= base_url() */?>assets/enus_22.png" data-placement="bottom" data-toggle="tooltip" title="English" alt="English"> |-->
                <img style="position: relative;left: -3px;" class="pull-right" src="<?= base_url() ?>assets/dk_22.png" data-placement="bottom" data-toggle="tooltip" title="Switch to Danish" alt="Danish">
                <ul id="gallery" class="gallery ui-helper-reset ui-helper-clearfix">
                </ul>
            </div>
            <div style="margin-top: 25px;" id="main" class="col-md-9">
                <div id="guide" style="color:#4B555B;background: #E3EFF8; border: 2px solid #90BFE5; border-radius: 4px;" class="col-md-4">
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
                    <div style="border-radius:4px;border: 1px solid #c0c0c0; opacity: 0.8;" class="category create-new ui-widget-content ui-state-default">
                        <h4 style="margin: 0; border: 0; padding-right: 7px;" class="ui-widget-header">Click to rename</h4>
                        <div style="height: 100px;line-height: 20px;padding-top: 27px;color: gray;font-size: 12px; text-align: center;">
                            <div class="temporary"><i class="fa fa-arrows" aria-hidden="true"></i></div>
                            Drag items here to create new group
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>


</div>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script>
    $(function(){
        tooltip();
    });
</script>
</body>
</html>