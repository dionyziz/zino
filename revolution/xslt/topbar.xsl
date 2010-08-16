<xsl:template name="banner">
    <div class="bar">
        <!-- <span>▼</span> -->
        <h1><a href=""><img src="http://static.zino.gr/phoenix/logo-trans.png" /></a></h1>
         
        <ul>
            <li id="chat_icon">
                <a href="" id="chatbutton">Chat</a>
            </li>
            <li id="feedback_icon">
                <a href="journals/13371">Feedback</a>
            </li>
            <!-- <xsl:choose>
                <xsl:when test="/social/photo">
                    <li class="breadcrumb">
                        <ol style="padding:9px">
                            <li>
                                <a>
                                    <xsl:attribute name="href">users/<xsl:value-of select="/social/photo/author/name" /></xsl:attribute>
                                    <xsl:value-of select="/social/photo/author/name" />
                                </a>
                            </li>
                            <li class="arrow">&#8250;</li>
                            <li>
                                <a>
                                    <xsl:attribute name="href">photos/<xsl:value-of select="/social/photo/author/name" /></xsl:attribute>
                                    albums
                                </a>
                            </li>
                            <li class="arrow">&#8250;</li>
                            <li>
                                <a>Εγώ</a>
                            </li>
                            <li class="arrow">&#8250;</li>
                        </ol>
                    </li>
                </xsl:when>
                <xsl:otherwise> -->
                    <li id="photo_icon">
                        <xsl:if test="/social/photos and not(/social/photos/author)">
                            <xsl:attribute name="class">selected</xsl:attribute>
                        </xsl:if>
                        <a href="">Εικόνες</a>
                    </li>
                    <li id="news_icon">
                        <xsl:if test="/social/news">
                            <xsl:attribute name="class">selected</xsl:attribute>
                        </xsl:if>
                        <a href="news">Νέα</a>
                    </li>
                    <li>
                        <xsl:if test="/social[@resource='user' and @method='view']/user/name = /social/@for">
                            <xsl:attribute name="class">selected</xsl:attribute>
                        </xsl:if>
                        <xsl:if test="/social/@for">
                            <xsl:attribute name="id">profile_icon</xsl:attribute>
                            <a id="logoutbutton">
                                <xsl:attribute name="href">
                                    users/<xsl:value-of select="/social/@for" />
                                </xsl:attribute>
                                Προφίλ
                            </a>
                        </xsl:if>
                        <xsl:if test="not(/social/@for)">
                            <xsl:attribute name="id">login_icon</xsl:attribute>
                            <a href="login" id="loginbutton">Είσοδος</a>
                        </xsl:if>
                    </li>
                <!-- </xsl:otherwise>
            </xsl:choose> -->
        </ul>
    </div>
</xsl:template>
