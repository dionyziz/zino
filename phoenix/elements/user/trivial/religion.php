<?php
	
	class ElementUserTrivialReligion extends Element {
        public function Render( $religion , $gender ) {
            if ( $gender == 'm' || $gender == '-' ) {
                $religions = array( '-'   => '-',
                            'christian'   => 'Χριστιανός',
                            'muslim'      => 'Ισλαμιστής',
                            'atheist'	  => 'Άθεος',
                            'agnostic'	  => 'Αγνωστικιστής',
                            'nothing'	  => 'Τίποτα'
                );
            }
            else {
                $religions = array( '-'	  => '-',
                            'christian'   => 'Χριστιανή',
                            'muslim' 	  => 'Ισλαμίστρια',
                            'atheist' 	  => 'Άθεη',
                            'agnostic'	  => 'Αγνωστικιστής',
                            'nothing' 	  => 'Τίποτα'
                );
            }
            echo htmlspecialchars( $religions[ $religion ] );
        }
    }
?>
