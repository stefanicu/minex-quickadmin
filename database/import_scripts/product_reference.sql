# product_reference import in pro_ref, process, delete pro_ref
DROP TABLE IF EXISTS $minexq.pro_ref;
CREATE TABLE $minexq.pro_ref (
    `id_ref` bigint NULL,
    `id_pro` bigint NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO $minexq.pro_ref(id_ref,id_pro)
SELECT * FROM (
    SELECT id_ref, id_pro FROM $minex_live.pro_ref WHERE 1 GROUP BY id_ref, id_pro
) AS pro_ref;

DELETE FROM $minexq.product_reference;
INSERT INTO $minexq.product_reference(reference_id,product_id)
SELECT * FROM (
    SELECT a.id AS reference_id, c.id AS product_id FROM $minexq.pro_ref ac
        INNER JOIN $minexq.references a on ac.id_ref = a.oldid
        INNER JOIN $minexq.products c on ac.id_pro = c.oldid
    ) AS product_reference;
DROP TABLE IF EXISTS $minexq.pro_ref;
