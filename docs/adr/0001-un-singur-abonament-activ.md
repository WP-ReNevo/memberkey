# Un singur Abonament activ per Membru

Un Membru are cel mult un Abonament activ (Active sau Cancelled încă neexpirat) la un moment dat; abonamentele vechi rămân doar ca istoric. Am respins abonamentele multiple simultane pentru că complică Checkout, My Account și calculul accesului, adică exact complexitatea care face pluginurile concurente greu de folosit.

## Consequences

În v1 nu există schimbare de Plan (upgrade/downgrade): un Membru care vrea alt Plan își anulează Abonamentul, așteaptă Data de expirare și cumpără din nou. Checkout-ul refuză un Plan nou cât timp există un Abonament activ.
