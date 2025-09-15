DELETE
FROM $dbName.front_page_translations
where locale = 'sr';
DELETE
FROM $dbName.industry_translations
where locale = 'sr';
UPDATE $dbName.application_translations
SET locale = 'sr'
WHERE locale = 'rs';
UPDATE $dbName.blog_translations
SET locale = 'sr'
WHERE locale = 'rs';
UPDATE $dbName.brand_translations
SET locale = 'sr'
WHERE locale = 'rs';
UPDATE $dbName.category_translations
SET locale = 'sr'
WHERE locale = 'rs';
UPDATE $dbName.filter_translations
SET locale = 'sr'
WHERE locale = 'rs';
UPDATE $dbName.front_page_translations
SET locale = 'sr'
WHERE locale = 'rs';
UPDATE $dbName.industry_translations
SET locale = 'sr'
WHERE locale = 'rs';
UPDATE $dbName.page_translations
SET locale = 'sr'
WHERE locale = 'rs';
UPDATE $dbName.productfile_translations
SET locale = 'sr'
WHERE locale = 'rs';
UPDATE $dbName.product_translations
SET locale = 'sr'
WHERE locale = 'rs';
UPDATE $dbName.reference_translations
SET locale = 'sr'
WHERE locale = 'rs';
UPDATE $dbName.testimonial_translations
SET locale = 'sr'
WHERE locale = 'rs';
UPDATE $dbName.translation_center_translations
SET locale = 'sr'
WHERE locale = 'rs';