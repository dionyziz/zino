<?xml version="1.0" encoding="utf-8"?>
<!--
    Copyright (c) 2005 - 2007, Dionysis Zindros <dionyziz@gmail.com>
    All rights reserved.

    Redistribution and use in source and binary forms, with or without
    modification, are permitted provided that the following conditions are met:
        *   Redistributions of source code must retain the above copyright
            notice, this list of conditions and the following disclaimer.
        *   Redistributions in binary form must reproduce the above copyright
            notice, this list of conditions and the following disclaimer in the
            documentation and/or other materials provided with the distribution.
        *   Neither the name of the author nor the names of its contributors may 
            be used to endorse or promote products derived from this software without
            specific prior written permission.

    THIS SOFTWARE IS PROVIDED BY THE REGENTS AND CONTRIBUTORS ``AS IS'' AND ANY
    EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
    WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
    DISCLAIMED. IN NO EVENT SHALL THE REGENTS AND CONTRIBUTORS BE LIABLE FOR ANY
    DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
    (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
    LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
    ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
    (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
    SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
-->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/">
        <html>
            <head>
                <title>
					<xsl:value-of select="/project/@name" /> v<xsl:value-of select="/project/@version" /> - SQL
                </title>
                <style type="text/css">
                    body {
                        font-family: Courier New;
                        font-size: 90%;
                        background-color: #eeeeee;
                        margin: 0;
                    }
                    div#sql {
                        margin: auto;
                        background-color: white;
                        border-left: 1px solid #333;
                        border-right: 1px solid #333;
                        color: black;
                        width: 90%;
                        padding: 5px;
                    }
                    span.keyword {
                        color: #099;
                        font-weight: bold;
                    }
                    span.name {
                        color: #090;
                    }
                    span.type {
                        color: #900;
                    }
                    span.string {
                        color: magenta;
                    }
                    div.indent {
                        padding-left: 30px;
                    }
                    div.copyright {
                        font-family: Helvetica;
                        color: #aaa;
                        text-align: center;
                        width: 400px;
                        margin: auto;
                        margin-top: 20px;
                        padding-top: 5px;
                        padding-bottom: 5px;
                        font-size: 80%;
                    }
                    a, a:hover, a:link, a:active {
                        text-decoration: underline;
                        color: #ccc;
                    }
                </style>
            </head>
            <body><div id="sql">
				<xsl:for-each select="/project/database">
					<xsl:sort select="@name" />
                    <a href="http://dev.mysql.com/doc/refman/5.0/en/use.html"><span class="keyword">USE</span></a><span class="name"> `<xsl:value-of select="@name" />`</span>;<br /><br />
                    <xsl:for-each select="table">
                    <a href="http://dev.mysql.com/doc/refman/5.0/en/create-table.html"><span class="keyword">CREATE TABLE</span></a>
                    <span class="name"> `<xsl:value-of select="@name" />`</span>
                    (<br />
                    <div class="indent">
                        <xsl:for-each select="field">
                        <span class="name">`<xsl:value-of select="@name" />` </span>
                        <xsl:choose>
                            <xsl:when test="@type='int'">
                                <a href="http://dev.mysql.com/doc/refman/5.0/en/numeric-type-overview.html"><span class="type">INT</span></a><span class="type">(11)</span> <span class="type"> NOT NULL </span>
                                <xsl:if test="not(@autoincrement='yes')">
                                    <a href="http://dev.mysql.com/doc/refman/5.1/en/data-type-defaults.html"><span class="keyword">DEFAULT</span></a>
                                    <span class="string"> '<xsl:choose test="@default">
                                        <xsl:when test="not(@default)">0</xsl:when>
                                        <xsl:otherwise><xsl:value-of select="@default" /></xsl:otherwise>
                                    </xsl:choose>'</span>
                                </xsl:if>
                                <xsl:if test="@autoincrement='yes'">
                                    <a href="http://dev.mysql.com/doc/refman/5.1/en/example-auto-increment.html"><span class="keyword">AUTO_INCREMENT</span></a>
                                </xsl:if>
                            </xsl:when>
                            <xsl:when test="@type='datetime'">
                                <a href="http://dev.mysql.com/doc/refman/5.0/en/datetime.html"><span class="type">DATETIME</span></a> <span class="type"> NOT NULL </span>
                                <a href="http://dev.mysql.com/doc/refman/5.1/en/data-type-defaults.html"><span class="keyword">DEFAULT</span></a>
                                <span class="string"> '<xsl:choose>
                                    <xsl:when test="not(@default)">0000-00-00 00:00:00</xsl:when>
                                    <xsl:otherwise><xsl:value-of select="@default" /></xsl:otherwise>
                                </xsl:choose>'</span>
                            </xsl:when>
                            <xsl:when test="@type='varchar'">
                                <a href="http://dev.mysql.com/doc/refman/5.0/en/char.html"><span class="type">VARCHAR</span></a><span class="type">(<xsl:choose test="">
                                        <xsl:when test="not(@length)">32</xsl:when>
                                        <xsl:otherwise>
                                            <xsl:value-of select="@length" />
                                        </xsl:otherwise>
                                </xsl:choose>) </span>
                                <a href="http://dev.mysql.com/doc/refman/5.1/en/data-type-defaults.html"><span class="keyword">DEFAULT</span></a>
                                <span class="string"> '<xsl:choose>
                                    <xsl:when test="not(@default)"></xsl:when>
                                    <xsl:otherwise><xsl:value-of select="@default" /></xsl:otherwise>
                                </xsl:choose>'</span>
                            </xsl:when>
                            <xsl:when test="@type='enum'">
                                <a href="http://dev.mysql.com/doc/refman/5.0/en/enum.html"><span class="type">ENUM</span></a>
                                (<xsl:for-each select="value">
                                    <span class="string">'<xsl:value-of select="." />'</span><xsl:if test="position()&lt;last()">, </xsl:if>
                                </xsl:for-each>)
                                <a href="http://dev.mysql.com/doc/refman/5.1/en/data-type-defaults.html"><span class="keyword">DEFAULT</span></a>
                                <span class="string"> '<xsl:choose>
                                    <xsl:when test="not(value[@default='yes'])">
                                        <xsl:value-of select="value[1]" />
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <xsl:value-of select="value[@default='yes']" />
                                    </xsl:otherwise>
                                </xsl:choose>'</span>
                            </xsl:when>
                        </xsl:choose>
                        <xsl:if test="position()&lt;last()">,<br /></xsl:if>
                        </xsl:for-each>
                        <xsl:for-each select="index">
                            ,<br />
                            <span class="keyword">
                            <xsl:choose>
                                <xsl:when test="@type='primary'">
                                    PRIMARY
                                </xsl:when>
                                <xsl:when test="@type='unique'">
                                    UNIQUE
                                </xsl:when>
                            </xsl:choose>
                            KEY
                            </span>
                            <xsl:if test="@name">
                                <span class="name">`<xsl:value-of select="@name" />`</span>
                            </xsl:if>
                            (<xsl:for-each select="indexfield">
                                <span class="name">`<xsl:value-of select="." />`</span>
                                <xsl:if test="position()&lt;last()">,</xsl:if>
                            </xsl:for-each>)
                        </xsl:for-each>
                    </div>);<br /><br />
                    </xsl:for-each>
                </xsl:for-each>
                <div class="copyright">
                    (c) 2007 Dionysis Zindros
                </div>
            </div></body>
        </html>
    </xsl:template>
</xsl:stylesheet>
