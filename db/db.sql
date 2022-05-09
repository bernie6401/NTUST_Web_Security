SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+08:00";

CREATE TABLE `users_info`
(
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `avatar_id` varchar(150) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `users_blog`
(
  `post_id` varchar(11) NOT NULL,
  `user_name` varchar(64) NOT NULL,
  `blog_title` varchar(200) NOT NULL,
  `blog_content` varchar(2000) NOT NULL,
  `post_time` timestamp NOT NULL,
  `attach_file_addr` varchar(200),
  PRIMARY KEY (post_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `page_title`
(
  `title_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- INSERT INTO `users_info` (`id`, `username`, `password`, `avatar_id`) VALUES ("1", "sbkadmin", "StandWithUkraine", "./upload_data/default_avatar.jpg");
-- INSERT INTO `users_blog` (`post_id`, `user_name`, `blog_title`, `blog_content`, `post_time`, `attach_file_addr`) VALUES ("1", "sbkadmin", "test", "test", now(), "");
INSERT INTO `page_title` (`title_name`) VALUES ("Login Page");