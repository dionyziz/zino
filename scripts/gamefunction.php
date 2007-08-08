<?php
	return;
	
	include "../header.php";
	// include "../libs/graph.php";

	global $water;

?>
<h2>Game Delta Function</h2>
<?php
	define( 'GAME_DELTA_ALPHA', 2 ); // minimum points in a game (when diff is 0)
	define( 'GAME_DELTA_BETA', 100 ); // maximum points in a game
	define( 'GAME_DELTA_RHO', 1 / 10000 ); // factor of magnitute of growth for the delta function
	define( 'GAME_DELTA_FACTOR', ( GAME_DELTA_BETA - GAME_DELTA_ALPHA ) / ( GAME_DELTA_ALPHA - 1 ) );
	
	?><br />ALPHA = <?php
	echo GAME_DELTA_ALPHA;
	?><br />BETA = <?php
	echo GAME_DELTA_BETA;
	?><br />RHO = <?php
	echo GAME_DELTA_RHO;
	?><br />FACTOR = (BETA - ALPHA) / (ALPHA - 1) = <?php
	echo GAME_DELTA_FACTOR;
	?><br /><br /><br /><?php
	
	function GameDelta( $x1, $x2 ) {
		$x = ( $x2 - 1.1 * $x1 ) * min( $x1 , $x2 );
		$num = GAME_DELTA_BETA - 1;
		$pwr = -GAME_DELTA_RHO * $x;
		$e = exp( $pwr );

		return $num / ( 1 + GAME_DELTA_FACTOR * $e ) + 1;
	}
	
	$min  = 10;
	$step = 5;
	$max  = 500;

	// $data = array();

	?>Applying function for x1, x2 = <?php
	echo $min;
	?>...<?php
	echo $max;
	?> (step <?php
	echo $step;
	?>)<br /><br /><?php
	
	for ( $x1 = $min ; $x1 < $max ; $x1 += $step ) {
		for ( $x2 = $min ; $x2 < $max ; $x2 += $step ) {
			?>(<?php
			echo $x1;
			?>, <?php
			echo $x2;
			?>) <b><?php
			echo GameDelta( $x1 , $x2 );
			?></b><br /><?php
		}
	}

	/*
	header( 'Content-type: image/png' );
	    	
	$graph = New Graph( 'GameDelta (a=' . GAME_DELTA_ALPHA . ',b=' . GAME_DELTA_BETA . ',c=' . GAME_DELTA_GAMMA . ')' );
	$graph->SetData( $data );
	$graph->SetSize( 600, 300 );
	$graph->SetTime( 0, $i );
	$graph->SetColor( "graphcolor", "red" );
	$graph->Render();
	*/
	
    // ob_get_clean();
    
	//$water->GenerateJS();
?>

