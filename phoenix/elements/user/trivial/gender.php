<?php
    
    class ElementUserTrivialGender extends Element {
        protected $mPersistent = array( 'gender' );

        public function Render( $gender ) {
            $sex = array( '-'    =>    '-',
                          'm'     => 'Άνδρας',
                          'f'    => 'Γυναίκα'
            );
            echo htmlspecialchars( $sex[ $gender ] );
        }
    }
?>
