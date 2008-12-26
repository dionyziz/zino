<?php
    function UnitUserSettingsSave( tInteger $dobd, tInteger $dobm,
         tInteger $doby, tText $gender,
         tInteger $place, tInteger $education,
         tInteger $school, tInteger $mood,
         tText $sex, tText $religion,
         tText $politics, tText $slogan,
         tText $aboutme, tText $favquote,
         tText $haircolor, tText $eyecolor,
         tInteger $height, tInteger $weight,
         tText $smoker, tText $drinker,
         tText $email, tText $msn,
         tText $gtalk, tText $skype,
         tText $yahoo, tText $web,
         tText $oldpassword, tText $newpassword,
         tText $emailprofilecomment, tText $notifyprofilecomment,
         tText $emailphotocomment, tText $notifyphotocomment,
         tText $emailpollcomment, tText $notifypollcomment,
         tText $emailjournalcomment, tText $notifyjournalcomment,
         tText $emailreply, tText $notifyreply,
         tText $emailfriendaddition, tText $notifyfriendaddition,
         tText $emailtagcreation, tText $notifytagcreation,
         tText $emailfavourite, tText $notifyfavourite ) {
        global $user;

        if ( $user->Exists() ) {
            $dobd = $dobd->Get();
            $dobm = $dobm->Get();
            $doby = $doby->Get();
            $gender = $gender->Get();
            $place = $place->Get();
            $education = $education->Get();
            $school = $school->Get();
            $mood = $mood->Get();
            $sex = $sex->Get();
            $religion = $religion->Get();
            $politics = $politics->Get();
            $slogan = $slogan->Get();
            $aboutme = $aboutme->Get();
            $favquote = $favquote->Get();
            $haircolor = $haircolor->Get();
            $eyecolor = $eyecolor->Get();
            $height = $height->Get();
            $weight = $weight->Get();
            $smoker = $smoker->Get();
            $drinker = $drinker->Get();
            $email = $email->Get();
            $msn = $msn->Get();
            $gtalk = $gtalk->Get();
            $skype = $skype->Get();
            $yahoo = $yahoo->Get();
            $web = $web->Get();
            $oldpassword = $oldpassword->Get();
            $newpassword = $newpassword->Get();
            $emailprofilecomment = $emailprofilecomment->Get();
            $notifyprofilecomment = $notifyprofilecomment->Get();
            $emailphotocomment = $emailphotocomment->Get();
            $notifyphotocomment = $notifyphotocomment->Get();
            $emailpollcomment = $emailpollcomment->Get();
            $notifypollcomment = $notifypollcomment->Get();
            $emailjournalcomment = $emailjournalcomment->Get();
            $notifyjournalcomment = $notifyjournalcomment->Get();
            $emailreply = $emailreply->Get();
            $notifyreply = $notifyreply->Get();
            $emailfriendaddition = $emailfriendaddition->Get();
            $notifyfriendaddition = $notifyfriendaddition->Get();
            $emailtagcreation = $emailtagcreation->Get();
            $notifytagcreation = $notifytagcreation->Get();
            $emailfavourite = $emailfavourite->Get();
            $notifyfavourite = $notifyfavourite->Get();

            if ( checkdate( $dobm , $dobd , $doby ) ) {
				$user->Profile->BirthDay = $dobd;
				$user->Profile->BirthMonth = $dobm;
				$user->Profile->BirthYear = $doby;                
            }
            if ( $gender ) {
                $user->Gender = $gender;
            }
            if ( $place ) {
                if ( $place == -1 ) {
                    $placeid = 0;
                }
                else {
                    $newplace = New Place( $place );
                    if ( $newplace->Exists() ) {
                        $placeid = $newplace->Id;
                    }
                }                
                $user->Profile->Placeid = $placeid;
            }
            if ( $education ) {
                $user->Profile->Education = $education;
            }
            if ( $school ) {
                $user->Profile->Schoolid = $school;
            }
            if ( $mood ) {
                $user->Profile->Moodid = $mood;
            }
            if ( $sex ) {    
                $user->Profile->Sexualorientation = $sex;
            }
            if ( $religion ) {
                $user->Profile->Religion =  $religion;
            }
            if ( $politics ) {
                $user->Profile->Politics = $politics;
            }
            if ( $slogan ) {
                if ( $slogan == '-1' ) {
                    $slogan = '';
                }
                $user->Profile->Slogan = $slogan;
            }
            if ( $aboutme ) {
                if ( $aboutme == '-1' ) {
                    $aboutme = '';
                }
                $user->Profile->Aboutme = $aboutme;
            }
            if ( $favquote ) {
                if ( $favquote == '-1' ) {
                    $favquote = '';
                }
                $user->Profile->Favquote = $favquote;
            }
            if ( $haircolor ) {
                $user->Profile->Haircolor = $haircolor;
            }
            if ( $eyecolor ) {
                $user->Profile->Eyecolor = $eyecolor;
            }
            if ( $height ) {
                $user->Profile->Height = $height;
            }
            if ( $weight ) {
                $user->Profile->Weight = $weight;
            }
            if ( $smoker ) {
                $user->Profile->Smoker = $smoker;
            }
            if ( $drinker ) {
                $user->Profile->Drinker = $drinker;
            }
            $emailerror = false;
            if ( $email ) {
                if ( $email == '-1' ) {
                    $user->Profile->Email = '';
                }
                else {
                    //if ( preg_match( '#^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9.\-_]+$#', $email ) ) {
                    if ( ValidEmail( $email ) ) {
                        $user->Profile->Email = $email;                        
                    }
                    else {
                        $emailerror = true;
                        ?>$( 'div#email span' ).css( "display", "inline" )
                        .animate( { opacity: "1"}, 200, function() {
                            Settings.invalidemail = true;
                        } );<?php
                    }
                }
                //if email == -1 save empty
            }
            $msnerror = false;
            if ( $msn ) {
                if ( $msn == '-1' ) {
                    $user->Profile->Msn = '';
                }
                else {
                    if ( preg_match( '#^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9.\-_]+$#', $msn ) ) {
                        $user->Profile->Msn = $msn;
                    }
                    else {
                        $msnerror = true;
                        ?>$( 'div#msn span' ).css( "display", "inline" )
                        .animate( { opacity: "1"}, 200, function() {
                            Settings.invalidmsn = true;
                        } );<?php
                    }
                }
            }
            if ( $gtalk ) {
				if ( $gtalk == '-1' ) {
					$user->Profile->Gtalk = '';
				}
				else {
					$user->Profile->Gtalk = $gtalk;
				}
            }
            if ( $skype ) {
				if ( $skype == '-1' ) {
					$user->Profile->Skype = '';
				}
				else { 
					$user->Profile->Skype = $skype;
				}
            }
            if ( $yahoo ) {
				if ( $yahoo == '-1' ) {
					$user->Profile->Yim = '';
				}
				else {
					$user->Profile->Yim = $yahoo;
				}
            }
            if ( $web ) {
				if ( $web == '-1' ) {
					$user->Profile->Homepage = '';
				}
				else {
					$user->Profile->Homepage = $web;
				}
            }
            if ( $oldpassword && $newpassword ) {
                if ( $user->IsCorrectPassword( $oldpassword ) ) {
                    if ( strlen( $newpassword ) >= 4 ) {
                        $user->Password = $newpassword;
                        ?>$( 'div#pwdmodal' ).jqmHide();<?php
                    }
                }
                else {
                    ?>Settings.oldpassworderror = true;
                    $( Settings.oldpassworddiv ).find( 'div div span' ).fadeIn( 400 );
                    Settings.oldpassword.focus();<?php
                }
            }
            if ( $emailprofilecomment ) {
                $user->Preferences->Emailprofilecomment = $emailprofilecomment;
            }
            if ( $notifyprofilecomment ) {
                $user->Preferences->Notifyprofilecomment = $notifyprofilecomment;
            }
            if ( $emailphotocomment ) {
                $user->Preferences->Emailphotocomment = $emailphotocomment;
            }
            if ( $notifyphotocomment ) {
                $user->Preferences->Notifyphotocomment = $notifyphotocomment;
            }
            if ( $emailpollcomment ) {
                $user->Preferences->Emailpollcomment = $emailpollcomment;
            }
            if ( $notifypollcomment ) {
                $user->Preferences->Notifypollcomment = $notifypollcomment;
            }
            if ( $emailjournalcomment ) {
                $user->Preferences->Emailjournalcomment = $emailjournalcomment;
            }
            if ( $notifyjournalcomment ) {
                $user->Preferences->Notifyjournalcomment = $notifyjournalcomment;
            }
            if ( $emailreply ) {
                $user->Preferences->Emailreply = $emailreply;
            }
            if ( $notifyreply ) {
                $user->Preferences->Notifyreply = $notifyreply;
            }
            if ( $emailfriendaddition ) {
                $user->Preferences->Emailfriendaddition = $emailfriendaddition;
            }
            if ( $notifyfriendaddition ) {
                $user->Preferences->Notifyfriendaddition = $notifyfriendaddition;
            }
            if ( $emailtagcreation ) { 
                $user->Preferences->Emailphototag = $emailtagcreation;
            }
            if ( $notifytagcreation ) {
                $user->Preferences->Notifyphototag = $notifytagcreation;
            }
            if ( $emailfavourite ) { 
                $user->Preferences->Emailfavourite = $emailfavourite;
            }
            if ( $notifyfavourite ) {
                $user->Preferences->Notifyfavourite = $notifyfavourite;
            }
 
            $user->Save();
            $user->Profile->Save();
            
            ?>$( 'div.savebutton a' ).empty()
            .append( document.createTextNode( 'Αποθήκευση ρυθμίσεων' ) )
			.addClass( 'disabled' );<?php

            $showschool = $user->Profile->Education >= 5 && $user->Profile->Placeid > 0;
            if ( $showschool ) {
                if ( $place || $education ) {
                    ?>$( '#school' ).html( <?php
                        ob_start();
                        Element( 'user/settings/personal/school', $user->Profile->Placeid, $user->Profile->Education );
                        echo w_json_encode( ob_get_clean() );
                    ?> );
                    $( '#school select' ).change( function() {
                        Settings.Enqueue( 'school', this.value, 1000 );
                    });
                    if ( $( $( '#school' )[ 0 ].parentNode ).hasClass( 'invisible' ) ) {
                        $( $( '#school' )[ 0 ].parentNode ).css( "opacity", "0" ).removeClass( "invisible" ).animate( { opacity : "1" }, 200 );
                        $( '#unibarfade' ).css( "opacity", "0" ).removeClass( "invisible" ).animate( { opacity : "1" }, 200 );
                    }<?php
                }
            }
            else {
                if ( $place || $education ) {
                    ?>if ( !$( $( '#school' )[ 0 ].parentNode ).hasClass( 'invisible' ) ) {
                        $( $( '#school' )[ 0 ].parentNode ).animate( { opacity : "0" }, 200, function() {
                            $( this ).addClass( "invisible" );
                        } );
                        $( '#unibarfade' ).animate( { opacity : "0" }, 200, function() {
                            $( this ).addClass( "invisible" );
                        } );
                    }<?php
                }
            }
        }
    }

?>
