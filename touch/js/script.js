PhotoView = {
	_threshold: 5,
	_pending: [],
	GetCardIndex: function( carusel, card ){
		var items = carusel.items.items;
		for( var i = 0; i < items.length; ++i ){
			if( card.id == items[ i ].id ){
				return i;
			}
		}
		return false;
	},
	AddEmptyCard: function( carusel, id, index ){
		if( typeof( index ) == 'undefined' ){
			index = 0;
		}
		carusel.insert( index, {
			data: {
				rendered: false
			},
			id: 'PhotoView_' + id
		});
		carusel.doLayout();
		return carusel.getComponent( 'PhotoView_' + id );
	},
	ParseXml: function( xml ){
        var selectValue = function(key, root, defaultValue){
			if( key == '#' ){
				return root.tagName;
			}
			if( key.indexOf( '@' ) != -1 ){
				var property = key.split( '@' )[ 1 ];
				key = key.split( '@' )[ 0 ];
			}
			var val;
			if( key.length ){
				var node = Ext.DomQuery.selectNode(key, root);
				if( node && node.firstChild ){
					node = node.firstChild;
				}
			}
			else{
				var node = root;
			}
            if(node){
				if( typeof( node.getAttribute ) != 'undefined' && typeof( property ) != 'undefined' ){
					val = node.getAttribute( property );
				}
				else{
					val = node.nodeValue;
				}
            }
            return Ext.isEmpty(val) ? defaultValue : val;
        };
		var photo = {
			title: selectValue( 'social > photo > title', xml ),
			album: {
				id: selectValue( 'social > photo album@id', xml ),
				name: selectValue( 'social > photo album name', xml ),
			},
			author: {
				name: selectValue( 'social > photo > author > name', xml ),
				gender: selectValue( 'social > photo > author > gender', xml ),
				avatarurl: selectValue( 'social > photo > author > avatar > media@url', xml ),
			},
			published: selectValue( 'social > photo > published', xml ),
			url: selectValue( 'social > photo > media@url', xml ),
			size: {
				width: parseInt( selectValue( 'social > photo > media@width', xml ) ),
				height: parseInt( selectValue( 'social > photo > media@height', xml ) )
			},
			favourites: selectValue( 'social > photo > favourites@count', xml ),
			comments: selectValue( 'social > photo > discussion@count', xml ),
			siblings: {
				prev: selectValue( 'photos photo[navigation=previous]@id', xml ),
				next: selectValue( 'photos photo[navigation=next]@id', xml )
			}
		};
		return photo;
	},
	SetDimentions: function( carusel, card, screenD, imageD ){
		if( typeof( screenD ) == 'undefined' || screenD === false ){
			var screenD = {
				width: carusel.getEl().getWidth(),
				height: carusel.getEl().getHeight()
			};		
		}
		if( typeof( imageD ) == 'undefined' || imageD === false ){
			var imageD = {
				width: card.getEl().select( 'img.image_content' ).first().getWidth(),
				height: card.getEl().select( 'img.image_content' ).first().getHeight()
			}
		}
		
		var img = card.getEl().select( 'img' );
		if( screenD.width / screenD.height < imageD.width / imageD.height ){
			var width = screenD.width;
			var height = imageD.height * ( screenD.width / imageD.width )
			img.setWidth( width );
			img.setHeight( height );
			img.setStyle( 'margin-top', ( screenD.height - height ) / 2 + 'px' );
			img.setStyle( 'margin-left', 0 );
		}
		else{
			var width = imageD.width * ( screenD.height / imageD.height );
			var height = screenD.height;
			img.setWidth( width );
			img.setHeight( height );
			img.setStyle( 'margin-left', ( screenD.width - width ) / 2 + 'px' );
			img.setStyle( 'margin-top', 0 );
		}
	},
	Get: function( id, chain ){
		if( typeof( chain ) == 'undefined' ){
			chain = PhotoView._threshold;
		}
		var carusel = Layout.getComponent( 'PhotoView' );
		var card = carusel.getComponent( 'PhotoView_' + id );
		if( !card ){
			card = PhotoView.AddEmptyCard( carusel, id );
		}
		if( card.data.rendered ){
			if( chain ){
				PhotoView.GetSiblings( carusel, card, chain );
			}
			return false;
		}
		if( card.data.downloading ){
			//setTimeout( function(){ PhotoView.Get( id, chain ); }, 300 );
			return false;
		}
		card.data.downloading = true;
		var request = Ext.Ajax.request({
			url: window.base + '?resource=photo&method=view&id=' + id,
			success: function( carusel, card ){
				return function( result ){
					card.data.downloading = false;
					if( !PhotoView._pending[ request.id ] ){
						return false;
					}
					PhotoView._pending[ request.id ] = false;
					
					var photo = PhotoView.ParseXml( result.responseXML );
					if( photo.siblings.prev && !carusel.getComponent( 'PhotoView_' + photo.siblings.prev ) ){
						PhotoView.AddEmptyCard( carusel, photo.siblings.prev, 0 );
					}
					if( photo.siblings.next && !carusel.getComponent( 'PhotoView_' + photo.siblings.next ) ){
						PhotoView.AddEmptyCard( carusel, photo.siblings.next, carusel.items.items.length + 1 );
					}
					
					card.update( '<img class="image_content" src="' + photo.url + '" />' );
					card.doLayout();
					PhotoView.SetDimentions( carusel, card, false, photo.size );
					card.data.rendered = true;
					if( chain ){
						PhotoView.GetSiblings( carusel, card, chain );
					}
				}
			}( carusel, card )
		});
		PhotoView._pending[ request.id ] = true;
		return true;
	},
	GetSiblings: function( carusel, card, steps ){
		var index = PhotoView.GetCardIndex( carusel, card );
		if( index === false ){
			return false;
		}
		
		//get Next, if exists
		if( index !== carusel.items.items.length - 1 ){
			nextid = carusel.items.items[ index + 1 ].id;
			var results = PhotoView.Get( nextid.split( '_' )[ 1 ], steps - 1 );
		}
		//get Previous, if exists
		if( index !== 0 ){
			previd = carusel.items.items[ index - 1 ].id;
			var result = PhotoView.Get( previd.split( '_' )[ 1 ], steps - 1 );
		}
		
	},
	RemoveAll: function(){
		Layout.getComponent( 'PhotoView' ).removeAll();
		PhotoView._pending = [];
	},
	OrientationChange: function(){
		var carusel = Layout.getComponent( 'PhotoView' );
		var items = carusel.items.items;
		for( var i = 0; card = items[ i ]; ++i ){
			if( card.data.rendered ){
				PhotoView.SetDimentions( carusel, card );
			}
		}
	},
	Init: function( id ){
		Navigation.Topbar.Fade( true, true );
		this.Get( id );
	},
	Destroy: function(){
		Navigation.Topbar.Fade( false, true );
		Navigation.Topbar.Show( true );
		this.RemoveAll();
	}
};


