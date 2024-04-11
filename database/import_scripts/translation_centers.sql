# translation_centers import
DELETE FROM minexq.translation_centers;
ALTER TABLE minexq.translation_centers AUTO_INCREMENT = 1;

INSERT INTO minexq.translation_centers(id)
SELECT * FROM (
    SELECT id
    FROM minex_live.texte
) AS translation_centers;

INSERT INTO minexq.translation_center_translations(locale, name, translation_center_id)
SELECT * FROM (
    SELECT 'ro'  AS locale, trim(nume) AS name, id as traslation_center_id
    FROM minex_live.texte
        UNION
    SELECT 'en'  AS locale, trim(nume1) AS name, id as traslation_center_id
    FROM minex_live.texte
        UNION
    SELECT 'bg' AS locale, trim(nume2) AS name, id as traslation_center_id
    FROM minex_live.texte

) AS translation_center_translations;
