var coalaconsole = {
	paramnum : 1,
	AddParam : function() {
		++coalaconsole.paramnum;
		
		theform = document.getElementById( 'coalaform' );
		theformdivs = theform.getElementsByTagName( 'div' );
		thediv = theformdivs[ 0 ];
		namespan = document.createElement( 'div' );
		namespan.appendChild( document.createTextNode( 'Name:' ) );
		namespan.className = 'spanparamname';
		inputname = document.createElement( 'input' )
		inputname.type = 'text';
		inputname.id = 'paramname_' + coalaconsole.paramnum;
		inputname.className = 'nameparam';
		
		valuespan = document.createElement( 'div' );
		valuespan.appendChild( document.createTextNode( 'Value:' ) );
		valuespan.className = 'spanparamvalue';
		inputvalue = document.createElement( 'input' );
		inputvalue.type = 'text';
		inputvalue.id = 'paramvalue_' + coalaconsole.paramnum;
		inputvalue.className = 'valueparam';
		
		thediv.appendChild( namespan );
		thediv.appendChild( inputname );
		thediv.appendChild( valuespan );
		thediv.appendChild( inputvalue );	
	}
	MakeCall : function () {
		/*
		make the coala call
		before i have to check whether all options are valid
		*there is a name and a value given
		*there is either a cold or warm call
		*/
		parameter1namevalue = document.getElementById( 'paramname_1' );
		parameter1valuevalue = document.getElementById( 'paramvalue_1' );
		coldcall = document.getElementById( 'coldcall' );
		warmcall = document.getElementById( 'warmcall' );
		if ( coala.paramnum == 1 && parameter1namevalue == '' && parameter1valuevalue == '' ) {
			alert( 'You have to specify at least one valid parameter' );
			return;
		}
		else if ( coldcall.selected == false && warmcoala.selected == false ) {
			alert( 'You have to specify whether you want a cold or a warm call' );
			return;
		}
		else {
			parameter = '';
			parameter2 = '';
			for ( i = 1; i <= coala.paramnum; ++i ) {
				//create parameters in a string
				parametername = document.getElementById( 'paramname_' + i );
				parametervalue = document.getElementById( 'paramvalue_' + i );
				if ( parametername.value != '' && parametervalue.value != '' ) {
					//create string
					parameter += '';
					parameter2 += '';
				}
				
			}
		}
		//making the coala call
		hiddenparametername = document.getElementById( 'ids' );
		hiddenparametername.value = parameter;
		hiddenparametervalue.value = parametervalue;
		document.getElementById( 'coalaform' ).submit();
		
		
	}
}