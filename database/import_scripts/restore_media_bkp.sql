# Restore Media table from Backup
INSERT INTO minexq.media SELECT * FROM minex_live.media_bkp

