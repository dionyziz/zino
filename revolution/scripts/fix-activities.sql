/*
UPDATE
    `activities` 
    LEFT JOIN `journals` ON 
        ( `activity_typeid` IN (1,2) AND 
        `activity_itemid` = `journal_id` ) OR
        ( `activity_typeid` = 7 AND 
        `activity_refid` = `journal_id` )
SET
    `activity_text` = `journal_title`,
    `activity_url` = `journal_url`
WHERE
    `activity_itemtype` = 4 AND
    `activity_typeid` IN (1,2,7);

UPDATE
    `activities` 
    LEFT JOIN `polls` ON 
        ( `activity_typeid` IN (1,2) AND 
        `activity_itemid` = `poll_id` ) OR
        ( `activity_typeid` = 7 AND 
        `activity_refid` = `poll_id` )
SET
    `activity_text` = `poll_question`
WHERE
    `activity_text` LIKE '%???%' AND
    `activity_itemtype` = 1 AND
    `activity_typeid` IN (1,2,7);
*/

UPDATE
    `activities` 
    LEFT JOIN `images` ON 
        ( `activity_typeid` IN (1,2) AND 
        `activity_itemid` = `image_id` ) OR
        ( `activity_typeid` = 7 AND 
        `activity_refid` = `image_id` )
SET
    `activity_text` = `image_name`
WHERE
    `activity_text` LIKE '%???%' AND
    `activity_itemtype` = 2 AND
    `activity_typeid` IN (1,2,7);
/*
UPDATE
    `activities`
    LEFT JOIN `statusbox` ON
        `statusbox_id` = `activity_refid`
SET
    `activity_text` = `statusbox_message`
WHERE
    `activity_typeid` = 6;
