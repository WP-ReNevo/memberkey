# Fișierele Download-urilor stau în afara webroot-ului

Fișierele uploadate se salvează implicit într-un folder lângă webroot (ex. `/home/user/memberkey-files/`), care nu are niciun URL public, deci protecția nu depinde de serverul web și nu cere configurare. Doar dacă hostul nu permite scrierea acolo (`open_basedir`, webroot = rădăcina contului) folosim `uploads/memberkey-files/` cu `index.php` + `.htaccess` generate automat, iar un self-test avertizează adminul doar dacă fișierele sunt totuși publice.

## Considered Options

- Folder random în uploads + reguli Nginx afișate adminului: respins, cere configurare și pare fragil.
- Doar în afara webroot-ului, fără fallback: respins, ar bloca uploadul pe o parte din hosturi.
