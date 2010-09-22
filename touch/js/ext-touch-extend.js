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