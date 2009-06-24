<?php
    
    class ElementUserTrivialReligion extends Element {
        protected $mPersistent = array( 'religion', 'gender' );

        public function Render( $religion , $gender ) {
            if ( $gender == 'm' || $gender == '-' ) {
                $religions = array( '-'   => '-',
                            'christian'   => 'Χριστιανός',
                            'muslim'      => 'Ισλαμιστής',
                            'atheist'      => 'Άθεος',
                            'agnostic'      => 'Αγνωστικιστής',
                            'nothing'      => 'Άθρησκος',
                            'pastafarian'  => 'Πασταφαριανός',
                            'pagan'        => 'Παγανιστής',
                            'budhist'       => 'Βουδιστής',
                            'greekpolytheism' => 'Δωδεκαθεϊστής',
                            'hindu'         => 'Ινδουιστής'
                            
                );
            }
            else {
                $religions = array( '-'      => '-',
                            'christian'   => 'Χριστιανή',
                            'muslim'       => 'Ισλαμίστρια',
                            'atheist'       => 'Άθεη',
                            'agnostic'      => 'Αγνωστικίστρια',
                            'nothing'       => 'Άθρησκη',
                            'pastafarian'   => 'Πασταφαριανή',
                            'pagan'         => 'Παγανίστρια',
                            'budhist'       => 'Βουδίστρια',
                            'greekpolytheism' => 'Δωδεκαθεΐστρια',
                            'hindu'         => 'Ινδουίστρια'
                );
            }
            echo htmlspecialchars( $religions[ $religion ] );
        }
    }
?>
