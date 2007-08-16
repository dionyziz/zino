<?php
    function ElementTest() {
    	global $libs;
    	$libs->Load( 'article' );
        /*var_dump(
            User_ByUsername(
                array(
                    'izual', 'dionyziz', 'abresas'
                )
            )
        );*/
        $arthro = New Article( 283 );
        echo "<!--";
        echo $arthro->SmallStory();
        echo "-->";
    }
?>
