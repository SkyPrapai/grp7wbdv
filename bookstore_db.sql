-- phpMyAdmin SQL Dump
-- version 2.9.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Nov 30, 2024 at 12:25 AM
-- Server version: 5.0.27
-- PHP Version: 5.2.1
-- 
-- Database: `bookstore_db`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `books`
-- 

CREATE TABLE `books` (
  `id` int(11) NOT NULL auto_increment,
  `book_title` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `book_image` varchar(255) NOT NULL,
  `book_author` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- 
-- Dumping data for table `books`
-- 

INSERT INTO `books` (`id`, `book_title`, `price`, `book_image`, `book_author`) VALUES 
(1, 'The Things You Can See Only When You Slow Down', 659.00, 'https://m.media-amazon.com/images/I/51qXi-sZYrL._SY780_.jpg', 'Haemin Sunim'),
(2, 'Atomic Habits', 1199.00, 'https://cdn.kobo.com/book-images/24463cb4-28ad-48cb-807f-158cf6d11a92/1200/1200/False/atomic-habits-tiny-changes-remarkable-results.jpg', 'James Clear'),
(3, 'The Subtle Art of Not Giving a F*ck', 845.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQzzZW-gz_vtgxuN0f2w_HwDXjbifEdCFxhwg&s', 'Mark Manson'),
(4, 'The Mountain Is You', 1080.00, 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1590806892i/53642699.jpg', 'Brianna Wiest'),
(5, 'A Gentle Reminder', 1029.00, 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1615620038i/57393737.jpg', 'Bianca Sparacino'),
(6, 'The Strength In Our Scars', 1050.00, 'https://assets.literal.club/2/ckrt59p0c2243131esqaoo45u7t.jpg?size=200', 'Bianca Sparacino'),
(7, 'You''re Not Enough (and That''s Okay)', 1450.00, 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1565249516l/51039323.jpg', 'Allie Beth Stuckey'),
(8, 'How to Win Friends & Influence People', 599.00, 'https://i.gr-assets.com/images/S/compressed.photo.goodreads.com/books/1650470724l/59366200.jpg', 'Dale Carnegie'),
(9, 'When You''re Ready, This Is How You Heal', 1125.00, 'https://dynamic.indigoimages.ca/v1/books/books/194975944X/1.jpg?width=810&maxHeight=810&quality=85', 'Brianna Wiest');

-- --------------------------------------------------------

-- 
-- Table structure for table `email_verifications`
-- 

CREATE TABLE `email_verifications` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `expires_at` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

-- 
-- Dumping data for table `email_verifications`
-- 

INSERT INTO `email_verifications` (`id`, `user_id`, `token`, `created_at`, `expires_at`) VALUES 
(8, 11, '13c61d0a289470a50a9c649b0fc2fe18', '2024-10-20 01:42:19', '2024-10-20 17:42:19'),
(9, 12, '8e38404193b2459d482e3652b498f9c5', '2024-10-20 01:47:23', '2024-10-20 17:47:23'),
(10, 13, '81f3112c8e63d229c1ccd4a74830f2e3', '2024-10-20 01:55:04', '2024-10-20 17:55:04'),
(11, 14, 'e85481e97ddc83177780296c363a6c49', '2024-10-20 02:00:28', '2024-10-20 18:00:28'),
(12, 15, '923917ea297fcf9174edab469f38c577', '2024-10-20 02:03:18', '2024-10-20 18:03:17'),
(13, 16, '92edc35af0cdf6a1e02a254c263733a2', '2024-10-20 02:07:32', '2024-10-20 18:07:32'),
(14, 17, '175686f7acafd943f15b900136880827', '2024-10-20 02:14:35', '2024-10-20 18:14:35'),
(15, 18, '87b83ef4f94f51ee23f554d47c2ca886', '2024-10-20 02:15:39', '2024-10-20 18:15:39'),
(16, 19, '980a546cd3ee2cc5f7ee33e24190b563', '2024-10-20 02:16:51', '2024-10-20 18:16:51'),
(17, 20, '7538dfac1e551231c6f770d72b46a1ab', '2024-10-20 02:19:44', '2024-10-20 18:19:44'),
(18, 21, 'da1b23ccdedbe58faf42230ef044b9ee', '2024-10-20 02:21:24', '2024-10-20 18:21:24'),
(19, 22, 'e8f2ce683ea81ec09fa7555bf5aad210', '2024-10-20 02:28:04', '2024-10-20 18:28:04'),
(20, 23, '31b1a8fe31b5184cc5602b74ecd4ca59', '2024-10-20 02:33:02', '2024-10-20 18:33:02'),
(21, 10, '5bb34fec1556c58957069fa71781b3c7', '2024-10-20 02:38:02', '2024-10-20 18:38:02'),
(22, 10, '44a827c2f6f0b160ae33d52d667cdb6c', '2024-10-20 02:42:47', '2024-10-20 18:42:47'),
(23, 11, 'eaa58f9b2ea3925a58fe3f0aeaafac6b', '2024-10-26 19:38:03', '2024-10-27 11:38:03'),
(24, 12, '2526132dd1b0f1704234f1fd3d6b890a', '2024-10-26 19:40:56', '2024-10-27 11:40:56'),
(25, 12, '46cb5821b19c46779e47713ff51dd79c', '2024-10-26 19:42:11', '2024-10-27 11:42:11'),
(26, 13, 'ef88b6434286804eb4c4f2abea8e382d', '2024-10-30 20:56:57', '2024-10-31 12:56:57'),
(27, 14, 'fbcdaa9e93d0f6df9a71f1de40d4ca30', '2024-10-31 03:33:58', '2024-10-31 19:33:58'),
(28, 15, 'f8b83f0cca9fc87e2667166a526cc53b', '2024-11-03 15:08:22', '2024-11-04 07:08:22'),
(29, 16, '44f7b0a472bc83800273f3ec47f7ee91', '2024-11-03 15:10:39', '2024-11-04 07:10:39'),
(30, 16, 'aaa2f5ac20f1c50ed81126d27a8d9d78', '2024-11-03 15:21:24', '2024-11-04 07:21:24'),
(31, 17, 'f24249eef27ebc903af19969b402b9a7', '2024-11-05 13:48:40', '2024-11-06 05:48:40'),
(32, 18, '2ceede78c0324c371e712cc5b84fc6ca', '2024-11-05 13:59:44', '2024-11-06 05:59:44'),
(33, 19, '07edd53285fb1e22edb971d8d55e2e14', '2024-11-05 15:14:51', '2024-11-06 07:14:51');

-- --------------------------------------------------------

-- 
-- Table structure for table `order_items`
-- 

CREATE TABLE `order_items` (
  `item_id` int(11) NOT NULL auto_increment,
  `order_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY  (`item_id`),
  KEY `order_id` (`order_id`),
  KEY `book_id` (`book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=177 ;

-- 
-- Dumping data for table `order_items`
-- 

INSERT INTO `order_items` (`item_id`, `order_id`, `book_id`, `quantity`, `price`) VALUES 
(159, 14, 3, 5, 845.00),
(160, 15, 2, 1, 1199.00),
(161, 15, 1, 1, 659.00),
(162, 16, 5, 2, 1029.00),
(166, 19, 4, 2, 1080.00),
(167, 19, 5, 1, 1029.00),
(168, 19, 6, 1, 1050.00),
(169, 20, 6, 1, 1050.00),
(170, 21, 7, 1, 1450.00),
(171, 22, 4, 1, 1080.00),
(172, 23, 6, 1, 1050.00),
(173, 23, 5, 1, 1029.00),
(174, 23, 4, 1, 1080.00),
(175, 24, 2, 1, 1199.00),
(176, 24, 3, 1, 845.00);

-- --------------------------------------------------------

-- 
-- Table structure for table `orders`
-- 

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `order_number` varchar(20) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `payment_method` varchar(20) NOT NULL,
  `order_date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `address` varchar(255) default NULL,
  PRIMARY KEY  (`order_id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

-- 
-- Dumping data for table `orders`
-- 

INSERT INTO `orders` (`order_id`, `user_id`, `order_number`, `total_price`, `payment_method`, `order_date`, `address`) VALUES 
(12, 13, '67228757bbfb8', 4225.00, 'Cash on Delivery', '2024-10-31 03:21:59', 'Quezon City'),
(13, 13, '6722877d5bc9a', 4225.00, 'Cash on Delivery', '2024-10-31 03:22:37', 'Quezon City'),
(14, 13, '672287c8666fb', 4225.00, 'Cash on Delivery', '2024-10-31 03:23:52', 'Quezon City'),
(15, 13, '6722881faec64', 1858.00, 'Gcash', '2024-10-31 03:25:19', 'Quezon City'),
(16, 13, '67228abe6c01b', 2058.00, 'Gcash', '2024-10-31 03:36:30', 'Quezon City'),
(19, 17, '6729b37572e5d', 4239.00, 'Cash on Delivery', '2024-11-05 13:56:05', 'Kahit saan'),
(20, 17, '6729b38b26fc6', 1050.00, 'PayPal', '2024-11-05 13:56:27', 'Kahit saan'),
(21, 18, '6729b5270017a', 1450.00, 'PayPal', '2024-11-05 14:03:19', 'caloocan city'),
(22, 18, '6729b55e93d5b', 1080.00, 'PayPal', '2024-11-05 14:04:14', 'caloocan city'),
(23, 13, '6729b66b9fe67', 3159.00, 'PayPal', '2024-11-05 14:08:43', 'Quezon City'),
(24, 18, '6729b735d8ebf', 2044.00, 'Cash on Delivery', '2024-11-05 14:12:05', 'caloocan city');

-- --------------------------------------------------------

-- 
-- Table structure for table `users`
-- 

CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `birthdate` date NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) NOT NULL,
  `account_status` enum('Active','Pending','Deactivated') default 'Pending',
  `role` enum('member','admin') NOT NULL default 'member',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

-- 
-- Dumping data for table `users`
-- 

INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `address`, `gender`, `birthdate`, `phone_number`, `password`, `profile_picture`, `account_status`, `role`) VALUES 
(13, 'Sky', 'Prapai', 'jamierivan123@gmail.com', 'Quezon City', 'Male', '2024-10-01', '09603081740', '7d504b6f9194dc6cc05260d0c8be0220', 'Pirena_WT.webp', 'Active', 'admin'),
(15, 'Uplift Page', 'Bookstore', 'upliftpagebookstore@gmail.com', 'Quezon City', 'Male', '2011-11-02', '09603081740', '7d504b6f9194dc6cc05260d0c8be0220', '', 'Pending', 'member'),
(17, 'Alfred', 'Suba', 'alfredsuba5523@gmail.com', 'Kahit saan', 'Male', '2005-01-05', '09123457890', '19054daecea9df81245612f869b4384e', '', 'Active', 'member'),
(18, 'jamaine', 'javier', 'jamainesanchez@gmail.com', 'caloocan city ', 'Male', '2005-03-03', '09134577290', 'f5f6c2d3199f4ff3b6af2350b4c8f97c', '', 'Active', 'member');

-- 
-- Constraints for dumped tables
-- 

-- 
-- Constraints for table `order_items`
-- 
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);

-- 
-- Constraints for table `orders`
-- 
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
