# System rezerwacji sal na uczelni

## Temat projektu

System rezerwacji sal na uczelni jest aplikacją webową przeznaczoną do zarządzania salami dydaktycznymi oraz procesem ich rezerwacji przez nauczycieli akademickich. Głównym celem systemu jest usprawnienie organizacji zajęć oraz eliminacja konfliktów związanych z równoczesnym wykorzystaniem tych samych pomieszczeń.

Aplikacja przeznaczona jest dla dwóch grup użytkowników:

* Administratorów odpowiedzialnych za zarządzanie użytkownikami, salami, harmonogramami zajęć oraz rezerwacjami.
* Nauczycieli odpowiedzialnych za przeglądanie harmonogramu, wyszukiwanie dostępnych sal oraz składanie rezerwacji.

### Jaki problem rozwiązuje aplikacja?

Na wielu uczelniach proces rezerwacji sal odbywa się ręcznie lub przy wykorzystaniu rozproszonych narzędzi, co prowadzi do problemów takich jak:

* nakładanie się rezerwacji,
* brak informacji o dostępności sal,
* trudności w planowaniu zajęć,
* brak centralnego miejsca zarządzania harmonogramem.

System automatycznie wykrywa konflikty terminów, uwzględnia istniejące zajęcia oraz blokuje możliwość rezerwacji sal w czasie, gdy są już zajęte.

### Czym aplikacja wyróżnia się na tle podobnych rozwiązań?

Najważniejsze cechy systemu:

* automatyczna kontrola konfliktów rezerwacji,
* integracja harmonogramów zajęć z procesem rezerwacji,
* automatyczny bufor 15 minut po zakończeniu zajęć,
* blokowanie rezerwacji w przeszłości,
* przejrzysty podział uprawnień administratora i nauczyciela,
* intuicyjny interfejs webowy dostępny z poziomu przeglądarki.

---

# Uruchomienie projektu (developer)

## Wykorzystane technologie

| Technologia  | Wersja  | Strona                  |
| ------------ | ------- | ----------------------- |
| PHP          | 8.5.6   | https://www.php.net     |
| Laravel      | 13.12.0 | https://laravel.com     |
| MySQL        | 8.x     | https://www.mysql.com   |
| Composer     | 2.9.7   | https://getcomposer.org |
| Node.js      | 24.15.0 | https://nodejs.org      |
| npm          | 11.12.1 | https://www.npmjs.com   |
| Tailwind CSS | 4.x     | https://tailwindcss.com |
| Alpine.js    | 3.x     | https://alpinejs.dev    |

---

## Wymagania programowe

Do uruchomienia projektu wymagane są:

### System operacyjny

* Windows 11
* Windows 10
* Linux
* macOS

### Środowisko uruchomieniowe

* PHP 8.5.6
* Composer 2.9.7
* Node.js 24.15.0
* npm 11.12.1

### Baza danych

* MySQL 8.x

### Dodatkowe narzędzia

* Git
* Terminal systemowy
* Przeglądarka internetowa

---

## Proces instalacji

### 1. Pobranie projektu

```bash
git clone <adres_repozytorium>
cd room-flow
```

### 2. Instalacja zależności PHP

```bash
composer install
```

### 3. Instalacja zależności JavaScript

```bash
npm install
```

---

## Proces konfiguracji

### 1. Utworzenie pliku środowiskowego

Skopiuj plik:

```bash
copy .env.example .env
```

lub

```bash
cp .env.example .env
```

### 2. Konfiguracja bazy danych

W pliku `.env` należy ustawić:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=roomflow
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Wygenerowanie klucza aplikacji

```bash
php artisan key:generate
```

### 4. Utworzenie struktury bazy danych

```bash
php artisan migrate:fresh
```

### 5. Wygenerowanie danych demonstracyjnych

```bash
php artisan db:seed --class=DemoDataSeeder
```

Seeder tworzy:

* konto administratora,
* 15 nauczycieli,
* przykładowe sale,
* harmonogramy zajęć,
* przykładowe rezerwacje.

---

## Dane logowania

### Administrator

Login:

```text
admin@roomflow.com
```

Hasło:

```text
password
```

### Nauczyciele

Przykładowe konto:

```text
john.smith@university.edu
```

Hasło:

```text
password
```

Pozostałe konta nauczycieli są generowane automatycznie przez seeder.

---

## Uruchomienie projektu

Do poprawnego działania aplikacji należy uruchomić trzy osobne terminale.

### Terminal 1

Uruchomienie serwera Laravel:

```bash
php artisan serve
```

### Terminal 2

Uruchomienie kompilatora zasobów frontendowych:

```bash
npm run dev
```

### Terminal 3

Uruchomienie harmonogramu zadań Laravel:

```bash
php artisan schedule:work
```

---

Po wykonaniu wszystkich kroków aplikacja będzie dostępna pod adresem:

```text
http://127.0.0.1:8000
```

---

# Uruchomienie projektu (user)

Projekt nie został wdrożony na publiczny serwer produkcyjny.

Aplikacja jest dostępna wyłącznie w środowisku deweloperskim i wymaga lokalnego uruchomienia zgodnie z instrukcją zawartą w sekcji „Uruchomienie projektu (developer)”.

Minimalne wymagania sprzętowe:

* Procesor dwurdzeniowy,
* 4 GB pamięci RAM,
* 500 MB wolnego miejsca na dysku,
* Aktualna przeglądarka internetowa (Chrome, Edge, Firefox).

