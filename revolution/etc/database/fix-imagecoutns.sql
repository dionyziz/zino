/* Fix images count */
UPDATE `usercounts` 
LEFT JOIN  ( 
SELECT count( `image_userid` ) as numimg,`image_userid` FROM `images`
WHERE `image_delid` = 0 
GROUP BY `image_userid` 
) as b
ON `count_userid` = b.`image_userid` 
SET `count_images` = IFNULL( b.numimg, 0 )
