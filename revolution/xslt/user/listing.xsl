<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="crowd">
        <ul>
            <xsl:for-each select="user">
                <li>
                    <xsl:value-of select="name[0]" />
                </li>
            </xsl:for-each>
        </ul>
    </xsl:template>
</xsl:stylesheet>

