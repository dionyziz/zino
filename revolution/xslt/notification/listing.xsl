<xsl:template match="/social[@resource='notification' and @method='listing']">
    <div id="notifications" class="panel bottom novideo">
        <div class="background"></div>
        <div class="vbutton"></div>
        <h3>Ενημερώσεις (<span><xsl:value-of select="notifications/@count" /></span>)</h3>
        <xsl:apply-templates select="notifications/notification" mode="list"/>
    </div>
    <xsl:apply-templates select="notifications/notification" mode="view"/>
</xsl:template>

<xsl:template match="notification[@type='favourite']" mode="list">
    <div class="box">
        <xsl:attribute name="id">notification_<xsl:value-of select="@type" />_<xsl:value-of select="@id"/></xsl:attribute>
        <div>
            <img>
                <xsl:choose>
                    <xsl:when test="*/favourites/user/avatar">
                        <xsl:attribute name="src">
                            <xsl:value-of select="*/favourites/user/avatar/media/@url" />
                        </xsl:attribute>
                    </xsl:when>
                    <xsl:otherwise>http://static.zino.gr/phoenix/anonymous100.jpg</xsl:otherwise>
                </xsl:choose>
                <xsl:attribute name="alt">
                    <xsl:value-of select="*/favourites/user/name" />
                </xsl:attribute>
                <xsl:attribute name="title">
                    <xsl:value-of select="*/favourites/user/name" />
                </xsl:attribute>
            </img>
        </div>
        <div class="details">
            <h4><xsl:value-of select="*/favourites/user/name" /></h4>
            <div class="background"></div>
            <div class="love">♥</div>
        </div>
    </div>
</xsl:template>

<xsl:template match="notification[@type='friend']" mode="list">
    <div class="box">
        <xsl:attribute name="id">notification_<xsl:value-of select="@type" />_<xsl:value-of select="@id"/></xsl:attribute>
        <div>
            <img>
                <xsl:attribute name="src">
                    <xsl:choose>
                        <xsl:when test="user/avatar">
                            <xsl:value-of select="user/avatar/media/@url" />
                        </xsl:when>
                        <xsl:otherwise>http://static.zino.gr/phoenix/anonymous100.jpg</xsl:otherwise>
                    </xsl:choose>
                </xsl:attribute>
                <xsl:attribute name="alt">
                    <xsl:value-of select="user/name" />
                </xsl:attribute>
                <xsl:attribute name="title">
                    <xsl:value-of select="user/name" />
                </xsl:attribute>
            </img>
        </div>
        <div class="details">
            <h4><xsl:value-of select="*/favourites/user/name" /></h4>
            <div class="friend">
                <xsl:choose>
                    <xsl:when test="user/gender='f'">
                        φίλη
                    </xsl:when>
                    <xsl:otherwise>
                        φίλος
                    </xsl:otherwise>
                </xsl:choose>
            </div>
        </div>
    </div>
</xsl:template>

<xsl:template match="notification[@type='comment']" mode="list">
    <div class="box">
        <xsl:attribute name="id">notification_<xsl:value-of select="@type" />_<xsl:value-of select="@id"/></xsl:attribute>
        <div>
            <img>
                <xsl:attribute name="src">
                    <xsl:choose>
                        <xsl:when test="*/discussion/comment/comment/author">
                            <xsl:choose>
                                <xsl:when test="*/discussion/comment/comment/author/avatar">
                                    <xsl:value-of select="*/discussion/comment/comment/author/avatar/media/@url" />
                                </xsl:when>
                                <xsl:otherwise>http://static.zino.gr/phoenix/anonymous100.jpg</xsl:otherwise>
                            </xsl:choose>
                        </xsl:when>
                        <xsl:when test="*/discussion/comment/author/avatar">
                            <xsl:value-of select="*/discussion/comment/author/avatar/media/@url" />
                        </xsl:when>
                        <xsl:otherwise>http://static.zino.gr/phoenix/anonymous100.jpg</xsl:otherwise>
                    </xsl:choose>
                </xsl:attribute>
                <xsl:attribute name="alt">
                    <xsl:value-of select=".//name" />
                </xsl:attribute>
            </img>
        </div>
        <div class="details">
            <h4>
                <xsl:choose>
                    <xsl:when test="*/discussion/comment/comment/author/name">
                        <xsl:value-of select="*/discussion/comment/comment/author/name" />
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:value-of select=".//name" />
                    </xsl:otherwise>
                </xsl:choose>
            </h4>
            <xsl:choose>
                <xsl:when test="*/discussion/comment/comment">
                    <div class="background"></div>
                    <div class="text">
                        <xsl:copy-of select="*/discussion/comment/comment/text/*|*/discussion/comment/comment/text/text()" />
                    </div>
                </xsl:when>
                <xsl:otherwise>
                    <div class="background"></div>
                    <div class="text">
                        <xsl:copy-of select="*/discussion/comment/text/*|*/discussion/comment/text/text()" />
                    </div>
                </xsl:otherwise>
            </xsl:choose>
        </div>
    </div>
