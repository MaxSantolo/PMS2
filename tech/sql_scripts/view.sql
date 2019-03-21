CREATE 
    ALGORITHM = UNDEFINED 
    DEFINER = `root`@`10.0.0.0/255.0.0.0` 
    SQL SECURITY DEFINER
VIEW `cdr_accessi` AS
    SELECT 
        `cdr`.`calldate` AS `calldate`,
        CAST(`calldate` as date) as `data_ingresso`,
		CAST(`calldate` as time) as `ora_ingresso`,
        `cdr`.`src` AS `src`,
        SUBSTRING_INDEX(`cdr`.`userfield`, '-', 1) AS `codice_pin`,
        SUBSTRING_INDEX(SUBSTRING_INDEX(`cdr`.`userfield`, '-', 2),
                '-',
                -(1)) AS `nome_azienda`,
        SUBSTRING_INDEX(SUBSTRING_INDEX(`cdr`.`userfield`, '-', 3),
                '-',
                -(1)) AS `stato`
    FROM
        `cdr`
    WHERE
        ((`cdr`.`src` LIKE '353%')
            AND (`cdr`.`userfield` LIKE '9%'))
    ORDER BY `cdr`.`calldate` DESC