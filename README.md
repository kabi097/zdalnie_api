# Zdalnie.com.pl - Serwer REST API
#### Witryna ogłoszeniowa z ofertami pracy zdalnej
Projekt pracy inżynierskiej zrealizowany dla Politechniki Poznańskiej. Celem projektu było zaprojektowanie i implementacja platformy internetowej służącej do publikowania ogłoszeń pracy zdalnej. Aplikację wykonano przy użyciu biblioteki API Platform (Symfony) oraz Vue.js (JavaScript). 

### Demo (video)
<a href="https://vimeo.com/450289918" target="_blank"><img alt="Zobacz film" src="https://i.imgur.com/umqA1VB.png" width="700"></a>
![](https://i.imgur.com/ncDHI2Z.png)
### Funkcje:
- Wygodna przeglądarka ogłoszeń z obsługą filtrowania i paginacji wyników
- Dodawanie i edycja ogłoszeń dla zalogowanych użytkowników
- Dodawanie i edycja odpowiedzi do ogłoszeń z możliwością zaznaczenia dodatkowych opcji
- Nowoczesny interfejs użytkownika dostosowany do urządzeń mobilnych (Vuetify)
- Strona profil użytkownika z możliwością edycji danych
- Specjalne uprawnienia dla administratora
- Skrypt dodający przykładowe dane do bazy danych


### Instrukcja

1. Ściągnij repozytorium
``` 
git clone git@github.com:kabi097/zdalnie_api.git 
cd zdalnie_api
```
2. Zainstaluj narzędzie [Symfony CLI](https://symfony.com/download)
```
wget https://get.symfony.com/cli/installer -O - | bash
```
3. Zainstaluj zależności
```
composer install
```
4. Zaloguj się do MySQL, dodaj bazę danych i przykładowego użytkownika 
```
sudo mysql -u root -p
[wpisz hasło]
CREATE DATABASE zdalnie_db;
CREATE USER 'zdalnie_user'@'localhost' IDENTIFIED BY 'qwerty123';
GRANT ALL PRIVILEGES ON on zdalnie_db.* TO zdalnie_user@'%' IDENTIFIED by 'qwerty123';
```
5. Skopiuj zawartość *.env.example* do pliku *.env* i uzupełnij dane w
```
DATABASE_URL=mysql://zdalnie_user:qwerty123@127.0.0.1:3306/zdalnie_db
```
6. Wygeneruj klucze SSH
```
mkdir -p config/jwt
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```
7. Wykonaj migrację
```
php bin/console doctrine:migrations:migrate
```
8. (opcjonalne) Wypełnij bazę danych przykładowymi danymi
```
php bin/console hautelook:fixtures:load
```
9. Uruchom serwer aplikacji 
``` 
symfony serve
```
10. Wejdź pod adres [http://127.0.0.1:8000/api](http://127.0.0.1:8000/api) i korzystaj z aplikacji.
