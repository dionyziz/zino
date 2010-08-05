<xsl:template name="banner">
    <div class="bar">
        <span>▼</span>
        <h1><a href=""><img src="http://static.zino.gr/phoenix/logo-trans.png" /></a></h1>
         
        <ul>
            <li>
                <xsl:if test="/social/photos and not(/social/photos/author)">
                    <xsl:attribute name="class">selected</xsl:attribute>
                </xsl:if>
                <a style="background-image: url('http://zino.gr:500/dionyziz/images/images.png');" href="">Εικόνες</a>
            </li>
            <li>
                <xsl:if test="/social/news">
                    <xsl:attribute name="class">selected</xsl:attribute>
                </xsl:if>
                <a style="background-image: url('http://zino.gr:500/dionyziz/images/world.png');" href="news">Νέα</a>
            </li>
            <li>
                <xsl:if test="/social[@resource='user' and @method='view']/user/name = /social/@for">
                    <xsl:attribute name="class">selected</xsl:attribute>
                </xsl:if>
                <xsl:if test="/social/@for">
                    <a id="logoutbutton" style="background-image: url('http://zino.gr:500/dionyziz/images/user.png');">
                        <xsl:attribute name="href">
                            users/<xsl:value-of select="/social/@for" />
                        </xsl:attribute>
                        Προφίλ
                    </a>
                </xsl:if>
                <xsl:if test="not(/social/@for)">
                    <a style="background-image: url('http://zino.gr:500/dionyziz/images/user.png');" href="login" id="loginbutton">Είσοδος</a>
                </xsl:if>
            </li>
            <li style="float: right; padding-right: 0px;margin-right: 7px;">
                <a href="" id="chatbutton" style="background-image: url('http://zino.gr:500/dionyziz/images/comments.png');">Chat</a>
            </li>
            <li style="float: right; padding-right: 0px;margin-right: 7px; background-color:#FC575E">
                <a href="http://dionyziz.zino.gr/journals/Xwros_anaforas_problimatwn" style="background-image:url('http://static.zino.gr/phoenix/up.png')">Feedback</a>
            </li>
        </ul>
    </div>
</xsl:template>
