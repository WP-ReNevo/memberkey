# Statusul Plăților PayPal vine doar din webhook-uri REST v2

Folosim PayPal REST API v2 (Orders pentru plăți unice, Subscriptions pentru Recurent) cu Smart Buttons, iar Abonamentele se activează, se reînnoiesc și se anulează doar pe baza webhook-urilor verificate prin semnătură; întoarcerea din popup afișează doar „se procesează”. Am respins IPN-ul legacy și activarea la redirect, care sunt sursa principală de plângeri din forumuri (plăți fără acces, anulări nesincronizate). Webhook-ul se creează automat prin API, separat pentru Sandbox și Live.
