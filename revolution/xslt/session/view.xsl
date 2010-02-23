<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="utf-8" indent="yes" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" />
    <xsl:template match="/social">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="el" lang="el">
        <head>
            <title>Zino</title>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
            <link href="css/loggedout.css" rel="stylesheet" type="text/css" />
            <script type="text/javascript" src="http://www.zino.gr/js/jquery.js"></script>
        </head>
        <body>
            <div class="card">
                <h1><img src="images/loggedout/logo-bubble.png" alt="Zino" /></h1>
                <p>
                    Το Zino είναι εσύ και η παρέα σου
                    ζωντανά online − είσαι μέσα?
                </p>
                <form class="register">
                    <span>Διάλεξε ένα ψευδώνυμο:</span>
                    <div>
                        <input class="submit" type="submit" value="Μπες τώρα" />
                        <input class="text" type="text" name="username" />
                    </div>
                </form>
                <div class="login">
                    <h2>Έχεις ήδη zino?</h2>
                    <form>
                        <label>Ψευδώνυμο</label>
                        <input class="text" type="text" />
                        <label>Κωδικός</label>
                        <input class="text" type="password" />
                        <input class="submit" type="submit" value="Είσοδος" />
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
                $( 'form' )[ 1 ].onsubmit = function () {
                    $.post( 'session/create', {
                        username: this.getElementsByTagName( 'input' )[ 0 ].value,
                        password: this.getElementsByTagName( 'input' )[ 1 ].value
                    }, function ( res ) {
                        if ( $( res ).find( 'operation result' ).text() == 'SUCCESS' ) {
                            // alert( 'Login successful!' );
                            window.location.href = 'photos';
                        }
                        else {
                            alert( 'Login failed!' );
                        }
                    }, 'xml' );
                    document.body.style.cursor = 'wait';
                    this.style.opacity = '0.5';
                    this.blur();
                    return false;
                };
            </script>
        </body>
    </html>
    </xsl:template>
</xsl:stylesheet>
