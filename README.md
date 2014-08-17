eZ Components / Database
========================

A lightweight database layer on top of PHP's PDO that allows you to utilize a
database without having to take care of differences in SQL dialects.

See [eZ Components](http://ezcomponents.org/)

# Конструктор запросов
Конструктор запросов ezc предоставляет объектно-ориентированный способ написания SQL-запросов. Он позволяет разработчику использовать методы и свойства класса для того, чтобы указать отдельные части SQL-запроса. Затем конструктор собирает отдельные части в единый SQL-запрос, который может быть выполнен вызовом методов ``query`` или ``prepare``.

### Подготовка конструктора запросов

- ``ezcQuerySelect()``
- ``ezcUpdateQuery()``
- ``ezcInsertQuery()``
- ``ezcQueryDelete()``
- ``ezcQueryExpression()``
- ``ezcDbUtilities()``



### Запросы на получение данных

Запросы на получение данных соответствуют SQL-запросам SELECT. В конструкторе есть ряд методов для сборки отдельных частей SELECT запроса. Так как все эти методы возвращают экземпляр ``ezcQuery``, мы можем использовать их цепочкой.

- ``select()``: часть запроса после SELECT.
- ``selectDistinct()``: часть запроса после SELECT. Добавляет DISTINCT.
- ``from()``: часть запроса после FROM.
- ``where()``: часть запроса после WHERE.
- ``join()``: добавляет к запросу INNER JOIN.
- ``leftJoin()``: добавляет к запросу LEFT OUTER JOIN.
- ``rightJoin()``: добавляет к запросу RIGHT OUTER JOIN.
- ``innerJoin()``: добавляет к запросу CROSS JOIN.
- ``groupBy()``: часть запроса после GROUP BY.
- ``having()``: часть запроса после HAVING.
- ``orderBy()``: часть запроса после ORDER BY.
- ``limit()``: часть запроса после LIMIT.