</xsl:template>

<xsl:template match="notification[@type='comment']" mode="view">
    <div class="instantbox">
        <xsl:attribute name="id">ib_<xsl:value-of select="@type" />_<xsl:value-of select="@id"/></xsl:attribute>
        <ul class="tips">
            <li>Enter = <strong>Αποθήκευση μηνύματος</strong></li>
            <li>Escape = <strong>Αγνόηση</strong></li>
        </ul>
        <div class="details">
            <form action="comment/create" method="post" class="save">
                <xsl:choose>
                    <xsl:when test="*/discussion/comment/comment">
                        <p><strong>
                            <xsl:choose>
                                <xsl:when test="*/discussion/comment/comment/author/gender='f'">
                                    <xsl:text>Η </xsl:text>
                                    <xsl:value-of select="*/discussion/comment/comment/author/name" />
                                    <xsl:text> απάντησε στο σχόλιό σου</xsl:text>
                                </xsl:when>
                                <xsl:otherwise>
                                    <xsl:text>O </xsl:text>
                                    <xsl:value-of select="*/discussion/comment/comment/author/name" />
                                    <xsl:text> απάντησε στο σχόλιό σου</xsl:text>
                                </xsl:otherwise>
                            </xsl:choose>
                        </strong></p>
                        <xsl:apply-templates select="*/discussion/comment"/>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:apply-templates select="*/discussion/comment"/>
                    </xsl:otherwise>
                </xsl:choose>
                <p class="note">
                    <input type="hidden" name="typeid">
                        <xsl:attribute name="value">
                            <xsl:choose>
                                <xsl:when test="poll">1</xsl:when>
                                <xsl:when test="photo">2</xsl:when>
                                <xsl:when test="user">3</xsl:when>
                                <xsl:when test="journal">4</xsl:when>
                            </xsl:choose>
                        </xsl:attribute>
                    </input>
                    <input type="hidden" name="itemid">
                        <xsl:attribute name="value">
                            <xsl:value-of select="*/@id" />
                        </xsl:attribute>
                    </input>
                    <input type="hidden" name="parentid">
                        <xsl:attribute name="value">
                            <xsl:choose>
                                <xsl:when test="*/discussion/comment/comment">
                                    <xsl:value-of select="*/discussion/comment/comment/@id" />
                                </xsl:when>
                                <xsl:otherwise>0</xsl:otherwise>
                            </xsl:choose>
                        </xsl:attribute>
                    </input>
                    <div class="thread new" style="display: block;">
                        <div class="message mine new">
                            <div><textarea name="text"></textarea></div>
                        </div>
                    </div>
                    Ή πάτησε ESC αν δεν θέλεις να αφήσεις σχόλιο
                </p>
            </form>
        </div>
    </div>
</xsl:template>

