<xsl:template name="banner">
    <div class="bar">
        <!-- <span>▼</span> -->
        <h1><a href=""><img src="http://static.zino.gr/phoenix/logo-trans.png" /></a></h1>

        <xsl:variable name="breadcrumb" select="/social/photo|/social/poll|/social/journal" />
        <xsl:choose>
            <xsl:when test="$breadcrumb">
                <ol class="breadcrumb">
                    <li>
                        <a>
                            <xsl:attribute name="href">users/<xsl:value-of select="/social/*/author/name" /></xsl:attribute>
                            <xsl:value-of select="/social/*/author/name" />
                        </a>
                    </li>
                    <li class="arrow">&#8250;</li>
                    <xsl:if test="/social/photo">
                        <li>
                            <a><xsl:attribute name="href">photos/<xsl:value-of select="/social/photo/author/name" /></xsl:attribute>albums</a>
                        </li>
                        <li class="arrow">&#8250;</li>
                        <li>
                            <a>
                                <xsl:attribute name="href">
                                    photos/<xsl:value-of select="/social/photo/author/name" />#album_<xsl:value-of select="/social/photo/containedWithin/album/id" />
                                </xsl:attribute>
                                <xsl:choose>
                                    <xsl:when test="/social/photo/containedWithin/album/@egoalbum">
                                        εγώ
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <xsl:value-of select="/social/photo/containedWithin/album/name" />
                                    </xsl:otherwise>
                                </xsl:choose>
                            </a>
                        </li>
                    </xsl:if>
                    <xsl:if test="/social/poll">
                        <li>
                            <a><xsl:attribute name="href">polls/<xsl:value-of select="/social/poll/author/name" /></xsl:attribute>δημοσκοπήσεις</a>
                        </li>
                    </xsl:if>
                    <xsl:if test="/social/journal">
                        <li>
                            <a><xsl:attribute name="href">journals/<xsl:value-of select="/social/journal/author/name" /></xsl:attribute>ημερολόγια</a>
                        </li>
                    </xsl:if>
                    <li class="arrow">&#8250;</li>
                </ol>
            </xsl:when>
            <xsl:otherwise>
            </xsl:otherwise>
        </xsl:choose>
        <ul>
            <li id="chat_icon">
                <a href="" id="chatbutton">Chat</a>
            </li>
            <li id="feedback_icon">
                <a href="journals/13371">Feedback</a>
            </li>
            <xsl:if test="not($breadcrumb)">
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
            </xsl:if>
        </ul>
    </div>
</xsl:template>
