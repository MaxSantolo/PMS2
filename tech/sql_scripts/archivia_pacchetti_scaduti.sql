INSERT INTO acs_pacchetti_scaduti (acs_pacchetti_scaduti.azienda, acs_pacchetti_scaduti.codice, acs_pacchetti_scaduti.cod_auth, acs_pacchetti_scaduti.ore_utilizzate, acs_pacchetti_scaduti.ore_totali, acs_pacchetti_scaduti.tipo, acs_pacchetti_scaduti.email_notifiche, acs_pacchetti_scaduti.delta_ore, acs_pacchetti_scaduti.data_inizio, acs_pacchetti_scaduti.data_fine)
      SELECT acs_pacchetti.azienda, acs_pacchetti.codice, acs_pacchetti.cod_auth, acs_pacchetti.ore_utilizzate, acs_pacchetti.ore_totali_pacchetto, acs_pacchetti.tipo, acs_pacchetti.email_notifiche, acs_pacchetti.delta_ore, acs_pacchetti.data_inizio_pacchetto, acs_pacchetti.data_fine_pacchetto
      FROM acs_pacchetti
      WHERE cestinato != '1' && ( data_fine_pacchetto <= curdate() || ore_utilizzate >= (ore_totali_pacchetto + delta_ore));
-- DELETE FROM acs_pacchetti
      WHERE cestinato != '1' && ( data_fine_pacchetto <= curdate() || ore_utilizzate >= (ore_totali_pacchetto + delta_ore));
