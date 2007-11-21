var WYSIWYG = {
    Create: function ( which, fieldname ) {
        which.style.backgroundColor = '#ccc';
        setTimeout( function () {
            WYSIWYG.CreateReal( which, fieldname );
        } , 1000 );
    },
    GetDocument: function ( iframe ) {
        if ( iframe.contentWindow ) {
            return iframe.contentWindow.document;
        }
        if ( iframe.contentDocument ) {
            return iframe.contentDocument;
        }
        return false;
    },
    CreateReal: function ( which, fieldname ) {
        var doc = WYSIWYG.GetDocument( which );
        if ( doc === false ) {
            alert( 'WYSIWYG is not supported by your browser' );
            return;
        }
        entrytext = which.previousSibling.firstChild.nodeValue;
        doc.body.innerHTML = entrytext;
        doc.designMode = 'on';
        frm = which;
        while (frm.nodeName != 'form') {
            frm = frm.parentNode;
            if (frm === null) {
                alert( 'WYSIWYG elements should only be called within HTML <form>' );
                return;
            }
        }
        scfield = document.createElement( 'input' );
        scfield.type = 'hidden';
        scfield.name = fieldname;
        scfield.value = '';
        frm.appendChild( scfield );
        var oldonsubmit = frm.onsubmit? frm.onsubmit: function () {};
        frm.onsubmit = function ( sc, ifdoc ) {
            return function () {
                oldonsubmit();
                sc.value = ifdoc.body.innerHTML;
            };
        }( scfield, doc );
        which.style.backgroundColor = 'white';
    }
};
