USE minex_live;

# applications import
DELETE FROM minexgroup.applications;
ALTER TABLE minexgroup.applications AUTO_INCREMENT = 1;

INSERT INTO minexgroup.applications(old_id, online, language, name, slug, image)
SELECT * FROM (
    SELECT id AS old_id, IF(online > 0, 1, 0) AS online, 'ro' AS language, nume AS name, slug, img AS image FROM `aplicatii` WHERE del='0' and nume!=''
        UNION
    SELECT id AS old_id, IF(online > 0, 1, 0) AS online, 'en' AS language, nume1 AS name, slug1 AS slug, img AS image FROM `aplicatii` WHERE del='0' and nume1!=''
        UNION
    SELECT id AS old_id, IF(online > 0, 1, 0) AS online, 'bg' AS language, nume2 AS name, slug2 AS slug, img AS image FROM `aplicatii` WHERE del='0' and nume2!=''
) AS applications;


# categories import
DELETE FROM minexgroup.categories;
ALTER TABLE minexgroup.categories AUTO_INCREMENT = 1;

INSERT INTO minexgroup.categories(old_id, group_id, online, language, name, slug, image, product_id, product_image, page_views)
SELECT * FROM (
    SELECT id AS old_id, id_gru AS group_id, IF(online > 0, 1, 0) AS online, 'ro' AS language, nume AS name, slug, img_xl AS image, id_pro AS product_id, img_pro AS product_image, viz AS page_views
    FROM `categorii` WHERE del='0' and nume!=''
        UNION
    SELECT id AS old_id, id_gru AS group_id, IF(online > 0, 1, 0) AS online, 'en' AS language, nume1 AS name, slug1 AS slug, img_xl AS image, id_pro AS product_id, img_pro AS product_image, viz AS page_views
    FROM `categorii` WHERE del='0' and nume1!=''
        UNION
    SELECT id AS old_id, id_gru AS group_id, IF(online > 0, 1, 0) AS online, 'bg' AS language, nume2 AS name, slug2 AS slug, img_xl AS image, id_pro AS product_id, img_pro AS product_image, viz AS page_views
    FROM `categorii` WHERE del='0' and nume2!=''
) AS categories;


# application_category import in apl_cat, process, delete apl_cat
DELETE FROM minexgroup.apl_cat;

INSERT INTO minexgroup.apl_cat(id_apl,id_cat)
SELECT * FROM (
    SELECT id_apl, id_cat FROM `apl_ind_cat` WHERE 1 GROUP BY id_apl, id_cat
) AS apl_cat;

USE minexgroup;
DELETE FROM minexgroup.application_category;
INSERT INTO minexgroup.application_category(category_id,application_id)
SELECT * FROM (
    SELECT c.id AS category_id, a.id AS application_id FROM `apl_cat` ac
        INNER JOIN applications a on ac.id_apl = a.old_id
        INNER JOIN categories c on ac.id_cat = c.old_id AND c.language = a.language
) AS application_category;


USE minex_live;
