# application_category import in apl_cat, process, delete apl_cat
DROP TABLE IF EXISTS minexq.apl_cat;
CREATE TABLE minexq.apl_cat (
    `id_apl` bigint NULL,
    `id_cat` bigint NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO minexq.apl_cat(id_apl,id_cat)
SELECT * FROM (
    SELECT id_apl, id_cat FROM minex_live.apl_ind_cat WHERE 1 GROUP BY id_apl, id_cat
) AS apl_cat;

DELETE FROM minexq.application_category;
INSERT INTO minexq.application_category(application_id,category_id)
SELECT * FROM (
    SELECT a.id AS application_id, c.id AS category_id FROM minexq.apl_cat ac
        INNER JOIN minexq.applications a on ac.id_apl = a.oldid
        INNER JOIN minexq.categories c on ac.id_cat = c.oldid AND c.language = a.language
    ) AS application_category;
#DROP TABLE IF EXISTS minexq.apl_cat;
