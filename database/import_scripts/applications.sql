# applications import
DELETE FROM minexq.applications;
ALTER TABLE minexq.applications AUTO_INCREMENT = 1;

INSERT INTO minexq.applications(id, online, language, name, slug, oldid, oldimage)
SELECT * FROM (
#     SELECT id, IF(online > 0, 1, 0) AS online, 'ro' AS language, nume AS name, slug, id AS oldid, img AS oldimage FROM minex_live.aplicatii WHERE del='0' and nume!=''
#         UNION
    SELECT id, IF(online > 0, 1, 0) AS online, 'en' AS language, nume1 AS name, slug1 AS slug, id AS oldid, img AS oldimage FROM minex_live.aplicatii WHERE del='0' and nume1!=''
#         UNION
#     SELECT id, IF(online > 0, 1, 0) AS online, 'bg' AS language, nume2 AS name, slug2 AS slug, id AS oldid, img AS oldimage FROM minex_live.aplicatii WHERE del='0' and nume2!=''
) AS applications;
