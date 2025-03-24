![ezgif-42e94197f3d4be](https://github.com/user-attachments/assets/cac9a293-15a3-4c92-b31b-f0cd53583dd6)# 🎵 matieusz vinyls - Twoja kolekcja winyli online! 🎶  

**matieusz vinyls** to aplikacja internetowa do zarządzania kolekcją winylowych płyt!  
Dzięki niej możesz **przechowywać, filtrować, wyszukiwać i dodawać albumy**, a także śledzić **wartość swojej kolekcji**.  
To nie jest zwykła lista – tutaj masz pełną kontrolę nad swoim zbiorem! 💿✨

![image](https://github.com/user-attachments/assets/f48a005b-3312-49ef-aa40-9b0ccaf92f8b)

---

## 📌 **Funkcje aplikacji**  

### 🔐 **Logowanie i dostęp do stron**  
- Logowanie administratora – tylko Ty masz pełen dostęp do zarządzania kolekcją  
- System uprawnień – jeśli nie masz dostępu, zobaczysz **elegancki komunikat o braku uprawnień**

![image](https://github.com/user-attachments/assets/374d2275-9ec7-4fb6-90c4-b1507fef8aaf)

### 🎶 **Dodawanie albumów**  
- Możesz dodawać albumy z takimi informacjami jak **tytuł, wykonawca, rok wydania, cena, ilość płyt, lista piosenek**  
- **Wybierasz gatunek** z listy lub **dodajesz nowy** – nie musisz ręcznie wpisywać tego samego!  
- **Podgląd zdjęcia albumu przed dodaniem** – widzisz, co wrzucasz, zanim klikniesz "Dodaj"!
  
![ezgif-42e94197f3d4be](https://github.com/user-attachments/assets/bf3b067f-7d95-47d7-8810-ba13ee6f7c6d)

### 🔍 **Filtracja i wyszukiwanie albumów**  
- **Nowe opcje sortowania i filtrowania** – szybciej znajdziesz to, czego szukasz!  
- **Filtruj po gatunkach** – zobacz tylko rock, metal, jazz czy cokolwiek chcesz! 🎸  
- **Filtruj po cenie** – wyświetl tylko albumy do określonej kwoty 💰  
- **Wyszukiwarka w czasie rzeczywistym** – znajdź album **bez odświeżania strony**

![image](https://github.com/user-attachments/assets/91a974ac-d7d6-471e-8161-2e7792cc9e26)

### 🖼️ **Strony Albumów**   
- Zdjęcia są **automatycznie dopasowane**, oraz się kręcą żeby wszystko wyglądało ładnie i schludnie
- Możliwe Edycje Limitowane
- Dokładna Tracklista
- Data produkcji
- Kraj produkcji
- Cena
- Gatunek
- Odnośnik do spotify z danym albumem
  
![ezgif-4e0b5c1cf061e7](https://github.com/user-attachments/assets/f069ff7b-7cb8-48aa-930e-8d491615ea16)

### 🗑️ **Usuwanie albumów**  
- Możesz **usunąć album jednym kliknięciem** – natychmiast zniknie z bazy!  
- **Zdjęcia albumu też się kasują**, więc nie zostają niepotrzebne pliki  

![image](https://github.com/user-attachments/assets/abe72a18-593b-434f-8415-442c7707b3ae)

### 📊 **Statystyki kolekcji**  
- **Łączna wartość kolekcji** wyświetlana w osobnym kafelku – wiesz, ile warte są Twoje winyle 💵  
- **Kafelek z ostatnio dodanym albumem** – zawsze widzisz, co ostatnio dołączyło do kolekcji

![image](https://github.com/user-attachments/assets/930931c1-a485-4bf8-8bbd-9586cb950e84)
  

### 🎛️ **Zmiana widoku albumów**  
- Możesz zmieniać wygląd kolekcji na **duże kwadraty, małe kwadraty lub listę** 📜  
- Wszystko działa **w czasie rzeczywistym**, bez odświeżania strony  

![image](https://github.com/user-attachments/assets/e0d89088-2914-4191-a6b3-8d732293ca9b)

![image](https://github.com/user-attachments/assets/3412de0d-ea56-4c9a-bae9-10eec1492fca)

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
