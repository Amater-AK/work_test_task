-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Июл 07 2022 г., 15:01
-- Версия сервера: 10.4.24-MariaDB
-- Версия PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `test_project_v1.demo`
--

-- --------------------------------------------------------

--
-- Структура таблицы `indicators`
--

CREATE TABLE `indicators` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `filler_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `indicators`
--

INSERT INTO `indicators` (`id`, `title`, `filler_id`) VALUES
(1, 'Показ-ь 1', 1),
(2, 'Показ-ь 2', 1),
(3, 'Показ-ь 3', 2),
(4, 'Показ-ь 4', 2),
(5, 'Показ-ь 5', 3),
(6, 'Показ-ь 6', 3);

-- --------------------------------------------------------

--
-- Структура таблицы `indicators_values`
--

CREATE TABLE `indicators_values` (
  `id` int(11) NOT NULL,
  `indicator_id` int(11) NOT NULL,
  `checker_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL DEFAULT '0',
  `period_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `indicators_values`
--

INSERT INTO `indicators_values` (`id`, `indicator_id`, `checker_id`, `value`, `period_id`) VALUES
(1, 1, 4, '1', 15),
(2, 2, 4, '2', 15),
(3, 3, 4, '3', 15),
(4, 4, 4, '4', 15),
(5, 5, 4, '5', 15),
(6, 6, 4, '6', 15),
(7, 1, 5, '7', 15),
(8, 2, 5, '8', 15),
(9, 3, 5, '9', 15),
(10, 4, 5, '10', 15),
(11, 5, 5, '11', 15),
(12, 6, 5, '12', 15),
(13, 1, 6, '13', 15),
(14, 2, 6, '14', 15),
(15, 3, 6, '15', 15),
(16, 4, 6, '16', 15),
(17, 5, 6, '17', 15),
(18, 6, 6, '18', 15),
(56, 1, 4, '0', 25),
(57, 2, 4, '0', 25),
(58, 3, 4, '0', 25),
(59, 4, 4, 't', 25),
(60, 5, 4, '0', 25),
(61, 6, 4, '0', 25),
(62, 1, 5, '0', 25),
(63, 2, 5, '0', 25),
(64, 3, 5, '0', 25),
(65, 4, 5, 'es', 25),
(66, 5, 5, '0', 25),
(67, 6, 5, '0', 25),
(68, 1, 6, '0', 25),
(69, 2, 6, '0', 25),
(70, 3, 6, '0', 25),
(71, 4, 6, 't', 25),
(72, 5, 6, '0', 25),
(73, 6, 6, 'a', 25),
(80, 1, 4, '0', 32),
(81, 2, 4, '0', 32),
(82, 3, 4, '0', 32),
(83, 4, 4, '0', 32),
(84, 5, 4, '0', 32),
(85, 6, 4, '0', 32),
(86, 1, 5, '0', 32),
(87, 2, 5, '0', 32),
(88, 3, 5, '0', 32),
(89, 4, 5, '0', 32),
(90, 5, 5, '0', 32),
(91, 6, 5, '0', 32),
(92, 1, 6, '0', 32),
(93, 2, 6, '0', 32),
(94, 3, 6, '0', 32),
(95, 4, 6, '0', 32),
(96, 5, 6, '0', 32),
(97, 6, 6, '0', 32);

-- --------------------------------------------------------

--
-- Структура таблицы `periods`
--

CREATE TABLE `periods` (
  `id` int(11) NOT NULL,
  `period_start` datetime NOT NULL DEFAULT current_timestamp(),
  `period_end` datetime DEFAULT NULL,
  `isArchived` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `periods`
--

INSERT INTO `periods` (`id`, `period_start`, `period_end`, `isArchived`) VALUES
(15, '2022-07-05 13:55:06', '2022-07-05 14:06:01', 1),
(25, '2022-07-05 15:14:02', '2022-07-07 15:43:01', 1),
(32, '2022-07-07 15:43:01', NULL, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `page_alias` varchar(255) NOT NULL,
  `isFiller` tinyint(1) NOT NULL DEFAULT 0,
  `isChecker` tinyint(1) NOT NULL DEFAULT 0,
  `isControl` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`id`, `title`, `page_alias`, `isFiller`, `isChecker`, `isControl`) VALUES
(1, 'Заполняющий', 'filler.php', 1, 0, 0),
(2, 'Проверяющий', 'checker.php', 0, 1, 0),
(3, 'Контроллёр', 'control.php', 0, 0, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `full_name`, `role_id`) VALUES
(1, 'filler1', '$2y$10$30rTiWi3A88kRzR1kD9tqO/m/myeEiKU/AknbmFN0zmNNGsa1kg2C', 'ФИО Запол-й 1', 1),
(2, 'filler2', '$2y$10$30rTiWi3A88kRzR1kD9tqO/m/myeEiKU/AknbmFN0zmNNGsa1kg2C', 'ФИО Запол-й 2', 1),
(3, 'filler3', '$2y$10$30rTiWi3A88kRzR1kD9tqO/m/myeEiKU/AknbmFN0zmNNGsa1kg2C', 'ФИО Запол-й 3', 1),
(4, 'checker1', '$2y$10$30rTiWi3A88kRzR1kD9tqO/m/myeEiKU/AknbmFN0zmNNGsa1kg2C', 'ФИО Провер-й 1', 2),
(5, 'checker2', '$2y$10$30rTiWi3A88kRzR1kD9tqO/m/myeEiKU/AknbmFN0zmNNGsa1kg2C', 'ФИО Провер-й 2', 2),
(6, 'checker3', '$2y$10$30rTiWi3A88kRzR1kD9tqO/m/myeEiKU/AknbmFN0zmNNGsa1kg2C', 'ФИО Провер-й 3', 2),
(7, 'control', '$2y$10$30rTiWi3A88kRzR1kD9tqO/m/myeEiKU/AknbmFN0zmNNGsa1kg2C', 'ФИО Контр-р', 3);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `indicators`
--
ALTER TABLE `indicators`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UN_title` (`title`) USING BTREE,
  ADD KEY `FK_filler_id` (`filler_id`);

--
-- Индексы таблицы `indicators_values`
--
ALTER TABLE `indicators_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_indicator_id` (`indicator_id`),
  ADD KEY `FK_checker_id` (`checker_id`),
  ADD KEY `FK_period_id` (`period_id`);

--
-- Индексы таблицы `periods`
--
ALTER TABLE `periods`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Индексы таблицы `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UN_title` (`title`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UN_login` (`login`) USING BTREE,
  ADD KEY `FK_role_id` (`role_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `indicators`
--
ALTER TABLE `indicators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `indicators_values`
--
ALTER TABLE `indicators_values`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT для таблицы `periods`
--
ALTER TABLE `periods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT для таблицы `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `indicators`
--
ALTER TABLE `indicators`
  ADD CONSTRAINT `FK_filler_id` FOREIGN KEY (`filler_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `indicators_values`
--
ALTER TABLE `indicators_values`
  ADD CONSTRAINT `FK_checker_id` FOREIGN KEY (`checker_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_indicator_id` FOREIGN KEY (`indicator_id`) REFERENCES `indicators` (`id`),
  ADD CONSTRAINT `FK_period_id` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`);

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_role_id` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
