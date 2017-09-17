<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Custom Cardsort</title>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="https://bootswatch.com/flatly/bootstrap.min.css">
	<style>
		#gallery { float: left; width: 65%; min-height: 12em; }
		.gallery.custom-state-active { background: #eee; }
		.gallery li { float: left; width: 96px; padding: 0.4em; margin: 0 0.4em 0.4em 0; text-align: center; }
		.gallery li h5 { margin: 0 0 0.4em; cursor: move; }
		.gallery li a { float: right; }
		.gallery li a.ui-icon-zoomin { float: left; }
		.gallery li img { width: 100%; cursor: move; }

		#trash {height:200px; padding: 1%;
			z-index: -5;}
		#trash h4 { line-height: 16px; margin: 0 0 0.4em; }
		#trash h4 .ui-icon { float: left; }
		#trash .gallery h5 { display: none; }
		#trash2 {height:200px; padding: 1%; }
		#trash2 h4 { line-height: 16px; margin: 0 0 0.4em; }
		#trash2 h4 .ui-icon { float: left; }
		#trash2 .gallery h5 { display: none; }
		.gallery li {
			z-index: 999999;
		}
	</style>
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script>
        $( function() {

            // There's the gallery and the trash
            var $gallery = $( "#gallery" ),
                $trash = $( "#trash" );
            $trash2 = $( "#trash2" );

            // Let the gallery items be draggable
            $( "li", $gallery ).draggable({
                cancel: "a.ui-icon", // clicking an icon won't initiate dragging
                revert: "invalid", // when not dropped, the item will revert back to its initial position
                containment: "document",
                helper: "clone",
                cursor: "move"
            });

            // Let the trash be droppable, accepting the gallery items
            $trash.droppable({
                accept: "#gallery > li",
                classes: {
                    "ui-droppable-active": "ui-state-highlight"
                },
                drop: function( event, ui ) {
                    deleteImage( ui.draggable );
                }
            });

            $trash2.droppable({
                accept: "#gallery > li",
                classes: {
                    "ui-droppable-active": "ui-state-highlight"
                },
                drop: function( event, ui ) {
                    deleteImage2( ui.draggable );
                }
            });

            // Let the gallery be droppable as well, accepting items from the trash
            $gallery.droppable({
                accept: "#trash li, #trash2 li",
                classes: {
                    "ui-droppable-active": "custom-state-active"
                },
                drop: function( event, ui ) {
                    recycleImage( ui.draggable );
                }
            });

            // Image deletion function
            var recycle_icon = "<a href='link/to/recycle/script/when/we/have/js/off' title='Recycle this image' class='ui-icon ui-icon-refresh'>Recycle image</a>";
            function deleteImage( $item ) {
                $item.fadeOut(function() {
                    var $list = $( "ul", $trash ).length ?
                        $( "ul", $trash ) :
                        $( "<ul class='gallery ui-helper-reset'/>" ).appendTo( $trash );

                    $item.find( "a.ui-icon-trash" ).remove();
                    $item.append( recycle_icon ).appendTo( $list ).fadeIn(function() {
                        $item
                            .animate({ width: "48px" })
                            .find( "img" )
                            .animate({ height: "36px" });
                    });
                });
            }

            function deleteImage2( $item ) {
                $item.fadeOut(function() {
                    var $list = $( "ul", $trash2 ).length ?
                        $( "ul", $trash2 ) :
                        $( "<ul class='gallery ui-helper-reset'/>" ).appendTo( $trash2 );

                    $item.find( "a.ui-icon-trash" ).remove();
                    $item.append( recycle_icon ).appendTo( $list ).fadeIn(function() {
                        $item
                            .animate({ width: "48px" })
                            .find( "img" )
                            .animate({ height: "36px" });
                    });
                });
            }

            // Image recycle function
            var trash_icon = "<a href='link/to/trash/script/when/we/have/js/off' title='Delete this image' class='ui-icon ui-icon-trash'>Delete image</a>";
            function recycleImage( $item ) {
                $item.fadeOut(function() {
                    $item
                        .find( "a.ui-icon-refresh" )
                        .remove()
                        .end()
                        .css( "width", "96px")
                        .append( trash_icon )
                        .find( "img" )
                        .css( "height", "72px" )
                        .end()
                        .appendTo( $gallery )
                        .fadeIn();
                });
            }

            // Image preview function, demonstrating the ui.dialog used as a modal window
            function viewLargerImage( $link ) {
                var src = $link.attr( "href" ),
                    title = $link.siblings( "img" ).attr( "alt" ),
                    $modal = $( "img[src$='" + src + "']" );

                if ( $modal.length ) {
                    $modal.dialog( "open" );
                } else {
                    var img = $( "<img alt='" + title + "' width='384' height='288' style='display: none; padding: 8px;' />" )
                        .attr( "src", src ).appendTo( "body" );
                    setTimeout(function() {
                        img.dialog({
                            title: title,
                            width: 400,
                            modal: true
                        });
                    }, 1 );
                }
            }

            // Resolve the icons behavior with event delegation
            $( "ul.gallery > li" ).on( "click", function( event ) {
                var $item = $( this ),
                    $target = $( event.target );

                if ( $target.is( "a.ui-icon-trash" ) ) {
                    deleteImage( $item );
                } else if ( $target.is( "a.ui-icon-zoomin" ) ) {
                    viewLargerImage( $target );
                } else if ( $target.is( "a.ui-icon-refresh" ) ) {
                    recycleImage( $item );
                }

                return false;
            });
        } );
	</script>
