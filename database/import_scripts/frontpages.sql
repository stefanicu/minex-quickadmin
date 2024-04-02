# frontpages import
DELETE FROM minexq.frontpages;
ALTER TABLE minexq.frontpages AUTO_INCREMENT = 1;

INSERT INTO minexq.frontpages(id, oldid, oldimage)
SELECT * FROM (
    SELECT id, id AS oldid, img AS oldimage FROM minex_live.acasa WHERE del='0'
) AS frontpages;

INSERT INTO minexq.frontpage_translations(locale, name, first_text, seccond_text, quote, button, frontpage_id)
SELECT * FROM (
    SELECT 'ro' AS locale, trim(nume) AS name, trim(text) AS first_text, trim(textb) AS seccond_text, trim(citat) AS quote, trim(buton) AS button, id AS frontpage_id FROM minex_live.acasa WHERE del='0' and nume!=''
        UNION
    SELECT 'en' AS locale, trim(nume1) AS name, trim(text1) AS first_text, trim(textb1) AS seccond_text, trim(citat1) AS quote, trim(buton1) AS button, id AS frontpage_id FROM minex_live.acasa WHERE del='0' and nume1!=''
        UNION
    SELECT 'bg' AS locale, trim(nume2) AS name, trim(text2) AS first_text, trim(textb2) AS seccond_text, trim(citat2) AS quote, trim(buton2) AS button, id AS frontpage_id FROM minex_live.acasa WHERE del='0' and nume2!=''
) AS frontpage_translations;


