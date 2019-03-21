select calldate, src,     
	cast(calldate as date) as data_ingresso,
    cast(calldate as time) as ora_ingresso,cdr_accessi
	SUBSTRING_INDEX(userfield, '-', 1) AS codice_pin,
    SUBSTRING_INDEX(SUBSTRING_INDEX(userfield, '-', 2), '-', -1) AS nome_azienda,
    SUBSTRING_INDEX(SUBSTRING_INDEX(userfield, '-', 3), '-', -1) AS stato
    
from cdr where 

	(src like '353%')
	
order by calldate desc
    