</head>
<body>

<div class="ui-widget ui-helper-clearfix">

	<div class="container">
		<div class="row">
			<ul id="gallery" class="gallery ui-helper-reset ui-helper-clearfix">
				<li class="ui-widget-content ui-corner-tr">
					<h5 class="ui-widget-header">High Tatras</h5>
					<img src="images/high_tatras_min.jpg" alt="The peaks of High Tatras" width="96" height="72">
					<a href="images/high_tatras.jpg" title="View larger image" class="ui-icon ui-icon-zoomin">View larger</a>
					<a href="link/to/trash/script/when/we/have/js/off" title="Delete this image" class="ui-icon ui-icon-trash">Delete image</a>
				</li>
				<li class="ui-widget-content ui-corner-tr">
					<h5 class="ui-widget-header">High Tatras 2</h5>
					<img src="images/high_tatras2_min.jpg" alt="The chalet at the Green mountain lake" width="96" height="72">
					<a href="images/high_tatras2.jpg" title="View larger image" class="ui-icon ui-icon-zoomin">View larger</a>
					<a href="link/to/trash/script/when/we/have/js/off" title="Delete this image" class="ui-icon ui-icon-trash">Delete image</a>
				</li>
				<li class="ui-widget-content ui-corner-tr">
					<h5 class="ui-widget-header">High Tatras 3</h5>
					<img src="images/high_tatras3_min.jpg" alt="Planning the ascent" width="96" height="72">
					<a href="images/high_tatras3.jpg" title="View larger image" class="ui-icon ui-icon-zoomin">View larger</a>
					<a href="link/to/trash/script/when/we/have/js/off" title="Delete this image" class="ui-icon ui-icon-trash">Delete image</a>
				</li>
				<li class="ui-widget-content ui-corner-tr">
					<h5 class="ui-widget-header">High Tatras 4</h5>
					<img src="images/high_tatras4_min.jpg" alt="On top of Kozi kopka" width="96" height="72">
					<a href="images/high_tatras4.jpg" title="View larger image" class="ui-icon ui-icon-zoomin">View larger</a>
					<a href="link/to/trash/script/when/we/have/js/off" title="Delete this image" class="ui-icon ui-icon-trash">Delete image</a>
				</li>
			</ul>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<div id="trash" class="ui-widget-content ui-state-default">
					<h4 class="ui-widget-header"><span class="ui-icon ui-icon-trash">Trash</span> Sample category</h4>
				</div>
			</div>

			<div class="cold-md-6">
				<div id="trash2" class="ui-widget-content ui-state-default">
					<h4 class="ui-widget-header"><span class="ui-icon ui-icon-trash">Trash</span> Sample category 2</h4>
				</div>
			</div>
		</div>
	</div>


</div>


</body>
</html>