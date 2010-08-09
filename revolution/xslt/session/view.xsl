<xsl:template match="/social[@resource='session' and @method='view']">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="el" lang="el">
        <head>
            <title>Zino</title>
            <base>
                <xsl:attribute name="href"><xsl:value-of select="@generator" />/</xsl:attribute>
            </base>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
            <meta http-equiv="X-UA-Compatible" content="IE=8,chrome=1" />
            <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/chrome-frame/1/CFInstall.min.js"></script>
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
                    <!-- <iframe id="iframe" src="login.html" style="height: 100%; left: 0; position: absolute; border: none; top: 0; width: 100%;margin: 0;"></iframe> -->
                    <h2>Έχεις ήδη zino?</h2>
                    <form method="post" action="session/create" name="loginform">
                        <label for="username">Ψευδώνυμο</label>
                        <input id="username" class="text" type="text" tabindex="1" name="username" />
                        <label for="password">Κωδικός</label>
                        <input id="password" class="text" type="password" tabindex="2" name="password" />
                        <input class="submit" type="submit" value="" />
                    </form>
                </div>
            </div>
            <div class="copy">
                &#0169; 2010 Kamibu
                <ul>
                    <li><a href="photos">Πρόσβαση χωρίς είσοδο</a></li>
                    <li><a href="">Πληροφορίες</a></li>
                    <li><a href="mailto:info@zino.gr">Διαφήμιση</a></li>
                    <li><a href="">API</a></li>
                    <li><a href="http://static.zino.gr/revolution/legal/" target="_blank">Νομικά</a></li>
                </ul>
            </div>

            <div id="registerbackground"></div>
            <div id="registermodal">
                <h2>Μπες στην παρέα</h2>
                <form action="?resource=user&amp;method=create" method="post">
                    <fieldset>
                        <label for="name">Ψευδώνυμο</label>
                            <input type="text" name="name" id="name"/>
                        <label for="password">Κωδικός</label>
                            <input type="password" name="password" id="pass" />
                        <label for="password2">Κωδικός (ξανά)</label>
                            <input type="password" name="password2" id="pass2" />
                        <label for="email">Email</label>
                            <input type="text" name="email" id="email"/>
                        <input class="submit" type="submit" value="Πάμε"/>
                    </fieldset>
                </form>
                <div class="xbutton"></div>
                <div class="strawberryfields"></div>
            </div>
            <script type="text/javascript" src="js/session.js"></script>
        </body>
    </html>
</xsl:template>
