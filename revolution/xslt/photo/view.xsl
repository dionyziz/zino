<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:include href="../zoomin.xsl" />
    <xsl:template match="entry">
        <div class="portrait">
            <a class="xbutton" href="photos">&#171;</a>
            <img>
                <xsl:attribute name="src">
                    <xsl:value-of select="media[1]/@url" />
                </xsl:attribute>
            </img>
            <xsl:apply-templates select="favourites" />
        </div>
        <xsl:apply-templates select="discussion" />
    </xsl:template>
    <xsl:template match="favourites">
        <div class="note">
            <xsl:for-each select="user">
                &#9829; <div class="username"><xsl:value-of select="name[1]" /> </div>
            </xsl:for-each>
        </div>
    </xsl:template>
    <xsl:template match="discussion">
        <div class="discussion">
            <xsl:apply-templates select="comment" />
        </div>
    </xsl:template>
    <xsl:template match="comment">
        <div class="thread">
            <div class="message">
                <div class="author">
                    <img class="avatar">
                        <xsl:attribute name="src">
                            <xsl:value-of select="author[1]/avatar[1]/media[1]/@url" />
                        </xsl:attribute>
                    </img>
                    <div class="details">
                        <div class="username"><xsl:value-of select="author[1]/name[1]" /></div>
                        <div class="time">
                            <xsl:value-of select="published" />
                        </div>
                    </div>
                </div>
                <div class="text">
                    <xsl:apply-templates select="text" />
                </div>
                <div class="eof"></div>
            </div>
            <xsl:apply-templates select="comment" />
        </div>
    </xsl:template>
    <xsl:template match="a|img|span">
        <xsl:copy-of select="." />
    </xsl:template>
</xsl:stylesheet>
