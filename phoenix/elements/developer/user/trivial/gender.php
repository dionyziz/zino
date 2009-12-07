<?php
    
    class ElementDeveloperUserTrivialGender extends Element {

        public function Render( $gender ) {
            $sex = array( '-'    =>    '-',
                          'm'     => 'Άνδρας',
                          'f'    => 'Γυναίκα'
            );
            echo htmlspecialchars( $sex[ $gender ] );
        }
    }
?>
