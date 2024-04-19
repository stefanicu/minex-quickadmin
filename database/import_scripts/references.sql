# references import
DELETE FROM minexq.references;
ALTER TABLE minexq.references AUTO_INCREMENT = 1;

INSERT INTO minexq.references(id, online, oldid, oldimage_1, oldimage_2, oldimage_3, oldimage_4, oldimage_5, industries_id)
SELECT * FROM (
    SELECT id, IF(online > 0, 1, 0) AS online, id AS oldid, img AS oldimage_1, img1 AS oldimage_2, img2 AS oldimage_3, img3 AS oldimage_4, img4 AS oldimage_5, id_ind AS industries_id
    FROM minex_live.referinte WHERE del='0'
) AS referencesx;

INSERT INTO minexq.reference_translations(online, locale, name, slug, content, reference_id)
SELECT * FROM (
    SELECT IF(online > 0, 1, 0) AS online, 'ro' AS locale, trim(nume) AS name, trim(slug), trim(content), id AS reference_id FROM minex_live.referinte WHERE del='0' and nume!=''
        UNION
    SELECT IF(online > 0, 1, 0) AS online, 'en' AS locale, trim(nume1) AS name, trim(slug1) AS slug, trim(content1) as content, id AS reference_id FROM minex_live.referinte WHERE del='0' and nume1!=''
        UNION
    SELECT IF(online > 0, 1, 0) AS online, 'bg' AS locale, trim(nume2) AS name, trim(slug2) AS slug, trim(content2) as content, id AS reference_id FROM minex_live.referinte WHERE del='0' and nume2!=''
) AS reference_translations;


