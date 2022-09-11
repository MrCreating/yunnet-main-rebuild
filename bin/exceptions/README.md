# bin/exceptions

Классы для исключений. Наследуем от `BaseException`

### Схема проекта
- `BaseException.php` - главный класс главной ошибки UntEngine.
- `EntityNotFoundException.php` - ошибка для несуществующей сущности
- `FatalException.php` - передается в конфиг ошибок при критической ошибке, завершающей работу PHP
- `FileNotFoundException.php` - ошибка несуществубщего файла
- `IncorrectSessionException.php` - ошибка авторизации
- `InvalidConfigException.php` - ошибка неправильной настройки конфига (не хватает полей или не тот тип данных)
- `InvalidPropertyException.php` - ошибка изменения защищённого объекта UntEngine