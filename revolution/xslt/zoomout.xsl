<xsl:template name="zoomout">
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
</xsl:template>