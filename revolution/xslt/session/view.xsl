<xsl:template match="/social[@resource='session' and @method='view']">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="el" lang="el">
        <head>
            <title>Zino</title>
            <base>
                <xsl:attribute name="href"><xsl:value-of select="@generator" />/</xsl:attribute>
            </base>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
            <link href="css/loggedout.css" rel="stylesheet" type="text/css" />
            <link href="css/panel.css" rel="stylesheet" type="text/css" />
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
                    <iframe id="iframe" src="login.html" style="height: 100%; left: 0; position: absolute; border: none; top: 0; width: 100%;margin: 0;"></iframe>
                    <h2>Έχεις ήδη zino?</h2>
                    <form method="post" action="session/create" name="loginform">
                        <label for="username">Ψευδώνυμο</label>
                        <input id="username" class="text" type="text" tabindex="1" name="username" />
                        <label for="password">Κωδικός</label>
                        <input id="password" class="text" type="password" tabindex="2" name="password" />
                        <input class="submit" type="submit" />
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

            <div id="registerbackground"></div>
            <div id="registermodal">
                <h2>Μπες στην παρέα</h2>
                <form action="?resource=user&amp;method=create" method="post">
                    <fieldset>
                        <label for="username">Ψευδώνυμο</label>
                            <input type="text" name="username"/>
                        <label for="password">Κωδικός</label>
                            <input type="password" name="password"/>
                        <label for="password2">Κωδικός (ξανά)</label>
                            <input type="password" name="password2"/>
                        <label for="email">Email</label>
                            <input type="text" name="email"/>
                        <input class="submit" type="submit" value="Πάμε"/>
                    </fieldset>
                </form>
                <div class="xbutton"></div>
            </div>

            <script type="text/javascript">
                var username = document.getElementById( 'username' );
                var password = document.getElementById( 'password' );
                function loginresult( result ) {
                    if ( result ) {
                        window.location.href = 'photos';
                    }
                    else {
                        alert( 'Λάθος κωδικός/όνομα χρήστη' );
                    }
                }
                $( '.register form' ).submit( function() {
                    $( '#registermodal input[name=username]' ).attr( 'value', $( '.register .text' ).attr( 'value' ) );
                    $( '#registermodal, #registerbackground' ).css( { display: 'block' } );
                    return false;
                } );
            </script>
        </body>
    </html>
</xsl:template>
