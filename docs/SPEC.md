# MemberKey – Spec v1

**Titlu wp.org:** MemberKey – Membership, Subscriptions, Content Restriction & Protected Downloads
**Slug / text-domain:** `memberkey` · **Prefix cod:** `mk_` / `memberkey_` · **REST:** `memberkey/v1`
**Cerințe:** WordPress 6.4+, PHP 7.4+ · **Licență:** GPLv2+ · **Mascotă:** pisica neagră cu medalion-gaură-de-cheie

Termenii folosiți aici sunt definiți în [GLOSSARY.md](../GLOSSARY.md). Deciziile cu trade-off sunt în [docs/adr/](adr/).

## Poziționare

Plugin gratuit, „merge în 5 minute”, care face într-un singur loc ce azi cere 2–3 pluginuri: vânzare de abonamente, restricție de conținut și Download-uri protejate real. Fără addon-uri și fără upsell în v1.

---

## 1. Planuri

- Câmpuri: nume, descriere scurtă, Beneficii (listă), Preț, Tip de facturare, badge „Recomandat” (cel mult un plan), ordine (drag & drop).
- Tipuri de facturare: **Recurent** (la fiecare N zile/luni/ani), **Durată fixă** (o plată, N zile/luni/ani), **Lifetime**.
- Preț 0 = Plan gratuit; Recurent e indisponibil la preț 0.
- O singură monedă pe site. Dacă moneda nu e suportată de PayPal, metoda PayPal se dezactivează, cu avertisment.
- Fără trial, taxă de setup sau limită de cicluri.
- Editarea prețului sau a facturării se aplică doar abonaților noi (PayPal: billing plan nou). UI-ul arată câți membri rămân pe vechiul preț.
- Un Plan cu abonați nu se șterge, doar se **arhivează** (dispare din Checkout și din tabelul de planuri).
- Stocare: CPT fără UI WordPress (`show_ui=false`).

## 2. Abonamente

- Un Membru are **cel mult un Abonament activ** ([ADR 0001](adr/0001-un-singur-abonament-activ.md)). Nu există schimbare de plan în v1: Checkout-ul refuză un Plan nou cât timp există un Abonament Active sau Cancelled neexpirat.
- Statusuri: **Pending → Active → Cancelled → Expired**.
- **Acces** = status Active sau Cancelled, cu Data de expirare în viitor (Lifetime nu are dată).
- **Reînnoire**: o Plată de reînnoire prelungește Data de expirare cu o perioadă.
- **Perioadă de grație**: implicit 3 zile (configurabilă) pentru Recurent, apoi Expired.
- **Anulare de către Membru**: doar pentru Recurent, din My Account. Statusul devine Cancelled, abonamentul e anulat și în PayPal, iar accesul rămâne până la expirare. Nu există reactivare: după expirare, Membrul cumpără din nou.
- **Pending abandonat**: PayPal se șterge după 24h. Manual devine Expired după 14 zile (configurabil), Plata devine Anulată și Membrul primește email. Un Pending nu blochează un nou Checkout, ci e înlocuit.
- Fiecare Abonament reține modul **Sandbox/Live**.
- Stocare: tabel `{prefix}mk_subscriptions` ([ADR 0002](adr/0002-tabele-custom-pentru-abonamente-si-plati.md)).

## 3. Plăți

Două metode: **Manual** și **PayPal**. Stocare: tabel `{prefix}mk_payments`. Fiecare Plată are un Cod de referință (`MK-1042`) și modul Sandbox/Live.

### 3.1 Manual (transfer bancar)
- Instrucțiunile sunt text liber, cu placeholder-e (`{suma}`, `{referinta}`, `{iban}`…), afișate după Checkout și trimise pe email.
- Abonamentul rămâne Pending până când adminul apasă **Aprobă**. **Respinge** e disponibil.
- La Recurent: cu 7 zile înainte de expirare, Membrul primește un email „Reînnoiește”. Linkul creează o Plată pending, iar aprobarea ei prelungește Data de expirare.
- „Marchează rambursată” → Abonamentul devine Expired imediat.

### 3.2 PayPal
- REST API v2: **Orders** pentru Durată fixă și Lifetime, **Subscriptions** pentru Recurent. **Smart Buttons** (JS SDK) pe Checkout.
- Credențiale separate pentru Sandbox și Live, toggle de mod și buton „Testează conexiunea”.
- La salvarea credențialelor, webhook-ul se creează **automat** prin API. Billing plans se creează și se actualizează automat.
- **Statusul vine doar din webhook-uri verificate prin semnătură** ([ADR 0003](adr/0003-statusul-platii-vine-doar-din-webhook.md)). Întoarcerea din popup afișează doar „Se procesează…”.
- Evenimente tratate: plată finalizată (activare / Reînnoire), abonament anulat sau suspendat în PayPal (devine Cancelled, cu acces până la expirare), `PAYMENT.CAPTURE.REFUNDED` (Plată Rambursată, Abonament Expired imediat).
- Log de webhook-uri vizibil în admin.
- Rambursările se fac din PayPal (butonul de refund din plugin e pentru v2).
- Datele de test: badge „Test”, excluse din Prezentare, buton „Șterge datele de test”.

