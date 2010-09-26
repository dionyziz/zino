PhotoView = {
	_threshold: 0,
	_pending: [],
	Tag: {
		Open: function(){},
	},
	Rename: {
		Open: function(){},
	},
	Comments: {
		Show: function(){},
	},
	Like: function(){
		var id = Ext.getCmp( 'PhotoView' ).getActiveItem().id.split( '_' )[ 1 ];
		Ext.Ajax.request({
			method: "POST",
			url: window.base + '?resource=favourite&method=create',
			params: {
				typeid: 2,
				itemid: id
			},
			success: function(){
				Ext.getCmp( 'PhotoLike' ).disable().addClass( 'liked' );
			},
			failure: function(){
				Ext.Msg.alert( 'Ωπ...', 'Υπήρξε κάποιο πρόβλημα.' );
			}
		});
	},
	Delete: function(){},
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
			title: jQuery( xml ).find( 'social > photo > title' ).text(),
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
			favourites: {
				totalCount: selectValue( 'favourites@count', xml ),
				users: []
			},
			comments: {
				totalCount: selectValue( 'discussion@count', xml ),
				items: Ext.DomQuery.select( 'discussion', xml )
			},
			siblings: {
				prev: selectValue( 'photos photo[navigation=previous]@id', xml ),
				next: selectValue( 'photos photo[navigation=next]@id', xml )
			}
		};
		var favs = Ext.DomQuery.select( 'favourites user name', xml );
		for( var i = 0; i < favs.length; ++i ){
			photo.favourites.users[ i ] = favs[ i ].textContent;
		}
		window.photo = photo;
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
			img.setWidth( screenD.width );
			img.setHeight( 'auto' );
			img.setStyle( 'margin-top', ( screenD.height - imageD.height * ( screenD.width / imageD.width ) ) / 2 + 'px' );
		}
		else{
			img.setWidth( 'auto' );
			img.setHeight( screenD.height );
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
	BeforeOrientationChange: function(){
		Navigation.Topbar.Hide( false );
		Navigation.Bottombar.Hide( false );
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
	/*	Navigation.Topbar.Hide( false );
		Navigation.Bottombar.Hide( false );
		Layout.ForceDoLayout();
		Navigation.Topbar.Show( false );
		
		Navigation.Bottombar.Show( false );
*/		Navigation.Topbar.FadeOut();
		this.Get( id );
	},
	Destroy: function(){
/*		Navigation.Topbar.Show( false );
		
		Navigation.Bottombar.Show( false );
		Layout.ForceDoLayout();
*/		Navigation.Topbar.FadeIn();
		this.RemoveAll();
	}
};

