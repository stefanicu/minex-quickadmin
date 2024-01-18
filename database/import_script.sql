USE minex_live;

# applications import
DELETE FROM minexq.applications;
ALTER TABLE minexq.applications AUTO_INCREMENT = 1;

INSERT INTO minexq.applications(oldid, online, language, name, slug, oldimage)
SELECT * FROM (
    SELECT id AS oldid, IF(online > 0, 1, 0) AS online, 'ro' AS language, nume AS name, slug, img AS oldimage FROM `aplicatii` WHERE del='0' and nume!=''
        UNION
    SELECT id AS oldid, IF(online > 0, 1, 0) AS online, 'en' AS language, nume1 AS name, slug1 AS slug, img AS oldimage FROM `aplicatii` WHERE del='0' and nume1!=''
        UNION
    SELECT id AS oldid, IF(online > 0, 1, 0) AS online, 'bg' AS language, nume2 AS name, slug2 AS slug, img AS oldimage FROM `aplicatii` WHERE del='0' and nume2!=''
) AS applications;


# categories import
DELETE FROM minexq.categories;
ALTER TABLE minexq.categories AUTO_INCREMENT = 1;

INSERT INTO minexq.categories(online, language, name, slug, oldid, oldgroupid, oldimage, oldproductid, oldproductimg, page_views)
SELECT * FROM (
    SELECT id AS oldid, id_gru AS oldgroupid, IF(online > 0, 1, 0) AS online, 'ro' AS language, nume AS name, slug, img_xl AS oldimage, id_pro AS oldproductid, img_pro AS oldproductimg, viz AS page_views
    FROM `categorii` WHERE del='0' and nume!=''
        UNION
    SELECT id AS oldid, id_gru AS oldgroupid, IF(online > 0, 1, 0) AS online, 'en' AS language, nume1 AS name, slug1 AS slug, img_xl AS oldimage, id_pro AS oldproductid, img_pro AS oldproductimg, viz AS page_views
    FROM `categorii` WHERE del='0' and nume1!=''
        UNION
    SELECT id AS oldid, id_gru AS oldgroupid, IF(online > 0, 1, 0) AS online, 'bg' AS language, nume2 AS name, slug2 AS slug, img_xl AS oldimage, id_pro AS oldproductid, img_pro AS oldproductimg, viz AS page_views
    FROM `categorii` WHERE del='0' and nume2!=''
) AS categories;


# application_category import in apl_cat, process, delete apl_cat
USE minexq;
CREATE TABLE `apl_cat` (
    `id_apl` bigint NOT NULL,
    `id_cat` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci

INSERT INTO minexq.apl_cat(id_apl,id_cat)
SELECT * FROM (
    SELECT id_apl, id_cat FROM `apl_ind_cat` WHERE 1 GROUP BY id_apl, id_cat
) AS apl_cat;

USE minexq;
DELETE FROM minexq.application_category;
INSERT INTO minexq.application_category(category_id,application_id)
SELECT * FROM (
    SELECT c.id AS category_id, a.id AS application_id FROM `apl_cat` ac
        INNER JOIN applications a on ac.id_apl = a.oldid
        INNER JOIN categories c on ac.id_cat = c.oldid AND c.language = a.language
) AS application_category;
DROP TABLE minexq.apl_cat;


USE minex_live;
