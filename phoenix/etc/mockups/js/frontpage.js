var Frontpage = {
	Closenewuser : function ( node ) {
		var parent = node.parentNode;
		parent.removeChild( node );
		Animations.Create( parent, 'height', 5000, 100, 0, function() {
			node.parentNode.removeChild( parent);
		} );
		
		Animations.Create( parent, 'opacity', 800, 10, 0, function () {
			parent.style.marginBottom = '0';	
			parent.style.padding = '0';
		} );
	},
	Showunis : function( node ) {
		var divlist = node.getElementsByTagName( 'div' );
		var contenthtml = "<span style=\"padding-left:5px;\">Πανεπιστήμιο:</span><select><option value=\"0\" selected=\"selected\">-</option><option value=\"2\">Φιλολογία</option><option value=\"6\">Ηλεκτρολόγων Μηχανικών &amp; Μηχανικών Υπολογιστών</option><option value=\"9\">Ιατρική</option><option value=\"23\">Ηλεκτρονική</option><option value=\"25\">Φιλοσοφία</option><option value=\"43\">Θεολογία</option><option value=\"35\">Πληροφορική</option><option value=\"67\">Μηχανικός Υπολογιστών</option><option value=\"98\">Οδοντοϊατρική</option></select>";
		var newdiv = document.createElement( 'div' );
		newdiv.innerHTML = contenthtml;
		node.insertBefore( newdiv, divlist[ 0 ].nextSibling );
	}
};