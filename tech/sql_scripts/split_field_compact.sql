select 	
		data_ingresso, 
		src, 
        left(codice_pin,2) as auth,
        substring(codice_pin,3) as pin, 
        nome_azienda, 
        concat('[',group_concat(convert(ora_ingresso, CHAR(5)), ' ', substring(stato,6) order by ora_ingresso asc SEPARATOR ']['),']') as ingressi
		
from cdr_accessi 
		where src like '353%' and stato like '%check%'


group by substring(convert(codice_pin, char(8)),3), convert(data_ingresso, char(10))

order by data_ingresso desc 