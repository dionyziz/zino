var Chess = {
	table: [],
	EnPassant: null,
	Piece: function( y, x, color ) {
		this.posY = y || 0;
		this.posX = x || 0;
		this.color = color;
		
		Chess.table[ y ][ x ] = this;
		
		this.move = function( y, x ) {
			this.posY = y;
			this.posX = x;
		}
	},
	King: function() { // βασιλιάς
		this.possiblePos = function() {
			pos = [];
			poscount = 0;
			
			pos[ poscount++ ] = [ this.posY + 1, this.posX ];
			pos[ poscount++ ] = [ this.posY + 1, this.posX + 1 ];
			pos[ poscount++ ] = [ this.posY + 1, this.posX - 1 ];
			pos[ poscount++ ] = [ this.posY - 1, this.posX ];
			pos[ poscount++ ] = [ this.posY - 1, this.posX + 1 ];
			pos[ poscount++ ] = [ this.posY - 1, this.posX - 1 ];
			pos[ poscount++ ] = [ this.posY, this.posX - 1 ];
			pos[ poscount++ ] = [ this.posY, this.posX + 1 ];
			
			pos = Chess.CheckPosValid( pos );
			
			return pos;
		}
	},
	Queen: function() { // βασίλισσα
		this.possiblePos = function() {
			pos = [];
			poscount = 0;
		}
	},
	Knight: function() { // άλογο
		this.possiblePos = function() {
			pos = [];
			poscount = 0;
			
			pos = Chess.CheckPosValid( pos );
		}
	},
	Pawn: function() { // στρατιωτάκι
		this.possiblePos = function() {
			pos = [];
			poscount = 0;
			
			if ( this.posX == 1 && Chess.table[ this.posY + 1, this.posX ] == null ) {
				pos[ poscount++ ] = [ this.posY + 2, this.posX ];
			}
			
			pos[ poscount++ ] = [ this.posY + 1, this.posX ];
			
			if ( Chess.PosHasEnemy( this.posY + 1, this.posX + 1, this.color ) ) {
				pos[ poscount++ ] = [ this.posY + 1, this.posX + 1 ];
			}
			
			if ( Chess.PosHasEnemy( this.posY + 1, this.posX -1, this.color ) ) {
				pos[ poscount++ ] = [ this.posY + 1, this.posX - 1 ];
			}
			
			// capture en passant
			if ( ( Chess.EnPassant == [ this.posY + 1, this.posX - 1 ] ) || ( Chess.EnPassant == [ this.posY + 1, this.posX - 1 ] ) ) {
				// TODO: when dragged to x, y en passant, capture capture the pawn on x, y - 1
				pos[ poscount++ ] = Chess.EnPassant;
			}
			
			pos = Chess.CheckPosValid( pos );
			
			return pos;
		}
	},
	CheckPosValid: function( ret ) {
		validret = [];
		countv = 0;
		for ( i in ret ) {
			if ( ret[ i ][ 0 ] >= 0 && ret[ i ][ 0 ] < 8 && ret[ i ][ 1 ] >= 0 && ret[ i ][ 1 ] < 8  ) {
				validret[ countv++ ] = ret[ i ];
			}
		}
		
		return validret;
	},
	PosHasEnemy: function( y, x, color ) {
		enemy = Chess.table[ y ][ x ];
		if ( enemy != null && enemy.color != color ) {
			return true;
		}
		return false;		
	}
	
}

for ( i = 0; i < 8; ++i ) {
	Chess.table[ i ] = [];
	for ( j = 0; j < 8; ++j ) {
		Chess.table[ i ][ j ] = null;
	}
}

king2 = new Chess.Piece( 2, 2, 1 );
king = new Chess.King();

for ( i in king2 ) {
	king[ i ] = king2[ i ];
}

soldier2 = new Chess.Piece( 1, 1, 1 );
soldier = new Chess.Pawn();

for ( i in soldier2 ) {
	soldier[ i ] = soldier2[ i ];
}

possible = soldier.possiblePos();

for ( i in possible ) {
	// alert( possible[ i ] );
}