## 4. Restricție de conținut

- Regula de acces: „Orice plan activ” (implicit) sau o listă de Planuri.
- Se poate pune pe: **post/pagină/orice post type public**, prin panou în sidebar-ul Gutenberg (fallback cu metabox pentru Classic Editor); pe **blocul Conținut restricționat** (InnerBlocks) / `[mk_restrict plans=""]`; pe **Download**.
- Restricția pe categorii, tag-uri și post type-uri rămâne pentru Pro ([ADR 0007](adr/0007-restrictia-e-doar-per-post-in-versiunea-gratuita.md)).
- **Teaser** activat implicit (excerpt sau primele N cuvinte) deasupra Ecranului de blocare.
- Arhive și search: titlu + Teaser. RSS și REST: doar Teaser-ul. Postul nu se ascunde din listări.
- Restricția se randează **în PHP**, iar conținutul protejat nu ajunge în browser ([ADR 0006](adr/0006-php-pentru-protectie-react-pentru-interactiune.md)).
- Userii cu `manage_options` sau `memberkey_bypass` văd tot, cu eticheta „Restricționat: vizibil pentru că ești admin”.
- Accesul depinde doar de Abonament, nu de rolurile WP. Userii noi primesc rolul implicit WP.

### Ecranul de blocare (comun pentru posturi, blocuri și Download-uri)
| Situație | Afișare |
|---|---|
| Nelogat | mesaj + formular de login inline + „Vezi planurile” |
| Logat, fără Abonament | mesaj + butoane spre Checkout pentru planurile din Regula de acces |
| Logat, Abonament expirat sau alt Plan | mesaj + „Reînnoiește” |

Mesajele se pot edita global.

## 5. Download-uri

- O singură entitate **Download** cu sursa **File** sau **Link** (CPT fără UI WP, ecran propriu în admin).
- **File**: upload din ecranul Download-ului (nu din Media Library), **chunked** (5 MB/bucată) cu bară de progres.
- **Stocare** ([ADR 0005](adr/0005-fisierele-stau-in-afara-webroot-ului.md)): implicit **în afara webroot-ului** (ex. `/home/user/memberkey-files/`). Fallback automat: `uploads/memberkey-files/` cu `index.php` + `.htaccess` generate de plugin. Self-test-ul afișează un avertisment doar dacă fișierele sunt totuși accesibile public. Adminul nu configurează nimic.
- **Endpoint**: `/?mk_download=<id>`, stabil, fără token ([ADR 0004](adr/0004-download-fara-token-semnat.md)). Accesul se verifică la fiecare request. File se servește prin streaming cu suport Range (opțional X-Sendfile/X-Accel-Redirect). Link face redirect 302, iar URL-ul extern nu apare în HTML.
- **Blocul Buton de download** / `[mk_download id=""]`: are acces → buton; nu are acces → Ecranul de blocare.
- Opțiunea „Ascunde din My Account” per Download.
- **Log de download** (`{prefix}mk_download_log`): user, Download, dată, **fără IP**. Se curăță după 12 luni. Contorul e vizibil în admin.
- Fără limite de download în v1.

## 6. Frontend

- Design: direcția **Paperwhite** (titluri Literata, interfață Inter, accent bleumarin) + **bara de progres** a abonamentului în My Account. Opțiunea „Folosește fonturile temei”. Temă de zi și de noapte.
- Componentele React și tokenii sunt **aceiași** cu ai admin-ului.

| Block | Shortcode | Randare |
|---|---|---|
| Checkout | `[mk_checkout plan=""]` | React |
| My Account | `[mk_account]` | React |
| Login | `[mk_login]` | React (submit prin REST, fără nonce care expiră) |
| Register | `[mk_register]` | React |
| Planuri | `[mk_plans]` | PHP + buton spre Checkout cu planul preselectat |
| Buton de download | `[mk_download id=""]` | PHP |
| Conținut restricționat | `[mk_restrict plans=""]…[/mk_restrict]` | PHP |

