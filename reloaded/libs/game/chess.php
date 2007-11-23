<?php
	define( "GAME_CHESS_PIECE_PAWN"   , 0 );
	define( "GAME_CHESS_PIECE_ROOK"   , 1 );
	define( "GAME_CHESS_PIECE_HORSE"  , 2 );
	define( "GAME_CHESS_PIECE_BISHOP" , 3 );
	define( "GAME_CHESS_PIECE_QUEEN"  , 4 );
	define( "GAME_CHESS_PIECE_KING"   , 5 );

	define( "GAME_CHESS_COLOUR_WHITE" , 0 );
	define( "GAME_CHESS_COLOUR_BLACK" , 1 );
	
	class Chess extends Board {
		private $mChessboard; /* $this->Chessboard[ $y ][ $x ] == $item */
		
		public function InitBoard() {
			$this->mChessboard = array(
				array( 
					array( GAME_CHESS_COLOUR_BLACK , GAME_CHESS_PIECE_ROOK ) , 
					array( GAME_CHESS_COLOUR_BLACK , GAME_CHESS_PIECE_HORSE ) , 
					array( GAME_CHESS_COLOUR_BLACK , GAME_CHESS_PIECE_BISHOP ) ,
					array( GAME_CHESS_COLOUR_BLACK , GAME_CHESS_PIECE_QUEEN ) , 
					array( GAME_CHESS_COLOUR_BLACK , GAME_CHESS_PIECE_KING ) , 
					array( GAME_CHESS_COLOUR_BLACK , GAME_CHESS_PIECE_BISHOP ) , 
					array( GAME_CHESS_COLOUR_BLACK , GAME_CHESS_PIECE_HORSE ) , 
					array( GAME_CHESS_COLOUR_BLACK , GAME_CHESS_PIECE_ROOK ) 
				) ,
				array_fill( 0 , 8 , array( GAME_CHESS_COLOUR_BLACK , GAME_CHESS_PIECE_PAWN ) ) ,
				array_fill( 0 , 8 , false ) ,
				array_fill( 0 , 8 , false ) ,
				array_fill( 0 , 8 , false ) ,
				array_fill( 0 , 8 , false ) ,
				array_fill( 0 , 8 , array( GAME_CHESS_COLOUR_WHITE , GAME_CHESS_PIECE_PAWN ) ) ,
				array( 
					array( GAME_CHESS_COLOUR_WHITE , GAME_CHESS_PIECE_ROOK ) , 
					array( GAME_CHESS_COLOUR_WHITE , GAME_CHESS_PIECE_HORSE ) , 
					array( GAME_CHESS_COLOUR_WHITE , GAME_CHESS_PIECE_BISHOP ) ,
					array( GAME_CHESS_COLOUR_WHITE , GAME_CHESS_PIECE_KING ) , 
					array( GAME_CHESS_COLOUR_WHITE , GAME_CHESS_PIECE_QUEEN ) , 
					array( GAME_CHESS_COLOUR_WHITE , GAME_CHESS_PIECE_BISHOP ) , 
					array( GAME_CHESS_COLOUR_WHITE , GAME_CHESS_PIECE_HORSE ) , 
					array( GAME_CHESS_COLOUR_WHITE , GAME_CHESS_PIECE_ROOK ) 
				) ,
			);
		}
		function ValidMove( $startx , $starty , $endx , $endy ) {
			$item = $this->mChessboard[ $starty ][ $startx ];
			if ( $item === false ) { // no piece to move
				return false;
			}
			$color = $item[ 0 ];
			$type = $item[ 1 ];
			switch ( $type ) {
				case GAME_CHESS_PIECE_ROOK:
					if ( $startx == $endx ) {
						$valid = true;
						if ( $starty > $endy ) {
							for ( $y = $starty; $y <= $endy; ++$y ) {
								if ( $this->mChessboard[ $startx ][ $i ] )  {
									$valid = false;
								}
							}
						}
						else {
							// TODO
						}
					}
					else if ( $starty == $endy ) {
					}
					return false;
			}
		}
		function Chess( $boardid ) {
			$this->Board( $boardid );
		}
	}
?>
