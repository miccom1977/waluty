# waluty

Ćwiczebny kod śledzenia walut

Route /destroyMailing/{code} służy do usuwania danych usera dotyczących śledzenia walut

Route /currencyCheck obsługuje wysyłanie emaili do wszystkich userów, którym zmieniły się ceny śledzonych walut

Route /currencyUpdate aktualizuje kursy poszczegółnych danych kursów walut NBP *

Najlepiej ustawić CRON po godz. wg. wytycznych poniżej.


Tabela A kursów średnich walut obcych publikowana (aktualizowana) jest na stronie internetowej NBP w dni robocze, pomiędzy godziną 11:45 a 12:15,
Tabela B kursów średnich walut obcych publikowana (aktualizowana) jest na stronie internetowej NBP w środy, pomiędzy godziną 11:45 a 12:15,
Tabela C kursów kupna i sprzedaży walut obcych oraz tabela kursów jednostek rozliczeniowych publikowane (aktualizowane) są na stronie internetowej NBP w dni robocze, pomiędzy godziną 7:45 a 8:15.

https://www.nbp.pl/home.aspx?f=/kursy/instrukcja_pobierania_kursow_walut.html
