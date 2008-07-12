<?php
	
	class ElementUserTrivialGender extends Element {
        public function Render( $gender ) {
            $sex = array( '-'	=>	'-',
                          'm' 	=> 'Άνδρας',
                          'f'	=> 'Γυναίκα'
            );
            echo htmlspecialchars( $sex[ $gender ] );
        }
    }
?>
