# categories import
DELETE FROM $minexq.categories;
ALTER TABLE $minexq.categories AUTO_INCREMENT = 1;

INSERT INTO $minexq.categories(id, online, page_views, oldid, oldgroupid, oldimage, oldproductid, oldproductimg)
SELECT * FROM (
     SELECT id, IF(online > 0, 1, 0) AS online, viz AS page_views, id AS oldid, id_gru AS oldgroupid, img_xl AS oldimage, id_pro AS oldproductid, img_pro AS oldproductimg
     FROM $minex_live.categorii WHERE del='0'
) AS categories;

INSERT INTO $minexq.category_translations(online, locale, name, slug, category_id)
SELECT * FROM (
     SELECT IF(online > 0, 1, 0) AS online, 'ro'  AS locale, trim(nume) AS name, trim(slug), id as category_id
     FROM $minex_live.categorii WHERE del='0' and nume!=''
         UNION
    SELECT IF(online > 0, 1, 0) AS online, 'en'  AS locale, trim(nume1) AS name, trim(slug1) AS slug, id as category_id
    FROM $minex_live.categorii WHERE del='0' and nume1!=''
         UNION
     SELECT IF(online > 0, 1, 0) AS online, 'bg' AS locale, trim(nume2) AS name, trim(slug2) AS slug, id as category_id
     FROM $minex_live.categorii WHERE del='0' and nume2!=''
) AS category_translations;