- **Checkout**: Plan Summary; vizitator nou → email, parolă, prenume și nume (opțional). Contul se creează la Checkout, fără guest checkout. Dacă emailul există: „Ai deja cont” + login inline. Checkbox opțional „Accept termenii”. Alegerea Metodei de plată.
- **Login**: autentificare WP standard. „Am uitat parola” e inline, iar linkul de reset duce pe pagina noastră de Login. Redirect înapoi pe pagina de origine.
- **My Account**: abonamentul curent cu bara de progres și Data de expirare, anulare (Recurent) cu confirmare, istoricul plăților, „Download-urile mele” (toate cele deblocate de plan; după expirare apar gri, cu lacăt, și „Reînnoiește”), link spre profilul WP.
- Bara de admin WP e ascunsă pentru Membrii fără alt rol (setare).
- Cache: `DONOTCACHEPAGE` + no-cache pe Checkout, My Account, Login și pe endpoint-ul de download. Excludere automată în WP Rocket, LiteSpeed Cache și W3TC.

## 7. Admin

- **Aplicație React full-screen** sub un singur meniu „MemberKey”. Layout propriu (sidebar + header), primitive `@wordpress/components` și `DataViews` re-stilizate cu tokenii designului, componente proprii unde lipsesc. Nu există nicio pagină de admin generată din PHP. Rutele sunt în URL.
- Secțiuni:
  - **Prezentare**: membri activi, venit luna curentă, plăți de aprobat, abonamente care expiră în 7 zile (fără date de test).
  - **Membri**: listă + pagină de detaliu (abonament curent cu bara de progres și acțiuni, istoric, plăți, ultimele download-uri, link spre profilul WP).
  - **Abonamente**, **Plăți**, **Planuri**, **Download-uri**.
  - **Setări**: General, Plăți, Email-uri, Fișiere, Design.
- Acțiuni manuale: **Acordă acces** (user + plan + dată sau lifetime, fără Plată), **Prelungește**, **Anulează** (sincronizat cu PayPal), **Expiră acum**, **Aprobă/Respinge** plata manuală, **Marchează rambursată**.

## 8. Email-uri

- Template HTML unic în stilul designului. Subiectul și textul se editează, cu placeholder-e (`{nume}`, `{plan}`, `{data_expirare}`, `{link_cont}`…). Fiecare email are toggle on/off. Se trimit prin `wp_mail`.
- **Membru**: bun venit, plată primită, plată în așteptare (cu instrucțiunile de transfer), expiră în 7 zile, expirat, anulat, comandă expirată (Pending manual), reînnoire manuală.
- **Admin**: plată manuală de aprobat, abonament nou, anulare.
- Cron (WP-Cron): expirări, Perioada de grație, remindere, curățarea Pending-urilor abandonate și a log-ului.

## 9. Setup wizard

Full-screen la prima activare, se poate sări:
1. **Plăți**: monedă; Manual (instrucțiuni) și/sau PayPal (credențiale, Sandbox, „Testează conexiunea”).
2. **Primul plan**: nume, preț, tip de facturare.
3. **Pagini**: creează automat Checkout, My Account și Login, cu block-urile puse, și afișează linkurile.

Ecranul „Gata!” sugerează pașii următori (adaugă un Download, restricționează o pagină). Self-test-ul de fișiere rulează în fundal.

## 10. Extensibilitate

Hook-uri documentate pe o pagină de docs pentru developeri:
- Actions: `memberkey_subscription_activated`, `memberkey_subscription_cancelled`, `memberkey_subscription_expired`, `memberkey_payment_completed`, `memberkey_download_served`
- Filters: `memberkey_can_access`

## 11. Non-funcționale

- **Stack**: PHP pe server (REST `memberkey/v1`), React pentru tot UI-ul interactiv, build cu `@wordpress/scripts`.
- **Conformitate wp.org**: cod neofuscat, fără tracking sau telemetrie, fără upsell, sanitizare/escaping/nonces/capabilities peste tot.
- **i18n**: textele din cod sunt în engleză (text-domain `memberkey`). Traducerea ro_RO e inclusă la lansare și publicată pe translate.wordpress.org.
- **GDPR**: integrare cu exportul și ștergerea datelor personale din WP. Log-ul de download nu conține IP. La dezinstalare datele se șterg doar dacă e bifat „Șterge toate datele la dezinstalare” (debifat implicit).
- **Multisite**: funcționează per site, fără funcții de rețea.
- **Testare**: logica de domeniu se scrie test-first, cu PHPUnit (acces, statusuri, Reînnoire, grație, webhook-uri cu payload-uri înregistrate). Jest pe componentele critice (Checkout). Playwright e2e pe PayPal Sandbox, rulat manual înainte de release.
- **Branding**: icon 256×256 / 128×128 și banner 1544×500 / 772×250, după mascota-pisică.

## 12. În afara v1

**v2 (gratuit):** drip content, cupoane, Stripe, taxe/facturi, export CSV, trial, limite de download, monede multiple, schimbare de plan cu proratare, refund din plugin, reactivarea unui abonament anulat.

**Pro (eventual):** restricție pe categorii, tag-uri și post type-uri; rol WP atribuit per Plan; funcții de rețea multisite.