Navigation = {
	Topbar: {
		Refresh: function(){
			if( Navigation.callstack.length ){
				Layout.getComponent( 'NavigationBar' ).items.get( 0 ).show();
				Layout.getComponent( 'NavigationBar' ).items.get( 1 ).hide();
				Layout.getComponent( 'NavigationBar' ).items.get( 2 ).hide();
				Layout.getComponent( 'NavigationBar' ).items.get( 3 ).hide();
				return;
			}
			Layout.getComponent( 'NavigationBar' ).items.get( 0 ).hide();
			Layout.getComponent( 'NavigationBar' ).items.get( 1 ).show();
			Layout.getComponent( 'NavigationBar' ).items.get( 2 ).show();
			Layout.getComponent( 'NavigationBar' ).items.get( 3 ).show();
		},
		Hide: function( dolayout, animation ){
			Layout.getDockedComponent( 0 ).hide( animation );
			if( dolayout ){
				Navigation.Topbar.doLayout();
			}
		},
		Fade: function( fade, dolayout ){
			Navigation.Topbar.Hide( true );
			Navigation.Topbar.Show();
			if( fade ){
				Layout.getDockedComponent( 0 ).getEl().addClass( 'fade' );
			}
			else{
				Layout.getDockedComponent( 0 ).getEl().removeClass( 'fade' );
			}
		},
		Show: function( dolayout, animation ){
			Layout.getDockedComponent( 0 ).show( animation );
			if( dolayout ){
				Navigation.Topbar.doLayout();
			}
		},
		Toggle: function(){
			if( Navigation.Topbar.IsVisible() ){
				Navigation.Topbar.Hide( false, {type:'slide',direction:'up'} );
				return;
			}
			Navigation.Topbar.Show( false, {type:'slide',direction:'down'});
		},
		doLayout: function(){
			Layout.componentLayout.childrenChanged = true;
			Layout.doComponentLayout();
		},
		IsVisible: function(){
			return Layout.getDockedComponent( 0 ).isVisible();
		}
	},
	callstack: [],
	Back: function(){
		var togo = Navigation.callstack.pop();
		if( !togo ){
			return;
		}
		var anim = {
			direction: 'right',
			type: 'slide'
		};
		if( !togo.id ){
			Navigation.Gotomain( togo.type, anim );
			return;
		}
		Navigation.Goto( togo.type, togo.id, anim, true );
	},
	BlankCallstack: function(){
		Navigation.callstack = [];
	},
	Gotomain: function( type, anim ){
		var current = Layout.getActiveItem().id.split( '_' );
		Navigation.BlankCallstack();
		Navigation.Topbar.Refresh();
		
		this.runDestroy( current[ 0 ] );
		Layout.setCard( type );
		this.runInit( type );
		
		Layout.getActiveItem().store.load();
	},
	runInit: function( type, id ){
		if( window[ type ] && typeof( window[ type ].Init ) == 'function' ){
			window[ type ].Init( id );
		}
	},
	runDestroy: function( type ){
		if( window[ type ] && typeof( window[ type ].Destroy ) == 'function' ){
			window[ type ].Destroy();
		}
	},
	Goto: function( type, id, back ){
		var current = Layout.getActiveItem().id.split( '_' );
		if( back === true ){
			Navigation.callstack.pop();
		}
		else{
			Navigation.callstack.push({ type: current[ 0 ], id: current[ 1 ] });
		}
		
		this.runDestroy( current[ 0 ] );
		Layout.setCard( type );
		this.runInit( type, id );
		
		Navigation.Topbar.Refresh();
	}
};


