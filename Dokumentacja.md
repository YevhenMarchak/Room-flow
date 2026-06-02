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
git clone https://github.com/YevhenMarchak/Room-flow.git
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

Po uruchomieniu aplikacji przez administratora systemu użytkownik może korzystać z aplikacji za pomocą dowolnej nowoczesnej przeglądarki internetowej.

Adres aplikacji:

http://127.0.0.1:8000

Ekran logowania:

http://127.0.0.1:8000/login

Minimalne wymagania sprzętowe:

* Procesor dwurdzeniowy,
* 4 GB pamięci RAM,
* 500 MB wolnego miejsca na dysku,
* Aktualna przeglądarka internetowa (Chrome, Edge, Firefox).

## Plany rozbudowy

Pierwsza wersja systemu skupia się na podstawowych funkcjonalnościach związanych z zarządzaniem salami, harmonogramami zajęć oraz procesem rezerwacji. Podczas realizacji projektu udało się zaimplementować wszystkie kluczowe funkcje wymagane do prawidłowego działania systemu, jednak istnieje wiele możliwości dalszego rozwoju aplikacji.

### Funkcjonalności, których zabrakło w pierwszej wersji

Ze względu na ograniczony czas realizacji projektu nie zostały zaimplementowane następujące funkcjonalności:

- Powiadomienia e-mail informujące o zaakceptowaniu lub odrzuceniu rezerwacji.
- System odzyskiwania hasła za pomocą wiadomości e-mail.
- Zaawansowane filtrowanie i wyszukiwanie rezerwacji.
- Eksport harmonogramów oraz rezerwacji do plików PDF lub Excel.
- Historia zmian wykonywanych przez administratora.
- Kalendarz prezentujący rezerwacje w widoku miesięcznym.

### Możliwe rozszerzenia w wersji 2.0

W kolejnych wersjach systemu planowane jest rozszerzenie funkcjonalności o:

- Integrację z uczelnianym systemem kont użytkowników.
- Automatyczne powiadomienia e-mail dla nauczycieli i administratorów.
- Powiadomienia w czasie rzeczywistym o zmianach w harmonogramie.
- Integrację z kalendarzami Google Calendar oraz Microsoft Outlook.
- Możliwość cyklicznego tworzenia rezerwacji.
- Generowanie raportów dotyczących wykorzystania sal.
- Statystyki obciążenia sal i harmonogramów.
- Aplikację mobilną dla systemów Android i iOS.
- Możliwość dodawania załączników do rezerwacji (np. plan zajęć lub dokumentacja wydarzenia).

### Potencjalne optymalizacje

W przyszłości możliwe jest również zwiększenie wydajności i skalowalności systemu poprzez:

- Wprowadzenie mechanizmu cache'owania najczęściej pobieranych danych.
- Optymalizację zapytań do bazy danych oraz wykorzystanie eager loading.
- Dodanie kolejek zadań (Laravel Queues) do obsługi powiadomień.
- Wdrożenie testów automatycznych obejmujących najważniejsze procesy biznesowe.
- Zastosowanie środowiska produkcyjnego opartego na Dockerze.
- Rozbudowę systemu uprawnień o dodatkowe role użytkowników.
- Zwiększenie poziomu bezpieczeństwa poprzez uwierzytelnianie dwuskładnikowe (2FA).

Rozwój wymienionych funkcjonalności pozwoliłby na wykorzystanie systemu nie tylko w małych jednostkach organizacyjnych, ale również w większych instytucjach edukacyjnych posiadających rozbudowaną infrastrukturę dydaktyczną.
