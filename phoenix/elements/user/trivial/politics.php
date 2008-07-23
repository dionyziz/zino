<?php
    
    class ElementUserTrivialPolitics extends Element {
        public function Render( $politic , $gender ) {
            if ( $gender == 'm' || $gender == '-' ) {                
                $politics = array( 
                        '-' => '-',
                        'right' => 'Δεξιός',
                        'left' => 'Αριστερός',
                        'center' => 'Κεντρώος',
                        'radical left' => 'Ακροαριστερός',
                        'radical right' => 'Ακροδεξιός',
                        'center left' => 'Κεντροαριστερός',
                        'center right' => 'Κεντροδεξιός',
                        'nothing' => 'Τίποτα'
                );
            }
            else {
                $politics = array( 
                        '-' => '-',
                        'right' => 'Δεξιά',
                        'left' => 'Αριστερή',
                        'center' => 'Κεντρώα',
                        'radical left' => 'Ακροαριστερή',
                        'radical right' => 'Ακροδεξιά',
                        'center left' => 'Κεντροαριστερή',
                        'center right' => 'Κεντροδεξιά',
                        'nothing' => 'Τίποτα'
                );
            }
            echo htmlspecialchars( $politics[ $politic ] );
        }
    }
?>
