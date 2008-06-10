<?php
    function ElementEventProfileUpdate( $eventlist ) {
        global $water;

        if ( $eventlist[ 0 ]->User->Gender =='f' ) {
            ?>Η <?php
            $self = 'της';
        }
        else {
            ?>O <?php
            $self = 'του';
        }
        echo $eventlist[ 0 ]->User->Name;
        $profileinfo = array();
        foreach ( $eventlist as $one ) {
            ob_start();
            switch ( $one->Typeid ) {
                case EVENT_USERPROFILE_EDUCATION_UPDATED:
                    ?>πάει <?php
                    ob_start();
                    Element( 'user/trivial/education' , $one->User->Profile->Education );
                    echo strtolower( ob_get_clean() );
                    break;
                case EVENT_USERPROFILE_SEXUALORIENTATION_UPDATED:
                    ?>είναι <?php
                    ob_start();
                    Element( 'user/trivial/sex' , $one->User->Profile->Sexualorientation , $one->User->Gender );
                    echo strtolower( ob_get_clean() );
                    break;
                case EVENT_USERPROFILE_RELIGION_UPDATED:
                    ?>είναι <?php
                    ob_start();
                    Element( 'user/trivial/religion' , $one->User->Profile->Religion , $one->User->Gender );
                    echo strtolower( ob_get_clean() );
                    break;
                case EVENT_USERPROFILE_POLITICS_UPDATED:
                    ?>είναι <?php
                    ob_start();
                    Element( 'user/trivial/politics' , $one->User->Profile->Politics , $one->User->Gender );
                    echo strtolower( ob_get_clean() );
                    break;
                case EVENT_USERPROFILE_SMOKER_UPDATED:
                    switch ( $one->User->Profile->Smoker ) {
                        case 'yes':
                            ?>καπνίζει<?php
                            break;
                        case 'no':
                            ?>δεν καπνίζει<?php
                            break;
                        case 'socially':
                            ?>καπνίζει με παρέα<?php
                            break;
                    }
                    break;
                case EVENT_USERPROFILE_DRINKER_UPDATED:
                    switch ( $one->User->Profile->Drinker ) {
                        case 'yes':
                            ?>πίνει<?php
                            break;
                        case 'no':
                            ?>δεν πίνει<?php
                            break;
                        case 'socially':
                            ?>πίνει με παρέα<?php
                            break;
                    }
                    break;
                case EVENT_USERPROFILE_ABOUTME_UPDATED:
                    ?>έγραψε για τον εαυτό <?php
                    echo $self;
                    ?> "<?php
                    echo htmlspecialchars( utf8_substr( $one->User->Profile->Aboutme , 0 , 20 ) );
                    ?>"<?php
                    break;
                case EVENT_USERPROFILE_MOOD_UPDATED:
                    break;
                case EVENT_USERPROFILE_LOCATION_UPDATED:
                    ?>είναι από <?php
                    echo htmlspecialchars( $one->User->Profile->Location->Name );
                    break;
                case EVENT_USERPROFILE_HEIGHT_UPDATED:
                    ?>είναι <?php
                    ob_start();
                    Element( 'user/trivial/height' , $one->User->Profile->Height );
                    echo strtolower( ob_get_clean() );
                    break;
                case EVENT_USERPROFILE_WEIGHT_UPDATED:
                    ?>είναι <?php
                    ob_start();
                    Element( 'user/trivial/weight' , $one->User->Profile->Weight );
                    echo strtolower( ob_get_clean() );
                    break;
                case EVENT_USERPROFILE_HAIRCOLOR_UPDATED:
                    if ( $one->User->Profile->Haircolor == 'highlights' ) {
                        ?>έχει ανταύγειες<?php
                    }
                    else if ( $one->User->Profile->Haircolor == 'skinhead' ) {
                        ?>είναι skinhead<?php
                    }
                    else {
                        ?>έχει <?php 
                        ob_start();
                        Element( 'user/trivial/haircolor' , $one->User->Profile->Haircolor );
                        echo strtolower( ob_get_clean() );
                        ?> μαλλί<?php
                    }
                    break;
                case EVENT_USERPROFILE_EYECOLOR_UPDATED:
                    ?>έχει <?php
                    ob_start();
                    $water->Trace( 'Eyecolor for : ' . $one->User->Name . ' is ' . $one->User->Profile->Eyecolor );
                    Element( 'user/trivial/eyecolor' , $one->User->Profile->Eyecolor );
                    echo strtolower( ob_get_clean() );
                    ?> χρώμα ματιών<?php
                    break;
            }
            $profileinfo[] = ob_get_clean();
        }
        if ( count( $profileinfo ) > 1 ) {
            $profileinfo[ count( $profileinfo ) - 2 ] = $profileinfo[ count( $profileinfo ) - 2 ] . " και " . $profileinfo[ count( $profileinfo ) - 1 ];
            unset( $profileinfo[ count( $profileinfo ) - 1 ] );
        }
        ?> <?php
        echo implode( ', ', $profileinfo );
    }
?>
