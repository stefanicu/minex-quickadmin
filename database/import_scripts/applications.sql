# applications import
DELETE FROM $minexq.applications;
ALTER TABLE $minexq.applications AUTO_INCREMENT = 1;

INSERT INTO $minexq.applications(id, online, oldid, oldimage)
SELECT * FROM (
    SELECT id, IF(online > 0, 1, 0) AS online, id AS oldid, img AS oldimage FROM $minex_live.aplicatii WHERE del='0'
) AS applications;

INSERT INTO $minexq.application_translations(online, locale, name, slug, application_id)
SELECT * FROM (
    SELECT IF(online > 0, 1, 0) AS online, 'ro' AS locale, trim(nume) AS name, trim(slug), id AS application_id FROM $minex_live.aplicatii WHERE del='0' and nume!=''
        UNION
    SELECT IF(online > 0, 1, 0) AS online, 'en' AS locale, trim(nume1) AS name, trim(slug1) AS slug, id AS application_id FROM $minex_live.aplicatii WHERE del='0' and nume1!=''
        UNION
    SELECT IF(online > 0, 1, 0) AS online, 'bg' AS locale, trim(nume2) AS name, trim(slug2) AS slug, id AS application_id FROM $minex_live.aplicatii WHERE del='0' and nume2!=''
) AS application_translations;


