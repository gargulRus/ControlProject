-- --------------------------------------------------------
-- Хост:                         192.168.62.33
-- Версия сервера:               5.7.27-0ubuntu0.16.04.1 - (Ubuntu)
-- Операционная система:         Linux
-- HeidiSQL Версия:              9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Дамп структуры базы данных abigordatabase
CREATE DATABASE IF NOT EXISTS `abigordatabase` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `abigordatabase`;

-- Дамп структуры для таблица abigordatabase.autent
CREATE TABLE IF NOT EXISTS `autent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) DEFAULT '0',
  `pass` varchar(50) DEFAULT '0',
  `role` varchar(50) DEFAULT '0',
  `name` varchar(50) DEFAULT '0',
  `exe_id` int(11) DEFAULT '0',
  `role_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы abigordatabase.autent: ~6 rows (приблизительно)
/*!40000 ALTER TABLE `autent` DISABLE KEYS */;
INSERT INTO `autent` (`id`, `login`, `pass`, `role`, `name`, `exe_id`, `role_id`) VALUES
	(2, 'admin', 'a1b4DD%', 'admin', 'Администратор', 0, 1),
	(3, 'user1', 'a1b4DD%', 'user', 'Пользователь', 1, 0),
	(5, 'Sharapov', 'a1b4DD%', 'user', 'Шарапов Сергей', 7, 2),
	(10, 'Aleskeev', 'a1b4DD%', 'user', 'Алексеев', 16, 1),
	(11, 'testuser', '12345', '0', 'testuser', 17, 2),
	(12, 'Andrey.Alekseev@vconnect.ru', '37bAm2cY7', '0', 'Алексеев Андрей', 19, 2);
/*!40000 ALTER TABLE `autent` ENABLE KEYS */;

-- Дамп структуры для таблица abigordatabase.client
CREATE TABLE IF NOT EXISTS `client` (
  `client_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы abigordatabase.client: ~4 rows (приблизительно)
/*!40000 ALTER TABLE `client` DISABLE KEYS */;
INSERT INTO `client` (`client_id`, `client_name`, `email`) VALUES
	(1, 'Спорт-мастер', NULL),
	(2, 'Директ-Кредит', NULL),
	(3, 'М-Видео', NULL),
	(5, 'А1', NULL);
/*!40000 ALTER TABLE `client` ENABLE KEYS */;

-- Дамп структуры для таблица abigordatabase.executor
CREATE TABLE IF NOT EXISTS `executor` (
  `executor_id` int(11) NOT NULL AUTO_INCREMENT,
  `executor_name` varchar(50) NOT NULL DEFAULT '0',
  `email` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`executor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы abigordatabase.executor: ~4 rows (приблизительно)
/*!40000 ALTER TABLE `executor` DISABLE KEYS */;
INSERT INTO `executor` (`executor_id`, `executor_name`, `email`) VALUES
	(7, 'Шарапов Сергей', 'kola9@yandex.ru'),
	(16, 'Алексеев', 'it@giprozdraw.ru'),
	(17, 'testuser', '123@123.ru'),
	(19, 'Алексеев Андрей', 'Andrey.Alekseev@vconnect.ru');
/*!40000 ALTER TABLE `executor` ENABLE KEYS */;

-- Дамп структуры для таблица abigordatabase.mainproject
CREATE TABLE IF NOT EXISTS `mainproject` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '0',
  `service` varchar(50) NOT NULL DEFAULT '0',
  `speed` varchar(50) NOT NULL DEFAULT '0',
  `address` varchar(50) NOT NULL DEFAULT '0',
  `status` varchar(50) NOT NULL DEFAULT '0',
  `state_id` int(11) NOT NULL DEFAULT '0',
  `executor_id` int(11) NOT NULL DEFAULT '0',
  `client_id` int(11) NOT NULL DEFAULT '0',
  `username` varchar(50) NOT NULL DEFAULT '0',
  `date_plan` date DEFAULT NULL,
  `edit_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `proj_num` int(11) DEFAULT NULL,
  `trash` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы abigordatabase.mainproject: ~6 rows (приблизительно)
/*!40000 ALTER TABLE `mainproject` DISABLE KEYS */;
INSERT INTO `mainproject` (`id`, `name`, `service`, `speed`, `address`, `status`, `state_id`, `executor_id`, `client_id`, `username`, `date_plan`, `edit_time`, `proj_num`, `trash`) VALUES
	(14, 'GA-2019/40-000001', 'Доступ в интернет', '25', 'г. Москва, Чонгралский, д.7', 'Прошу взять в работу.', 1, 16, 3, 'Администратор', '2019-09-25', '2019-10-01 17:47:31', NULL, NULL),
	(15, 'GA-2019/40-000002', 'Доступ в интернет', '25', 'Москва, красная площадь', 'Прошу взять в работу.', 1, 16, 2, 'Администратор', '2019-11-22', '2019-10-01 17:47:49', NULL, NULL),
	(16, 'GA-2019/40-000003', 'VPN', '10', 'Москва, ТЦ РИО', '', 1, 7, 1, 'Администратор', '2019-10-29', '2019-10-01 17:47:41', NULL, NULL),
	(17, 'GA-2019/40-000004', 'VPN', '10', 'Москва, Ленинградское ш. д.10', 'Прошу взять в работу.', 1, 16, 5, 'Администратор', '2019-10-27', '2019-10-01 17:47:58', NULL, NULL),
	(27, 'GA-2019/41-000005', 'VPN', '10', 'Самара городок', 'Взяд в работу. Запрос отправлен оператору.', 1, 19, 1, 'Алексеев', '2019-10-13', '2019-10-07 13:27:34', NULL, NULL),
	(28, 'GA-2019/41-000006', 'Услуга-4', '66', 'Marksistskaya st, 7, 77', 'Статус -4', 2, 7, 1, '0', '2019-10-23', '2019-10-07 12:47:57', NULL, 1);
/*!40000 ALTER TABLE `mainproject` ENABLE KEYS */;

-- Дамп структуры для таблица abigordatabase.role
CREATE TABLE IF NOT EXISTS `role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы abigordatabase.role: ~2 rows (приблизительно)
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` (`role_id`, `role_name`) VALUES
	(1, 'admin'),
	(2, 'user');
/*!40000 ALTER TABLE `role` ENABLE KEYS */;

-- Дамп структуры для таблица abigordatabase.state
CREATE TABLE IF NOT EXISTS `state` (
  `state_id` int(11) NOT NULL AUTO_INCREMENT,
  `state_name` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`state_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы abigordatabase.state: ~4 rows (приблизительно)
/*!40000 ALTER TABLE `state` DISABLE KEYS */;
INSERT INTO `state` (`state_id`, `state_name`) VALUES
	(1, 'В работе'),
	(2, 'Выполнено'),
	(3, 'Hold'),
	(4, 'Отменён');
/*!40000 ALTER TABLE `state` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
