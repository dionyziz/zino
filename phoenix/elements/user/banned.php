<?php
    function ElementUserBanned() {
        global $xc_settings;
        global $rabbit_settings;
        global $page;
        
        $page->SetTitle( 'Ο λογαριασμός έχει τεθεί εκτός λειτουργίας' );
        
        ?><div style="margin:10px auto 0 auto;padding:10px;width:70%;">
            <img src="<?php
            echo $xc_settings[ 'staticimagesurl' ];
            ?>zino-150-reflection.jpg" alt="<?php 
            echo htmlspecialchars( $rabbit_settings[ 'applicationname' ] ); 
            ?>" style="float:left;margin:10px;" />
            <div style="margin-left:170px;">
                <br />Το σύστημά μας παρατήρησε ότι πραγματοποιείς ασυνήθιστη χρήση του λογαριασμού σου. Προκειμένου να προστατευθούν οι χρήστες του <?php 
                echo htmlspecialchars( $rabbit_settings[ 'applicationname' ] );
                ?> από την ενδεχομένως επιβλαβή χρήση του <?php
                echo htmlspecialchars( $rabbit_settings[ 'applicationname' ] );
                ?>, αυτός ο λογαριασμό έχει τεθεί εκτός λειτουργίας για μερικές ώρες. Εάν χρησιμοποιείς οποιοδήποτε λογισμικό τρίτων που αλληλεπιδρά με τον λογαριασμό σου στο <?php 
                echo htmlspecialchars( $rabbit_settings[ 'applicationname' ] );
                ?>, παρακαλούμε απενεργοποίησέ το ή ρυθμισέ το έτσι ώστε η λειτουργία του να συμμορφώνεται με τους Όρους Χρήσης των υπηρεσιών του <?php 
                echo htmlspecialchars( $rabbit_settings[ 'applicationname' ] );
                ?>. Εάν θεωρείς ότι έχεις χρησιμοποιήσει τον λογαριασμό σου στο <?php 
                echo htmlspecialchars( $rabbit_settings[ 'applicationname' ] );
                ?> σύμφωνα με τους Όρους Χρήσης, παρακαλώ <a href="mailto:webmaster@zino.gr">επικοινώνησε μαζί μας</a> για να επιλύσουμε αυτό το πρόβλημα.
            </div>
        </div><?php
    }
?>
