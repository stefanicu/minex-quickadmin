
# run this command to bring all quick admin changes and custom user, this will clean the database and you will need to run entire import
# php artisan migrate:fresh --seed

# applications import
DELETE FROM minexq.applications;
ALTER TABLE minexq.applications AUTO_INCREMENT = 1;

INSERT INTO minexq.applications(online, language, name, slug, oldid, oldimage)
SELECT * FROM (
    SELECT IF(online > 0, 1, 0) AS online, 'ro' AS language, nume AS name, slug, id AS oldid, img AS oldimage FROM minex_live.aplicatii WHERE del='0' and nume!=''
#         UNION
#     SELECT IF(online > 0, 1, 0) AS online, 'en' AS language, nume1 AS name, slug1 AS slug, id AS oldid, img AS oldimage FROM minex_live.aplicatii WHERE del='0' and nume1!=''
#         UNION
#     SELECT IF(online > 0, 1, 0) AS online, 'bg' AS language, nume2 AS name, slug2 AS slug, id AS oldid, img AS oldimage FROM minex_live.aplicatii WHERE del='0' and nume2!=''
) AS applications;


# categories import
DELETE FROM minexq.categories;
ALTER TABLE minexq.categories AUTO_INCREMENT = 1;

INSERT INTO minexq.categories(online, language, name, slug, oldid, oldgroupid, oldimage, oldproductid, oldproductimg, page_views)
SELECT * FROM (
    SELECT IF(online > 0, 1, 0) AS online, 'ro' AS language, nume AS name, slug, id AS oldid, id_gru AS oldgroupid, img_xl AS oldimage, id_pro AS oldproductid, img_pro AS oldproductimg, viz AS page_views
    FROM minex_live.categorii WHERE del='0' and nume!=''
#         UNION
#     SELECT IF(online > 0, 1, 0) AS online, 'en' AS language, nume1 AS name, slug1 AS slug, id AS oldid, id_gru AS oldgroupid, img_xl AS oldimage, id_pro AS oldproductid, img_pro AS oldproductimg, viz AS page_views
#     FROM minex_live.categorii WHERE del='0' and nume1!=''
#         UNION
#     SELECT IF(online > 0, 1, 0) AS online, 'bg' AS language, nume2 AS name, slug2 AS slug, id AS oldid, id_gru AS oldgroupid, img_xl AS oldimage, id_pro AS oldproductid, img_pro AS oldproductimg, viz AS page_views
#     FROM minex_live.categorii WHERE del='0' and nume2!=''
) AS categories;


# application_category import in apl_cat, process, delete apl_cat
USE minex_live;
CREATE TABLE minexq.apl_cat (
    `id_apl` bigint NULL,
    `id_cat` bigint NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO minexq.apl_cat(id_apl,id_cat)
SELECT * FROM (
    SELECT id_apl, id_cat FROM minex_live.apl_ind_cat WHERE 1 GROUP BY id_apl, id_cat
) AS apl_cat;

DELETE FROM minexq.application_category;
INSERT INTO minexq.application_category(category_id,application_id)
SELECT * FROM (
    SELECT c.id AS category_id, a.id AS application_id FROM minexq.apl_cat
        INNER JOIN minexq.applications a on ac.id_apl = a.oldid
        INNER JOIN minexq.categories c on ac.id_cat = c.oldid AND c.language = a.language
) AS application_category;
DROP TABLE IF EXISTS minexq.apl_cat;


# blogs import
DELETE FROM minexq.blogs;
ALTER TABLE minexq.blogs AUTO_INCREMENT = 1;

INSERT INTO minexq.blogs(online, language, name, slug, content, created_at, oldid, oldimage)
SELECT * FROM (
    SELECT IF(online > 0, 1, 0) AS online, 'ro' AS language, nume AS name, slug, content, dt as created_at, id AS oldid, img AS oldimage
    FROM minex_live.iqindustrial WHERE del='0' and nume!=''
#         UNION
#     SELECT IF(online > 0, 1, 0) AS online, 'en' AS language, nume1 AS name, slug1 AS slug, content1 as content, dt as created_at, id AS oldid, img AS oldimage
#     FROM minex_live.iqindustrial WHERE del='0' and nume1!=''
#         UNION
#     SELECT IF(online > 0, 1, 0) AS online, 'bg' AS language, nume2 AS name, slug2 AS slug, content2 as content, dt as created_at, id AS oldid, img AS oldimage
#     FROM minex_live.iqindustrial WHERE del='0' and nume2!=''

        UNION

    SELECT IF(online > 0, 1, 0) AS online, 'ro' AS language, nume AS name, slug, content, dt as created_at, id AS oldid, img AS oldimage
    FROM minex_live.noutati WHERE del='0' and nume!=''
#         UNION
#     SELECT IF(online > 0, 1, 0) AS online, 'en' AS language, nume1 AS name, slug1 AS slug, content1 as content, dt as created_at, id AS oldid, img AS oldimage
#     FROM minex_live.noutati WHERE del='0' and nume1!=''
#         UNION
#     SELECT IF(online > 0, 1, 0) AS online, 'bg' AS language, nume2 AS name, slug2 AS slug, content2 as content, dt as created_at, id AS oldid, img AS oldimage
#     FROM minex_live.noutati WHERE del='0' and nume2!=''
) AS blogs;

#brands import
DELETE FROM minexq.brands;
ALTER TABLE minexq.brands AUTO_INCREMENT = 1;

INSERT INTO minexq.brands(online, name, slug, oldid, oldimage)
SELECT * FROM (
    SELECT IF(online > 0, 1, 0) AS online, nume AS name, slug, id AS oldid, img AS oldimage
    FROM minex_live.branduri WHERE del='0' and nume!=''
) AS applications;


