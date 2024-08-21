# application_product import in apl_pro, process, delete apl_pro
DROP TABLE IF EXISTS $minexq.apl_pro;
CREATE TABLE $minexq.apl_pro (
    `id_apl` bigint NULL,
    `id_pro` bigint NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO $minexq.apl_pro(id_apl,id_pro)
SELECT * FROM (
    SELECT id_apl, id_pro FROM $minex_live.apl_ind_pro WHERE 1 GROUP BY id_apl, id_pro
) AS apl_pro;

DELETE FROM $minexq.application_product;
INSERT INTO $minexq.application_product(application_id,product_id)
SELECT * FROM (
    SELECT a.id AS application_id, c.id AS product_id FROM $minexq.apl_pro ac
        INNER JOIN $minexq.applications a on ac.id_apl = a.oldid
        INNER JOIN $minexq.products c on ac.id_pro = c.oldid
    ) AS application_product;
DROP TABLE IF EXISTS $minexq.apl_pro;
