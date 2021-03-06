# Infonesy: реализация под BORS©

Пока рыба для накопления задач в Issues. Код будет позже.

## Ссылки

* [Проект Infonesy](https://github.com/Balancer/infonesy) — распределённая социальная метасистема.
* [Основной код на Bitbucket](https://bitbucket.org/Balancer/infonesy-bors).

## Пакеты драйверов в отдельных репозиториях под Composer, в стадии разработки

### Драйвера форумов

- [Vanilla Forums](https://github.com/Balancer/infonesy-driver-vanilla) — стадия ранней разработки
- [Flarum](https://github.com/Balancer/infonesy-driver-flarum) — стадия ранней разработки

### Драйвера транспорта

- [FileSync](https://github.com/Balancer/infonesy-transport-filesync) — обмен данными через BTSync, Syncthing и т.п.

## Планируется поддержка:

* [MyBB](https://www.mybb.com/) — основной упор будет на вторую версию после выхода (с поддержкой Markdown). Пока демо и тесты на [unlimit-talks.tk](http://www.unlimit-talks.tk/) (трансяция форумов Авиабазы)
* [Vanilla](https://vanillaforums.org/). Демо на [vanilla.docker.home.balancer.ru](http://vanilla.docker.home.balancer.ru/). Трансляция не идёт.
* [Flarum](http://flarum.org/) — демо на [flarum.ultimon.wrk.ru](http://flarum.ultimon.wrk.ru/) (разовые тестовые трансляции)
* [FluxBB](http://fluxbb.org/) — базовый упрощённый функционал. Демо на [fluxbb.ams.wrk.ru](http://fluxbb.ams.wrk.ru/). Живой трансляции нет.
* [ZeroNet](http://zeronet.io) — на стадии разработки. Почитать о ходе работ можно в ZeroNet по адресу [1BpFtPez7mSiShtXfb4wPfMT1dZTuRybfZ/?Topic:2_1PniNzyi8fygvwyBaLpA9oBDVWZ5fXuJUw](https://proxy1.zn.kindlyfire.me/1BpFtPez7mSiShtXfb4wPfMT1dZTuRybfZ/?Topic:2_1PniNzyi8fygvwyBaLpA9oBDVWZ5fXuJUw)

## Изучается возможность взаимодействия с:

* [Anahita CMS](https://www.getanahita.com/) — [Демо](http://anahita.works.home.balancer.ru/) — без регистрации ничего на главной увидеть нельзя, что минус.
* [LiveStreet CMS](http://livestreetcms.ru/) —  [Демо](http://ls.balancer.ru/)
* [Retroshare](http://retroshare.sourceforge.net/) — пока абсолютно непонятно, как с ней работать через cli или API

## Поддержка отложена надолго:

- [phpBB3 (демосайт Infonesy)](http://phpbb3.infonesy.tk/) — из-за [крайней кривости движка](http://phpbb3.infonesy.tk/viewtopic.php?f=2&t=2).
