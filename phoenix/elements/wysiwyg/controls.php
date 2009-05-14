<?php
    class ElementWYSIWYGControls extends Element {
        public function Render() {
            global $user;

            ?><div class="wysiwyg-control" id="wysiwyg-controls">
                <form class="wysiwyg-control-video">
                    <br /><br />Πληκτρολόγησε την διεύθυνση του video στο YouTube:
                    <br /><br />
                    <input type="text" value="" style="width:400px" />
                    <br /><br />
                    <input type="button" value="Εισαγωγή" onclick="WYSIWYG.InsertVideo(WYSIWYG.CurrentTarget, $( this.parentNode ).find( 'input' )[ 0 ].value );Modals.Destroy();" />
                    <input type="button" value="Ακύρωση" onclick="Modals.Destroy()" />
                </form>
                <form class="wysiwyg-control-image-start">
                    <br /><br />
                    <ul>
                        <li><a href="" onclick="Modals.Destroy();Modals.Create($('#wysiwyg-controls form.wysiwyg-control-image-url')[0].cloneNode(true));return false">Εισαγωγή εικόνας με την διεύθυνσή της</a></li>
                        <?php
                        $hasalbums = false;
                        foreach ( $user->Albums as $album ) {
                            if ( $album->Numphotos ) {
                                $hasalbums = true;
                            }
                        }
                        if ( $hasalbums ) {
                            ?><li><a href="" onclick="Modals.Destroy();Modals.Create($('#wysiwyg-controls form.wysiwyg-control-image-album')[0].cloneNode(true),700,500);return false">Εισαγωγή εικόνας από τα albums μου</a></li><?php
                        }
                        ?>
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
                    <div class="albumlist"><?php
                    foreach ( $user->Albums as $album ) {
                        if ( !$album->Numphotos ) {
                            continue;
                        }
                        if ( $album->Id == $user->EgoAlbum->Id ) {
                            $title = 'Φωτογραφίες μου';
                        }
                        else {
                            $title = $album->Name;
                        }
                        ?>
                        <div class="album">
                            <a href="" onclick="WYSIWYG.InsertFromAlbum(WYSIWYG.CurrentTarget,<?php
                            echo $album->Id;
                            ?>, this);return false">
                            <?php
                            Element( 'image/view', $album->Mainimage->Id , $album->Mainimage->User->Id , $album->Mainimage->Width , $album->Mainimage->Height , IMAGE_CROPPED_100x100 , '' , $title , '', false, 0, 0 , 0 ); // TODO: Optimize
                            ?><br /><?php
                            echo htmlspecialchars( $title );
                            ?></a>
                        </div><?php
                    }
                    ?></div>
                    <div class="photolist">
                        <br /><br />Επίλεξε το album από το οποίο θέλεις να εισάγεις την φωτογραφία.
                    </div>
                    <input type="button" value="X" onclick="Modals.Destroy()" />
                </form>
            </div><?php
        }
    }
?>
