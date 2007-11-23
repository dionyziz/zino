<?php
	return;
	
	set_include_path( '../:./' );
	
	global $page;
	global $libs;
    
	require_once 'libs/rabbit/rabbit.php';
    Rabbit_Construct( 'HTML' );
    $req = $_GET;
    
	Rabbit_ClearPostGet();
	
	$libs->Load( 'comment' );
	$libs->Load( 'faq' );
	
    global $db;
    
    $sql = "SELECT `faqquestion_id` AS id, `faqquestion_answer` AS answer FROM `merlin_faqquestions`;";
    $res = $db->Query( $sql );
	
	$answers = array();
	while ( $row = $res->FetchArray() ) {
		$answers[ $row[ "id" ] ] = $row[ "answer" ];
	}
	
	$aFormatted = mformatstories( $answers );
	
	foreach ( $aFormatted as $id => $answer ) {
		global $db;
		
		echo "Question $i ....";
		$answer = myescape( $answer );
		
		$sql = "UPDATE `merlin_faqquestions` SET `faqquestion_answerformatted` = '$answer' WHERE `faqquestion_id` = '$id' LIMIT 1;";
		echo( $sql );
		$change = $db->Query( $sql );
	
		if ( $change->Impact() ) {
			echo "DONE";
		}
		else {
			echo "NO IMPACT";
		}
		echo "<br />";
		
		ob_flush();
	}
	
    $page->Output();

    Rabbit_Destruct();
	
?>
