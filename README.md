# Preview
Preview is available here http://cardsort.nabytky.sk/
![alt tag](http://showcase.stranovsky.sk/github/freecardsort.jpg)

# What is cardsort?
Card sorting is a technique in user experience design in which a person tests a group of subject experts or users to generate a dendrogram (category tree) or folksonomy. It is a useful approach for designing information architecture, workflows, menu structure, or web site navigation paths.

# Server requirements
PHP 7

Apache

Mysql
# Installation
1. Rename config/config.sample.php to config/config.php and fill the base_url.
2. Create database with following structure 

```sql
SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `applicants`;
CREATE TABLE `applicants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `age_group` text NOT NULL,
  `gender` text NOT NULL,
  `ip_address` text NOT NULL,
  `language` text,
  `started_timestamp` text,
  `end_timestamp` text,
  `data` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2018-01-10 22:54:36
```

3. Rename config/database.sample.php to config/database.php and fill data to database.
4. Write your own card in cards.js


# TODO
1. Responsive version.