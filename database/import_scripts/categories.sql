# categories import
DELETE FROM minexq.categories;
ALTER TABLE minexq.categories AUTO_INCREMENT = 1;

INSERT INTO minexq.categories(id, online, language, name, slug, oldid, oldgroupid, oldimage, oldproductid, oldproductimg, page_views)
SELECT * FROM (
#     SELECT id, IF(online > 0, 1, 0) AS online, 'ro' AS language, nume AS name, slug, id AS oldid, id_gru AS oldgroupid, img_xl AS oldimage, id_pro AS oldproductid, img_pro AS oldproductimg, viz AS page_views
#     FROM minex_live.categorii WHERE del='0' and nume!=''
#         UNION
    SELECT id, IF(online > 0, 1, 0) AS online, 'en' AS language, nume1 AS name, slug1 AS slug, id AS oldid, id_gru AS oldgroupid, img_xl AS oldimage, id_pro AS oldproductid, img_pro AS oldproductimg, viz AS page_views
    FROM minex_live.categorii WHERE del='0' and nume1!=''
#         UNION
#     SELECT id, IF(online > 0, 1, 0) AS online, 'bg' AS language, nume2 AS name, slug2 AS slug, id AS oldid, id_gru AS oldgroupid, img_xl AS oldimage, id_pro AS oldproductid, img_pro AS oldproductimg, viz AS page_views
#     FROM minex_live.categorii WHERE del='0' and nume2!=''
) AS categories;
