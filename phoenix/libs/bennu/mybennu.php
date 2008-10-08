<?php
    class BennuRule {
        protected $mSigma;
        protected $mValue;
        protected $mCost;//low medium high 
        protected $mAttribute;
        
        protected function Get( $sample ) {
            $parts = explode( '->', $this->mAttribute );
            
            if ( count( $parts ) == 2 ) {
                return $sample->$parts[ 1 ];
            }
            else if ( count( $parts ) == 3 ) {
                return $sample->$parts[ 1 ]->$parts[ 2 ];
            }
        }
        
        public function SetRule( $attribute, $value, $priority, $sigma = 0 ) {
            $this->mSigma = $sigma;
            $this->mValue = $value;//the ideal value
            $this->mAttribute = $attribute;
            
            switch ( $priority ) {
                case 'low' : 
                    $this->mCost = 5;
                    break;
                case 'medium' : 
                    $this->mCost = 10;
                    break;
                case 'high' : 
                    $this->mCost = 15;
                    break;
            }      
        }
        
        public function Calculate( $sample ) {
            $value;
            
            if ( $this->mSigma == 0 ) {// for yes-on type of rules
                if ( $this->Get( $sample ) === $this->mValue ) {
                    return $this->mCost;
                }
                else {
                    return 0;
                }
            }            
            
            if ( $this->mSigma > 0 ) {//for int rules with sigma            
                $value = abs( ( $this->mCost * ( $this->Get( $sample )  - $this->mValue ) ) / $this->mSigma );
                if ( $value < $this->mCost ) {
                    return ( $this->mCost - $value );
                }
                else {
                    return 0;
                }
            }   
            
            return;
        }
    }

    class Bennu {
        protected $mInput;
        protected $mTarget;
        protected $mRules;

        public function SetData( $users, $target ) {
            $this->mInput = $users;
            $this->mTarget = $target;
            return;
        }
        
        public function AddRule( $attribute, $value, $priority = 'medium', $sigma = 0 ) {     
            $rule = new BennuRule();
            $rule->SetRule( $attribute, $value, $priority, $sigma );       
            $this->mRules[] = $rule;
            return;
        }
        
        public function GetResult() {
            $res = array();
            foreach ( $this->mInput as $sample ) {
                $res[ $sample->Name ] = $this->GetScore( $sample );
            }
            
            return $res;
        }
        
        protected function GetScore( $sample ) {        
            $total_score = 0;            
            foreach ( $this->mRules as $rule ) {
                $score += $rule->Calculate( $sample );
            }            
            return $score;      
        }
    }
?>
