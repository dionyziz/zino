<?php
    global $libs;
    
    $libs->Load( 'place' );
    $libs->Load( 'university' );
    $libs->Load( 'user/oldprofile' );
	
    class UserProfile extends Satori {
        protected $mDbTableAlias = 'userprofiles';
        
        public function CopyLocationFrom( $value ) {
            $this->mRelations[ 'Location' ]->CopyFrom( $value );
        }
        public function CopyUniversityFrom( $value ) {
            $this->mRelations[ 'University' ]->CopyFrom( $value );
        }
        public function CopyMoodFrom( $value ) {
            $this->mRelations[ 'Mood' ]->CopyFrom( $value );
        }
        protected function GetAge() {
            $validdob = false;
    		if ( $this->Dob != "0000-00-00" ) {
                $validdob = true;
                $nowdate = getdate();
                $nowyear = $nowdate[ "year" ];
                $ageyear = $nowyear - $this->BirthYear;
                $nowmonth = $nowdate[ "mon" ];
                $nowday = $nowdate[ "mday" ];
                $hasbirthday = false;
                if ( $nowmonth < $this->BirthMonth ) {
                    --$ageyear;
                }
                else {
                    if ( $nowmonth == $this->BirthMonth ) {
                        if ( $nowday < $this->BirthDay ) {
                            --$ageyear;
                        }
                        else {
                            if ( $nowday == $this->BirthDay ) {
                                $hasbirthday = true;
                            }
                        }
                    }
                }
    		}
            if ( isset( $ageyear ) && $ageyear > 5 ) {
                return $ageyear;
            }
            return false;
        }
        protected function GetBirthDay() {
            if ( $this->Dob == '0000-00-00' ) {
                return 0;
            }
            return ( int )date( 'j', strtotime( $this->Dob ) );
        }
        protected function GetBirthMonth() {
            if ( $this->Dob == '0000-00-00' ) {
                return 0;
            }
            return ( int )date( 'n', strtotime( $this->Dob ) );
        }
        protected function GetBirthYear() {
            if ( $this->Dob == '0000-00-00' ) {
                return 0;
            }
            return ( int )date( 'Y', strtotime( $this->Dob ) );
        }
        protected function MakeBirthdate( $day, $month, $year ) {
            w_assert( is_int( $day ) );
            w_assert( is_int( $month ) );
            w_assert( is_int( $year ) );
            if ( $month < 10 ) {
                $month = '0' . ( string )$month;
            }
            else {
                $month = ( string )$month;
            }
            if ( $day < 10 ) {
                $day = '0' . ( string )$day;
            }
            else {
                $day = ( string )$day;
            }
            return "$year-$month-$day";
        }
        protected function SetBirthDay( $value ) {
            global $water;
            
            w_assert( is_int( $value ) );
            $this->Dob = $this->MakeBirthdate( $value, $this->BirthMonth, $this->BirthYear );
            $water->Trace( 'Updated DOB to ' . $this->Dob );
        }
        protected function SetBirthMonth( $value ) {
            global $water;

            w_assert( is_int( $value ) );
            $this->Dob = $this->MakeBirthdate( $this->BirthDay, $value, $this->BirthYear );
            $water->Trace( 'Updated DOB to ' . $this->Dob );
        }
        protected function SetBirthYear( $value ) {
            global $water;

            w_assert( is_int( $value ) );
            $this->Dob = $this->MakeBirthdate( $this->BirthDay, $this->BirthMonth, $value );
            $water->Trace( 'Updated DOB to ' . $this->Dob );
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
        protected function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
            $this->Location = $this->HasOne( 'Place', 'Placeid' );
            $this->Uni = $this->HasOne( 'Uni', 'Uniid' );
            $this->Mood = $this->HasOne( 'Mood', 'Moodid' );
            $this->OldProfile = $this->HasOne( 'OldUserProfile', 'Userid' );
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
			$this->Height = -1;
			$this->Weight = -1;
		}
        protected function OnUpdate( $updatedAttributes, $previousValues ) {
            global $libs;
            $libs->Load( 'event' );

            $events = array(
                'Moodid' => EVENT_USERPROFILE_MOOD_UPDATED,
                'Education' => EVENT_USERPROFILE_EDUCATION_UPDATED,
                'Sexualorientation' => EVENT_USERPROFILE_SEXUALORIENTATION_UPDATED,
                'Religion' => EVENT_USERPROFILE_RELIGION_UPDATED,
                'Politics' => EVENT_USERPROFILE_POLITICS_UPDATED,
                'Eyecolor' => EVENT_USERPROFILE_EYECOLOR_UPDATED,
                'Haircolor' => EVENT_USERPROFILE_HAIRCOLOR_UPDATED,
                'Smoker' => EVENT_USERPROFILE_SMOKER_UPDATED,
                'Drinker' => EVENT_USERPROFILE_DRINKER_UPDATED,
                'Placeid' => EVENT_USERPROFILE_LOCATION_UPDATED,
                'Height' => EVENT_USERPROFILE_HEIGHT_UPDATED,
                'Weight' => EVENT_USERPROFILE_WEIGHT_UPDATED,
                'Aboutme' => EVENT_USERPROFILE_ABOUTME_UPDATED
            );

            $finder = New EventFinder();
            foreach ( $events as $attribute => $typeid ) {
                if ( isset( $updatedAttributes[ $attribute ] ) && $updatedAttributes[ $attribute ] && !empty( $this->$attribute ) && $this->$attribute != '-' ) {
                    $event = New Event();
                    $event->Typeid = $typeid;
                    $event->Itemid = $this->Userid;
                    $event->Userid = $this->Userid;
                    $event->Save();
                }
                else if ( isset( $updatedAttributes[ $attribute ] ) ) {
                    $finder->DeleteByUserAndType( $this->User, $typeid );
                }
            }

            foreach ( $previousValues as $attribute => $value ) {
                $this->OldProfile->$attribute = $value;
            }

            $this->OldProfile->Save();
        }
        public function OnCommentCreate() {
            ++$this->Numcomments;
            $this->Save();
        }
        public function OnCommentDelete() {
            --$this->Numcomments;
            $this->Save();
        }
    }

?>
