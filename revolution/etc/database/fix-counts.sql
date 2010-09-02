/* Fix images count */
UPDATE `usercounts` 
LEFT JOIN  ( 
SELECT count( `image_userid` ) as numimg,`image_userid` FROM `images`
WHERE `image_delid` = 0 
GROUP BY `image_userid` 
) as b
ON `count_userid` = b.`image_userid` 
SET `count_images` = IFNULL( b.numimg, 0 )


UPDATE `usercounts` 
LEFT JOIN  ( 
SELECT count( `journal_userid` ) as numjournals,`journal_userid` FROM `journals`
WHERE `journal_delid` = 0 
GROUP BY `journal_userid` 
) as b
ON `count_userid` = b.`journal_userid` 
SET `count_journals` = IFNULL( b.numjournals, 0 )

UPDATE `usercounts` 
LEFT JOIN  ( 
SELECT count( `poll_userid` ) as numpolls,`poll_userid` FROM `polls`
WHERE `poll_delid` = 0  
GROUP BY `poll_userid` 
) as b
ON `count_userid` = b.`poll_userid` 
SET `count_polls` = IFNULL( b.numpolls, 0 )
