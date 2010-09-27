Ext.override( Ext.data.XmlReader, {
    createAccessor: function() {
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

        return function(key) {
            var fn;

            if (key == this.totalProperty) {
                fn = function(root, defaultValue) {
                    var value = selectValue(key, root, defaultValue);
                    return parseFloat(value);
                };
            }

            else if (key == this.successProperty) {
                fn = function(root, defaultValue) {
                    var value = selectValue(key, root, true);
                    return (value !== false && value !== 'false');
                };
            }

            else {
                fn = function(root, defaultValue) {
                    return selectValue(key, root, defaultValue);
                };
            }

            return fn;
        };
    }(),
});

Ext.anims.fadeTo = new Ext.Anim({
	before: function(el) {
		
		var fromOpacity = this.prevOp || el.getStyle( 'opacity' ) || 1,
			toOpacity = this.prevOp || el.getStyle( 'opacity' ) || 1,
			curZ = el.getStyle('z-index') == 'auto' ? 0 : el.getStyle('z-index'),
			zIndex = curZ;
		if( !this.prevOp ){
			this.prevOp = el.getStyle( 'opacity' ) || 1;
		}
		if (this.out) {
			toOpacity = 0;
		} else {
			zIndex = curZ + 1;
			fromOpacity = 0;
		}

		this.from = {
			'opacity': fromOpacity,
			'z-index': zIndex
		};
		this.to = {
			'opacity': toOpacity,
			'z-index': zIndex
		};
	}
});

Ext.override( Ext.form.FormPanel, {
    submit : function(options) {
        var form = this.el.dom || {},
            O = Ext.apply({
               url : this.url || form.action,
               submitDisabled : false,
               method : form.method || 'post',
               autoAbort : false,
               params : null,
               waitMsg : null,
               headers : null,
               success : null,
               failure : null
            }, options || {}),
            formValues = this.getValues(this.standardSubmit || !O.submitDisabled);
        
        if (this.standardSubmit) {
            if (form) {
                if (O.url && Ext.isEmpty(form.action)) {
                    form.action = O.url;
                }
                form.method = (O.method || form.method).toLowerCase();
                form.submit();
            }
            return null;
        }
        if (this.fireEvent('beforesubmit', this, formValues, options ) !== false) {
            if (O.waitMsg) {
                this.showMask(O.waitMsg);
            }
            
            return Ext.Ajax.request({
                url     : O.url,
                method  : O.method,
                rawData : Ext.urlEncode(Ext.apply(
                    Ext.apply({},this.baseParams || {}),
                    O.params || {},
                    formValues
                  )),
                autoAbort : O.autoAbort,
                headers  : Ext.apply(
                   {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'},
                    O.headers || {}),
                scope    : this,
                callback : function(options, success, response) {
                     var R = response;
                     this.hideMask();   
                        
					if (success) {
						if( !this.type || this.type.toLowerCase() == 'xml' ){
							R = R.responseXML;
						}
						else if( this.type.toLowerCase() == 'json' ){
							R = Ext.decode(R.responseText);
							success = !!R.success;
						}
						if (success) {
							if (typeof O.success == 'function') {
								O.scope ? O.success.call(O.scope, this, R) : O.success(this, R);
							}
							this.fireEvent('submit', this, R);
							return;
						}
					}
                    if (typeof O.failure == 'function') {
                        O.scope ? O.failure.call(O.scope, this, R) : O.failure(this, R);
                    }
                    this.fireEvent('exception', this, R);
                }
            });
        }
    }
});