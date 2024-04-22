# front_pages import
DELETE FROM $minexq.front_pages;
ALTER TABLE $minexq.front_pages AUTO_INCREMENT = 1;

INSERT INTO $minexq.front_pages(id, oldid, oldimage)
SELECT * FROM (
    SELECT online as id, id AS oldid, img AS oldimage FROM $minex_live.acasa WHERE del='0'
) AS front_pages;


DELETE FROM $minexq.front_page_translations;
ALTER TABLE $minexq.front_page_translations AUTO_INCREMENT = 1;
INSERT INTO $minexq.front_page_translations(locale, name, first_text, second_text, quote, button, front_page_id)
SELECT * FROM (
    SELECT 'ro' AS locale, trim(nume) AS name, trim(text) AS first_text, trim(textb) AS second_text, trim(citat) AS quote, trim(buton) AS button, online AS front_page_id FROM $minex_live.acasa WHERE del='0' and nume!=''
        UNION
    SELECT 'en' AS locale, trim(nume1) AS name, trim(text1) AS first_text, trim(textb1) AS second_text, trim(citat1) AS quote, trim(buton1) AS button, online AS front_page_id FROM $minex_live.acasa WHERE del='0' and nume1!=''
        UNION
    SELECT 'bg' AS locale, trim(nume2) AS name, trim(text2) AS first_text, trim(textb2) AS second_text, trim(citat2) AS quote, trim(buton2) AS button, online AS front_page_id FROM $minex_live.acasa WHERE del='0' and nume2!=''
) AS front_page_translations;
