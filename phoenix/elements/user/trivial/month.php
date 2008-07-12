<?php
	
	class ElementUserTrivialMonth extends Element {
        public function Render( $month ) {
            $months = array( '-' => '-',
                             '01' => 'Ιανουαρίου',
                             '02' => 'Φεβρουαρίου',
                             '03' => 'Μαρτίου',
                             '04' => 'Απριλίου',
                             '05' => 'Μαίου',
                             '06' => 'Ιουνίου',
                             '07' => 'Ιουλίου',
                             '08' => 'Αυγούστου',
                             '09' => 'Σεπτεμβρίου',
                             '10' => 'Οκτωβρίου',
                             '11' => 'Νοεμβρίου',
                             '12' => 'Δεκεμβρίου'
            );
            echo htmlspecialchars( $months[ $month ] );
        }
    }
?>
