<?php

    class Bennu {
        private $mRules;
        private $mUsers;
        private $mScores;

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
                $ret[] = new User( $row );
            }

            return $ret;
        }
        public function AddRule( $rule ) {
            w_assert( $rule instanceof BennuRule );

            $this->mRules[] = $rule;
        }
        public function Exclude( $user ) {
            w_assert( $user instanceof User );
            
            $pos = array_search( $user, $this->mUsers );
            array_merge( array_slice( $this->mUsers, 0, $pos - 1 ), array_slice( $this->mUsers, $pos + 1, count( $this->mUsers ) ) );
        }
        public function Get( $limit = false ) {
            foreach ( $this->mUsers as $user ) {
                $score = 0;
                foreach ( $this->mRules as $rule ) {
                    $score += $rule->Get( $user );
                }
                $this->mScores[] = $score;
            }

            array_multisort( $this->mUsers, $this->mScores );

            return array_slice( $this->mUsers, 0, $limit );
        }
        public function Bennu() {
            $this->mUsers = $this->GetAllUsers();
            $this->mRules = array();
            $this->mScores = array();
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
    }

    class BennuRuleAge extends BennuRule {
    }

    class BennuRuleCreation extends BennuRule {
    }

    class BennuRulePhotos extends BennuRule {
    }

    class BennuRuleLocation extends BennuRule {
    }

    class BennuRuleFriends extends BennuRule {
    }

    class BennuRuleLastActive extends BennuRule {
    }

    class BennuRuleRandom extends BennuRule {
    }

?>
