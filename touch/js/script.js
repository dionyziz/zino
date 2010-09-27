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
		//add the liked class immediatly, for visual purposes
		Ext.getCmp( 'PhotoLike' ).disable().addClass( 'liked' );
		Ext.Ajax.request({
			method: "POST",
			url: window.base + '?resource=favourite&method=create',
			params: {
				typeid: 2,
				itemid: id
			},
			success: function(){
				
				Ext.getCmp( 'PhotoView' ).getActiveItem().data.photo.favourites.users.push( Session.User.name );
			},
			failure: function(){
				Ext.getCmp( 'PhotoLike' ).disable().removeClass( 'liked' );
				Ext.Msg.alert( 'Ωπ...', 'Υπήρξε κάποιο πρόβλημα.' );
			}
		});
	},
	Delete: function(){
		
	},
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
		var photo = {
			id: $( xml ).find( 'social > photo' ).attr( 'id' ),
			title: $( xml ).find( 'social > photo > title' ).text(),
			album: {
				id: $( xml ).find( 'social > photo album' ).attr( 'id' ),
				name: $( xml ).find( 'social > photo album name' ).text(),
			},
			author: {
				name: $( xml ).find( 'social > photo > author > name' ).text(),
				gender: $( xml ).find( 'social > photo > author > gender' ).text(),
				avatarurl: $( xml ).find( 'social > photo > author > avatar > media' ).attr( 'url' ),
			},
			published: $( xml ).find( 'social > photo > published' ).text(),
			url: $( xml ).find( 'social > photo > media' ).attr( 'url' ),
			size: {
				width: parseInt( $( xml ).find( 'social > photo > media' ).attr( 'width' ) ),
				height: parseInt( $( xml ).find( 'social > photo > media' ).attr( 'height' ) )
			},
			favourites: {
				totalCount: $( xml ).find( 'favourites' ).attr( 'count' ),
				users: []
			},
			comments: {
				totalCount: $( xml ).find( 'discussion' ).attr( 'count' ),
				items: $( xml ).find( 'discussion' )
			},
			siblings: {
				prev: $( xml ).find( 'photos photo[navigation=previous]' ).attr( 'id' ),
				next: $( xml ).find( 'photos photo[navigation=next]' ).attr( 'id' )
			}
		};
		$( xml ).find( 'favourites user name' ).each( function( i ){
			photo.favourites.users[ i ] = $( this ).text().toLowerCase();
		});
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
	RefreshActions: function( photo ){
		if( Session.User && Ext.getCmp( 'PhotoView' ).getActiveItem().id.split( '_' )[ 1 ] == photo.id ){
			if( photo.favourites.users.indexOf( Session.User.name ) != -1 ){
				Ext.getCmp( 'PhotoLike' ).disable();
			}
			else{
				Ext.getCmp( 'PhotoLike' ).enable();
			}
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
			PhotoView.RefreshActions( card.data.photo );
			return false;
		}
		if( card.data.downloading ){
			//setTimeout( function(){ PhotoView.Get( id, chain ); }, 300 );
			return false;
		}
		card.data.downloading = true;
		Ext.getCmp( 'PhotoViewBar' ).disable();
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
					card.data.photo = photo;
					if( photo.siblings.prev && !carusel.getComponent( 'PhotoView_' + photo.siblings.prev ) ){
						PhotoView.AddEmptyCard( carusel, photo.siblings.prev, 0 );
					}
					if( photo.siblings.next && !carusel.getComponent( 'PhotoView_' + photo.siblings.next ) ){
						PhotoView.AddEmptyCard( carusel, photo.siblings.next, carusel.items.items.length + 1 );
					}
					
					Ext.getCmp( 'PhotoViewBar' ).enable();
					
					PhotoView.RefreshActions( photo );
					
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

Session = {
	Login: {
		Do: function(){
			if( Session.User ){
				return true;
			}
			Ext.getCmp( 'LoginForm' ).submit({
				type: 'xml',
				success: function( scope, result ){
					if( $( result ).find( 'result' ).text() == 'SUCCESS' ){
						Session.Login.Success();
						return true;
					}
					Session.Login.Failure( 
						$( result ).find( 'cause' ).text().toLowerCase(), 
						$( result ).find( 'user name' ).text() 
					);
				}
			});
		},
		Success: function(){
			Session.CheckLogin( function(){
				Navigation.StartSession();
			});
		},
		Failure: function( cause, name ){
			if( cause == 'name' ){
				Ext.getCmp( 'LoginError' ).update( 'Το ψευδόνυμο που πληκτρολόγησες δεν υπάρχει' );
			}
			else{
				$.get( window.base + 'users/' + name, function( result ){
					Ext.getCmp( 'LoginUsername' ).hide();
					Ext.getCmp( 'LoginUserdetails' ).show();
					Ext.getCmp( 'LoginUserdetails' ).update({
						avatarurl: 'http://images2.zino.gr/media/3890/256907/256907_100.jpg', //$( result ).find( 'social > user > avatar > media' ).attr( 'url' ),
						username: $( result ).find( 'social > user > name' ).text()
					});
					
					Ext.getCmp( 'LoginError' ).update( 'Ο κωδικός πρόσβασης είναι λανθασμένος' );
				});
			}
		}
	},
	CheckLogin: function( callback ){
		Ext.Ajax.request({
			url: window.base + '?resource=session&method=view',
			success: function( result ){
				result = result.responseXML;
				if( $( result ).find( 'user' ).length ){
					Session.User = {
						id: $( result ).find( 'user' ).attr( 'id' ),
						name: $( result ).find( 'social' ).attr( 'for' )
					};
					if( callback ){
						callback( Session.User );
					}
					return;
				}
				Session.User = false;
				if( callback ){
					callback( false );
				}
			}
		});
	}
}

Ext.setup({
	icon: 'http://static.zino.gr/touch/icon.png',
	tabletStartupScreen: 'http://static.zino.gr/touch/wallpapers/zino_black_768x1004jpg',
	phoneStartupScreen: 'http://static.zino.gr/touch/wallpapers/zino_black_320x460.jpg',
	glossOnIcon: true,
	fullscreen: true,
	statusBarStyle: 'black-translucent',
	onReady: function(){
		Ext.regModel( 'PhotoList', {
			fields: [
				{ name: 'id', mapping: '@id', type: 'string' },
				{ name: 'author', mapping: 'author name', type: 'string' },
				{ name: 'src', mapping: 'media@url', type: 'string' },
				{ name: 'comments', mapping: 'discussion@count', type: 'int' }
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
					id: 'LoginUsername',
					cls: 'field',
					xtype: 'textfield',
					hasFocus: true,
					label: 'Ψευδώνυμο',
					name: 'username',
					required: true,
				}, {
					id: 'LoginUserdetails',
					cls: 'field',
					height: 52,
					tpl: new Ext.XTemplate(
						'<img src="{avatarurl}" alt="{username}" />' + 
						'<div>{username}</div>'
					),
					hidden: true,
				}, {
					cls: 'field',
					xtype: 'passwordfield',
					label: 'Κωδικός',
					name: 'password',
					required: true,
				}, {
					id: 'LoginError',
					cls: 'error field',
					height: 60,
					html: '<span class="signup">Δεν έχεις λογαριασμό; <a href="">Δημιούργησε έναν</a></span>'
				}, {
					cls: 'login',
					xtype: 'button',
					text: 'Είσοδος',
					ui: 'confirm',
					width: 120,
					handler: Session.Login.Do
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
							if( !this.PhotoAction ){
								this.PhotoAction = new Ext.ActionSheet({
									id: 'PhotoAction',
									hideOnMaskTap: true,
									defaults: {
										scope: this
									},
									items: [{
										text: 'Γνωρίζω κάποιον',
										handler: PhotoView.Tag.Open
									}, {
										text: 'Μετονομασία',
										handler: PhotoView.Rename.Open
									}, {
										text: 'Διαγραφή',
										ui: 'decline',
										handler: PhotoView.Delete,
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
					cls: 'card',
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
		
		Session.CheckLogin( function( user ){
			$( '.ph' ).remove();
			window.user = user;
			if( !user ){
				Layout.show();
				return;
			}
			Navigation.StartSession();
		} );
	}
});
