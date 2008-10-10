<?php
    /*
        Developer:Pagio
    */

    class BennuRule {
        protected $mSigma;
        protected $mValue; // best value
        protected $mCost; // cost defined by the priority of the rule
        protected $mAttribute; // attributes name, ex User->Profile->Age
        protected $mParts;//attribute's parts array
        protected $mPartsN;
        protected $mType; // { 'INT', 'DATE' }
        protected $mPlace; // { 'IN', 'OUT' }
        protected $mRuleType; // { 'Boolean' , 'NormalDist' , 'InArray' }
        
        protected function Get( $sample ) { 
            $part1;
            $part2;
                
            if ( $this->mPartsN == 2 ) {
                $part1 = $this->mParts[ 1 ];
                return $sample->$part1;
            }
            else if ( $this->mPartsN == 3 ) {
                $part1 = $this->mParts[ 1 ];
                $part2 = $this->mParts[ 2 ];
                return $sample->$part1->$part2;
            }
        }
        
        protected function SetParts() {
            $parts = array();
            $parts = explode( '->', $this->mAttribute );
            $this->mParts = $parts;
            $this->mPartsN = count( $parts );
            return;
        }
        
        public function SetRuleBoolean( $attribute, $value, $cost ) {
            $this->mValue = $value;
            $this->mAttribute = $attribute;
            $this->mRuleType = 'Boolean';
            $this->mCost = $cost;
            $this->SetParts();
            return;  
        }
                
        public function SetRuleNormalDist( $attribute, $value, $sigma, $type, $cost ) {
            $this->mValue = $value;
            $this->mSigma = $sigma;            
            $this->mAttribute = $attribute;
            $this->mType = $type;
            $this->mRuleType = 'NormalDist';            
            $this->mCost = $cost;
            $this->SetParts();
            return;
        }
        
        public function SetRuleInArray( $attribute, $values, $place, $cost ) {
            
            /*foreach ( $value as $values ) {
            TODO
            }*/
            $this->mValue = $values;
            $this->mAttribute = $attribute;
            $this->mPlace = $place;
            $this->mCost = $cost;
            $this->mRuleType = 'InArray';
            $this->SetParts();
            return;
        } 
        
        public function Calculate( $sample ) {
            switch ( $this->mRuleType ) {
                case 'Boolean' :
                    return $this->CalculateBoolean( $sample );
                case 'NormalDist' :
                    return $this->CalculateNormalDist( $sample );
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
        
        protected function CalculateNormalDist( $sample ) {   
            $sampe_value;
            $ideal_value;
            $value;
            if ( $this->mType == 'INT' ) {
                $sample_value = $this->Get( $sample );
                $ideal_value = $this->mValue;
            }
            else if ( $this->mType == 'DATE' ) {
                $sample_value = strtotime( $this->Get( $sample ) ); 
                $ideal_value = strtotime( $this->mValue );
            }            
            //value for normal distrinution graph with sigma = mSigma,x=sample_value,and m = ideal_value 
            $value = ( exp( -0.5*pow( ( ( $sample_value - $ideal_value ) / $this->mSigma ) , 2 ) ) / ( $this->mSigma * 2.5 ) );    
            
            return $value;
        }
        
        protected function CalculateInArray( $sample ) {
            $in = false;
            $val = $this->Get( $sample );
            foreach ( $this->mValue as $part ) {
                if ( $val === $part ) {
                    $in = true;
                    break;
                }
            }
            
            if ( ( $in == true && $this->mPlace == 'IN' )
                || ( $in == false && $this->mPlace == 'OUT' ) ) {
                return $this->mCost;
            }
            else {
            return 0;
            }
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
        
        public function AddRuleBoolean( $attribute, $value, $cost = 10 ) {     
            $rule = new BennuRule();
            $rule->SetRuleBoolean( $attribute, $value, $cost );       
            $this->mRules[] = $rule;
            return;
        }
        
        public function AddRuleNormalDist( $attribute, $value, $sigma, $type, $cost = 10 ) {     
            $rule = new BennuRule();
            $rule->SetRuleNormalDist( $attribute, $value, $sigma, $type, $cost );       
            $this->mRules[] = $rule;
            return;
        }
        
        public function AddRuleInArray( $attribute, $values, $place = 'IN' , $cost = 10 ) {     
            $rule = new BennuRule();
            $rule->SetRuleInArray( $attribute, $values, $place, $cost );       
            $this->mRules[] = $rule;
            return;
        }
        
        public function GetResult() {
            $res = array();
            $users = array();           
            $last = array();
                        
            foreach ( $this->mInput as $sample ) {
                $res[ $sample->Id ] = $this->GetScore( $sample );
                $users[ $sample->Id ] = $sample;
            }           
            arsort( $res );            
            $i = 0;
            foreach ( $res as $key=>$val ) {
                $last[ $i ] = $users[ $key ];
                $i++;
            }            
            return $last;
        }
        
        protected function GetScore( $sample ) {        
            $total_score = 0;                        
            foreach ( $this->mRules as $rule ) {
                $total_score += $rule->Calculate( $sample );
            }            
            return $total_score;      
        }
    }
    
    function Bennu_OnlineNow( $target, $input ) {
        global $db;
        global $libs;
        
        $libs->Load( 'user/profile' );
        $libs->Load( 'user/user' );
        
        //check data 
        if ( count ( $input ) < 2 ) {
            return $input;
        }
        //
        
        
        //add Profile values from database to speed things up
        $ids = array();
        foreach ( $input as $sample ) {
            $ids[] = $sample->Id;
        }
        
        $sql = $db->Prepare(
            'SELECT * FROM :userprofiles
             WHERE `profile_userid` IN :ids
             ;'
        );
        $sql->BindTable( 'userprofiles' );
        $sql->Bind( 'ids', $ids );
        $res = $sql->Execute();
        
        $profiles = array();
        while ( $row = $res->FetchArray() ) {
            $profiles[ $row[ 'profile_userid' ] ] = new UserProfile( $row );
        }
        
        foreach ( $input as $sample ) {
            $sample->CopyProfileFrom( $profiles[ $sample->Id ] );
        }
        //
        
        $bennu = new Bennu();
        $bennu->SetData( $input, $target );	
        
        switch ( $target->Profile->Sexualorientation ) {
            case 'straight' :
                if ( $target->Gender == 'm' ) {
                    $bennu->AddRuleBoolean( 'User->Gender', 'f', 10 );
                }
                else if ( $target->Gender == 'f' ) {
                    $bennu->AddRuleBoolean( 'User->Gender', 'm', 10 );
                }
                break;
            case 'gay' :
                $bennu->AddRuleBoolean( 'User->Gender', $target->Gender, 10 );
                break;
        }
          
        $bennu->AddRuleNormalDist( 'User->Profile->Age', $target->Profile->Age, 2, 'INT', 10 ); 
        $bennu->AddRuleNormalDist( 'User->Created' , NowDate(), 7*24*60*60, 'DATE', 10 );
        $bennu->AddRuleBoolean( 'User->Profile->Location' , $target->Profile->Location, 10 );

        $res = $bennu->GetResult();
        return $res;
    } 
?>
