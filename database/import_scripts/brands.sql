#brands import
DELETE FROM $minexq.brands;
ALTER TABLE $minexq.brands AUTO_INCREMENT = 1;

INSERT INTO $minexq.brands(id, online, name, slug, oldid, oldimage)
SELECT * FROM (
    SELECT id, IF(online > 0, 1, 0) AS online, trim(nume) AS name, trim(slug), id AS oldid, img AS oldimage
    FROM $minex_live.branduri WHERE del='0' and nume!=''
) AS brands;

INSERT INTO $minexq.brand_translations(locale,brand_id, online)
SELECT * FROM (
    SELECT 'en'  AS locale, id as brand_id,IF(online > 0, 1, 0) AS online
    FROM $minex_live.branduri WHERE del='0' and nume!=''
        UNION
    SELECT 'ro'  AS locale, id as brand_id,IF(online > 0, 1, 0) AS online
    FROM $minex_live.branduri WHERE del='0' and nume!=''
        UNION
    SELECT 'bg'  AS locale, id as brand_id,IF(online > 0, 1, 0) AS online
    FROM $minex_live.branduri WHERE del='0' and nume!=''
) AS brand_translations;
