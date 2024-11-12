# application_product import in cat_pro, process, delete cat_pro
DROP TABLE IF EXISTS $minexq.cat_pro;
CREATE TABLE $minexq.cat_pro (
    `id_cat` bigint NULL,
    `id_pro` bigint NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO $minexq.cat_pro(id_cat,id_pro)
SELECT * FROM (
    SELECT id_cat, id_pro FROM $minex_live.cat_pro WHERE 1 GROUP BY id_cat, id_pro
) AS cat_pro;

DELETE FROM $minexq.category_product;
INSERT INTO $minexq.category_product(category_id,product_id)
SELECT * FROM (
    SELECT a.id AS category_id, c.id AS product_id FROM $minexq.cat_pro ac
        INNER JOIN $minexq.categories a on ac.id_cat = a.oldid
        INNER JOIN $minexq.products c on ac.id_pro = c.oldid
    ) AS category_product;
DROP TABLE IF EXISTS $minexq.cat_pro;
