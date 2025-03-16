# 🎵 matieusz vinyls - Twoja kolekcja winyli online! 🎶  

**matieusz vinyls** to aplikacja internetowa do zarządzania kolekcją winylowych płyt!  
Dzięki niej możesz **przechowywać, filtrować, wyszukiwać i dodawać albumy**, a także śledzić **wartość swojej kolekcji**.  
To nie jest zwykła lista – tutaj masz pełną kontrolę nad swoim zbiorem! 💿✨

![image](https://github.com/user-attachments/assets/05a2dc5b-5b1a-4042-9051-625d9c0accff)

---

## 📌 **Funkcje aplikacji**  

### 🔐 **Logowanie i dostęp do stron**  
- Logowanie administratora – tylko Ty masz pełen dostęp do zarządzania kolekcją  
- System uprawnień – jeśli nie masz dostępu, zobaczysz **elegancki komunikat o braku uprawnień**  

### 🎶 **Dodawanie albumów**  
- Możesz dodawać albumy z takimi informacjami jak **tytuł, wykonawca, rok wydania, cena, ilość płyt, lista piosenek**  
- **Wybierasz gatunek** z listy lub **dodajesz nowy** – nie musisz ręcznie wpisywać tego samego!  
- **Podgląd zdjęcia albumu przed dodaniem** – widzisz, co wrzucasz, zanim klikniesz "Dodaj"!
  
![image](https://github.com/user-attachments/assets/e0af08bc-8ab8-465a-a2d6-b26cc70a9f22)  

### 🔍 **Filtracja i wyszukiwanie albumów**  
- **Nowe opcje sortowania i filtrowania** – szybciej znajdziesz to, czego szukasz!  
- **Filtruj po gatunkach** – zobacz tylko rock, metal, jazz czy cokolwiek chcesz! 🎸  
- **Filtruj po cenie** – wyświetl tylko albumy do określonej kwoty 💰  
- **Wyszukiwarka w czasie rzeczywistym** – znajdź album **bez odświeżania strony**  

### 🖼️ **Zdjęcia albumów**  
- Każdy album ma **okładkę**, a po najechaniu myszką zmienia się na drugie zdjęcie  
- Zdjęcia są **automatycznie dopasowane**, żeby wszystko wyglądało schludnie  

### 🗑️ **Usuwanie albumów**  
- Możesz **usunąć album jednym kliknięciem** – natychmiast zniknie z bazy!  
- **Zdjęcia albumu też się kasują**, więc nie zostają niepotrzebne pliki  

### 📊 **Statystyki kolekcji**  
- **Łączna wartość kolekcji** wyświetlana w osobnym kafelku – wiesz, ile warte są Twoje winyle 💵  
- **Kafelek z ostatnio dodanym albumem** – zawsze widzisz, co ostatnio dołączyło do kolekcji  

### 🎛️ **Zmiana widoku albumów**  
- Możesz zmieniać wygląd kolekcji na **duże kwadraty, małe kwadraty lub listę** 📜  
- Wszystko działa **w czasie rzeczywistym**, bez odświeżania strony  

---

## 📌 **Jak uruchomić projekt?**  
1. Pobierz kod i wrzuć go do folderu XAMPP (np. `C:/xampp/htdocs/vinyls_app`)  
2. Uruchom **phpMyAdmin** i zaimportuj plik `database.sql`  
3. W pliku `db.php` wpisz swoje dane dostępowe do MySQL  
4. **Chcesz dodać administratora?** Wystarczy zmienic plik `add_admin.php`, podać w nim nazwę administratora oraz hasło jakie chcemy stworzyć, zapisać i odpalić plik `add_admin.php` w przeglądarce! 👑
```
<?php
require 'php/db.php';

$username = "admin"; // Nazwa użytkownika (zmień na własną)
$password = "admin"; // Hasło admina (zmień na własne)
$hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hashowanie hasła

// Dodanie użytkownika do bazy
$stmt = $pdo->prepare("INSERT INTO users (username, password, is_admin) VALUES (?, ?, ?)");
$stmt->execute([$username, $hashedPassword, 1]);

echo "Administrator został dodany!";
?>
```
5. Odpal XAMPP i wejdź na `http://localhost/vinyls_app` 🚀  

---

## 🔧 **Technologie użyte w projekcie**  
✅ **HTML, CSS, JavaScript** – frontend aplikacji  
✅ **PHP + MySQL** – backend i baza danych  
✅ **AJAX + JavaScript** – filtrowanie i wyszukiwanie bez odświeżania strony  
✅ **XAMPP** – lokalny serwer do uruchomienia projektu  

---

## 🤝 **Autor**  
Projekt wykonany przez **matieusza** 🎵💿  
Masz pomysły na rozwój aplikacji? **Daj znać!** 🚀  

---

🚀 **matieusz vinyls** – Twoja kolekcja, Twoje zasady! 🎶💿🔥
