<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="utf-8" indent="yes" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" />
    <xsl:template match="/social">
        <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="el" lang="el">
            <head>
                <title>Zino</title>
                <base>
                    <xsl:attribute name="href"><xsl:value-of select="@generator" />/</xsl:attribute>
                </base>
                <link type="text/css" href="css/frontpage.css" rel="stylesheet" />
                <link type="text/css" href="http://static.zino.gr/css/emoticons.css" rel="stylesheet" />
            </head>
            <body>
                <div class="highlight-content">
                    <xsl:apply-templates />
                </div>
                <script type="text/javascript" src="http://www.zino.gr/js/date.js"></script>
                <script type="text/javascript" src="http://code.jquery.com/jquery-1.4.2.min.js"></script>
                <script type="text/javascript">
                    var User = '<xsl:value-of select="@for" />';
                    $( 'div.time' ).each( function () {
                        this.innerHTML = greekDateDiff( dateDiff( this.innerHTML, '<xsl:value-of select="@generated" />' ) );
                    } );

                    var favourites = $( 'div.love' );
                    for ( i in favourites ) {
                        if ( favourites[ i ] == User ) {
                            // I have already fav'ed this
                            break;
                        }
                    }

                    $( 'a.love' ).show();
                    if ( $( 'a.love' ).length ) {
                        $( 'a.love' )[ 0 ].onclick = function () {
                            $.post( 'favourite/create', { typeid: 2, itemid: <xsl:value-of select="/social/entry[1]/@id" /> } );
                            this.href = '';
                            this.style.cursor = 'default';
                            this.onclick = function () { return false; };
                            this.innerHTML = '&#9829; ' + User;
                            var div = document.createElement( 'div' );
                            div.style.position = 'absolute';
                            div.style.fontSize = '400%';
                            div.innerHTML = '&#9829;';
                            div.style.top = this.offsetTop - 40 + 'px';
                            div.style.left = this.offsetLeft - 10 + 'px'; 
                            div.style.color = 'red';
                            document.body.appendChild( div );
                            $( div ).animate( {
                                top: this.offsetTop - 100,
                                opacity: 0
                            }, 'slow' );
                            this.blur();
                            return false;
                        };
                    }
                </script>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