<xsl:template match="notification[@type='favourite']" mode="view">
    <div class="instantbox">
        <xsl:attribute name="id">ib_<xsl:value-of select="@type" />_<xsl:value-of select="@id"/></xsl:attribute>
        <ul class="tips">
            <li>
                Enter =
                <xsl:choose>
                    <xsl:when test="user/knows/user/knows"><!-- friend relationship mutual already -->
                        <strong>Αποθήκευση μηνύματος</strong>
                    </xsl:when>
                    <xsl:otherwise>
                        <strong>Προσθήκη φίλου</strong>
                    </xsl:otherwise>
                </xsl:choose>
            </li>
            <li>Escape = <strong>Αγνόηση</strong></li>
        </ul>
        <div class="details">
            <div class="thread">
                <div class="note">
                    <div class="businesscard">
                        <div class="avatar">
                            <a>
                                <xsl:attribute name="href">
                                    <xsl:text>users/</xsl:text>
                                    <xsl:value-of select="*/favourites/user/name" />
                                </xsl:attribute>
                                <img>
                                    <xsl:attribute name="src">
                                        <xsl:choose>
                                            <xsl:when test="*/favourites/user/avatar/media">
                                                <xsl:value-of select="*/favourites/user/avatar/media/@url" />
                                            </xsl:when>
                                            <xsl:otherwise>
                                               <xsl:text>http://static.zino.gr/phoenix/anonymous100.jpg</xsl:text>
                                            </xsl:otherwise>
                                        </xsl:choose>
                                    </xsl:attribute>
                                    <xsl:attribute name="alt">
                                        <xsl:value-of select="*/favourites/user/name"/>
                                    </xsl:attribute>
                                </img>
                            </a>
                        </div>
                        <div class="username">
                            <a>
                                <xsl:attribute name="href">
                                    <xsl:text>users/</xsl:text>
                                    <xsl:value-of select="*/favourites/user/name"/>
                                </xsl:attribute>
                                <xsl:value-of select="*/favourites/user/name" />
                            </a>
                        </div>
                        <ul class="details">
                            <xsl:if test="*/favourites/user/gender!='-'">
                                <li>
                                    <xsl:choose>
                                        <xsl:when test="*/favourites/user/gender='f'">
                                            Κορίτσι &#8226; 
                                        </xsl:when>
                                        <xsl:otherwise>
                                            Αγόρι &#8226; 
                                        </xsl:otherwise>
                                    </xsl:choose>
                                </li>
                            </xsl:if>
                            <xsl:if test="*/favourites/user/age">
                                <li>
                                    <xsl:value-of select="*/favourites/user/age" /> &#8226; 
                                </li>
                            </xsl:if>
                            <xsl:if test="*/favourites/user/location">
                                <li>
                                    <xsl:value-of select="*/favourites/user/location" /> &#8226; 
                                </li>
                            </xsl:if>
                        </ul>
                    </div>
                    <p><strong>
                        <xsl:choose>
                            <xsl:when test="*/favourites/user/gender='f'">
                                Η
                            </xsl:when>
                            <xsl:otherwise>
                                Ο
                            </xsl:otherwise>
                        </xsl:choose>
                        <xsl:text> </xsl:text>
                        <xsl:value-of select="*/favourites/user/name" />
                        <xsl:text> αγαπάει </xsl:text>
                        <xsl:choose>
                            <xsl:when test="photo">τη φωτογραφία</xsl:when>
                            <xsl:when test="poll">τη δημοσκόπησή</xsl:when>
                            <xsl:when test="journal">το ημερολόγιό</xsl:when>
                        </xsl:choose>
                    </strong></p>
                    <p><strong>Γράψε ένα σχόλιο στο προφίλ 
                        <xsl:choose>
                            <xsl:when test="*/favourites/user/gender='f'">
                                <xsl:text> της</xsl:text>
                            </xsl:when>
                            <xsl:otherwise>
                                <xsl:text> του</xsl:text>
                            </xsl:otherwise>
                        </xsl:choose>:
                    </strong></p>
                    <form action="comment/create" method="post" class="save">
                        <div class="thread new" style="display: block;">
                            <input type="hidden" name="typeid" value="3" />
                            <input type="hidden" name="itemid">
                                <xsl:attribute name="value">
                                    <xsl:value-of select="*/favourites/user/@id" />
                                </xsl:attribute>
                            </input>
                            <input type="hidden" name="parentid" value="0" />
                            <div class="message mine new">
                                <div><textarea name="text"></textarea></div>
                            </div>
                        </div>
                        <p>Ή πάτησε ESC αν δεν θέλεις να αφήσεις σχόλιο</p>
                    </form>
                </div>
            </div>
        </div>
        <div class="content"><!-- TODO: load content here using axslt? -->
        </div>
    </div>
</xsl:template>

