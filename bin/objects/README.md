# unt/objects

Здесь содержится, по факту, 90% бизнес-логики проекта, это объекты ядра. Они что-то делают, читают файлы, кидают ошибки, делают HTTP или БД запросы и т.д. Вся жизнь - тут.

### Схема проекта
- `BaseObject.php` - Базовый объект
- `Bot.php` - сущность для ботов
- `Config.php` - парсер конфига
- `Context.php` - Контекст для текущей сессии пользователя
- `DataBase.php` - Объект для работы с БД. Мы используем PDO MySQL.
- `Entity.php` - Родительский объект для всех сущностей
- `HeadView.php` - Объект для создания базового каркаса HTML
- `Memcached.php` - Объект для работы с кешем
- `User.php` - сущность пользователя
- `View.php` - Объект для работы с статическим серверным HTML.