DELETE FROM
    `merlin_comments`
WHERE
    `comment_textraw` REGEXP
        '[[.left-square-bracket.]]merlin[[.colon.]]link ([^[.right-square-bracket.][.vertical-line.]]*)[[.newline.]]([^[.right-square-bracket.]]*)[[.right-square-bracket.]]';