<xsl:template match="notification[@type='friend']" mode="view">
    <div class="instantbox">
        <xsl:attribute name="id">ib_<xsl:value-of select="@type" />_<xsl:value-of select="@id"/></xsl:attribute>
        <ul class="tips">
            <li>Enter = <strong>Αποθήκευση μηνύματος</strong></li>
            <li>Escape = <strong>Αγνόηση</strong></li>
        </ul>
        <div class="details">
            <p><strong>
                <xsl:choose>
                    <xsl:when test="user/gender = 'f'">Η</xsl:when>
                    <xsl:otherwise>Ο</xsl:otherwise>
                </xsl:choose>
                <xsl:text> </xsl:text>
                <xsl:value-of select="user/name" />
                <xsl:text> </xsl:text>
                σε πρόσθεσε στους φίλους.
            </strong></p>
            <div class="businesscard">
                <div class="avatar">
                    <a>
                        <xsl:attribute name="href">
                            <xsl:text>users/</xsl:text>
                            <xsl:value-of select="user/name" />
                        </xsl:attribute>
                        <img>
                            <xsl:attribute name="src">
                                <xsl:choose>
                                    <xsl:when test="user/avatar/media">
                                        <xsl:value-of select="user/avatar/media/@url" />
                                    </xsl:when>
                                    <xsl:otherwise>
                                       <xsl:text>http://static.zino.gr/phoenix/anonymous100.jpg</xsl:text>
                                    </xsl:otherwise>
                                </xsl:choose>
                            </xsl:attribute>
                            <xsl:attribute name="alt">
                                <xsl:value-of select="user/name"/>
                            </xsl:attribute>
                        </img>
                    </a>
                </div>
                <div class="username">
                    <a>
                        <xsl:attribute name="href">
                            <xsl:text>users/</xsl:text>
                            <xsl:value-of select="user/name"/>
                        </xsl:attribute>
                        <xsl:value-of select="user/name" />
                    </a>
                </div>
                <ul class="details">
                    <xsl:if test="user/gender!='-'">
                        <li>
                            <xsl:choose>
                                <xsl:when test="user/gender='f'">
                                    Κορίτσι
                                </xsl:when>
                                <xsl:otherwise>
                                    Αγόρι
                                </xsl:otherwise>
                            </xsl:choose>
                        </li>
                    </xsl:if>
                    <xsl:if test="user/age">
                        <li>
                            <xsl:value-of select="user/age" />
                        </li>
                    </xsl:if>
                    <xsl:if test="user/location">
                        <li>
                            <xsl:value-of select="user/location" />
                        </li>
                    </xsl:if>
                </ul>
            </div><!-- businesscard -->
        </div>
        <xsl:choose>
            <xsl:when test="user/knows/user/knows"><!-- friend relationship mutual already -->
                <div class="note">
                    <p><strong>Γράψε ένα σχόλιο στο προφίλ
                    <xsl:choose>
                        <xsl:when test="user/gender='f'">
                            <xsl:text> της</xsl:text>
                        </xsl:when>
                        <xsl:otherwise>
                            <xsl:text> του</xsl:text>
                        </xsl:otherwise>
                    </xsl:choose>:
                    </strong></p>
                    <form action="comment/create" method="post" class="save">
                        <div class="thread new" style="display: block;">
                            <input type="hidden" name="typeid" value="3" />
                            <input type="hidden" name="itemid">
                                <xsl:attribute name="value">
                                    <xsl:value-of select="user/@id" />
                                </xsl:attribute>
                            </input>
                            <input type="hidden" name="parentid" value="0" />
                            <div class="message mine new">
                                <div><textarea name="text"></textarea></div>
                            </div>
                        </div>
                        <p>Ή πάτησε ESC αν δεν θέλεις να αφήσεις σχόλιο</p>
                    </form>
                </div>
            </xsl:when>
            <xsl:otherwise>
                <form action="friendship/create" method="post" class="save">
                    <p>
                        <input type="hidden" name="name">
                            <xsl:attribute name="value">
                                <xsl:value-of select="user/name" />
                            </xsl:attribute>
                        </input>
                        <a class="friend" href="">
                            Πρόσθεσέ
                            <xsl:choose>
                                <xsl:when test="user/gender='f'">
                                    <xsl:text> την</xsl:text>
                                </xsl:when>
                                <xsl:otherwise>
                                    <xsl:text> τον</xsl:text>
                                </xsl:otherwise>
                            </xsl:choose>!
                        </a>
                    </p>
                </form>
                ή
                <a href="" class="ignore">
                    αγνόησέ
                    <xsl:choose>
                        <xsl:when test="user/gender='f'">
                            <xsl:text> την</xsl:text>
                        </xsl:when>
                        <xsl:otherwise>
                            <xsl:text> τον</xsl:text>
                        </xsl:otherwise>
                    </xsl:choose>
                </a>
            </xsl:otherwise>
        </xsl:choose>
    </div>
</xsl:template>
