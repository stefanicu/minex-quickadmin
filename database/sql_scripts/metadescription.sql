UPDATE $dbName.application_translations
SET meta_description = REGEXP_REPLACE(meta_description, '<[^>]*>', '')
WHERE meta_description REGEXP '<[^>]*>';
UPDATE $dbName.blog_translations
SET meta_description = REGEXP_REPLACE(meta_description, '<[^>]*>', '')
WHERE meta_description REGEXP '<[^>]*>';
UPDATE $dbName.brand_translations
SET meta_description = REGEXP_REPLACE(meta_description, '<[^>]*>', '')
WHERE meta_description REGEXP '<[^>]*>';
UPDATE $dbName.category_translations
SET meta_description = REGEXP_REPLACE(meta_description, '<[^>]*>', '')
WHERE meta_description REGEXP '<[^>]*>';
# UPDATE $dbName.filter_translations
# SET meta_description = REGEXP_REPLACE(meta_description, '<[^>]*>', '')
# WHERE meta_description REGEXP '<[^>]*>';
# UPDATE $dbName.front_page_translations
# SET meta_description = REGEXP_REPLACE(meta_description, '<[^>]*>', '')
# WHERE meta_description REGEXP '<[^>]*>';
# UPDATE $dbName.industry_translations
# SET meta_description = REGEXP_REPLACE(meta_description, '<[^>]*>', '')
# WHERE meta_description REGEXP '<[^>]*>';
UPDATE $dbName.page_translations
SET meta_description = REGEXP_REPLACE(meta_description, '<[^>]*>', '')
WHERE meta_description REGEXP '<[^>]*>';
# UPDATE $dbName.productfile_translations
# SET meta_description = REGEXP_REPLACE(meta_description, '<[^>]*>', '')
# WHERE meta_description REGEXP '<[^>]*>';
UPDATE $dbName.product_translations
SET meta_description = REGEXP_REPLACE(meta_description, '<[^>]*>', '')
WHERE meta_description REGEXP '<[^>]*>';
UPDATE $dbName.reference_translations
SET meta_description = REGEXP_REPLACE(meta_description, '<[^>]*>', '')
WHERE meta_description REGEXP '<[^>]*>';
# UPDATE $dbName.testimonial_translations
# SET meta_description = REGEXP_REPLACE(meta_description, '<[^>]*>', '')
# WHERE meta_description REGEXP '<[^>]*>';
# UPDATE $dbName.translation_center_translations
# SET meta_description = REGEXP_REPLACE(meta_description, '<[^>]*>', '')
# WHERE meta_description REGEXP '<[^>]*>';