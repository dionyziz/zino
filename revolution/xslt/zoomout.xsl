<xsl:template name="zoomout">
    <div class="col1 vbar">
        <h1><img src="http://static.zino.gr/phoenix/logo-trans.png" alt="Zino Bubble" /></h1>
        <ul>
            <!-- <li><a href=""><img src="images/house.png" alt="Όλα" title="Όλα" /><span>Όλα</span></a></li> -->
            <li>
                <xsl:if test="/social/photos">
                    <xsl:attribute name="class">selected</xsl:attribute>
                </xsl:if>
                <a href=""><img src="images/images.png" alt="Φωτογραφίες" title="Φωτογραφίες" /><span>Εικόνες</span></a>
            </li>
            <li>
                <xsl:if test="/social/news">
                    <xsl:attribute name="class">selected</xsl:attribute>
                </xsl:if>
                <a href="news"><img src="images/world.png" alt="Νέα" title="Νέα" /><span>Νέα</span></a>
            </li>
            <xsl:if test="/social/@for">
                <li><a id="logoutbutton">
                    <xsl:attribute name="href">
                        users/<xsl:value-of select="/social/@for" />
                    </xsl:attribute>
                    <img src="images/user.png" alt="Προφίλ" title="Προφίλ" /><span>Προφίλ</span>
                </a></li>
            </xsl:if>
            <xsl:if test="not(/social/@for)">
                <li><a href="login" id="loginbutton"><img src="images/user.png" alt="Είσοδος" title="Είσοδος" /><span>Είσοδος</span></a></li>
            </xsl:if>
            <li class="bl"><a href="" id="chatbutton"><img src="images/comments.png" alt="Συζήτηση" title="Συζήτηση" /><span>Chat</span></a></li>
        </ul>
    </div>
    <div class="col2">
        <div id="content">
            <xsl:apply-templates />
        </div>
    </div>
    <script type="text/javascript">
        Notifications.Check();
    </script>
</xsl:template> 
