# Back Up Media table
DROP TABLE IF EXISTS `minex_live.media_bkp`;
CREATE TABLE minex_live.media_bkp AS SELECT * FROM minexq.media;

