<?php

    function UnitFrontpageWelcomeoptions( tInteger $place , tText $education , tInteger $university ) {
        global $user;
        
        if ( $user->Exists() ) {
            $place = $place->Get();
            $education = $education->Get();
            $university = $university->Get();
            
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
            if ( $university ) {
                if ( $university == -1 ) {
                    $uniid = 0;
                }
                else {
                    $newuni = New Uni( $university );
                    if ( $newuni->Exists() ) {
                        $uniid = $newuni->Id;
                    }
                }
                $user->Profile->Uniid = $uniid;
            }
            $user->Save();
            $user->Profile->Save();
            ?>$( 'div.frontpage div.ybubble div.body div.saving' ).animate( { opacity : "0" } , 200 , function() {
                $( 'div.frontpage div.ybubble div.body div.saving' ).addClass( 'invisible' ).css( "opacity" , "1" );
                $( 'div.frontpage div.ybubble div.body div.saved' )
                    .css( "opacity" , "0" )
                    .removeClass( "invisible" )
                    .animate( { opacity : "1" } , 2000 , function() {
                        $( 'div.frontpage div.ybubble div.body div.saved' ).addClass( 'invisible' );
                } );
            } );<?php
            if ( $user->Profile->Education == 'university' ) {
                $typeid = 0;
            }
            else if ( $user->Profile->Education == 'TEI' ) {
                $typeid  = 1;
            }
            $showuni = isset( $typeid ) && $user->Profile->Placeid > 0;
            if ( $showuni ) {
                if ( $place || $education ) {
                    ?>$( '#selectuni' ).html( <?php
                        ob_start();
                        ?><span>Πανεπιστήμιο</span><?php
                        Element( 'user/settings/personal/university' , $user->Profile->Placeid , $typeid );
                        echo w_json_encode( ob_get_clean() );
                    ?> );
                    $( '#selectuni select' ).change( function() {
                        var uni = $( '#selectuni select' )[ 0 ].value;
                        $( 'div.frontpage div.ybubble div.body div.saving' ).removeClass( 'invisible' );
                        Coala.Warm( 'frontpage/welcomeoptions' , { university : uni } );
                    });
                    if ( $( '#selectuni' ).hasClass( 'invisible' ) ) {
                        $( '#selectuni' ).css( "opacity" , "0" ).removeClass( "invisible" ).animate( { opacity : "1" } , 200 );
                    }<?php
                }
            }
            else {
                if ( $place || $education ) {
                    ?>if ( !$( '#selectuni' ).hasClass( 'invisible' ) ) {
                        $( '#selectuni' ).animate( { opacity : "0" } , 200 , function() {
                            $( this ).addClass( "invisible" );
                        } );
                    }<?php
                }
            }
            
            
            
        }
    }
?>
