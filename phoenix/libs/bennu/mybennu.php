<?php
    /*
        Developer:Pagio
    */

    class BennuRule {
        protected $mSigma;
        protected $mValue; // best value
        protected $mCost; // cost defined by the priority of the rule
        protected $mAttribute; // attributes name, ex User->Profile->Age
        protected $mType; // { 'INT', 'DATE' }
        protected $mPlace; // { 'IN', 'OUT' }
        protected $mRuleType; // { 'Boolean' , 'NormalDist' , 'InArray' }
        
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
                case 'LOW' : 
                    $this->mCost = 10;
                    return;
                case 'MEDIUM' : 
                    $this->mCost = 20;
                    return;
                case 'HIGH' : 
                    $this->mCost = 40;
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
                
        public function SetRuleNormalDist( $attribute, $value, $sigma, $type, $priority ) {
            $this->mValue = $value;
            $this->mSigma = $sigma;            
            $this->mAttribute = $attribute;
            $this->mType = $type;
            $this->mRuleType = 'NormalDist';            
            $this->SetCost( $priority );
            return;
        }
        
        public function SetRuleInArray( $attribute, $values, $place, $priority ) {
            
            /*foreach ( $value as $values ) {
            
            }*/
            $this->mValue = $values;
            $this->mAttribute = $attribute;
            $this->mPlace = $place;
            $this->SetCost( $priority );
            $this->mRuleType = 'InArray';
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
            if ( $this->mType == 'INT' ) {
                $sample_value = $this->Get( $sample );
                $ideal_value = $this->mValue;
            }
            else if ( $this->mType == 'DATE' ) {
                $sample_value = strtotime( $this->Get( $sample ) ); 
                $ideal_value = strtotime( $this->mValue );
            }            
            //value for normal distrinution graph with sigma = mSigma,x=sample_value,and m = ideal_value 
            $value = ( exp( -1*2*pow( ( ( $sample_value - $ideal_value ) / $this->mSigma ) , 2 ) ) / ( $this->mSigma * sqrt( 2 * pi() ) ) );    
            
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
        
        public function AddRuleBoolean( $attribute, $value, $priority = 'MEDIUM' ) {     
            $rule = new BennuRule();
            $rule->SetRuleBoolean( $attribute, $value, $priority );       
            $this->mRules[] = $rule;
            return;
        }
        
        public function AddRuleNormalDist( $attribute, $value, $sigma, $type, $priority = 'MEDIUM' ) {     
            $rule = new BennuRule();
            $rule->SetRuleNormalDist( $attribute, $value, $sigma, $type, $priority );       
            $this->mRules[] = $rule;
            return;
        }
        
        public function AddRuleInArray( $attribute, $values, $place = 'IN', $priority = 'MEDIUM' ) {     
            $rule = new BennuRule();
            $rule->SetRuleInArray( $attribute, $values, $place, $priority );       
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
                $score += $rule->Calculate( $sample );
            }            
            return $score;      
        }
    }
    
    function Bennu_OnlineNow( $target, $input ) {
        global $db;
        global $libs;
        
        $libs->Load( 'user/profile' );
        $libs->Load( 'user/user' );
        
        //add Profile values from database to speed things up
        /*$ids = array();
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
        }*/
        //
        
        $bennu = new Bennu();
        $bennu->SetData( $input, $target );	
                
        if ( $target->Gender == 'm' ) {
            $bennu->AddRuleBoolean( 'User->Gender', 'f' );
        }
        else if ( $target->Gender == 'f' ) {
            $bennu->AddRuleBoolean( 'User->Gender', 'm' );
        }  
        /*
        $sql = $db->Prepare( 
            'SELECT `relation_friendid`
             FROM :relations
             WHERE `relation_userid` = :targetid
             ;'
        );
        $sql->BindTable( 'relations' );
        $sql->Bind( 'targetid', $target->Id );
        $list = $sql->Execute();
        $friends = array();
        while ( $row = $list->FetchArray() ) {
            $friends[] = $row[ 'relation_friendid' ];
        }*/
        
        //$bennu->AddRuleInArray( 'User->Id', $friends, 'OUT' );        
        //$bennu->AddRuleNormalDist( 'User->Profile->Age', $target->Profile->Age, 2, 'INT' ); 
        //$bennu->AddRuleNormalDist( 'User->Created' , NowDate(), 7*24*60*60, 'DATE' );
        //$bennu->AddRuleBoolean( 'User->Profile->Location' , $target->Profile->Location, 'HIGH' );

        $res = $bennu->GetResult();
        return $res;
    } 
?>
