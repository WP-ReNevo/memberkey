# PHP randează tot ce protejează, React tot ce e interactiv

Conținutul restricționat, Ecranul de blocare și butonul de download se randează pe server, astfel încât conținutul protejat nu ajunge niciodată în browser la cine nu are acces. Checkout-ul, My Account și întregul admin sunt aplicații React care vorbesc cu REST namespace-ul `memberkey/v1`. Nu muta restricția în React: ar însemna să trimiți conținutul în pagină și să-l ascunzi pe client.
