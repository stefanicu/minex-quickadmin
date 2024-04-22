#brands import
DELETE FROM $minexq.brands;
ALTER TABLE $minexq.brands AUTO_INCREMENT = 1;

INSERT INTO $minexq.brands(id, online, name, slug, oldid, oldimage)
SELECT * FROM (
    SELECT id, IF(online > 0, 1, 0) AS online, trim(nume) AS name, trim(slug), id AS oldid, img AS oldimage
    FROM $minex_live.branduri WHERE del='0' and nume!=''
) AS brands;
