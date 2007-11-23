<?php

    class Bennu {
        private $mRules;
        private $mUsers;
        private $mUids;
        private $mScores;
        private $mExclude;

        private function GetAllUsers( $limit = false ) {
            global $db;
            global $users;

            $sql = "SELECT 
                        * 
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
                $user = new User( $row );
                $ret[ $user->Id() ] = $user;
            }

            return $ret;
        }
        public function AddRule( $rule ) {
            w_assert( $rule instanceof BennuRule );

            $this->mRules[] = $rule;
        }
        public function Exclude( $user ) {
            w_assert( $user instanceof User );
            
            $this->mExclude[] = $user->Id();
        }
        public function Get( $limit = false ) {
            $this->mScores = array();
            $this->mUids = array();

            foreach ( $this->mUsers as $user ) {
                if ( in_array( $user->Id(), $this->mExclude ) ) {
                    continue;
                }

                $score = 0;
                foreach ( $this->mRules as $rule ) {
                    $score += $rule->Get( $user );
                }

                $this->mUids[] = $user->Id();
                $this->mScores[] = $score;
            }
            
            w_assert( count( $this->mUids ) == count( $this->mScores ) );
            array_multisort( $this->mUids, $this->mScores );

            if ( $limit > count( $this->mUids ) || $limit === false ) {
               $limit = count( $this->mUids );
            }

            $ret = array();
            for ( $i = 0; $i < $limit; ++$i ) {
                $uid = $this->mUids[ $i ];
                $ret[] = $this->mUsers[ $uid ];
            }

            return $ret;
        }
        public function Bennu() {
            $this->mUsers = $this->GetAllUsers();

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

        public function __set( $name, $value ) {
            // check if a custom setter is specified
            $methodname = 'Set' . $name;
            if ( method_exists( $this, $methodname ) ) {
                $success = $this->$methodname( $value ); // MAGIC!
                if ( $success !== false ) {
                    return;
                }
                /* else fallthru */
            }
           
            w_assert( $name == 'Value' || $name == 'Sigma' || $name == 'Score' );
            $varname = 'm' . $name;
            $this->$varname = $value; // MAGIC!
        }
        public function __get( $name ) {
            // check if a custom getter is specified
            $methodname = 'Get' . $name;
            if ( method_exists( $this, $methodname ) ) {
                return $this->$methodname(); // MAGIC!
            }
            
            w_assert( $name == 'Value' || $name == 'Sigma' || $name == 'Score' );

            $varname = 'm' . $name;
            return $this->$varname; // MAGIC!
        }
        protected function IsEqual( $value ) {
            return ( $this->Value === $value ) ? $this->Score : 0;
        }
        protected function NormalDistribution( $value ) {
        }
        protected function Random() {
            return rand( $this->Value - $this->Sigma, $this->Value + $this->Sigma );
        }
        protected function UserValue( $user ) {
            // override me!
        }
        protected function Calculate( $value ) {
            // default calculation
            $this->NormalDistribution( $value );
        }
        public function Get( $user ) {
            return $this->Calculate( $this->UserValue( $user ) );
        }
        public function BennuRule() {
        }
    }

    /* default bennu rules */

    class BennuRuleSex extends BennuRule {
        public function UserValue( $user ) {
            return $user->Gender();
        }
        public function Calculate( $value ) {
            return $this->IsEqual( $value );
        }
    }

    class BennuRuleAge extends BennuRule {
        public function UserValue( $user ) {
            return $user->Age();
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
