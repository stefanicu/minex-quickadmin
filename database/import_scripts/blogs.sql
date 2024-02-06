# blogs import
DELETE FROM minexq.blogs;
ALTER TABLE minexq.blogs AUTO_INCREMENT = 1;

INSERT INTO minexq.blogs(id, online, language, name, slug, content, created_at, oldid, oldimage)
SELECT * FROM (
#     SELECT id, IF(online > 0, 1, 0) AS online, 'ro' AS language, nume AS name, slug, content, dt as created_at, id AS oldid, img AS oldimage
#     FROM minex_live.iqindustrial WHERE del='0' and nume!=''
#         UNION
    SELECT id, IF(online > 0, 1, 0) AS online, 'en' AS language, nume1 AS name, slug1 AS slug, content1 as content, dt as created_at, id AS oldid, img AS oldimage
    FROM minex_live.iqindustrial WHERE del='0' and nume1!=''
#         UNION
#     SELECT id, IF(online > 0, 1, 0) AS online, 'bg' AS language, nume2 AS name, slug2 AS slug, content2 as content, dt as created_at, id AS oldid, img AS oldimage
#     FROM minex_live.iqindustrial WHERE del='0' and nume2!=''

        UNION

#     SELECT id, IF(online > 0, 1, 0) AS online, 'ro' AS language, nume AS name, slug, content, dt as created_at, id AS oldid, img AS oldimage
#     FROM minex_live.noutati WHERE del='0' and nume!=''
#         UNION
    SELECT id, IF(online > 0, 1, 0) AS online, 'en' AS language, nume1 AS name, slug1 AS slug, content1 as content, dt as created_at, id AS oldid, img AS oldimage
    FROM minex_live.noutati WHERE del='0' and nume1!=''
#         UNION
#     SELECT id, IF(online > 0, 1, 0) AS online, 'bg' AS language, nume2 AS name, slug2 AS slug, content2 as content, dt as created_at, id AS oldid, img AS oldimage
#     FROM minex_live.noutati WHERE del='0' and nume2!=''
) AS blogs;
