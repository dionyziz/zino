<xsl:template name="zoomout">
    <div class="bar">
        <span>▼</span>
        <img src="http://static.zino.gr/phoenix/logo-trans.png" />
         
        <ul>
            <li>
                <xsl:if test="/social/photos">
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
                <xsl:if test="/social/@for">
                    <a id="logoutbutton" style="background-image: url('http://zino.gr:500/dionyziz/images/user.png');">
                        <xsl:attribute name="href">
                            users/<xsl:value-of select="/social/@for" />
                        </xsl:attribute>
                        Προφίλ
                    </a>
                </xsl:if>
                <xsl:if test="not(/social/@for)">
                    <a style="background-image: url('http://zino.gr:500/dionyziz/images/user.png');" href="login" id="loginbutton"><img src="images/user.png" alt="Είσοδος" title="Είσοδος" /><span>Είσοδος</span></a>
                </xsl:if>
            </li>
            <li style="float: right; padding-right: 0px;margin-right: 7px;">
                <a href="" id="chatbutton" style="background-image: url('http://zino.gr:500/dionyziz/images/comments.png');">Chat</a>
            </li>
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
