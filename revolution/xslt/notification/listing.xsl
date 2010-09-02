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
                <xsl:attribute name="src">
                    <xsl:choose>
                        <xsl:when test="*/favourites/user/avatar">
                            <xsl:value-of select="*/favourites/user/avatar/media/@url" />
                        </xsl:when>
                        <xsl:otherwise>http://static.zino.gr/phoenix/anonymous100.jpg</xsl:otherwise>
                    </xsl:choose>
                </xsl:attribute>
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
            <h4><xsl:value-of select="user/name" /></h4>
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

<xsl:template match="notification[@type='tag']" mode="list">
    <xsl:variable name="IW" select="photo/width" />
    <xsl:variable name="IH" select="photo/height" />
    <xsl:variable name="TW" select="photo/imagetags/imagetag/width" />
    <xsl:variable name="TH" select="photo/imagetags/imagetag/height" />
    <xsl:variable name="TL" select="photo/imagetags/imagetag/left" />
    <xsl:variable name="TT" select="photo/imagetags/imagetag/top" />
    
    <xsl:variable name="scale" select="100 div $TH" />
    <xsl:variable name="which">height</xsl:variable>
    <xsl:if test="$TW div $TH > 2">
        <xsl:variable name="scale" select="200 div $TW" />
        <xsl:variable name="which">width</xsl:variable>
    </xsl:if>
    <xsl:variable name="NIW" select="$scale * $IW" />
    <xsl:variable name="NIH" select="$scale * $IH" />
    <xsl:variable name="NTW" select="$scale * $TW" />
    <xsl:variable name="NTH" select="$scale * $TH" />
    <xsl:variable name="NTL" select="$scale * $TL" />
    <xsl:variable name="NTT" select="$scale * $TT" />
    <div class="box tagbox">
        <xsl:attribute name="id">notification_<xsl:value-of select="@type" />_<xsl:value-of select="@id"/></xsl:attribute>
        <div class="details tag">
            <xsl:attribute name="style">
                <xsl:if test="200 > $NTW">
                    left: <xsl:value-of select="( 200 - $NTW ) div 2" />px;
                    right: <xsl:value-of select="( 200 - $NTW ) div 2" />px;
                </xsl:if>
                <xsl:if test="100 > $NTH">
                    top: <xsl:value-of select="( 100 - $NTH ) div 2" />px;
                    bottom: <xsl:value-of select="( 100 - $NTH ) div 2" />px;
                </xsl:if>
            </xsl:attribute>
            <img>
                <xsl:attribute name="src">
                    <xsl:value-of select="photo/media/@url" />
                </xsl:attribute>
                <xsl:attribute name="alt">
                    <xsl:value-of select="photo/title" />
                </xsl:attribute>
                <xsl:attribute name="style">
                    width: <xsl:value-of select="$NIW" />px;
                    height: <xsl:value-of select="$NIH" />px;
                    top: -<xsl:value-of select="$NTT" />px;
                    left: -<xsl:value-of select="$NTL" />px;
                </xsl:attribute>
            </img>
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
        <div class="content"></div>
        <div class="details">
            <form action="comment/create" method="post" class="save">
                <xsl:choose>
                    <xsl:when test="*/discussion/comment/comment">
                        <p class="note"><strong>
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
                    <input type="hidden" name="type">
                        <xsl:attribute name="value">
                            <xsl:choose>
                                <xsl:when test="poll">poll</xsl:when>
                                <xsl:when test="photo">photo</xsl:when>
                                <xsl:when test="user">user</xsl:when>
                                <xsl:when test="journal">journal</xsl:when>
                            </xsl:choose>
                        </xsl:attribute>
                    </input>
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
                                <xsl:otherwise>
                                    <xsl:value-of select="*/discussion/comment/@id" />
                                </xsl:otherwise>
                            </xsl:choose>
                        </xsl:attribute>
                    </input>
                    <div class="thread new" style="display: block;">
                        <div class="message mine new">
                            <div><textarea name="text"></textarea></div>
                        </div>
                    </div>
                    <p class="note">Ή πάτησε ESC αν δεν θέλεις να αφήσεις σχόλιο</p>
                </p>
            </form>
        </div>
    </div>
</xsl:template>

<xsl:template match="notification//user">
    <div class="businesscard">
        <div class="avatar">
            <a>
                <xsl:attribute name="href">
                    <xsl:text>users/</xsl:text>
                    <xsl:value-of select="name" />
                </xsl:attribute>
                <img>
                    <xsl:attribute name="src">
                        <xsl:choose>
                            <xsl:when test="avatar/media">
                                <xsl:value-of select="avatar/media/@url" />
                            </xsl:when>
                            <xsl:otherwise>
                               <xsl:text>http://static.zino.gr/phoenix/anonymous100.jpg</xsl:text>
                            </xsl:otherwise>
                        </xsl:choose>
                    </xsl:attribute>
                    <xsl:attribute name="alt">
                        <xsl:value-of select="name"/>
                    </xsl:attribute>
                </img>
            </a>
        </div>
        <div class="username">
            <a>
                <xsl:attribute name="href">
                    <xsl:text>users/</xsl:text>
                    <xsl:value-of select="name"/>
                </xsl:attribute>
                <xsl:value-of select="name" />
            </a>
        </div>
        <ul class="details">
            <xsl:if test="gender!='-'">
                <li>
                    <xsl:choose>
                        <xsl:when test="gender='f'">
                            Κορίτσι
                        </xsl:when>
                        <xsl:otherwise>
                            Αγόρι
                        </xsl:otherwise>
                    </xsl:choose>
                </li>
            </xsl:if>
            <xsl:if test="age">
                <li>
                    <xsl:value-of select="age" />
                </li>
            </xsl:if>
            <xsl:if test="location">
                <li>
                    <xsl:value-of select="location" />
                </li>
            </xsl:if>
        </ul>
    </div>
