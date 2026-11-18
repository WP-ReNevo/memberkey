# Download-urile nu folosesc URL-uri semnate

Linkul unui Download e stabil (`/?mk_download=<id>`), iar accesul se verifică la fiecare request după userul logat și Regula de acces. Un URL semnat sau cu expirare nu adaugă protecție, pentru că cine primește linkul tot trebuie să fie logat și să aibă acces, dar ar strica bookmark-urile și cache-ul. Nu „repara” asta adăugând tokenuri.
