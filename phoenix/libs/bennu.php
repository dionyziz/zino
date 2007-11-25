<?php


    class Bennu {
        private $mRules;
        private $mUsers;
        private $mUids;
        private $mScores;
        private $mExclude;
        private $mFields;

        private function GetAllUsers( $limit = false ) {
            global $db;
            global $users;

            $sql = "SELECT 
                        `user_id`, `user_name`, `user_rights`, `user_gender`, `user_dob`, `user_lastactive`
                    FROM 
                        `$users` 
                    WHERE
                        `user_locked` = 'no'
                    ";

            if ( $limit !== false ) {
                $sql .= "LIMIT $limit";
            }
            $sql .= ";";

            $ret = array();
            $res = $db->Query( $sql );
            while ( $row = $res->FetchArray() ) {
                $ret[ (int)$row[ "user_id" ] ] = $row;
            }

            return $ret;
        }
        public function AddRule( $rule ) {
            w_assert( $rule instanceof BennuRule );

            $this->mRules[] = $rule;
            // $this->mFields = array_merge( $this->mFields, $rule->Fields );    
        }
        public function Exclude( $user ) {
            w_assert( $user instanceof User );
            
            $this->mExclude[] = $user->Id();
        }
        public function Get( $limit = false ) {
            global $water;

            $this->mUsers = $this->GetAllUsers();
            $this->mScores = array();
            $this->mUids = array();

//            $water->Trace( "number of bennu rules: " . count( $this->mRules ) );

            foreach ( $this->mUsers as $user ) {
                if ( in_array( $user[ "user_id" ], $this->mExclude ) ) {
                    continue;
                }

                $score = 0;
                foreach ( $this->mRules as $rule ) {
                    $score += $rule->Get( $user );
                }

                $this->mUids[] = $user[ "user_id" ];
                $this->mScores[] = $score;
            }
            
            w_assert( count( $this->mUids ) == count( $this->mScores ) );
            array_multisort( $this->mScores, SORT_DESC, SORT_NUMERIC, $this->mUids, SORT_DESC, SORT_STRING );

            $water->Trace( "bennu uids", array_slice( $this->mUids, 0, 50 ) );
            $water->Trace( "bennu scores", array_slice( $this->mScores, 0, 50 ) );

            if ( $limit > count( $this->mUids ) || $limit === false ) {
               $limit = count( $this->mUids );
            }

            $ret = array();
            for ( $i = 0; $i < $limit; ++$i ) {
                $uid = $this->mUids[ $i ];
                $ret[] = new User( $this->mUsers[ $uid ] );
            }

            return $ret;
        }
        public function Bennu() {
            $this->mUids  = array();
            $this->mRules = array();
            $this->mScores = array();
            $this->mExclude = array();
        }
    }

    abstract class BennuRule {
        protected $mValue;
        protected $mSigma;
        protected $mScore;
        protected $mFields;

        public function __set( $name, $value ) {
//            global $water;

            // check if a custom setter is specified
            $methodname = 'Set' . $name;
            if ( method_exists( $this, $methodname ) ) {
                $success = $this->$methodname( $value ); // MAGIC!
                if ( $success !== false ) {
                    return;
                }
                /* else fallthru */
            }
           
 //           w_assert( $name == 'Value' || $name == 'Sigma' || $name == 'Score' || $name == 'Fields' );
            $varname = 'm' . $name;
            $this->$varname = $value; // MAGIC!
        }
        public function __get( $name ) {
            // check if a custom getter is specified
            $methodname = 'Get' . $name;
            if ( method_exists( $this, $methodname ) ) {
                return $this->$methodname(); // MAGIC!
            }
            
//            w_assert( $name == 'Value' || $name == 'Sigma' || $name == 'Score' || $name == 'Fields' );

            $varname = 'm' . $name;
            return $this->$varname; // MAGIC!
        }
        protected function IsEqual( $value ) {
            return ( $this->Value === $value ) ? $this->Score : 0;
        }
        protected function NormalDistribution( $value ) {
            global $water;

            w_assert( $this->Sigma != 0 );
            
            return $this->Score * pow( M_E, ( pow( -( $value - $this->Value ), 2 ) / ( 3 * $this->Sigma ) ) );
        }
        protected function Random() {
            return rand( $this->Value - $this->Sigma, $this->Value + $this->Sigma );
        }
        /*
        public function UserValue( $user ) {
            // override me!
        }
        */
        public function Calculate( $value ) {
            // default calculation
            return $this->NormalDistribution( $value );
        }
        public function Get( $user ) {
//            global $water;

            return $this->Calculate( $this->UserValue( $user ) );
        }
        public function BennuRule() {
            $this->mFields = array();
        }
    }

    /* default bennu rules */

    class BennuRuleSex extends BennuRule {
        public function UserValue( $user ) {
            return $user[ "user_gender" ];
        }
        public function Calculate( $value ) {
            return $this->IsEqual( $value );
        }
    }

    class BennuRuleAge extends BennuRule {
        public function UserValue( $user ) {
            $nowdate = getdate();
            $nowyear = (int)$nowdate[ "year" ];
            $dobyear = (int)substr( $user[ "user_dob" ], 0, 4 );

            if ( $dobyear == "0000" ) {
                return false;
            }

            return $nowyear - $dobyear;
        }
        public function Calculate( $value ) {
            if ( $value === false ) {
                return 0;
            }
            return $this->NormalDistribution( $value );
        }
    }

    class BennuRuleCreation extends BennuRule {
        public function UserValue( $user ) {
            return strtotime( $user->Created() );
        }
    }

    /*
    class BennuRulePhotos extends BennuRule {
        public function UserValue( $user ) {
            return $user->MyPhotosNum;
        }
    }
    */

    class BennuRuleLocation extends BennuRule {
        public function UserValue( $user ) {
            return $user->Location();
        }
        public function Calculate( $value ) {
            return IsEqual( $value );
        }
    }

    class BennuRuleFriends extends BennuRule {
        public function UserValue( $user ) {
            return $user->IsFriend( $this->Value );
        }
        public function Calculate( $value ) {
            return $this->IsEqual( $value );
        }
    }

    class BennuRuleLastActive extends BennuRule {
    }

    class BennuRuleRandom extends BennuRule {
        public function UserValue( $user ) {
            return $this->Random();
        }
    }

?>
