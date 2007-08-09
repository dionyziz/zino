var Frontpage = {
	OnlineUsersBox: false,
    SwitchOnlineUsersBox: function( section ) {
        switch ( section ) {
            case 'online':
				if ( !Frontpage.OnlineUsersBox ) {
					return;
				}
				Frontpage.OnlineUsersBox = false;
                g( 'onlineuserscontent' ).style.display = 'block';
                g( 'newuserscontent' ).style.display = 'none';
                Animations.Create( g( 'onlineuheader' ) , 'opacity' , 2000 , 0.3 , 1 );
                Animations.Create( g( 'newuheader' ) , 'opacity' , 2000 , 1 , 0.3 );
                break;
            case 'new':
				if ( Frontpage.OnlineUsersBox ) {
					return;
				}
				Frontpage.OnlineUsersBox = true;
                g( 'newuserscontent' ).style.display = 'block';
                g( 'onlineuserscontent' ).style.display = 'none';
                Animations.Create( g( 'onlineuheader' ) , 'opacity' , 2000 , 1 , 0.3 );
                Animations.Create( g( 'newuheader' ) , 'opacity' , 2000 , 0.3 , 1 );
        }
    }
};
