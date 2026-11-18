# Tabele custom pentru Abonamente, Plăți și log de download

Planurile și Download-urile sunt CPT-uri (se editează ca postări), dar Abonamentele, Plățile și log-ul de download stau în tabele proprii (`{prefix}mk_subscriptions`, `{prefix}mk_payments`, `{prefix}mk_download_log`). Cron-ul de expirări și listele din admin interoghează după status și dată, ceea ce pe `postmeta` e lent și greu de indexat; în schimb, avem nevoie de migrații de schemă la update.