</xsl:template>

<xsl:template match="notification[@type='favourite']" mode="view">
    <div class="instantbox">
        <xsl:attribute name="id">ib_<xsl:value-of select="@type" />_<xsl:value-of select="@id"/></xsl:attribute>
        <ul class="tips">
            <li>Enter = <strong>Αποθήκευση μηνύματος</strong></li>
            <li>Escape = <strong>Αγνόηση</strong></li>
        </ul>
        <div class="content"></div>
        <div class="details">
            <div class="thread">
                <div class="note">
                    <xsl:apply-templates select="*/favourites/user" />
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
                        <xsl:text> σου</xsl:text>
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
                            <input type="hidden" name="favouritetype">
                                <xsl:attribute name="value">
                                    <xsl:choose>
                                        <xsl:when test="poll">poll</xsl:when>
                                        <xsl:when test="photo">photo</xsl:when>
                                        <xsl:when test="journal">journal</xsl:when>
                                    </xsl:choose>
                                </xsl:attribute>
                            </input>
                            <input type="hidden" name="favouriteitemid">
                                <xsl:attribute name="value">
                                    <xsl:value-of select="*/@id" />
                                </xsl:attribute>
                            </input>
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
                        <p class="note">Ή πάτησε ESC αν δεν θέλεις να αφήσεις σχόλιο</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</xsl:template>

<xsl:template match="notification[@type='tag']" mode="view">
    <div class="instantbox">
        <xsl:attribute name="id">ib_<xsl:value-of select="@type" />_<xsl:value-of select="@id"/></xsl:attribute>
        <ul class="tips">
            <li>Enter = <strong>Προσθήκη στα αγαπημένα</strong></li>
            <li>Escape = <strong>Αγνόηση</strong></li>
        </ul>
        <div class="details">
            <p class="note"><strong>
                <xsl:choose>
                    <xsl:when test="photo/imagetags/imagetag/creator/gender = 'f'">Η</xsl:when>
                    <xsl:otherwise>Ο</xsl:otherwise>
                </xsl:choose>
                <xsl:text> </xsl:text>
                <xsl:value-of select="photo/imagetags/imagetag/creator/name" />
                <xsl:text> </xsl:text>
                σε αναγνώρισε σε μία φωτογραφία.
            </strong></p>
            <div class="image">
                <xsl:attribute name="id">photo_<xsl:value-of select="photo/@id" /></xsl:attribute>
                <xsl:attribute name="style">width:<xsl:value-of select="photo/width" />px;cursor:pointer;</xsl:attribute>
                <img class="hover">
                    <xsl:attribute name="src"><xsl:value-of select="photo/media/@url" /></xsl:attribute>
                </img>
                <div class="tag visible">
                    <xsl:attribute name="style">
                        top: <xsl:value-of select="photo/imagetags/imagetag/top" />px;
                        left: <xsl:value-of select="photo/imagetags/imagetag/left" />px;
                        width: <xsl:value-of select="photo/imagetags/imagetag/width" />px;
                        height: <xsl:value-of select="photo/imagetags/imagetag/height" />px;
                    </xsl:attribute>
                    <div class="imagecontainer">
                        <img>
                            <xsl:attribute name="src"><xsl:value-of select="photo/media/@url" /></xsl:attribute>
                            <xsl:attribute name="style">
                                top: -<xsl:value-of select="photo/imagetags/imagetag/top" />px;
                                left: -<xsl:value-of select="photo/imagetags/imagetag/left" />px;
                            </xsl:attribute>
                        </img>
                    </div>
                </div>
            </div>
            <form action="favourite/create" method="post" class="save">
                <input type="hidden" name="typeid" value="2" />
                <input type="hidden" name="itemid">
                    <xsl:attribute name="value"><xsl:value-of select="photo/@id" /></xsl:attribute>
                </input>
            </form>
        </div>
    </div>
</xsl:template>
<xsl:template match="notification[@type='friend']" mode="view">
    <div class="instantbox">
        <xsl:attribute name="id">ib_<xsl:value-of select="@type" />_<xsl:value-of select="@id"/></xsl:attribute>
        <ul class="tips">
            <li>Enter = 
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
            <p class="note"><strong>
                <xsl:choose>
                    <xsl:when test="user/gender = 'f'">Η</xsl:when>
                    <xsl:otherwise>Ο</xsl:otherwise>
                </xsl:choose>
                <xsl:text> </xsl:text>
                <xsl:value-of select="user/name" />
                <xsl:text> </xsl:text>
                σε πρόσθεσε στους φίλους.
            </strong></p>
            <xsl:apply-templates select="user" />
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
                        <p class="note">Ή πάτησε ESC αν δεν θέλεις να αφήσεις σχόλιο</p>
                    </form>
                </div>
            </xsl:when>
            <xsl:otherwise>
                <form action="friendship/create" method="post" class="save">
                    <p>
                        <input type="hidden" name="username">
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
