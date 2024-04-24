# testimonials import
DELETE FROM $minexq.testimonials;
ALTER TABLE $minexq.testimonials AUTO_INCREMENT = 1;

INSERT INTO $minexq.testimonials(id, online, oldid, oldimage)
SELECT * FROM (
    SELECT id, IF(online > 0, 1, 0) AS online, id AS oldid, img AS oldimage FROM $minex_live.testimoniale WHERE del='0'
) AS testimonials;

INSERT INTO $minexq.testimonial_translations(online, locale, company, content, name, job, testimonial_id)
SELECT * FROM (
    SELECT IF(online > 0, 1, 0) AS online, 'ro' AS locale, trim(nume) AS company, trim(text) as content, ExtractValue(textb, '//em[1]') as name, ExtractValue(textb, '//em[2]') as job , id AS testimonial_id FROM $minex_live.testimoniale WHERE del='0' and nume!=''
        UNION
    SELECT IF(online > 0, 1, 0) AS online, 'en' AS locale, trim(nume1) AS company, trim(text1) as content, ExtractValue(textb1, '//em[1]') as name, ExtractValue(textb1, '//em[2]') as job , id AS testimonial_id FROM $minex_live.testimoniale WHERE del='0' and nume1!=''
        UNION
    SELECT IF(online > 0, 1, 0) AS online, 'bg' AS locale, trim(nume2) AS company, trim(text2) as content, ExtractValue(textb2, '//em[1]') as name, ExtractValue(textb2, '//em[2]') as job , id AS testimonial_id FROM $minex_live.testimoniale WHERE del='0' and nume2!=''
) AS testimonial_translations;


