<?php
    class Rule {
        protected $mAttribute;
        protected $mSigma;
        protected $mValue;
        protected $mCost;//low medium high 
        
        public function SetRule( $attribute, $value, $priority, $sigma ) {
            $this->mAttribute = $attribute;
            $this->mSigma = $sigma;
            $this->mValue = $value;
            
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
            if ( $this->mSigma == 0 ) {
                if ( $this->Value( $sample ) === $this->mValue ) {
                    $value = 1;
                }
                else {
                    $value = 0;
                }
                
                return $this->mCost*$value;
            }
            return;
        }
        
        protected function Value( $sample ) {
        
            $attributes = explode( '->', $this->mAttribute );
            
            if ( count( $attributes ) == 2 ) {
                return $sample->$attributes[ 1 ];
            }
            else if ( count( $attributes ) == 3 ) {
                return $sample->$attributes[ 1 ]->$attributes[ 2 ];
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
            $rule = new Rule;         
            $rule->SetRule( $attribute, $value, $priority, $sigma );
            
            $this->mRules[] = $rule;
            
            $score = $rule->Calculate( $this->mTarget );
            
            return $score;
        }
        
        public function GetResult() {
            $res = array();
            foreach ( $this->mInput as $sample ) {
                
                if ( $sampe->Id == $this->mTarget->Id ) {//exclude the target from  evaluating
                    continue;
                }
            
                $res[ $sample->Name ] = $this->Calculate( $sample );
            }
            
            w_assert( !empty( $res ), "No results" );
            
            return $res;
        }
        
        protected function Calculate( $sample ) {
            $total_score = 0;
            $score;
            $value = 10;
            
            //date
            
            
            //age sigma = 2
            $score = abs( ( 2 * ( $sample->Profile->Age - $this->mTarget->Profile->Age ) ) / $value );
            if ( $score < $value ) {
                $total_score += $value - $score;
            }
            
            //location
            if ( $sample->Profile->Placeid === $this->mTarget->Profile->Placeid ) {
                $total_score += $value;
            }
            
            //friends
            
            //sex
            if ( $sample->Gender === $this->mTarget->Gender ) {
                $total_score += $value;
            }
            
            //activity sigma = 7*24*60*60
            $sigma = 7*24*60*60;
            $score = abs( ( $sigma * ( strtotime( $sample->Lastlogin ) - strtotime( $this->mTarget->Lastlogin ) ) ) / $value );
            if ( $score < $value ) {
                $total_score += $value - $score;
            }
            
            return $total_score;
        }
    }
?>
