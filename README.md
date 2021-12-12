# HOMEWORK 3

## Zadání

Máme REST API pro nákupní košík. Položky do košíku můžeme vkládat, odebírat nebo měnit jejich množství. Data ukládáme do databáze.

Každou půlnoc se spouští skript. Ten kontroluje, zdali existují týden staré, tzv. nedokončené nákupy. Tedy, že zákazník si na poslední chvíli nákup rozmyslel a objednávku neodeslal. Pokud takový "košík" najde, zákazníkovi pošle e-mailem připomenutí. Takových zapomenutých košíků mohou být stovky až tisíce, proto k tomuto aspektu přihlédněte při jeho řešení.

Obecné požadavky:

- Projekt je spustitelný v Dockeru.
- Existuje README.md soubor s dokumentací.
- Kód projektu je publikovaný na GitHubu (či jiné podobné službě).
- Zdrojový kód je napsaný v jazyku PHP/Golang/Node.js (stačí jeden).
- Databázové řešení volte dle svých preferencí.
- Není nutné, aby se zákazníkovi e-mail reálně odeslal.

Některé parametry projektu jsme záměrně vynechali, abychom současně nenaváděli k řešení.


## Scripts

Vytvoření služeb

``docker-compose build``

Spuštění služeb

``docker-compose up -d``

Spuštění příkazové řádky

``docker-compose run --rm php_apache /bin/sh``

Vytvoření migrace (v příkazové řádce)

``vendor/bin/phinx create -c "db/phinx.php" MigrationName``

Spuštění migrací (v příkazové řádce)

``vendor/bin/phinx migrate -c "db/phinx.php" -e test``

Vytvoření Seedu (v příkazové řádce)

``vendor/bin/phinx seed:create -c "db/phinx.php" NameSeeds``

Spuštění seedu (v příkazové řádce)

- Smaže obsah všech tabulek a vygeneruje nový náhodný obsah

``vendor/bin/phinx seed:run -c "db/phinx.php" -e test``

Kontrola kódu pomocí statické analýzy (PHP STAN level 7)

``composer stan``

Kontrola formátu kódu (PSR-12)

``composer phpcs``

Oprava formátu kódu (PSR-12)

``composer phpcbf``

## Princip návrhu

Je předopkládané, že každým requestem je zaslaný TOKEN v hlavičce, díky kterému se identifikuje zákazník, který je
načtený službou **IdentityManager**, tato služba také poskytuje každému zákazníkovi nákupní košík. Pokud ještě uživatel košík nemá tak
se mu automaticky vytvoří.

Každá iterace s košíkem (GET, SET, UPDATE), vyvolá akci, která aktualizuje sloupec **updated** na aktualní čas, díky tomuto sloupci jde
následně jednoduše poznat, že zákazník opustil košík bez doékončení objednávky.

## Seznam endpointů

### Produkty

Načtení všech produktů

``GET /products/``

Načtení konkrétního produktu

``GET /products/{id}``

Odstranění produktu

``DELETE /products/{id}``

Vytvoření produktu

``POST /products/``

Vzor těla postu:

```
{
   "name": "Název produktu",
   "description": "Popis produktu",
   "price": 200
}
```

Update produktu

``PUT /products/{id}``

Vzor těla updatu:

```
{
   "name": "Název produktu",
   "description": "Popis produktu",
   "price": 200,
}
```

### Košík

Načtení informací o košíku

``GET /cart/``

Načtení položek v košíku

``GET /cart/products``

Přidání položky do košíku

``POST /cart/products/{product_id}/ammont/{počet položek}``

Smazání položky v košíku

``DELETE /cart/products/{product_id}``

Aktualizace položek v košíku

``PUT /cart/products/{product_id}/ammont/{počet položek}``

## Spuštění skriptu

Spuštění akce na kontrolu 7 dní starých košíků a "odeslání" emailů.

``php bin/console.php app:notifyLeftCarts``

## Technologie a knihovny

- Docker
- PHP 8
- PostgresSQL
- Slim Framework 4
- Nette knihovny
  - Nette DI
  - Nette Database
  - Nette Neon
  - Nette Caching
  - Nette Schema
  - Nette Utils
- Symfony console
- Phinx
