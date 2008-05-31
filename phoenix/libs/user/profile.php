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
            if ( $ageyear > 5 ) {
                return $ageyear;
            }
            return false;
        }
        protected function GetBirthDay() {
            return ( int )date( 'j', strtotime( $this->Dob ) );
        }
        protected function GetBirthMonth() {
            return ( int )date( 'n', strtotime( $this->Dob ) );
        }
        protected function GetBirthYear() {
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
            w_assert( is_int( $value ) );
            $this->Dob = $this->MakeBirthdate( $value, $this->BirthMonth, $this->BirthYear );
        }
        protected function SetBirthMonth( $value ) {
            w_assert( is_int( $value ) );
            $this->Dob = $this->MakeBirthdate( $this->BirthDay, $value, $this->BirthYear );
        }
        protected function SetBirthYear( $value ) {
            w_assert( is_int( $value ) );
            $this->Dob = $this->MakeBirthdate( $this->BirthDay, $this->BirthMonth, $value );
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
        }
        protected function AfterConstruct() {
            global $water;
            
            $water->Trace('UserProfile::AfterConstruct parameters:' . serialize(func_get_args()) . ', Userid = ' . $this->Userid);
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
			$this->Placeid = -1;
			$this->Height = -1;
			$this->Weight = -1;
		}
        protected function OnUpdate( $updatedAttributes ) {
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

            foreach ( $events as $attribute => $typeid ) {
                if ( isset( $updatedAttributes[ $attribute ] ) && $updatedAttributes[ $attribute ] ) {
                    $event = New Event();
                    $event->Typeid = $typeid;
                    $event->Itemid = $this->Userid;
                    $event->Userid = $this->Userid;
                    $event->Save();
                }
            }
        }
    }

?>