Ext.setup({
	icon: 'icon.png',
	tabletStartupScreen: 'tablet_startup.jpg',
	phoneStartupScreen: 'phone_startup.jpg',
	glossOnIcon: true,
	fullscreen: true,
	monitorOrientation: true,
	onReady: function(){
		Ext.regModel( 'PhotoList', {
			fields: [
				{ name: 'id', mapping: '@id', type: 'string' },
				{ name: 'author', mapping: 'author name', type: 'string' },
				{ name: 'src', mapping: 'media@url', type: 'string' },
				{ name: 'comments', mapping: 'discussion@count', type: 'int' }
			]
		});
		Ext.regModel( 'PhotoView', {
			fields: [
				{ name: 'id', mapping: '@id', type: 'string' },
				{ name: 'title', mapping: 'title', type: 'string' },
				{ name: 'album_id', mapping: 'containedwithin album@id', type: 'string' },
				{ name: 'album_name', mapping: 'containedwithin album name', type: 'string' },
				{ name: 'author_name', mapping: 'author name', type: 'string' },
				{ name: 'author_gender', mapping: 'author gender', type: 'string' },
				{ name: 'author_avatarurl', mapping: 'author avatar media@url', type: 'string' },
				{ name: 'published', mapping: 'published', type: 'date', dateFormat: 'Y-m-d H:i:s' },
				{ name: 'src', mapping: 'media@url', type: 'string' },
				{ name: 'comments', mappiing: 'discussion@count', type: 'string' },
				{ name: 'favourites', mapping: 'favourites@count', type: 'string' }
			]
		});
		Ext.regModel( 'NewList', {
			fields: [
				{ name: 'id', mapping: '@id', type: 'string' },
				{ name: 'title', mapping: 'title,question', type: 'string' },
				{ name: 'published', mapping: 'published', type: 'date', dateFormat: 'Y-m-d H:i:s' },
				{ name: 'comments', mapping: 'discussion@count', type: 'int' },
				{ name: 'author_name', mapping: 'author name', type: 'string' },
				{ name: 'author_gender', mapping: 'author gender', type: 'string' },
				{ name: 'author_avatarurl', mapping: 'author avatar media@url', type: 'string' },
				{ name: 'type', mapping: '#', type: 'string' }
			]
		});
		window.Layout = new Ext.Panel({
			layout: 'card',
			fullscreen: true,
			monitorOrientation: true,
			listeners: {
				orientationchange: function( panel, orientation, width, height ){
					var activeitemid = Layout.getActiveItem().id;
					if( window[ activeitemid ] && window[ activeitemid ].OrientationChange ){
						window[ activeitemid ].OrientationChange();
					}
				}
			},
			defaults:{
				scroll: 'vertical',
				centered: true,
				loadingText: 'loading...',
				
			},
			dockedItems: [ new Ext.Toolbar({
				ui: 'dark',
				dock: 'top',
				id: 'NavigationBar',
				items: [{
					text: 'Πίσω',
					cls: 'back',
					dockposition: 'top',
					hidden: true,
					handler: Navigation.Back,
					ui: 'back'
				}, {
					text: 'Εικόνες',
					handler: function(){
						Navigation.Gotomain( 'PhotoList' );
					}
				}, {
					text: 'Νέα',
					handler: function(){
						Navigation.Gotomain( 'NewList' );
					}
				}, {
					text: 'Προφίλ',
					handler: function(){
//						Navigation.Gotomain( 'OwnProfile' );
					}
				}, {
					xtype: 'spacer'
				},{
					text: 'Chat',
					ui: 'action',
					handler: function(){
//						Navigation.Goto( 'Chat' );
					}
				}]
			})],
			items: [
			{
				id: 'PhotoList',
				xtype: 'dataview',
				itemSelector: 'li.photo',
				store: new Ext.data.Store({
					model: 'PhotoList',
					autoLoad: true,
					proxy: {
						type: 'ajax',
						url: window.base + '?resource=photo&method=listing',
						reader: {
							type: 'xml',
							root: 'photo'
						}
					}
				}),
				tpl: new Ext.XTemplate(
					'<ul>' + 
						'<tpl for=".">' + 
							'<li class="photo" id="photo_{id}">'+
								'<img id="{id}" src="{src}" alt="{author}" title="{author}"/>' +
								'<tpl if="comments != 0">' +
									'<div class="commentnum">{comments}</div>' +
								'</tpl>' +
							'</li>' +
						'</tpl>' +
					'</ul>',
					{
						compiled: true
					}
				),
				listeners: {
					itemtap: function( list, index ){
						PhotoView.RemoveAll();
						Navigation.Goto( 'PhotoView', list.all.elements[ index ].id.split( '_' )[ 1 ] );
					}
				}
			}, 
			{
				id: 'PhotoView',
				xtype: 'carousel',
				direction: 'horizontal',
				indicator: false,
				scroll: 'none',
				style: 'background: black;',
				monitorOrientation: false,
				
				listeners: {
					cardswitch: function( carusel, newcard ){
						PhotoView.Get( newcard.id.split( '_' )[ 1 ] );
						if( newcard.data.rendered ){
							PhotoView.SetDimentions( carusel, newcard );
						}
					},
					tap: function(){
						Navigation.Topbar.Toggle();
					},
					afterrender: function( carusel ){
						carusel.mon( carusel.getEl(), {
							tap: function(){
								carusel.fireEvent( 'tap' );
							}
						});
					}
				},
				defaults: {
					scroll: 'vertical',
					html:'<div class="photo_loading">' +
							'<img src="images/photoloading.gif" />' +
						'</div>',
					data: {
						rendered: false,
						downloading: false
					},
				},
				items: []
			},
			{
				id: 'NewList',
				xtype: 'dataview',
				itemSelector: 'li.new',
				store: new Ext.data.Store({
					model: 'NewList',
					autoLoad: true,
					proxy: {
						type: 'ajax',
						url: window.base + '?resource=news&method=listing',
						reader: {
							type: 'xml',
							root: 'poll,journal'
						}
					},
					listeners: {
						read: function( store ){
							store.sort( 'published', 'DESC' );
						}
					}
				}),
				layout: 'vbox',
				tpl: new Ext.XTemplate(
					'<ul>' +
						'<tpl for=".">' +
							'<li onclick="Navigation.Goto( \'{type}\', \'{id}\' )" class="new {type}" id="{id}">' +
								'<img src="{author_avatarurl}" alt="{author_name}" />' +
								'<div class="details">' +
									'<div class="top">' +
										'<span class="username">{author_name}</span>' +
										'<span class="time">{[ this.greekDate( values.published ) ]}</span>' +
									'</div>' +
									'<div class="title">{title}</div>' +
								'</div>' +
								'<div class="eof"></div>' +
							'</li>' +
						'</tpl>' +
					'</ul>',
					{
						compiled: true,
						greekDate: function( date ){
							return greekDateDiff( dateDiff( dateToString( date ), Now ) );
						}
					}
				)
			}, 
			{
				title: 'profile',
				html: '<h1>profile</h1>'
			}, 
			{
				title: 'Chat',
				html: '<h1>chat here</h1>'
			}]
		});
	}
});
