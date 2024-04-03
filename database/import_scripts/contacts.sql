#contacts import
DELETE FROM minexq.contacts;
ALTER TABLE minexq.contacts AUTO_INCREMENT = 1;

INSERT INTO minexq.contacts(id, name, surname, email, job, industry, how_about, message, company, phone, country, county, city, checkbox, product, created_at)
SELECT * FROM (
    SELECT
        id,
        nume AS name,
        pren AS surname,
        email,
        func AS job,
        dom AS industry,
        cumati AS how_about,
        mesaj AS message,
        companie AS company,
        tel AS phone,
        tara AS country,
        jud AS county,
        loca AS city,
        acord AS checkbox,
        id_pro AS product,
        datai AS created_at
    FROM minex_live.contact WHERE del='0'
) AS contacts;
