-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- ホスト: 127.0.0.1
-- 生成日時: 2013 年 6 月 10 日 08:57
-- サーバのバージョン: 5.5.27
-- PHP のバージョン: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- データベース: `charcoal`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `blogs`
--

CREATE TABLE IF NOT EXISTS `blogs` (
  `blog_id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_category_id` int(11) DEFAULT NULL,
  `blog_name` varchar(255) DEFAULT NULL,
  `post_total` int(11) DEFAULT NULL,
  PRIMARY KEY (`blog_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- テーブルのデータのダンプ `blogs`
--

INSERT INTO `blogs` (`blog_id`, `blog_category_id`, `blog_name`, `post_total`) VALUES
(1, 1, 'my blog', 2),
(2, 2, 'another blog', 1);

-- --------------------------------------------------------

--
-- テーブルの構造 `blog_category`
--

CREATE TABLE IF NOT EXISTS `blog_category` (
  `blog_category_id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_category_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`blog_category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- テーブルのデータのダンプ `blog_category`
--

INSERT INTO `blog_category` (`blog_category_id`, `blog_category_name`) VALUES
(1, 'Books'),
(2, 'Hobby'),
(3, 'Job'),
(4, 'Diary');

-- --------------------------------------------------------

--
-- テーブルの構造 `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) DEFAULT NULL,
  `comment_title` text,
  `comment_body` text,
  `comment_user` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- テーブルのデータのダンプ `comments`
--

INSERT INTO `comments` (`comment_id`, `post_id`, `comment_title`, `comment_body`, `comment_user`) VALUES
(1, 1, 'wolf''s comment', 'my name id wolf.', 'wolf'),
(2, 1, 'bear''s comment', 'Bear comes here', 'bear'),
(3, 2, 'fox''s comment', 'Fox will be back', 'fox');

-- --------------------------------------------------------

--
-- テーブルの構造 `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) DEFAULT NULL,
  `post_title` text,
  `post_body` text,
  `post_user` varchar(255) DEFAULT NULL,
  `favorite` int(11) DEFAULT '0',
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- テーブルのデータのダンプ `posts`
--

INSERT INTO `posts` (`post_id`, `blog_id`, `post_title`, `post_body`, `post_user`, `favorite`) VALUES
(1, 1, 'This is test.', 'My first blog post!', 'hoge', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
