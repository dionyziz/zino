<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="utf-8" indent="yes" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" />
    <xsl:template match="/social">
        <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="el" lang="el">
            <head>
                <base><xsl:attribute name="href"><xsl:value-of select="/social[1]/@generator" />/</xsl:attribute></base>
                <title>Zino</title>
                <link type="text/css" href="css/frontpage.css" rel="stylesheet" />
                <link type="text/css" href="css/chat.css" rel="stylesheet" />
                <script type="text/javascript" src="http://code.jquery.com/jquery-1.4.2.min.js"></script>
                <script type="text/javascript" src="http://www.zino.gr/js/jquery.modal.js"></script>
                <script type="text/javascript" src="http://www.zino.gr/js/modal.js"></script>
                <script type="text/javascript" src="js/chat.js"></script>
                <script type="text/javascript" src="http://www.zino.gr/js/kamibu.js"></script>
                <link type="text/css" href="http://static.zino.gr/css/emoticons.css" rel="stylesheet" />
                <script type="text/javascript" src="js/comet.js"></script>
                <script type="text/javascript">
                    var User = '<xsl:value-of select="@for" />';
                    var Now = '<xsl:value-of select="@generated" />';
                    var Which = '<xsl:value-of select="/social/entry[1]/@id" />';
                </script>
                <script type="text/javascript">
                document.domain = 'zino.gr';
                var Meteor = {
                    register: function ( target ) {
                        alert( "Hello from the parent." );
                    }
                };
                </script>
            </head>
            <body>
                <div class="col1 vbar">
                    <h1><img src="http://static.zino.gr/phoenix/logo-trans.png" alt="Zino Bubble" /></h1>
                    <ul>
                        <li><a href=""><img src="images/house.png" alt="Όλα" title="Όλα" /><span>Όλα</span></a></li>
                        <li>
                            <xsl:if test="/social/feed[1]/@type = 'photos'">
                                <xsl:attribute name="class">selected</xsl:attribute>
                            </xsl:if>
                            <a href=""><img src="images/images.png" alt="Φωτογραφίες" title="Φωτογραφίες" /><span>Εικόνες</span></a>
                        </li>
                        <li>
                            <xsl:if test="/social/feed[1]/@type = 'news'">
                                <xsl:attribute name="class">selected</xsl:attribute>
                            </xsl:if>
                            <a href="news"><img src="images/world.png" alt="Νέα" title="Νέα" /><span>Νέα</span></a>
                        </li>
                        <xsl:if test="/social/@for">
                            <li><a href="" id="logoutbutton"><img src="images/user.png" alt="Προφίλ" title="Προφίλ" /><span>Temp Logout</span></a></li>
                        </xsl:if>
                        <xsl:if test="not(/social/@for)">
                            <li><a href="login" id="loginbutton"><img src="images/user.png" alt="Είσοδος" title="Είσοδος" /><span>Είσοδος</span></a></li>
                        </xsl:if>
                        <li class="bl"><a href="" id="chatbutton"><img src="images/comments.png" alt="Συζήτηση" title="Συζήτηση" /><span>Chat</span></a></li>
                    </ul>
                </div>
                <div class="col2">
                    <xsl:apply-templates />
                </div>
                <script type="text/javascript" src="js/menu.js"></script>
                <script type="text/javascript" src="js/photo/listing.js"></script>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
