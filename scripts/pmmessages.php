<?php
	set_include_path( '../:./' );
	
	global $page;
	global $libs;
    global $db;
    global $pmmessages;
    global $pmmessageinfolder;
    
	require_once 'libs/rabbit/rabbit.php';
    Rabbit_Construct( 'HTML' );
    $req = $_GET;
    
	Rabbit_ClearPostGet();
	
	$libs->Load( 'albums' );

    function GetAllPms() {
        global $db;
        global $pms;

        $sql = "SELECT * FROM `$pms`;";

        return $db->Query( $sql )->MakeArray();
    }

    $ret = GetAllPms();
    $texts = array();
    foreach ( $ret as $pm ) {
        $texts[ $pm[ 'pm_id' ] ] = $pm[ 'pm_text' ];
    }

    $formatted = mformatpms( $texts );

    foreach ( $ret as $pm ) {
        ?>Updating pm <?php
        echo $pm->Id
        ?>... <?php

        $pmm = array(
            'pm_id'             => $pm[ 'pm_id' ],
            'pm_senderid'       => $pm[ 'pm_from' ],
            'pm_text'           => $pm[ 'pm_text' ],
            'pm_textformatted'  => $formatted[ 'pm_id' ],
            'pm_date'           => $pm[ 'pm_created' ]
        );

        $pmifsend = array(
            'pmif_id'       => $pm[ 'pm_id' ],
            'pmif_userid'   => $pm[ 'pm_from' ],
            'pmif_folderid' => -2,
            'pmif_delid'    => 0
        );

        $pmifrecv = array(
            'pmif_id'       => $pm[ 'pm_id' ],
            'pmif_userid'   => $pm[ 'pm_to' ],
            'pmif_folderid' => -1,
            'pmif_delid'    => $pm[ 'pm_delid' ]
        );

        $db->Insert( $pmm, $pmmessages );
        $db->Insert( $pmifsend, $pmmessageinfolder );
        $db->Insert( $pmifrecv, $pmmessageinfolder );

        ?>OK<br /><?php
    }
	
    $page->Output();

    Rabbit_Destruct();
	
?>
