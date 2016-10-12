# Сравнение популярности открытых форумных движков на PHP

Форумный движок | Контриб. | Коммиты | AT Rating | Markdown | composer | Tapatalk | Размер | Исходники                                 | Коммит     | Примечания
----------------|----------|---------|-----------|----------|----------|----------|--------|-------------------------------------------|------------|-----------
Discourse       |      538 |   20515 |        54 | да       | нет      |          |        | https://github.com/discourse/discourse    | 07.10.2016 |
phpBB 3         |      145 |   28951 |        64 | нет      | нет      | да       |   19MB | https://github.com/phpbb/phpbb            | 03.10.2016 |
Vanilla forums  |       90 |   15384 |           | да       | да       |          |   31MB | https://github.com/vanilla/vanilla        | 07.10.2016 |
MyBB            |       68 |    4172 |        17 | нет      | нет      | да       |   11MB | https://github.com/mybb/mybb              | 23.09.2016 |
SMF2.1          |       65 |    8223 |        19 |          | нет      | да       |        | https://github.com/SimpleMachines/SMF2.1  | 30.09.2016 |
FluxBB          |       25 |    1625 |        17 | нет      | нет      |          |        | https://github.com/fluxbb/fluxbb          | 16.06.2016 |
Phosphorum 3    |       20 |     853 |           |          | да       |          |        | https://github.com/phalcon/forum          | 29.08.2016 | Phalcon official Forum
MyBB2           |       17 |    1012 |           | да       | да       |          |        | https://github.com/mybb/mybb2             | 09.09.2016 |
PunBB           |       16 |    1378 |         4 |          | нет      |          |        | https://github.com/punbb/punbb            | 14.09.2016 |
Carbon-Forum    |       14 |    1059 |           |          | нет      |          |        | https://github.com/lincanbin/Carbon-Forum | 08.09.2016 |
Flarum          |       11 |     248 |        18 | да       | да       |          |   53MB | https://github.com/flarum/flarum          | 19.07.2016 |
Phorum          |       10 |    3719 |         1 |          | нет      |          |        | https://github.com/Phorum/Core            | 27.09.2016 |
FeatherBB       |        5 |    1097 |           |          | да       |          |        | https://github.com/featherbb/featherbb    | 22.02.2016 | Форк FluxBB 1.5
TangoBB         |        5 |     441 |         2 |          | нет      |          |        | https://github.com/Codetana/TangoBB       | 09.06.2016 |

### Также

- esoTalk превратился во Flarum

## Впечатления

### Discourse

- Тема по умолчанию корявая, но, вроде, есть приличные: http://forums.tumult.com/
- AJAX-дополнение списков тем при пролистывании
- AJAX-загрузка топиков при пролистывании
- Двухпанельный редактор с живым просмотром

### phpBB3

- При установке по дефолту полез длинный редирект: «http://phpbb3.infonesy.tk/index.php/install/install/install/install/install/install/install/install/install/install/install/install/install/install/install/install/install/install/install/install/install/index.php».
- PHP7 не поддерживается.
- Установка языкового расширения — всё такое же ручное копирование файлов с перезаписью системных.
- Тема по умолчанию ужасна, поиск тем нужно производить вручную, качать их и устанавливать — тоже вручную.
- Такая же беда с расширениями. Всё ручками, ручками... Даже сложно представить, почему этот убогий движок до сих пор так популярен :)
- Поддержки Markdown нет и, возможно, не предвидится вообще.
- Есть поддержка уймы БД, в т.ч. SQLite
