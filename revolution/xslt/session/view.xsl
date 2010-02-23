<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="utf-8" indent="yes" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" />
    <xsl:template match="/social">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="el" lang="el">
        <head>
            <title>Zino</title>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
            <link href="css/loggedout.css" rel="stylesheet" type="text/css" />
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
        </body>
    </html>
    </xsl:template>
</xsl:stylesheet>
