# blogs import
DELETE FROM minexq.blogs;
ALTER TABLE minexq.blogs AUTO_INCREMENT = 1;

INSERT INTO minexq.blogs(id, online, created_at, oldid, oldimage, oldarticletype)
SELECT * FROM (

#     SELECT id,IF(online > 0, 1, 0) AS online, datai as created_at, id AS oldid, NULL AS oldimage, 'Menu' AS oldarticletype
#     FROM minex_live.cms WHERE del='0'
#
#     UNION

    SELECT id+50, IF(online > 0, 1, 0) AS online, datai as created_at, id AS oldid, img AS oldimage, 'IQ Industrial' AS oldarticletype
    FROM minex_live.iqindustrial WHERE del='0'

    UNION

    SELECT id+200, IF(online > 0, 1, 0) AS online, dt as created_at, id AS oldid, img AS oldimage, if(img='','Page','News') AS oldarticletype
    FROM minex_live.noutati WHERE del='0'

) AS blogs;


# INSERT INTO minexq.blog_translations(online, locale, name, slug, content, blog_id)
#
# SELECT * FROM (
#
#     SELECT IF(online > 0, 1, 0) AS online, 'ro'  AS locale, trim(nume) AS name, trim(slug), 'menu', id
#     FROM minex_live.cms WHERE del='0' and nume!=''
#     UNION
#     SELECT IF(online > 0, 1, 0) AS online, 'ro'  AS locale, trim(nume1) AS name, trim(slug1), 'menu', id
#     FROM minex_live.cms WHERE del='0' and nume1!=''
#     UNION
#     SELECT IF(online > 0, 1, 0) AS online, 'ro'  AS locale, trim(nume2) AS name, trim(slug2), 'menu', id
#     FROM minex_live.cms WHERE del='0' and nume2!=''
#
# ) AS blog_translations;

INSERT INTO minexq.blog_translations(online, locale, name, slug, content, oldimg1, oldimg2, oldimg3, oldcategory, blog_id)

SELECT * FROM (

    SELECT IF(a.online > 0, 1, 0) AS online, 'ro'  AS locale, trim(a.nume) AS name, trim(a.slug), a.content as content, a.img1 as oldimg1, a.img2 as oldimg2, a.img3 as oldimg3, c.nume as oldcategory, a.id+50 as blog_id
    FROM minex_live.iqindustrial as a left Join minex_live.iqcategorii as c on a.id_iqc = c.id WHERE a.del='0' and a.nume!=''
        UNION
    SELECT IF(a.online > 0, 1, 0) AS online, 'en'  AS locale, trim(a.nume1) AS name, trim(a.slug1) AS slug, a.content1 as content, a.img11 as oldimg1, a.img21 as oldimg2, a.img31 as oldimg3, c.nume1 as oldcategory, a.id+50 as blog_id
    FROM minex_live.iqindustrial as a left Join minex_live.iqcategorii as c on a.id_iqc = c.id WHERE a.del='0' and a.nume1!=''
        UNION
    SELECT IF(a.online > 0, 1, 0) AS online, 'bg' AS locale, trim(a.nume2) AS name, trim(a.slug2) AS slug, a.content2 as content, a.img12 as oldimg1, a.img22 as oldimg2, a.img32 as oldimg3, c.nume2 as oldcategory, a.id+50 as blog_id
    FROM minex_live.iqindustrial as a left Join minex_live.iqcategorii as c on a.id_iqc = c.id WHERE a.del='0' and a.nume2!=''

        UNION

    SELECT IF(online > 0, 1, 0) AS online, 'ro'  AS locale, trim(nume) AS name, trim(slug), content, img1 as oldimg1, img2 as oldimg2, img3 as oldimg3, if('img'='','Pagina','Noutati') as oldcategory, id+200 as blog_id
    FROM minex_live.noutati WHERE del='0' and nume!=''
        UNION
    SELECT IF(online > 0, 1, 0) AS online, 'en'  AS locale, trim(nume1) AS name, trim(slug1) AS slug, content1 as content, img11 as oldimg1, img21 as oldimg2, img31 as oldimg3, if('img'='','Page','News') as oldcategory, id+200 as blog_id
    FROM minex_live.noutati WHERE del='0' and nume1!=''
        UNION
    SELECT IF(online > 0, 1, 0) AS online, 'bg' AS locale, trim(nume2) AS name, trim(slug2) AS slug, content2 as content, img12 as oldimg1, img22 as oldimg2, img32 as oldimg3, if('img'='','Страница', 'Новини') as oldcategory, id+200 as blog_id
    FROM minex_live.noutati WHERE del='0' and nume2!=''

) AS blog_translations;
