<?php
    
    class ElementUserTrivialSex extends Element {
        protected $mPersistent = array( 'sex', 'gender' );

        public function Render( $sex , $gender ) {
            if ( $gender == 'm' || $gender == '-' ) {
                $sexes = array( 
                            '-' => '-',
                            'straight' => 'Straight',
                            'bi' => 'Bisexual',
                            'gay' => 'Gay'
                );
            }
            else {
                $sexes = array( 
                        '-' => '-',
                        'straight' => 'Straight',
                        'bi' => 'Bisexual',
                        'gay' => 'Λεσβία'
                );
            }
            echo htmlspecialchars( $sexes[ $sex ] );
        }
    }
?>
