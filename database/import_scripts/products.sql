# products import
DELETE FROM $minexq.products;
ALTER TABLE $minexq.products AUTO_INCREMENT = 1;

INSERT INTO $minexq.products(id, online, brand_id, oldid, oldimage, created_at, updated_at)
SELECT * FROM (
    SELECT id, IF(online > 0, 1, 0) AS online, id_bra AS brand_id, id AS oldid, img AS oldimage, now() AS created_at, datau AS updated_at FROM $minex_live.produse WHERE del='0'
) AS products;

INSERT INTO $minexq.product_translations(online, locale, name, slug, description, specifications, advantages, usages, accessories, product_id)
SELECT * FROM (
    SELECT
        IF(online > 0, 1, 0) AS online, 'ro' AS locale, trim(nume) AS name, trim(slug), descriere AS description, specificatii AS specifications, avantaje AS advantages, aplicatii AS usages, accesorii AS accessories, id AS product_id
    FROM $minex_live.produse WHERE del='0' and nume != ''
        UNION
    SELECT IF(online > 0, 1, 0) AS online, 'en' AS locale, trim(nume1) AS name, trim(slug1) AS slug, descriere1 AS description, specificatii1 AS specifications, avantaje1 AS advantages, aplicatii1 AS usages, accesorii1 AS accessories, id AS product_id
    FROM $minex_live.produse WHERE del='0' and nume1 != '' and id != 588
        UNION
    SELECT IF(online > 0, 1, 0) AS online, 'bg' AS locale, trim(nume2) AS name, trim(slug2) AS slug,  descriere2 AS description, specificatii2 AS specifications, avantaje2 AS advantages, aplicatii2 AS usages, accesorii2 AS accessories, id AS product_id
    FROM $minex_live.produse WHERE del='0' and nume2 != ''
) AS product_translations;