Navigation = {
	Togglebars: function(){
		if( this.Topbar.IsVisible() ){
			this.Topbar.Hide( true );
			this.Bottombar.Hide( true );
		}
		else{
			Navigation.Topbar.Show( true );
			Navigation.Bottombar.Show( true );
		}
	},
	Bottombar: {
		Hide: function( animation ){
			if( animation === true ){
				animation = {type:'slide',direction:'down'};
			}
			Ext.getCmp( 'PhotoViewBar' ).hide( animation );
		},
		Show: function( animation ){
			if( animation === true ){
				animation = {type:'slide',direction:'up'};
			}
			Ext.getCmp( 'PhotoViewBar' ).show( animation );
		},
		Toggle: function(){
			if( Navigation.Bottombar.IsVisible() ){
				Navigation.Bottombar.Hide( true );
				return;
			}
			Navigation.Bottombar.Show( true );
		},
		IsVisible: function(){
			return Ext.getCmp( 'PhotoViewBar' ).isVisible();
		}
	},
	Topbar: {
		Refresh: function(){
			var bar =  Ext.getCmp( 'NavigationBar' );
			if( Navigation.callstack.length ){
				bar.items.get( 0 ).show();
				bar.items.get( 1 ).hide();
				bar.items.get( 2 ).hide();
				bar.items.get( 3 ).hide();
				return;
			}
			bar.items.get( 0 ).hide();
			bar.items.get( 1 ).show();
			bar.items.get( 2 ).show();
			bar.items.get( 3 ).show();
		},
		Hide: function( animation ){
			if( animation === true ){
				animation = {type:'slide',direction:'up'};
			}
			Ext.getCmp( 'NavigationBar' ).hide( animation );
		},
		Show: function( animation ){
			if( animation === true ){
				animation = {type:'slide',direction:'down'};
			}
			Ext.getCmp( 'NavigationBar' ).show( animation );
		},
		Toggle: function(){
			if( Navigation.Topbar.IsVisible() ){
				Navigation.Topbar.Hide( true );
				return;
			}
			Navigation.Topbar.Show( true );
		},
		IsVisible: function(){
			return Ext.getCmp( 'NavigationBar' ).isVisible();
		},
		FadeOut: function(){
			Ext.getCmp( 'NavigationBar' ).addClass( 'fade' );
		},
		FadeIn: function(){
			Ext.getCmp( 'NavigationBar' ).removeClass( 'fade' );
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
		Navigation.Goto( togo.type, togo.id, true );
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
	Gotomain: function( type ){
		this.Goto( type, null, true, false );
	},
	Goto: function( type, id, back, animation ){
		var current = Layout.getActiveItem().id.split( '_' );
		if( back === true ){
			var anim = {
				type: 'slide',
				direction: 'right'
			};
		}
		else{
			Navigation.callstack.push({ type: current[ 0 ], id: current[ 1 ] });
			var anim = {
				type: 'slide',
				direction: 'left'
			};
		}
		Navigation.Topbar.Refresh();
		if( animation === false ){
			anim = false;
		}
		setTimeout( function(){
			Navigation.runDestroy( current[ 0 ] );
		}, 300 );
		Layout.setCard( type, anim );
		this.runInit( type, id );
		
		if( Layout.getActiveItem().store ){
			Layout.getActiveItem().store.load();
		}
	},
	StartSession: function(){
		Layout.setCard( 'PhotoList', false );
		Layout.show();
		Ext.getCmp( 'NavigationBar' ).show();
		Ext.getCmp( 'PhotoList' ).store.load();
	}
};


Ext.setup({
	icon: 'http://static.zino.gr/touch/icon.png',
	tabletStartupScreen: 'http://static.zino.gr/touch/wallpapers/zino_black_768x1004jpg',
	phoneStartupScreen: 'http://static.zino.gr/touch/wallpapers/zino_black_320x460.jpg',
	glossOnIcon: true,
	fullscreen: true,
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
			id: 'Layout',
			fullscreen: true,
			monitorOrientation: true,
			hidden: true,
			listeners: {
				beforeorientationchange: function(){
					this.getActiveItem().fireEvent( 'beforeorientationchange' );
				},
				orientationchange: function( p, o ){
					
					Ext.getBody().removeClass( 'landscape' );
					Ext.getBody().removeClass( 'portrait' );
					Ext.getBody().addClass( o );
					
					this.getActiveItem().fireEvent( 'orientationchange' );
				},
				beforerender: function(){
					Ext.getBody().addClass( 'loading' );
					Ext.getBody().addClass( Ext.getOrientation() );
					if( navigator.standalone ){
						Ext.getBody().addClass( 'standalone' );
					}
				}
			},
			defaults:{
				scroll: 'vertical',
				centered: true,
				loadingText: 'loading...',
				
			},
			dockedItems: [ new Ext.Toolbar({
				overlay: true,
				ui: 'dark',
				dock: 'top',
				hidden: true,
				id: 'NavigationBar',
				items: [{
					text: 'Πίσω',
					cls: 'back',
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
						Navigation.Gotomain( 'OwnProfile' );
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
			new Ext.form.FormPanel({ //Login
				type: 'xml',
				id: 'LoginForm',
				method: "POST",
				url: window.base + '?resource=session&method=create',
				items:[{
					cls: 'h2',
					html: '<h2>Καλωσήρθες στο Zino</h2>',
				}, {
					cls: 'field',
					xtype: 'textfield',
					hasFocus: true,
					label: 'Ψευδώνυμο',
					name: 'username',
					required: true,
				}, {
					cls: 'field',
					xtype: 'passwordfield',
					label: 'Κωδικός',
					name: 'password',
					required: true
				}, {
					cls: 'login',
					xtype: 'button',
					text: 'Είσοδος',
					ui: 'confirm',
					width: 120,
					handler: function(){
						Ext.getCmp( 'LoginForm' ).submit({
							type: 'xml',
							success: function(){
								Navigation.Gotomain( 'PhotoList' );
								Layout.getDockedComponent( 'NavigationBar' ).show();
							}
						});
					}
				}, {
					cls: 'eof'
				}]
			}),
			{ //PhotoList
				id: 'PhotoList',
				xtype: 'dataview',
				itemSelector: 'li.photo',
				store: new Ext.data.Store({
					model: 'PhotoList',
				//	autoLoad: true,
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
						Navigation.Goto( 'PhotoView', list.all.elements[ index ].id.split( '_' )[ 1 ] );
					}
				}
			}, 
			{ //PhotoView
				id: 'PhotoView',
				xtype: 'carousel',
				direction: 'horizontal',
				indicator: false,
				scroll: 'none',
				style: 'background: black;',
				dockedItems: [ new Ext.Toolbar({
					overlay: true,
					ui: 'dark',
					dock: 'bottom',
					cls: 'fade',
					id: 'PhotoViewBar',
					listeners: {
						tap: function(){
							Ext.getCmp( 'PhotoView' ).data.stopPropagation = true;
						},
						afterrender: function( bar ){
							bar.mon( bar.getEl(), {
								tap: function(){
									bar.fireEvent( 'tap' );
								}
							});
						}
					},
					items: [{
						text: 'actions',
						id: 'PhotoActions',
						handler: function(){
							if( !this.actions ){
								this.PhotoAction = new Ext.ActionSheet({
									id: 'PhotoAction',
									defaults: {
										scope: this
									},
									items: [{
										text: 'Το αγαπώ',
										ui: 'confirm',
										handler: PhotoView.Like
									}, {
										text: 'Γνωρίζω κάποιον',
										handler: PhotoView.Tag.Open
									}, {
										text: 'Σχόλια',
										handler: PhotoView.Comments.Show
									}, {
										text: 'Μετονομασία',
										handler: PhotoView.Rename.Open
									}, {
										text: 'Διαγραφή',
										ui: 'decline',
										handler: PhotoView.Delete,
									}, {
										text: 'Κλείσιμο',
										handler: function(){
											this.PhotoAction.hide();
										}
									}]
								});
							}
							this.PhotoAction.show();
						}
					}, {
						text: '',
						id: "PhotoLike",
						icon: 'http://static.zino.gr/touch/like_grey.png',
						disabledicon: 'http://static.zino.gr/touch/like.png',
						handler: PhotoView.Like,
						listeners: {
							disable: function(){
								this.el.select( 'img' ).setStyle( 'background-image', 'url("' + this.disabledicon + '")' );
							},
							enable: function(){
								this.el.select( 'img' ).setStyle( 'background-image', 'url("' + this.icon + '")' );
							},
							
						}
					}]
				})],
				listeners: {
					beforeorientationchange: function(){
						Navigation.Topbar.Show( false );
						Navigation.Bottombar.Show( false );
					},
					orientationchange: function(){
						PhotoView.OrientationChange();
						Navigation.Topbar.Hide( false );
						Navigation.Bottombar.Hide( false );
					},
					cardswitch: function( carusel, newcard ){
						PhotoView.Get( newcard.id.split( '_' )[ 1 ] );
						if( newcard.data.rendered ){
							PhotoView.SetDimentions( carusel, newcard );
						}
					},
					tap: function(){
						Navigation.Togglebars();
					},
					afterrender: function( carusel ){
						carusel.mon( carusel.getEl(), {
							tap: function(){
								carusel.data.stopPropagation || carusel.fireEvent( 'tap' );
								carusel.data.stopPropagation = false;
							}
						});
					}
				},
				data: {},
				defaults: {
					scroll: 'vertical',
					html:'<div class="photo_loading">' +
							'<img src="http://static.zino.gr/touch/photoloading.gif" />' +
						'</div>',
					data: {
						rendered: false,
						downloading: false
					},
				},
				items: []
			},
			{ //NewList
				id: 'NewList',
				xtype: 'dataview',
				itemSelector: 'li.new',
				store: new Ext.data.Store({
					model: 'NewList',
			//		autoLoad: true,
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
							'<li class="new {type}" id="{id}">' +
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
			{ //Profile
				title: 'profile',
				html: '<h1>profile</h1>'
			}, 
			{ //Chat
				title: 'Chat',
				html: '<h1>chat here</h1>'
			}]
		});
		
		Ext.Ajax.request({ //check logged in
			url: window.base + '?resource=session&method=view',
			success: function( result ){
				
				var user = Ext.DomQuery.selectNode( 'user', result.responseXML );
				if( !user ){
					window.User = false;
					Layout.show();
					return;
				}
				window.User = user.getAttribute( 'id' );
				Navigation.StartSession();
			}
		});
	}
});
