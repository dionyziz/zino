<?php
    function ElementWYSIWYG( $id = 'wysiwyg', $target = 'text', $contents = '' ) {
        ?><div id="<?php
        echo $id;
        ?>" class="wysiwyg"><?php
        echo $contents;
        ?></div>

        <div class="wysiwyg-control">
            <form id="wysiwyg-control-video">
                <br /><br />Πληκτρολόγησε την διεύθυνση του video στο YouTube:
                <br /><br />
                <input type="text" value="" style="width:400px" />
                <br /><br />
                <input type="submit" value="Εισαγωγή" onclick="WYSIWYG.InsertVideo('<?php
                echo $target;
                ?>', $( this.parentNode ).find( 'input' )[ 0 ].value );Modals.Destroy();" />
                <input type="button" value="Ακύρωση" onclick="Modals.Destroy()" />
            </form>
            <form id="wysiwyg-control-image-start">
                <br /><br />
                <ul>
                    <li><a href="" onclick="Modals.Destroy();Modals.Create($('#wysiwyg-control-image-url')[0].cloneNode(true));return false;">Εισαγωγή εικόνας με την διεύθυνσή της</a></li>
                    <li><a href="" onclick="Modals.Destroy();return false;">Εισαγωγή εικόνας από τα albums μου</a></li>
                    <li><a href="" onclick="Modals.Destroy();return false;">Εισαγωγή εικόνας από τον υπολογιστή μου</a></li>
                </ul>
                <input type="button" value="Ακύρωση" onclick="Modals.Destroy()" />
            </form>
            <form id="wysiwyg-control-image-url">
                <br /><br />Πληκτρολόγησε την διεύθυνση της εικόνας:
                <br /><br />
                <input type="text" value="" style="width:400px" />
                <br /><br />
                <input type="submit" value="Εισαγωγή" onclick="WYSIWYG.InsertImage('<?php
                echo $target;
                ?>', $( this.parentNode ).find( 'input' )[ 0 ].value );Modals.Destroy();" />
                <input type="button" value="Ακύρωση" onclick="Modals.Destroy()" />
            </form>
        </div>
        <?php
    }
?>
