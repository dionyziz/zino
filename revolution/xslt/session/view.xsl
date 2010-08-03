<xsl:template match="/social[@resource='session' and @method='view']">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="el" lang="el">
        <head>
            <title>Zino</title>
            <base>
                <xsl:attribute name="href"><xsl:value-of select="@generator" />/</xsl:attribute>
            </base>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
            <link href="css/loggedout.css" rel="stylesheet" type="text/css" />
            <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
        </head>
        <body>
            <div class="card">
                <h1><img src="images/loggedout/logo-bubble.png" alt="Zino" /></h1>
                <p>
                    Το Zino είναι εσύ και η παρέα σου
                    ζωντανά online − είσαι μέσα?
                </p>
                <div class="register">
                    <form>
                        <span>Διάλεξε ένα ψευδώνυμο:</span>
                        <div>
                            <input class="submit" type="submit" value="" tabindex="5" />
                            <input class="text" type="text" name="username" tabindex="4" />
                        </div>
                    </form>
                </div>
                <div class="login">
                    <h2>Έχεις ήδη zino?</h2>
                    <form method="POST" action="session/create" name="loginform">
                        <label>Ψευδώνυμο</label>
                        <input class="text" type="text" tabindex="1" name="username" />
                        <label>Κωδικός</label>
                        <input class="text" type="password" tabindex="2" name="password" />
                        <input class="submit" type="submit" value="" tabindex="3" />
                    </form>
                </div>
            </div>
            <div class="copy">
                &#0169; 2010 Kamibu
                <ul>
                    <li><a href="">Πρόσβαση χωρίς είσοδο</a></li>
                    <li><a href="">Πληροφορίες</a></li>
                    <li><a href="">Διαφήμιση</a></li>
                    <li><a href="">API</a></li>
                    <li><a href="">Νομικά</a></li>
                </ul>
            </div>
            <script type="text/javascript">
                var loginsuccess = false;
                //$( function() { $( '.login > form' ).find( 'input' )[ 0 ].focus(); } );
                $( '.login > form' ).submit( function () {
                    if ( !loginsuccess ) {
                        $.post( 'session/create', {
                            username: $( 'input:eq(0)', this ).val(),
                            password: $( 'input:eq(1)', this ).val()
                        }, function ( res ) {
                            if ( $( res ).find( 'operation result' ).text() == 'SUCCESS' ) {
                                 loginsuccess = true;
                                $( '.login > form' ).submit();
                            }
                            else {
                                alert( 'Λάθος όνομα/κωδικός' );
                                document.body.style.cursor = '';
                                $( '.login > form' ).css( 'opacity', 1 ).find( 'input' ).attr( 'disabled', '' ).index( 1 ).focus();
                            }
                        }, 'xml' );
                        document.body.style.cursor = 'wait';                        
                        $( this ).css( 'opacity', 0.5 ).blur().find( 'input' ).attr( 'disabled', 'disabled' );
                        return false;
                    }
                    $( '.login > form' ).find( 'input' ).attr( 'disabled', '' );
                    return true;
                } );
            </script>
        </body>
    </html>
</xsl:template>
