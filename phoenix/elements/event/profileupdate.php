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

        // abresas
        // user $one->Item instead of $one->User->Profile for optimization!

        foreach ( $eventlist as $one ) {
            ob_start();
            switch ( $one->Typeid ) {
                case EVENT_USERPROFILE_EDUCATION_UPDATED:
                    ?>πάει <?php
                    ob_start();
                    Element( 'user/trivial/education' , $one->Item->Education );
                    $education = ob_get_clean();
                    if ( $education != mb_strtoupper( $education ) ) {
                        // if it's not all-upper case (abbreviation), lower-case it
                        $education = mb_strtolower( $education );
                    }
                    echo $education;
                    break;
                case EVENT_USERPROFILE_SEXUALORIENTATION_UPDATED:
                    ?>είναι <?php
                    ob_start();
                    Element( 'user/trivial/sex' , $one->Item->Sexualorientation , $one->User->Gender );
                    echo mb_strtolower( ob_get_clean() );
                    break;
                case EVENT_USERPROFILE_RELIGION_UPDATED:
                    ob_start();
                    switch ( $one->Item->Religion ) {
                        case 'nothing':
                            ?>δεν έχει θρησκευτικές πεποιθήσεις<?php
                            break;
                        default:
                            ?>είναι <?php
                            Element( 'user/trivial/religion' , $one->Item->Religion , $one->User->Gender );
                    }
                    echo mb_strtolower( ob_get_clean() );
                    break;
                case EVENT_USERPROFILE_POLITICS_UPDATED:
                    ob_start();
                    switch ( $one->Item->Politics ) {
                        case 'nothing':
                            ?>δεν έχει πολιτικές πεποιθήσεις<?php
                            break;
                        default:
                            ?>είναι <?php
                            Element( 'user/trivial/politics' , $one->Item->Politics , $one->User->Gender );
                    }
                    echo mb_strtolower( ob_get_clean() );
                    break;
                case EVENT_USERPROFILE_SMOKER_UPDATED:
                    switch ( $one->Item->Smoker ) {
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
                    switch ( $one->Item->Drinker ) {
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
					$aboutme = mb_substr( $one->Item->Aboutme, 0, 20 );
                    echo htmlspecialchars( $aboutme );
					if ( mb_strlen( $one->Item->Aboutme ) > mb_strlen( $aboutme ) ) {
						?>...<?php
					}
                    ?>"<?php
                    break;
                case EVENT_USERPROFILE_MOOD_UPDATED:
                    ?>είναι <?php
                    if ( $one->User->Gender == 'm' ) {
                        echo htmlspecialchars( mb_strtolower( $one->Item->Mood->Labelmale ) );
                    }
                    else {
                        echo htmlspecialchars( mb_strtolower( $one->Item->Mood->Labelfemale ) );
                    }
                    break;
                case EVENT_USERPROFILE_LOCATION_UPDATED:
                    ?>μένει <?php
                    echo htmlspecialchars( $one->Item->Location->Nameaccusative );
                    break;
                case EVENT_USERPROFILE_HEIGHT_UPDATED:
                    ?>είναι <?php
                    ob_start();
                    Element( 'user/trivial/height' , $one->Item->Height );
                    echo mb_strtolower( ob_get_clean() );
                    break;
                case EVENT_USERPROFILE_WEIGHT_UPDATED:
                    ?>είναι <?php
                    ob_start();
                    Element( 'user/trivial/weight' , $one->Item->Weight );
                    echo mb_strtolower( ob_get_clean() );
                    break;
                case EVENT_USERPROFILE_HAIRCOLOR_UPDATED:
                    if ( $one->Item->Haircolor == 'highlights' ) {
                        ?>έχει ανταύγειες<?php
                    }
                    else if ( $one->Item->Haircolor == 'skinhead' ) {
                        ?>είναι skinhead<?php
                    }
                    else {
                        ?>έχει <?php 
                        ob_start();
                        Element( 'user/trivial/haircolor' , $one->Item->Haircolor );
                        echo mb_strtolower( ob_get_clean() );
                        ?> μαλλί<?php
                    }
                    break;
                case EVENT_USERPROFILE_EYECOLOR_UPDATED:
                    ?>έχει <?php
                    ob_start();
                    Element( 'user/trivial/eyecolor' , $one->Item->Eyecolor );
                    echo mb_strtolower( ob_get_clean() );
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
