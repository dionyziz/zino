<?php
    global $libs;
    
    $libs->Load( 'place' );
    $libs->Load( 'university' );
    
    class UserProfile extends Satori {
        protected $mDbTableAlias = 'userprofiles';
        
        protected function GetAge() {
            $validdob = false;
    		if ( $this->Dob != "0000-00-00" ) {
                $validdob = true;
                $nowdate = getdate();
                $nowyear = $nowdate[ "year" ];
                $dobyear = ( int )date( 'Y', $this->Dob );
                $dobmonth = ( int )date( 'n', $this->Dob );
                $dobday = ( int )date( 'j', $this->Dob );
                $ageyear = $nowyear - $dobyear;
                $nowmonth = $nowdate[ "mon" ];
                $nowday = $nowdate[ "mday" ];
                $hasbirthday = false;
                if ( $nowmonth < $dobmonth ) {
                    --$ageyear;
                }
                else {
                    if ( $nowmonth == $dobmonth ) {
                        if ( $nowday < $dobday ) {
                            --$ageyear;
                        }
                        else {
                            if ( $nowday == $dobday ) {
                                $hasbirthday = true;
                            }
                        }
                    }
                }
    		}
            if ( $ageyear > 5 ) {
                return $ageyear;
            }
            return false;
        }
        protected function GetHasBirthday() {
    		if ( $this->Dob != "0000-00-00" ) {
                $nowdate = getdate();
                $dobmonth = ( int )date( 'n', $this->Dob );
                $dobday = ( int )date( 'j', $this->Dob );
                $ageyear = $nowyear - $dobyear;
                $nowmonth = $nowdate[ "mon" ];
                $nowday = $nowdate[ "mday" ];
                if ( $nowmonth == $dobmonth && $nowday == $dobday ) {
                    return true;
                }
    		}
            return false;
        }
        public function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
            $this->Location = $this->HasOne( 'Place', 'Placeid' );
            $this->University = $this->HasOne( 'Uni', 'Uniid' );
        }
        public function Delete() {
            throw New UserException( 'User profiles cannot be deleted' );
        }
		protected function LoadDefaults() {
			$this->Education = '-';
			$this->Sexualorientation = '-';
			$this->Religion = '-';
			$this->Politics = '-';	
			$this->Eyecolor = '-';
			$this->Haircolor = '-';
			$this->Smoker = '-';
			$this->Drinker = '-';
			$this->Placeid = 0;
			$this->Height = 0;
			$this->Weight = 0;
		}
    }

?>
