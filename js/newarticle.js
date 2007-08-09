var NewArticle = {
	Stag : function ( what ) {
		var f = document.getElementById( 'newarticle' );
		var s = f.getElementsByTagName( 'textarea' )[ 0 ];
		
		// IE
		if (document.selection) {
			s.focus();
			sel = document.selection.createRange();
			sel.text = what;
			s.focus();
		}
		// MOZ
		else if (s.selectionStart || s.selectionStart == "0") {
			var startPos = s.selectionStart;
			var endPos = s.selectionEnd;
			var c = s.value;

			s.value = c.substring(0, startPos) + what + c.substring(endPos, c.length);
		}
		else {
			s.value += what;
		}
		
		return true;
	},
	Validate: function () {
		if ( g( 'categoryid' ).selectedIndex === 0 ) {
			return confirm( 'Έχεις επιλέξει το συγκεκριμένο άρθρο να μην ενταχθεί σε καμία κατηγορία.Κάνοντάς το αυτό, το άρθρο σου δεν θα εμφανιστεί μαζί με τα υπόλοιπα νέα άρθρα, άλλα ούτε και με τα δημοφιλή άρθρα. Θέλεις σίγουρα να συνεχίσεις;');
		}
		return true;
	}
};
