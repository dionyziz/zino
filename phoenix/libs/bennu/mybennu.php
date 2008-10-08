<?php
    class BennuRule {
        protected $mSigma;
        protected $mValue; // best value
        protected $mCost; // cost defined by the priority of the rule
        protected $mAttribute; // attributes name, ex User->Profile->Age
        protected $mType; // { 'INT', 'DATE' }
        protected $mRuleType; // Boolean, Sigma, InArray
        
        protected function Get( $sample ) {
            $parts = explode( '->', $this->mAttribute );
            
            if ( count( $parts ) == 2 ) {
                return $sample->$parts[ 1 ];
            }
            else if ( count( $parts ) == 3 ) {
                return $sample->$parts[ 1 ]->$parts[ 2 ];
            }
        }
                
        protected function SetCost( $priority ) {
            switch ( $priority ) {
                case 'low' : 
                    $this->mCost = 5;
                    return;
                case 'medium' : 
                    $this->mCost = 10;
                    return;
                case 'high' : 
                    $this->mCost = 15;
                    return;
            }
        }
        
        public function SetRuleBoolean( $attribute, $value, $priority ) {
            $this->mValue = $value;
            $this->mAttribute = $attribute;
            $this->mRuleType = 'Boolean';
            $this->SetCost( $priority );
            return;  
        }
                
        public function SetRuleSigma( $attribute, $value, $sigma, $type, $priority ) {
            $this->mValue = $value;
            $this->mSigma = $sigma;            
            $this->mAttribute = $attribute;
            $this->mType = $type;
            $this->mRuleType = 'Sigma';            
            $this->SetCost( $priority );
            return;
        }
        
        public function SetRuleInArray( $attribute, $value, $priority, $sigma ) {
            //TODO
        } 
        
        public function Calculate( $sample ) {
            switch ( $this->mRuleType ) {
                case 'Boolean' :
                    return $this->CalculateBoolean( $sample );
                case 'Sigma' :
                    return $this->CalculateSigma( $sample );
                case 'InArray' :
                    return $this->CalculateInArray( $sample );
            }
        }
        
        protected function CalculateBoolean( $sample ) {
            if ( $this->Get( $sample ) === $this->mValue ) {
                return $this->mCost;
            }
            else {
                return 0;
            }
        }
        
        protected function CalculateSigma( $sample ) {   
            $sampe_value;
            $ideal_value;
            if ( $this->mType == 'INT' ) {
                $sample_value = $this->Get( $sample );
                $ideal_value = $this->mValue;
            }
            else if ( $this->mType == 'DATE' ) {
                $sample_value = strtotime( $this->Get( $sample ) ); 
                $ideal_value = strtotime( $this->mValue );
            }            
                 
            $value = abs( ( $this->mCost * ( $sample_value  - $ideal_value ) ) / $this->mSigma );
            if ( $value < $this->mCost ) {
                return ( $this->mCost - $value );
            }
            else {
                return 0;
            }
        }
        
        protected function CalculateInArray( $sample ) {
            //TODO
            return 0;        
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
        
        public function AddRuleBoolean( $attribute, $value, $priority = 'medium' ) {     
            $rule = new BennuRule();
            $rule->SetRuleBoolean( $attribute, $value, $priority );       
            $this->mRules[] = $rule;
            return;
        }
        
        public function AddRuleSigma( $attribute, $value, $sigma, $type, $priority = 'medium' ) {     
            $rule = new BennuRule();
            $rule->SetRuleSigma( $attribute, $value, $sigma, $type, $priority = 'medium' );       
            $this->mRules[] = $rule;
            return;
        }
        
        public function AddRuleInArray( $attribute, $value, $priority = 'medium', $sigma = 0 ) {     
            $rule = new BennuRule();//TODO
            $rule->SetRuleInArray( $attribute, $value, $priority, $sigma );       
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
