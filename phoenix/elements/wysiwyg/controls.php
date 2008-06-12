<?php
    function ElementWYSIWYGControls() {
        ?><div class="wysiwyg-control" id="wysiwyg-controls">
            <form class="wysiwyg-control-video">
                <br /><br />Πληκτρολόγησε την διεύθυνση του video στο YouTube:
                <br /><br />
                <input type="text" value="" style="width:400px" />
                <br /><br />
                <input type="submit" value="Εισαγωγή" onclick="WYSIWYG.InsertVideo(WYSIWYG.CurrentTarget, $( this.parentNode ).find( 'input' )[ 0 ].value );Modals.Destroy();" />
                <input type="button" value="Ακύρωση" onclick="Modals.Destroy()" />
            </form>
            <form class="wysiwyg-control-image-start">
                <br /><br />
                <ul>
                    <li><a href="" onclick="Modals.Destroy();alert($(this).parents('div')[0].className);return false;">Εισαγωγή εικόνας με την διεύθυνσή της</a></li>
                    <li><a href="" onclick="Modals.Destroy();Modals.Create($(this).parents('div.wysiwyg-control').find('.wysiwyg-control-image-album')[0].cloneNode(true));return false;">Εισαγωγή εικόνας από τα albums μου</a></li>
                    <li><a href="" onclick="Modals.Destroy();return false;">Εισαγωγή εικόνας από τον υπολογιστή μου</a></li>
                </ul>
                <input type="button" value="Ακύρωση" onclick="Modals.Destroy()" />
            </form>
            <form class="wysiwyg-control-image-url">
                <br /><br />Πληκτρολόγησε την διεύθυνση της εικόνας:
                <br /><br />
                <input type="text" value="" style="width:400px" />
                <br /><br />
                <input type="submit" value="Εισαγωγή" onclick="WYSIWYG.InsertImage(WYSIWYG.CurrentTarget, $( this.parentNode ).find( 'input' )[ 0 ].value );Modals.Destroy();" />
                <input type="button" value="Ακύρωση" onclick="Modals.Destroy()" />
            </form>
            <form class="wysiwyg-control-image-album">
                <div class="photolist">
                </div>
                <div class="albumlist"><?php
                foreach ( $user->Albums as $album ) {
                    if ( $album->Id == $user->EgoAlbum->Id ) {
                        $title = 'Φωτογραφίες μου';
                    }
                    else {
                        $title = $album->Name;
                    }
                    ?><a href="" onclick="WYSIWYG.InsertFromAlbum(WYSIWYG.CurrentTarget,<?php
                    echo $album->Id;
                    ?>);return false;">
                    <?php
                    Element( 'image', New Image( $album->Mainimage ), IMAGE_CROPPED_100x100, '', $title, $title, '', false, 0, 0 ); // TODO: Optimize
                    ?>" alt="<?php
                    echo htmlspecialchars( $album->Name );
                    ?>" /><?php
                    echo htmlspecialchars( $album->Name );
                    ?></a><?php
                }
                ?></div>
                <input type="button" value="Ακύρωση" onclick="Modals.Destroy()" />
            </form>
        </div><?php
    }
?>
