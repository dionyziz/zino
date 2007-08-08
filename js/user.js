var user = {
	LoginUser : function ( username , password , form , parentdiv ) { 
		userbox = parentdiv.parentNode.parentNode.parentNode;
		timer = Animations.Create( userbox , 'opacity' , 4000 , 1 , 0 );
		Coala.Warm( 'users/login' , { username : username , password : password , form : form , parentdiv : parentdiv , timer : timer } );
	}
}
