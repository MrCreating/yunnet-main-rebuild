# config/local/config/postfix

Конфиги для почтового сервера Postfix.

### Схема проекта
- `exim4.conf.template` - шаблон для конфига от "писем в спам"
- `mail-addresses` - список почтовых адресов с которых можно слать письма
- `nain.cf` - Главный конфиг Postfix
- `naster.cf` - Конфиг процесса Postfix
- `sendmail` - расширение для связи с почтовым сервером (пользуется в PHP)