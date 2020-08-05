![counter](https://i.imgur.com/klnnsOS.jpg)

-----------------------------------------------------------------------------------------------------

DB name - ttask4ksoft

-----------------------------------------------------------------------------------------------------

SQL для создания таблицы

CREATE TABLE IF NOT EXISTS users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL,
  password CHAR(97) NOT NULL,
  birth_date DATE NOT NULL,
  counter INT DEFAULT 0,
  status BOOLEAN DEFAULT 0
);

-----------------------------------------------------------------------------------------------------

При регистрации и авторизации происходит
  проверка на существование username в БД,
  устанавливается cookie -> userID.

При авторизации происходит проверка пароля.

При разлогинивании удаляется cookie.

Счетчик работает через AJAX(файл counter.php).

