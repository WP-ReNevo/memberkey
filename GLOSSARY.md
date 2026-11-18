# MemberKey

Plugin WordPress gratuit pentru vânzarea de abonamente, restricționarea conținutului și livrarea de fișiere și link-uri protejate.
Termenii sunt în română; în paranteză e numele folosit în cod și în textele originale (engleză) ale pluginului.

## Planuri și abonamente

**Plan** (`Plan`):
O ofertă pe care un vizitator o poate cumpăra; are un Preț și un Tip de facturare.
_Avoid_: Membership level, product, package, tier

**Tip de facturare** (`Billing type`):
Felul în care un Plan e plătit: *Recurent* (la fiecare N zile/luni/ani până la anulare), *Durată fixă* (o plată, acces N zile/luni/ani) sau *Lifetime* (o plată, acces nelimitat).
_Avoid_: Billing cycle, subscription type

**Beneficii** (`Features`):
Lista scurtă de avantaje a unui Plan, afișată în tabelul de planuri.
_Avoid_: Perks, bullet points

**Plan gratuit** (`Free plan`):
Un Plan cu Preț 0; nu are nevoie de Plată.
_Avoid_: Free membership, free tier

**Abonament** (`Subscription`):
Legătura dintre un Membru și un Plan pe o anumită perioadă; un Membru are cel mult un Abonament activ.
_Avoid_: Subscription (în UI în română), membership

**Data de expirare** (`Expiration date`):
Momentul până la care un Abonament dă acces; un Abonament Lifetime nu are.
_Avoid_: End date, renewal date, due date

**Status abonament** (`Subscription status`):
*Pending* (așteaptă prima Plată), *Active* (plătit, se reînnoiește dacă e Recurent), *Cancelled* (nu se mai reînnoiește, dar dă acces până la Data de expirare) sau *Expired* (perioada s-a încheiat).
_Avoid_: On hold, suspended, inactive

**Perioadă de grație** (`Grace period`):
Zilele de după Data de expirare în care un Abonament Recurent rămâne Active în așteptarea Plății de reînnoire.
_Avoid_: Grace time, buffer

**Reînnoire** (`Renewal`):
Prelungirea Datei de expirare cu încă o perioadă, în urma unei Plăți.
_Avoid_: Renewal charge, rebill

**Membru** (`Member`):
Un user WordPress care are sau a avut cel puțin un Abonament.
_Avoid_: Customer, subscriber (rolul WP), client

## Acces

**Regulă de acces** (`Access rule`):
Lista de Planuri care deblochează un conținut sau un Download; „Orice plan activ” e varianta implicită.
_Avoid_: Restriction rule, access level, permission

**Conținut restricționat** (`Restricted content`):
Un post, o pagină sau o porțiune de pagină care are o Regulă de acces.
_Avoid_: Protected content, premium content

**Download** (`Download`):
O resursă livrată doar Membrilor cu acces; sursa e fie un *Fișier* (uploadat în zona protejată), fie un *Link* (URL extern ascuns).
_Avoid_: Protected file, resource, asset, attachment

**Ecran de blocare** (`Lock screen`):
Ce vede cineva fără acces la un Conținut restricționat sau un Download: un mesaj adaptat situației (nelogat, fără Abonament, Abonament expirat sau alt Plan) și pașii spre acces.
_Avoid_: Paywall, restriction message, locked screen

**Teaser** (`Teaser`):
Începutul unui Conținut restricționat afișat oricui, deasupra Ecranului de blocare.
_Avoid_: Preview, excerpt (în sensul de restricție)

## Plăți

**Plată** (`Payment`):
O sumă încasată (sau în curs de încasare) pentru un Abonament; are un Cod de referință.
_Avoid_: Transaction, order, invoice

**Cod de referință** (`Reference`):
Identificatorul unei Plăți (ex. `MK-1042`), pe care Membrul îl trece în descrierea transferului bancar.
_Avoid_: Order number, invoice number

**Metodă de plată** (`Payment method`):
Modul prin care se încasează o Plată: *Manual* (transfer bancar aprobat de admin) sau *PayPal*.
_Avoid_: Gateway, payment processor

**Checkout** (`Checkout`):
Formularul prin care un vizitator alege un Plan, își creează contul (dacă nu are) și plătește.
_Avoid_: Signup, purchase form, register form
