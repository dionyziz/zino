<?php
    class ElementUserTrivialRelationship extends Element {
        protected $mPersistent = array( 'status', 'gender' );

        public function Render( $status , $gender ) {
            if ( $gender == 'm' || $gender == '-' ) {
                $statuses = array( 
                    '-' => '-',
                    'single' => 'Ελεύθερος',
                    'relationship' => 'Σε σχέση',
                    'casual' => 'Ελεύθερη σχέση',
                    'engaged' => 'Δεσμευμένος',
                    'married' => 'Παντρεμένος'
                );
            }
            else {
                $statuses = array( 
                    '-' => '-',
                    'single' => 'Ελεύθερη',
                    'relationship' => 'Σε σχέση',
                    'casual' => 'Ελεύθερη σχέση',
                    'engaged' => 'Δεσμευμένη',
                    'married' => 'Παντρεμένη'
                );
            }
            echo htmlspecialchars( $statuses[ $status ] );
        }
    }
?>
