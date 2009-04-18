<?php
    global $libs;
    
    $libs->Load( 'place' );
    $libs->Load( 'school/school' );
    $libs->Load( 'user/oldprofile' );
    
    function ValidateEmail( $id, $hash ) {
        global $libs;
        
        $libs->Load( 'user/user');
    
        $_user = new User( $id );
        if( $_user->Exists() 
            && ( $_user->Profile->emailvalidationhash == $hash || $_user->Profile->emailvalidated == true ) ) {
            $_user->Profile->emailvalidated = true;
            $_user->Save();
            return true;
        }
        return false;            
    }
    
    class UserProfileFinder extends Finder {
        protected $mModel = 'UserProfile';

        public function FindBySchool( $school, $offset = 0, $limit = 10000 ) {
            $prototype = New UserProfile();
            $prototype->Schoolid = $school->Id;
            return $this->FindByPrototype( $prototype, $offset, $limit, array( 'Userid', 'DESC' ) );
        }
        
        public function FindAllUsersByEmails( $mails ) {
            $query = $this->mDb->Prepare(
                'SELECT * FROM `userprofiles` WHERE
                `profile_email` IN :emails
                ;'
            );            
            $query->Bind( 'emails', $mails );  
            $res = $query->Execute();
            
            $users = array();
            while ( $row = $res->FetchArray() ) {
                $users[ $row[ 'profile_email' ] ] = $row[ 'profile_userid' ];
            }
            return $users;//<-return array[ 'profile_email' ] = 'profile_userid'
        }
    }

    class UserProfile extends Satori {
        protected $mDbTableAlias = 'userprofiles';
        
        public function CopyLocationFrom( $value ) {
            $this->mRelations[ 'Location' ]->CopyFrom( $value );
        }
        public function CopySchoolFrom( $value ) {
            $this->mRelations[ 'School' ]->CopyFrom( $value );
        }
        public function CopyMoodFrom( $value ) {
            $this->mRelations[ 'Mood' ]->CopyFrom( $value );
        }
        protected function OnBeforeUpdate() {
            $this->Updated = NowDate();
        }
        public function __get( $key ) {
            switch ( $key ) {
                case 'Age':
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
                case 'BirthDay':
                    if ( $this->Dob == '0000-00-00' ) {
                        return 0;
                    }
                    return ( int )date( 'j', strtotime( $this->Dob ) );
                case 'BirthMonth':
                    if ( $this->Dob == '0000-00-00' ) {
                        return 0;
                    }
                    return ( int )date( 'n', strtotime( $this->Dob ) );
                case 'BirthYear':
                    if ( $this->Dob == '0000-00-00' ) {
                        return 0;
                    }
                    return ( int )date( 'Y', strtotime( $this->Dob ) );
                case 'HasBirthday':
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
                case 'Song':
                    switch ( $this->Userid ) {
                        case 1:
							return array( "name" => "Coldplay - Viva La Vida", "url" => "http://cache11-music01.myspacecdn.com/87/std_a3e66d536b1307a5c2b813d2114422f7.mp3" );		
						case 58:
							return array( "name" => "Blank & Jones ft Elles -  Mind of the wonderful" , "url" => "http://izual.kamibu.com/song.mp3" );
						case 872:
							return array( "name" => "Peace Burial At Sea - 52 Faces" , "url" => "http://static.zino.gr/phoenix/art/player/52_faces.mp3" );
						case 3890:
							return array( "name" => "Muse - Time is running out", "url" => "http://static.zino.gr/phoenix/art/player/timerunning.mp3" );
						case 4005:
							return array( "name" => "Los Lobos - Cancion del Mariachi", "url" => "http://pagio.kamibu.com/01%20Cancion%20del%20Mariachi.mp3");
						default:
							return false;
                    }
                    return false;
                default:
                    return parent::__get( $key );
            }
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
        public function __set( $key, $value ) {
            global $water;

            if ( $key == 'School' && $this->mAllowRelationDefinition ) {
                return parent::__set( $key, $value );
            }
            switch ( $key ) {
                case 'BirthDay':
                    w_assert( is_int( $value ) );
                    $this->Dob = $this->MakeBirthdate( $value, $this->BirthMonth, $this->BirthYear );
                    $water->Trace( 'Updated DOB to ' . $this->Dob );
                    break;
                case 'BirthMonth':
                    w_assert( is_int( $value ) );
                    $this->Dob = $this->MakeBirthdate( $this->BirthDay, $value, $this->BirthYear );
                    $water->Trace( 'Updated DOB to ' . $this->Dob );
                    break;
                case 'BirthYear':
                    w_assert( is_int( $value ) );
                    $this->Dob = $this->MakeBirthdate( $this->BirthDay, $this->BirthMonth, $value );
                    $water->Trace( 'Updated DOB to ' . $this->Dob );
                    break;
                case 'School':
                    $this->School->Id = $value->Id;
                    break;
                default:
                    parent::__set( $key, $value );
            }
        }
        protected function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
            $this->Location = $this->HasOne( 'Place', 'Placeid' );
            $this->School = $this->HasOne( 'School', 'Schoolid' );
            $this->Mood = $this->HasOne( 'Mood', 'Moodid' );
            $this->OldProfile = $this->HasOne( 'OldUserProfile', 'Userid' );
        }
        protected function LoadDefaults() {
            $this->Education = 0;
            $this->Sexualorientation = '-';
            $this->Relationship = '-';
            $this->Religion = '-';
            $this->Politics = '-';    
            $this->Eyecolor = '-';
            $this->Haircolor = '-';
            $this->Smoker = '-';
            $this->Drinker = '-';
            $this->Height = -3;
            $this->Weight = -3;
            $this->Updated = NowDate();
        }
        protected function OnUpdate( $updatedAttributes, $previousValues ) {
            global $libs;
            global $user;
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
                if ( isset( $updatedAttributes[ $attribute ] ) ) {
                    // if the attribute has change, delete the old value
                    // whether it has an empty value or not
                    $finder->DeleteByUserAndType( $this->User, $typeid );
                }
            }

            foreach ( $previousValues as $attribute => $value ) {
                $this->OldProfile->$attribute = $value;
            }

            $this->OldProfile->Save();
            
            if ( $updatedAttributes[ 'Email' ] ) {
                $this->ChangedEmail( $previousValues[ 'Email' ], $user->Name );
            }
        }
        public function OnCommentCreate() {
            ++$this->Numcomments;
            $this->Save();
        }
        public function OnCommentDelete() {
            --$this->Numcomments;
            $this->Save();
        }
        
        public function ChangedEmail( $previousEmail, $username ) {
            global $libs;
            global $rabbit_settings;
            global $water;
                        
            $libs->Load( 'rabbit/helpers/helpers' );
            
            if ( $previousEmail == '' ) {
                $this->Emailvalidated = false;                
                $this->Emailvalidationhash = GenerateRandomHash();
                $this->Save();
                $link =  $rabbit_settings[ 'webaddress' ] . '/?p=emailvalidate&userid=' . $this->Userid . '&hash=' . $this->Emailvalidationhash . '&firsttime=true';
                return $link;
            }
            if ( $previousEmail != $this->Email ) {// Sent validation email,set new mail,and set email-validation false
                if ( $this->Email != "" ) {
                    //$this->Email = $email;
                    $this->Emailvalidated = false;                
                    $this->Emailvalidationhash = GenerateRandomHash();
                    $this->Save();
                    
                    $link =  $rabbit_settings[ 'webaddress' ] . '/?p=emailvalidate&userid=' . $this->Userid . '&hash=' . $this->Emailvalidationhash;                    
                    ob_start();
                    $subject = Element( 'email/validate', $username, $link );
                    $message = ob_get_clean();
                    Email( $username, $this->Email, $subject, $message, $rabbit_settings[ 'applicationname' ], 'noreply@' . $rabbit_settings[ 'hostname' ] );
                }
                else {
                    //$this->Email = $email;
                    $this->Emailvalidated = false;                
                    $this->Emailvalidationhash = "";
                    $this->Save();
                }
            }
            
            return;
        }
        
        
    }

?>
