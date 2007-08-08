/*

old pms

var Pms = {
	activecontent : 'inbox',
	InitTabs: function( id ) {		
		var outboxTab = 'inactive';
		var inboxTab = 'inactive';
		var newTab = 'inactive';
		
		switch ( id ) {
			case 'outbox':
				outboxTab = 'active';
				break;
			case 'new':
				newTab = 'active';
				break;
			default:
				inboxTab = 'active';
				break;
		}
		
		g( 'outbox_link' ).className = outboxTab;
		g( 'inbox_link' ).className = inboxTab;
		g( 'new_link' ).className = newTab;
	},
	Display: function( index ) {
		document.getElementById( Pms.activecontent + '_link' ).className = "inactive";
		document.getElementById( index + '_link' ).className = "active";
		var element = document.getElementById( 'pmstitle' );
		while ( element.firstChild ) {
			element.removeChild(element.firstChild);
		}
		element.appendChild( document.createTextNode( Pms.Title( index ) ) );
		document.getElementById( Pms.activecontent + '_div' ).style.display = "none";
		document.getElementById( index + '_div' ).style.display = "block";
		Pms.activecontent = index;
	},
	Title: function( index ) {
		switch( index ) {
			case 'outbox':
				return 'Απεσταλμένα';
				break;
			case 'new':
				return 'Νέο Μήνυμα';
				break;
			case 'inbox':
			default:
				return 'Εισερχόμενα';
				break;
		}
	},
	Answer: function( username ) {
		g('receiver').value = username;
		Pms.Display( 'new' );
		document.getElementById('pmtext').focus();
	}
};

var id = g( 'pm_page_id' ).childNodes[ 0 ].nodeValue;

if ( id != '' ) {
	Pms.InitTabs( id );
}
*/