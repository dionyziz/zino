<?php
    /*
        Developer:Pagio
    */
    
    
    
    class ReportUser extends Satori {
        protected $mDbTableAlias = 'report'; 
        
        public function __get( $key ) {
            switch ( $key ) {
                case 'Reason':
                    switch( $this->Cause ) {
                        case 1:
                            return "Ανάρτηση πορνογραφικού υλικού";
                        case 2:
                            return "Ρατσιστική επίθεση σε μεμονωμένα άτομα ή ομάδες ατόμων";
                        case 3:
                            return "Προώθηση χρήσης απαγορευμένων ουσιών";
                        case 4:
                            return "Προώθηση βίας";
                        case 5:
                            return "Ανάρτηση διαφημιστικού ή επαναλαμβανόμενου περιεχομένου (spam)";
                        case 6:
                            return "Λογαριασμός που δεν αντιστοιχεί σε πραγματικό πρόσωπο (fake)";
                    }               
            }            
            return parent::__get( $key );
        }  
        
        protected function LoadDefaults() {
            global $user;
                        
            $this->AuthorId = $user->Id;
            $this->Date = NowDate();
        }  
    }
?>
