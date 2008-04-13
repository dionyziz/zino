<?php
	function ElementDeveloperWater() {
        global $rabbit_settings;
        
		?><script type="text/javascript"><?php	
		ob_start();
		?>
			var imgfolder = <?php
            echo w_json_encode( $rabbit_settings[ 'imagesurl' ] );
            ?> + 'water/';
			var d = document;
			var mywidth = screen.availWidth.toString() * 80/100;
			var myspacer = "    ";
			var page = opener.water_debug_uri;
			var ulid = 0;
			var previsarray = false;
			var activetab = false;

			function g(id) {
				return d.getElementById(id);
			}
			
			function round2( n ) {
				var ans;
				var dot;
				
				// round to 2 decimal places
				ans = ( Math.round( n * 100 ) ) / 100 + "";
				dot = ans.indexOf( "." , 0 );
				if ( dot == ans.length - 2) {
					ans = ans + "0";
				}
				return ans;
			} 
			
			function print_r( Obj ) {
				var dump;
				var element;
				
				if( typeof( Obj ) == 'array' || typeof( Obj ) == 'object' ) {
					dump = d.createElement( 'ul' );
					dump.id = 'ul_' + ulid;
					if( previsarray ) {
						dump.style.display = "none";
					}
					++ulid;
					for( var p in Obj ) {
						element = d.createElement( 'li' );
						
						if ( typeof( Obj[ p ] ) == 'array' || typeof( Obj[ p ] ) == 'object' ){
							var expandimg = d.createElement( 'img' );
							expandimg.src = imgfolder + 'water_expand_icon.png';
							
							var id = "ul_" + ulid;
							
							expandimg.onclick = (function( id ){ return function(){ ArrayDisplay(id);} })(id);
							expandimg.className = 'expandar';
							expandimg.id = 'expand_ul_' + ulid;
							
							element.appendChild( expandimg );
							element.appendChild( d.createTextNode( " " + p ) );
							dump.appendChild( element );
							
							previsarray = true;
							dump.appendChild( print_r( Obj[ p ] ) );
						}
						else {
							element.appendChild( d.createTextNode( p + " : " + Obj[ p ] ) );
							element.style.marginLeft = "13px";
							previsarray = false;
							dump.appendChild( element );
						}
					}
				}
				else {
					previsarray = false;
					dump = d.createTextNode( Obj );
				}
				
				return dump;
			}
			
			function getErrorType( errno ) {
				var errtype;
				var erricon;
				
				switch( errno ) {
					case 1024:
					case 8:
						errtype = "Notice";
						erricon = "water_notice_icon.png";
						break;
					case 256:
						errtype = "Error";
						erricon = "water_error_icon.png";
						break;
					case 2:
					case 512:
						errtype = "Warning";
						erricon = "water_warning_icon.png";
						break;
					case 16384:
						errtype = "Trace";
						erricon = "water_trace_icon.png";
						break;
					case 2048:
						errtype = "Strict Notice";
						erricon = "water_strict_icon.png";
						break;
					default:
						errtype = "Error " + errno + "";
						erricon = "none";
						break;
				}
				
				return [errtype, erricon];
			}

			function getDisplay( id ) {
				switch( g(id).style.display ) {
					case "":
					case "block":
						return "none";
						break;
					default:
						return "";
						break;
				}
			}
			function ShowCallstack( start, end ) {
				var i;
				var condition;
				
				condition = getDisplay( "call_" + start );
				
				for(i = start; i <= end; ++i )
					g("call_" + i).style.display = condition;
			}
			
			function ArrayDisplay( arrayid ) {
				ShowElement( arrayid );
				switch( g( arrayid ).style.display ) {
					case "block":
					case "":
						g( "expand_" + arrayid ).src = imgfolder + "water_collapse_icon.png"; 
						break;
					case "none":
						g( "expand_" + arrayid ).src = imgfolder + "water_expand_icon.png";
						break;
					default:
						g( "expand_" + arrayid ).src = imgfolder + "water_expand_icon.png";
						break;
				}
			}
			
			
			function ShowElement( id ) {
				g( id ).style.display = getDisplay( id );
			}
			
			function Reload( page ) {
				g( 'reloadbutton' ).style.opacity = '0.5';
				g( 'reloadbutton' ).style.cursor = 'default';
				
				var url = page;
				if ( url.indexOf("?") == -1 ) {
					url += "?water_quickdebug";
				}
				else {
					url += "&water_quickdebug";
				}
				var xmlHttp = getXmlHttp();
				if (xmlHttp==null)
				{
					alert ("Browser does not support HTTP Request");
					return;
				}
				xmlHttp.onreadystatechange = function() {
					if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete")
					{ 
						debug_data = eval('(' + xmlHttp.responseText + ')');
						StartDebugging( debug_data, page );
					}
				};
				xmlHttp.open("GET",url,true);
				xmlHttp.send(null); 
			}
			function ContentDisplay( name ) {
				g('main_sql').style.display = 'none';
				g('main_alerts').style.display = 'none';
				g('main_profiles').style.display = 'none';
				g('main_' + name ).style.display = 'block';
				activetab = name;
			}
			
			function CreateTab( name, water_debug_data, page ) {
				//function for creating tabs
				// Use: tabulator.appendChild( CreateTab( 'alerts', water_debug_data, page ) );
				var title;
				var func;
				
				switch( name ) {
					case "alerts":
						title = "Alerts";
						break;
					case "profiles":
						title = "Profiles";
						break;
					case "sql":
						title = "SQL";
						break;
				}
				
				var tab = d.createElement( 'div' );
				tab.className = "tab " + name + "tab";
				tab.onclick = (function( name ){ return function(){ ContentDisplay(name);} })(name);

				tab.appendChild( d.createElement( 'br' ) );
				
				var icon = d.createElement('img');
				icon.src = imgfolder + "water_" + name + "_icon.png";
				icon.alt = title;
				icon.width = 16;
				icon.height = 16;
			
				tab.appendChild( icon );
				
				tab.appendChild( d.createTextNode( ' ' + title ) );
				
				tab.appendChild( d.createElement( 'br' ) );
				tab.appendChild( d.createElement( 'br' ) );
				
				return tab;
			}
			
			function DisplayCopyrightNotice() {

				var copyright = d.createElement( 'div' );
				copyright.className = 'copyright';
				
				var copycontent = d.createTextNode( "Copyright <?php
				
				echo htmlspecialchars( ob_get_clean() );
				
				?>&copy;<?php
				
				ob_start();
				
				?> " );
				copyright.appendChild( copycontent );
				
				var dionyziz = d.createElement( 'a' );
				dionyziz.href = "mailto:dionyziz@gmail.com";
				dionyziz.appendChild( d.createTextNode( 'Dionyziz Zindros' ) );

				var abresas = d.createElement( 'a' );
				abresas.href = "mailto:abresas@gmail.com";
				abresas.appendChild( d.createTextNode( 'Aleksis Brezas' ) );
				
				var contributors = [ dionyziz , abresas ];
				var r = Math.round( Math.random() );
				
				copyright.appendChild( contributors[ r ] );
				copyright.appendChild( d.createTextNode( " and " ) );
				copyright.appendChild( contributors[ 1 - r ] );
				copyright.appendChild( d.createTextNode( ", 2005 - 2007 " ) );
				
				d.body.appendChild( copyright );
			
			}
			
			
			function StartDebugging( debug_data, page ) {
				if ( debug_data != "" ) {
					Display( debug_data, page );
				}
				else {
					var element = document.body;
					while (element.firstChild) {
						element.removeChild(element.firstChild);
					}
					
					var re = document.createElement('a');
					re.href = "javascript:Reload(\"" + page + "\")";

					var link = document.createTextNode( "Refresh" );
					re.appendChild( link );		
					document.body.appendChild(re);
					
					for ( i = 0 ; i < 3 ; ++i ) {
						document.body.appendChild( d.createElement( 'br' ) );
					}
					
					var x = d.createTextNode( "Nothing Here!" );
					d.body.appendChild( x );
					
					for ( i = 0 ; i < 3 ; ++i ) {
						document.body.appendChild( d.createElement( 'br' ) );
					}
					
				}
				DisplayCopyrightNotice();
				if( activetab != false ) {
					DisplayContent( activetab );
				}
				
			}
			
			function Display( water_debug_data,page ) {
				var element = document.body;
				while (element.firstChild) {
					element.removeChild(element.firstChild);
				}
				element.style.padding = "4px";
				
				var refresh = d.createElement( 'div' );
				refresh.style.textAlign = 'center';
				refresh.style.position = 'absolute';
				refresh.style.right = '10px';
				refresh.id = 'reloadbutton';
				
				var re = d.createElement('a');
				re.style.textAlign = 'center';
				re.href = "javascript:Reload(\"" + page + "\")";

				var refreshimg = d.createElement( 'img' );
				refreshimg.src = imgfolder + 'water_refresh_icon.png';
				
				re.appendChild( refreshimg );
				re.appendChild( d.createElement( 'br' ) );
				
				var link = document.createTextNode( "Refresh" );
				re.appendChild( link );
				
				refresh.appendChild( re );
				d.body.appendChild( refresh );
				
				for ( i = 0 ; i < 3 ; ++i ) {
					d.body.appendChild( d.createElement( 'br' ) );
				}
							
				var tabulator = d.createElement( 'div' );
				tabulator.className = "tabulator";
							
				tabulator.appendChild( CreateTab( 'alerts', water_debug_data, page ) );
				
				tabulator.appendChild( CreateTab( 'profiles', water_debug_data, page ) );
				
				tabulator.appendChild( CreateTab( 'sql', water_debug_data, page ) );
				
				d.body.appendChild( tabulator );
				
				var x = d.createElement('div');
				x.className = 'watertrace';
				x.style.width = mywidth + 'px';
				x.style.marginTop = "30px";
				x.style.marginLeft = "50px";
				x.style.paddingLeft = "10px";
				x.id = 'main';
				
				d.body.appendChild( x );
				Display_Alerts( water_debug_data, page );
				Display_Profiles( water_debug_data, page );
				Display_SQL( water_debug_data, page );
			}
			
			function Display_Profile( debug_data, parentid, x, indent ) {
				// d.body.appendChild( print_r( debug_data ) );
				
				var realtime;
				for( var i in debug_data[ parentid ] ) {
					var profile = debug_data[ parentid ][ i ];
						
					var row = d.createElement( 'div' );
					row.id = 'profilerow_' + i;
					row.style.textAlign = 'left';
					row.style.padding = '2px';
					row.style.borderTop = '1px solid #4a85c5';
					row.style.borderLeft = '1px solid #4a85c5';
					row.style.borderRight = '1px solid #4a85c5';
					row.style.borderBottom = '1px solid #4a85c5';
					row.style.backgroundColor = '#C7CCE0';
					row.style.color = 'black';
					row.style.fontFamily = 'Trebuchet MS,sans-serif,Helvetica';
					row.style.marginLeft = indent * 10 + "px";
					row.style.marginBottom = "1px";			
					
					realtime = ( Math.round( profile.time * 1000 * 100 ) / 100 ) + ""; 

					var time = d.createElement( 'div' );
					time.style.cssFloat = 'left';
					time.style.height = '100%';
					time.style.width = '80px';
					time.appendChild( d.createTextNode( realtime + 'ms' ) );
					
					var algorithm = d.createElement( 'div' );
					algorithm.style.display = 'block';
					algorithm.style.width = '900px';
					
					algorithm.appendChild( d.createTextNode( profile.algorithm ) );
					
					row.appendChild( time );
					row.appendChild( algorithm );

					x.appendChild( row );

					if( debug_data[ profile.index ] != null ) {
						Display_Profile( debug_data, profile.index, x, indent + 1 );
					}
				}
			}
			
			function Display_Profiles( water_debug_data, page ) {
				var debug_data = water_debug_data.profiles;
				
				var element = d.createElement('div');
				element.style.display = 'none';
				element.id = 'main_profiles';
				
				var parentid = 0;
				var indentation = 0;
				Display_Profile( debug_data, parentid, element, indentation );
				
				g( 'main' ).appendChild( element );
			}
			
			function Display_SQL( water_debug_data, page ) {
				var debug_data = water_debug_data.sql;
				
				var element = d.createElement('table');
				element.className = 'callstack';
				element.style.width = '100%';
				element.style.borderLeft = '0px solid black';
				element.style.borderRight = '0px solid black';
				element.style.display = 'none';
				element.id = 'main_sql';			
				
				calls = 0;
				
				for( var i in debug_data ) {
					var sql = debug_data[ i ];
					
					/* row = d.createElement( 'tr' );
					row.id = 'sqlrow_' + i;
					row.className = 'error';
					row.style.textAlign = 'left';
					row.style.padding = '2px';
									
					realtime = ( Math.round( sql.time * 1000 * 100 ) / 100 ) + ""; 

					var time = d.createElement( 'td' );
					time.style.cssFloat = 'left';
					time.style.height = '100%';
					time.style.width = '80px';
					time.appendChild( d.createTextNode( realtime + 'ms' ) );
					
					query = d.createElement( 'td' );
					query.id = 'query_' + i;
					query.style.display = 'block';
					query.style.height = '18px';
					query.style.overflow = 'hidden';
					query.style.cursor = 'pointer';
					query.style.clear = 'none';
					
					query.onclick = (function (){ return function(){ if( this.style.height == '' ) { this.style.height = '18px'; } else { this.style.height = '' } } })();
					
					query.appendChild( d.createTextNode( sql.query ) );
					
					row.appendChild( time );
					row.appendChild( query );
					
					element.appendChild( row ); */
					
					var stackstart = calls;
					
					var sqltr = d.createElement('tr');
					sqltr.id = "query_" + i;
					sqltr.className = "error";
					
					var topsqltd = d.createElement('td');
					topsqltd.colSpan = 3;
					topsqltd.style.borderLeft = '1px solid #4a78c5';
					topsqltd.style.paddingRight = '4px';

					if ( sql.calltrace[1] != null && sql.calltrace[1].file != "" && sql.calltrace[1].line != "-" ) {
						var sqlfile = d.createElement('div');
						sqlfile.appendChild( d.createTextNode( sql.calltrace[1].file ) );
						sqlfile.appendChild( d.createTextNode( " line " + sql.calltrace[1].line ) );
						sqlfile.style.cssFloat = "right";
						sqlfile.style.paddingRight = "5px";
						topsqltd.appendChild(sqlfile);
					}
						
					var sqldescr = d.createElement('div');
					
					var sqlcontent = d.createTextNode( ( Math.round( sql.time * 1000 * 100 ) / 100 ) + "ms" + myspacer + sql.query );
					
					sqldescr.appendChild(sqlcontent);
					topsqltd.appendChild(sqldescr);
					sqltr.appendChild( topsqltd );
					
					element.appendChild( sqltr );
					
					var sacktitle = d.createElement('tr');
					sacktitle.id = "sqlcall_" + calls;
					sacktitle.className = "title";
					sacktitle.style.width = "100%";
					sacktitle.style.borderLeft = "1px solid #4a85c5";
					sacktitle.style.display = "none";
					
					var functitle = d.createElement('td');
					functitle.className = "title";
					functitlecontent = d.createTextNode( "function" );
					functitle.appendChild( functitlecontent );
					sacktitle.appendChild( functitle );
					
					var sourcetitle = d.createElement('td');
					sourcetitle.className = "title";
					sourcetitlecontent = d.createTextNode( "source" );
					sourcetitle.appendChild( sourcetitlecontent );
					sacktitle.appendChild( sourcetitle );
					
					var linetitle = d.createElement('td');
					linetitle.className = "title";
					linetitle.appendChild( d.createTextNode( "line" ) );
					sacktitle.appendChild( linetitle );
					
					element.appendChild( sacktitle );
					++calls;
					for ( j	in sql.calltrace ) {
						sqlcall = sql.calltrace[ j ];
						
						if ( sqlcall.class == "Water" && sqlcall.name =="LogSQLEnd" ) {
							continue; // Water->LogSQLEnd() should not be displayed
						}
												
						var calltr = d.createElement('tr');
						calltr.id = "sqlcall_" + calls;
						calltr.style.borderLeft = "1px solid #4a85c5";
						calltr.style.display = "";
						
						var callfunc = d.createElement('td');
						callfunc.className = "function";
						
						var functiontext = "";
						if( sqlcall.class != "" ) {
							functiontext = sqlcall.class + "->";
						}
						functiontext += sqlcall.name;
						
						if( sqlcall.phpfunction == 1 ) {
							phplink = d.createElement( 'a' );
							phplink.href = "http://www.php.net/" + sqlcall.name;
							phplink.appendChild( d.createTextNode( functiontext ) );
							callfunc.appendChild( phplink );
						}
						else {
							callfunc.appendChild( d.createTextNode( functiontext ) );
						}
						
						if( sqlcall.args != "" ) {
							var functionargs = "(";
							var k = 0;
							while( arg = sqlcall.args[k] ) {
								if( k != 0 ) {
									functionargs += ",";
								}
								functionargs += " " + arg;
								++k;
							}
							functionargs += " )";
							content = d.createTextNode( functionargs );
							callfunc.appendChild( content );
						}
						
						calltr.appendChild( callfunc );
						
						var callfile = d.createElement( 'td' );
						callfile.className = "file";
						if( j == 0 ) {
							callfile.style.fontWeight = "bold";
						}
						var content = d.createTextNode( sqlcall.file );
						callfile.appendChild( content );
						calltr.appendChild( callfile );
						
						var callline = d.createElement( 'td' );
						callline.className = "line";
						var content = d.createTextNode( sqlcall.line );
						callline.appendChild( content );
						calltr.appendChild( callline );
						
						element.appendChild( calltr );
						
						++j;
						++calls;
					}	
				}
				g( 'main' ).appendChild( element );
				
				g( 'sqlrow_' + i ).style.borderBottom = '1px solid #4a85c5';
				g( 'sqlrow_' + i ).style.marginBottom = '10px';
			}
			
			function Display_Alerts(water_debug_data, page) {
				var calls;
				
				var debug_data = water_debug_data.alerts;
				
				var element = d.createElement( 'div' );
				element.id = 'main_alerts';
				
				var mytable = document.createElement('table');
				mytable.className = 'callstack';
				mytable.style.width = '100%';
				mytable.style.borderLeft = '0px solid black';
				mytable.style.borderRight = '0px solid black';
				mytable.id = 'water';

				calls = 0;
				for( var i in debug_data ) {

					var error = debug_data[i];

					var errtype = getErrorType(error.id);
					
					var stackstart = calls;
					
					var errortr = d.createElement('tr');
					errortr.id = "err_" + i;
					errortr.className = "error";
					
					var toperrortd = d.createElement('td');
					toperrortd.colSpan = 3;
					toperrortd.style.borderLeft = '1px solid #4a78c5';
					toperrortd.style.paddingRight = '4px';

					if ( error.calltrace[0] != null && error.calltrace[0].file != "" && error.calltrace[0].line != "-" ) {
						var errorfile = d.createElement('div');
						if( error.calltrace[0].file != "" ) {
							content = d.createTextNode( error.calltrace[0].file );
							errorfile.appendChild( content );
						}
						if( error.calltrace[0].line != "-" ) {
							content = d.createTextNode( " line " + error.calltrace[0].line );
							errorfile.appendChild( content );
						}
						errorfile.style.cssFloat = "right";
						errorfile.style.paddingRight = "5px";
						toperrortd.appendChild(errorfile);
					}
						
					var errordescr = d.createElement('div');
					toperrortd.appendChild(errordescr);
					
					var errorcontent = d.createTextNode( myspacer + errtype[0] + ":" + myspacer + error.description );
					
					if( errtype[1] != "none" ) {
						var erroricon = d.createElement('img');
						erroricon.src = imgfolder + errtype[1];
						erroricon.alt = errtype[0];
						erroricon.width = 12;
						erroricon.height = 12;
					
						errordescr.appendChild(erroricon);	
					}
					
					errordescr.appendChild(errorcontent);
					
					errortr.appendChild( toperrortd );
					
					mytable.appendChild( errortr );
					
					var dump = error[ 'dump' ];
					if( error[ 'dump' ] != null ) {
						var dumptr = d.createElement('tr');
						dumptr.style.borderLeft = "1px solid #4a85c5";
						dumptr.style.display = "none";
						dumptr.id = "call_" + calls;
						dumptr.style.backgroundColor = "#DFDFD1";
						dumptr.style.backgroundImage = "";
						
						dumptd = d.createElement( 'td' );
						dumptd.className = "file";
						dumptd.colSpan = "3";
						
						dumptd.appendChild( print_r( dump ) );
						previsarray = false;
						
						dumptr.appendChild( dumptd );
					
						mytable.appendChild( dumptr );
						++calls;
					}
					
					var sacktitle = d.createElement('tr');
					sacktitle.id = "call_" + calls;
					sacktitle.className = "title";
					sacktitle.style.width = "100%";
					sacktitle.style.borderLeft = "1px solid #4a85c5";
					sacktitle.style.display = "none";
					
					var functitle = d.createElement('td');
					functitle.className = "title";
					functitlecontent = d.createTextNode( "function" );
					functitle.appendChild( functitlecontent );
					sacktitle.appendChild( functitle );
					
					var sourcetitle = d.createElement('td');
					sourcetitle.className = "title";
					sourcetitlecontent = d.createTextNode( "source" );
					sourcetitle.appendChild( sourcetitlecontent );
					sacktitle.appendChild( sourcetitle );
					
					var linetitle = d.createElement('td');
					linetitle.className = "title";
					content = d.createTextNode( "line" );
					linetitle.appendChild( content );
					sacktitle.appendChild( linetitle );
					
					mytable.appendChild( sacktitle );
					++calls;
					
					for( j in error.calltrace ) {
						
						var call = error.calltrace[j];
						var calltr = d.createElement('tr');
						calltr.id = "call_" + calls;
						calltr.style.borderLeft = "1px solid #4a85c5";
						calltr.style.display = "none";
						
						var callfunc = d.createElement('td');
						callfunc.className = "function";
						
						var functiontext = "";
						if( call.class != "" ) {
							functiontext = call.class + "->";
						}
						functiontext += call.name;
						
						if( call.phpfunction == 1 ) {
							phplink = d.createElement( 'a' );
							phplink.href = "http://www.php.net/" + call.name;
							content = d.createTextNode( functiontext );
							phplink.appendChild( content );
							callfunc.appendChild( phplink );
						}
						else {
							content = d.createTextNode( functiontext );
							callfunc.appendChild( content );
						}
						
						if( call.args != "" ) {
							var functionargs = "(";
							var k = 0;
							while( arg = call.args[k] ) {
								if( k != 0 ) {
									functionargs += ",";
								}
								functionargs += " " + arg;
								++k;
							}
							functionargs += " )";
							content = d.createTextNode( functionargs );
							callfunc.appendChild( content );
						}
						
						calltr.appendChild( callfunc );
						
						var callfile = d.createElement( 'td' );
						callfile.className = "file";
						if( j == 0 ) {
							callfile.style.fontWeight = "bold";
						}
						var content = d.createTextNode( call.file );
						callfile.appendChild( content );
						calltr.appendChild( callfile );
						
						var callline = d.createElement( 'td' );
						callline.className = "line";
						var content = d.createTextNode( call.line );
						callline.appendChild( content );
						calltr.appendChild( callline );
						
						mytable.appendChild( calltr );
						
						++j;
						++calls;
					}
					
					blanktr = d.createElement('tr');
					blanktr.id = "call_" + calls;
					blanktr.style.backgroundColor = "white";
					blanktr.style.borderRight = "0px solid black";
					blanktr.style.display = "none";
					
					blanktd = d.createElement('td');
					blanktd.colSpan = 3;
					blanktd.style.borderRight = "0px solid black";
					blanktd.appendChild( d.createElement( 'br' ) );
					
					blanktr.appendChild( blanktd );
					
					mytable.appendChild( blanktr );
					
					++calls;
					stackend = calls - 1;

					toperrortd.onclick = (function(stackstart, stackend){ return function(){ ShowCallstack(stackstart, stackend);} })(stackstart, stackend);
					++i;
				}
				element.appendChild(mytable);
				
				g( 'main' ).appendChild(element);
			}
			
			StartDebugging(opener.water_debug_data, page);
		<?php

		echo htmlspecialchars( ob_get_clean() );

		?></script><?php
	}
?>
