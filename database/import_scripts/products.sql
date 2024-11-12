# products import
DROP TABLE IF EXISTS $minexq.uploads;

DELETE FROM $minexq.products;
ALTER TABLE $minexq.products AUTO_INCREMENT = 1;

INSERT INTO $minexq.products(id, online, brand_id, oldid, oldimage, created_at, updated_at)
SELECT * FROM (
    SELECT id, IF(online > 0, 1, 0) AS online, id_bra AS brand_id, id AS oldid, img AS oldimage, now() AS created_at, datau AS updated_at FROM $minex_live.produse WHERE del='0'
) AS products;

CREATE TABLE $minexq.uploads (
     `id_pro` INT NOT NULL,
     `moreimages` TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO $minexq.uploads(id_pro, moreimages)
SELECT * FROM (
      SELECT
          idd as id_pro,
          GROUP_CONCAT(DISTINCT file_name SEPARATOR ',') as moreimages
      FROM
          $minex_live.uploads
      WHERE
          tabel = 'produse' and online = 0

      GROUP BY
          idd
) AS uploads;

UPDATE $minexq.products
    JOIN $minexq.uploads ON $minexq.products.id = $minexq.uploads.id_pro
SET $minexq.products.oldmoreimages = $minexq.uploads.moreimages;


INSERT INTO $minexq.productfiles(product_id, name, languages)
SELECT * FROM (
      SELECT
          idd as product_id,
          file_name as name,
          if(
              lng = '1',
              if(
                  lng1 = '1',
                  if(lng2 = '1', 'ro,en,bg', 'ro,en'),
                  if(lng2 = '1', 'ro,bg', 'ro')
              ),
              if(
                  lng1 = '1',
                  if(lng2 = '1', 'en,bg', 'en'),
                  if(lng3 = '1', 'bg', '')
              )
          ) as languages
      FROM
          $minex_live.uploads
      WHERE
          tabel = 'produse' and file_ext = '.pdf'

  ) AS productfiles;

INSERT INTO $minexq.productfile_translations(locale, productfile_id, online, title)
SELECT * FROM (
      SELECT
          'en' as locale,
          1 as productfile_id,
          1 as online,
          numefisier as title
      FROM
          $minex_live.uploads
      WHERE
          tabel = 'produse' and file_ext = '.pdf'

) AS productfiles;


UPDATE $minexq.productfile_translations SET productfile_id = id;


INSERT INTO $minexq.product_translations(online, locale, name, slug, description, specifications, advantages, usages, accessories, product_id)
SELECT * FROM (
    SELECT
        IF(online > 0, 1, 0) AS online, 'ro' AS locale, trim(nume) AS name, trim(slug), descriere AS description, specificatii AS specifications, avantaje AS advantages, aplicatii AS usages, accesorii AS accessories, id AS product_id
    FROM $minex_live.produse WHERE del='0' and nume != ''
        UNION
    SELECT IF(online > 0, 1, 0) AS online, 'en' AS locale, trim(nume1) AS name, trim(slug1) AS slug, descriere1 AS description, specificatii1 AS specifications, avantaje1 AS advantages, aplicatii1 AS usages, accesorii1 AS accessories, id AS product_id
    FROM $minex_live.produse WHERE del='0' and nume1 != ''
        UNION
    SELECT IF(online > 0, 1, 0) AS online, 'bg' AS locale, trim(nume2) AS name, trim(slug2) AS slug,  descriere2 AS description, specificatii2 AS specifications, avantaje2 AS advantages, aplicatii2 AS usages, accesorii2 AS accessories, id AS product_id
    FROM $minex_live.produse WHERE del='0' and nume2 != ''
) AS product_translations;

DROP TABLE IF EXISTS $minexq.uploads;
