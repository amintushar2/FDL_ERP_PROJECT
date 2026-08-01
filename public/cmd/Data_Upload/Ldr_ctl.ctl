LOAD DATA
infile 'D:\Data_Upload\data_file\att_data.txt'
INTO TABLE atnd_raw REPLACE
FIELDS TERMINATED BY ':'
(MACH_NO "to_char(:MACH_NO,'fm099')",
CARD_NO "(:CARD_NO)",
ATND_DATE "to_date(:ATND_DATE,'RRRRMMDD')",
ATND_TIME "to_char(:ATND_TIME,'fm099999')")





