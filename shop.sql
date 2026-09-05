-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 26, 2025 at 11:09 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_table`
--

CREATE TABLE `admin_table` (
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(100) NOT NULL,
  `admin_email` varchar(255) NOT NULL,
  `admin_password` varchar(255) NOT NULL,
  `verification_code` int(11) NOT NULL,
  `email_verified` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_table`
--

INSERT INTO `admin_table` (`admin_id`, `admin_name`, `admin_email`, `admin_password`, `verification_code`, `email_verified`) VALUES
(1, 'root', 'root@.com', 'root', 981532, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cart_details`
--

CREATE TABLE `cart_details` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`) VALUES
(1, 'Gaming'),
(2, 'Games'),
(3, 'Devices');

-- --------------------------------------------------------

--
-- Table structure for table `contact_message`
--

CREATE TABLE `contact_message` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_message`
--

INSERT INTO `contact_message` (`id`, `name`, `email`, `subject`, `message`) VALUES
(2, 'Ajaya', 'ajayalama939@gmail.com', 'To me', 'hshajggaslalhas'),
(3, 'Bijesh', 'ddrakegoch@gmail.com', 'To me', 'AHJKSKJHSKHSKSHKSKSHKSHKSH');

-- --------------------------------------------------------

--
-- Table structure for table `order_status`
--

CREATE TABLE `order_status` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `invoice_number` int(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(255) NOT NULL,
  `order_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_status`
--

INSERT INTO `order_status` (`order_id`, `user_id`, `invoice_number`, `product_id`, `quantity`, `order_status`) VALUES
(1, 2, 1007576434, 2, 1, 'complete'),
(2, 2, 1725054760, 2, 1, 'complete'),
(3, 2, 1161085221, 2, 1, 'pending'),
(4, 2, 154291620, 3, 5, 'complete'),
(5, 2, 154291620, 4, 1, 'complete'),
(6, 2, 1334200051, 9, 1, 'complete'),
(7, 2, 362029646, 2, 4, 'complete'),
(8, 2, 88245633, 2, 8, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_description` text DEFAULT NULL,
  `tag_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `product_image_1` varchar(255) DEFAULT NULL,
  `product_image_2` varchar(255) DEFAULT NULL,
  `product_price` decimal(10,2) DEFAULT NULL,
  `product_in_store` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(100) NOT NULL,
  `stock_update_date` datetime NOT NULL DEFAULT current_timestamp(),
  `quantity_added` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `product_description`, `tag_id`, `category_id`, `product_image_1`, `product_image_2`, `product_price`, `product_in_store`, `date`, `status`, `stock_update_date`, `quantity_added`) VALUES
(2, 'Xbox', 'Xbox is a gaming brand created and owned by Microsoft, first launched in 2001 with the original Xbox console. It has since evolved into a leading platform for both casual and hardcore gamers. The Xbox lineup includes several generations of consoles, such as Xbox 360, Xbox One, and the current Xbox Series X|S, known for their powerful performance, stunning visuals, and large game libraries.\r\n\r\nXbox also offers a subscription service called Xbox Game Pass, giving players access to hundreds of games for a monthly fee. With features like cloud gaming, cross-platform play, and a strong online community through Xbox Live, Xbox continues to be a major player in the gaming industry.', 5, 3, 'x1.png', 'x2.jpg', 100.00, 94, '2025-07-26 08:13:34', 'true', '2025-06-28 19:01:02', 0),
(3, 'Playstation', 'PlayStation is a popular gaming console brand developed by Sony Interactive Entertainment, first released in 1994. Known for its cutting-edge graphics, immersive gameplay, and exclusive titles like God of War, Spider-Man, and The Last of Us, PlayStation has become a leading name in the gaming world.\r\n\r\nThe latest model, PlayStation 5 (PS5), offers ultra-fast load times, ray tracing, and 4K gaming, along with a redesigned DualSense controller featuring advanced haptics and adaptive triggers. PlayStation also provides a subscription service called PlayStation Plus, offering online multiplayer, game trials, and access to a growing library of games.\r\n', 5, 3, 'pl2.jpg', 'pl3.jpg', 200.00, 195, '2025-07-26 08:19:10', 'true', '2025-06-28 19:04:08', 0),
(4, 'IPhone', 'Aaaaa aaa aaa ', 5, 3, 'p1.png', 'p3.jpg', 150.00, 119, '2025-07-25 10:33:37', 'true', '2025-06-28 19:05:18', 0),
(8, 'Macbook', 'The MacBook is a line of laptops developed by Apple Inc., known for its sleek design, high performance, and macOS operating system. First introduced in 2006, MacBooks have become popular among students, professionals, and creatives for their reliability and seamless integration with other Apple devices.\r\n\r\nCurrent models like the MacBook Air and MacBook Pro feature Apple’s custom M-series chips, delivering powerful performance, long battery life, and impressive graphics. MacBooks are favored for tasks like video editing, programming, and everyday productivity, all within a lightweight and premium build.\r\n', 5, 3, 'm1.png', 'm2.jpg', 300.00, 20, '2025-07-26 08:21:01', 'true', '2025-06-28 19:23:11', 0),
(9, 'Minecraft', 'Minecraft is a sandbox video game developed by Mojang Studios and released in 2011. It allows players to explore, build, and survive in a blocky, procedurally generated 3D world. Players can mine resources, craft tools, build structures, and interact with creatures in various game modes, including Survival, Creative, Adventure, and Hardcore.\r\n\r\nWith its simple graphics and limitless creative potential, Minecraft has become one of the best-selling games of all time. It supports single-player and multiplayer gameplay, and its community thrives on custom mods, servers, and user-generated content. Suitable for all ages, Minecraft encourages creativity, problem-solving, and collaboration.\r\n', 1, 2, '1745420298_minecraft.jpg', '1.webp', 10.00, 19, '2025-07-26 08:21:39', 'true', '2025-06-28 19:28:32', 0),
(10, 'Legend of Zelda', 'The Legend of Zelda is a popular action-adventure video game series developed by Nintendo. It follows the hero, Link, as he embarks on quests to rescue Princess Zelda and defeat the evil villain Ganon, often involving puzzle-solving, exploration, and combat.\r\n\r\nFirst released in 1986, the series is known for its rich storytelling, open-world gameplay, and iconic music. Games like Breath of the Wild and Tears of the Kingdom have received critical acclaim for their innovation and expansive worlds, making Zelda one of the most beloved franchises in gaming history.\r\n', 1, 2, '1745398304_zelda_tears_of_the_kingdom.jpg', '74584-3840x2160-desktop-4k-the-legend-of-zelda-wallpaper-photo.webp', 100.00, 12, '2025-07-26 08:52:34', 'true', '2025-06-28 19:31:23', 0),
(12, 'Final Fantasy', 'Final Fantasy is a long-running role-playing game (RPG) series developed by Square Enix. First released in 1987, the series is known for its rich storytelling, memorable characters, and epic fantasy worlds. Each game typically features a new cast, setting, and battle system, often blending science fiction with magic.\r\n\r\nThe series includes both turn-based and real-time combat styles, with popular titles like Final Fantasy VII, X, and XV gaining worldwide acclaim. Final Fantasy has also expanded into movies, anime, and online multiplayer games, making it one of the most influential and successful RPG franchises in gaming history.\r\n', 1, 2, '1745420263_final_fantasy_xvi.jpg', 'ff.webp', 10.00, 60, '2025-07-26 08:53:05', 'true', '2025-06-28 19:34:39', 0),
(13, 'Phasmaphobia', 'Phasmophobia is a popular multiplayer horror game developed by Kinetic Games. Released in early access in 2020, players take on the role of paranormal investigators who explore haunted locations to gather evidence of ghost activity.\r\n\r\nThe game features immersive first-person gameplay, where players use various ghost-hunting tools like EMF readers and spirit boxes while communicating via voice recognition. Its tense atmosphere, unpredictable ghost behavior, and cooperative gameplay have made Phasmophobia a favorite among horror and online gaming communities.\r\n', 4, 2, 'pp.webp', 'pp1.webp', 200.00, 40, '2025-07-26 08:53:46', 'true', '2025-06-28 19:37:07', 0),
(14, 'Madison', 'Madison is a first-person psychological horror game that puts you in the shoes of Luca, a 16-year-old boy who wakes up trapped in a mysterious and terrifying ritual. Armed only with an instant camera, Luca must explore eerie, haunted locations to uncover hidden clues, solve puzzles, and confront malevolent supernatural forces. The game emphasizes atmosphere and suspense, using the camera mechanic to reveal secrets invisible to the naked eye. With its chilling story and immersive gameplay, Madison delivers a tense and unsettling horror experience.\r\n', 4, 2, 'm.webp', 'Madison2.webp', 150.00, 2, '2025-07-26 08:55:16', 'true', '2025-06-28 19:39:46', 0),
(15, 'Call of Duty', 'Call of Duty is a popular first-person shooter video game franchise developed by Infinity Ward and published by Activision. Launched in 2003, the series is known for its intense, fast-paced multiplayer modes and cinematic single-player campaigns, often set during historical wars or modern combat scenarios.\r\n\r\nWith numerous installments like Modern Warfare, Black Ops, and Warzone, Call of Duty has become one of the best-selling and most influential shooters in gaming history, featuring realistic graphics, competitive gameplay, and a strong online community.\r\n', 3, 2, 'c1.webp', 'c2.webp', 150.00, 2, '2025-07-26 08:55:45', 'true', '2025-06-28 19:43:34', 0),
(16, 'GtaV', 'Grand Theft Auto V (GTA V) is an open-world action-adventure game developed by Rockstar Games and released in 2013. Set in the fictional city of Los Santos, it follows three protagonists—Michael, Franklin, and Trevor—as they engage in heists, missions, and various criminal activities.\r\n\r\nKnown for its vast, detailed world and freedom of gameplay, GTA V offers a mix of driving, shooting, exploration, and storytelling. Its online multiplayer mode, GTA Online, provides players with continuous updates, new content, and cooperative or competitive play, making it one of the most popular and successful games ever.\r\n', 2, 1, 'g1.webp', 'g2.webp', 20.00, 22, '2025-07-26 08:56:19', 'true', '2025-06-28 19:45:37', 0),
(17, 'Uncharted', 'Uncharted is a popular action-adventure video game series developed by Naughty Dog and published by Sony Interactive Entertainment. The games follow treasure hunter Nathan Drake as he embarks on globe-trotting adventures filled with puzzles, platforming, and intense combat.\r\n\r\nKnown for its cinematic storytelling, stunning visuals, and engaging gameplay, the Uncharted series has become a flagship franchise for PlayStation. Titles like Uncharted: Drake’s Fortune, Uncharted 2: Among Thieves, and Uncharted 4: A Thief’s End have received critical acclaim for their narrative and production quality.\r\n', 2, 1, '1.webp', '2.jpg', 200.00, 90, '2025-07-26 08:56:47', 'true', '2025-06-28 19:49:01', 0);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `rating` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `username`, `comment`, `rating`, `created_at`, `updated_at`) VALUES
(2, 17, 2, 'Ajaya', 'bad', 5, '2025-07-22 12:00:35', '2025-07-22 12:00:35'),
(3, 16, 2, 'Ajaya', 'gjgkjgg', 5, '2025-07-22 12:01:49', '2025-07-22 12:01:49'),
(4, 16, 3, 'root', 'GOOOD', 4, '2025-07-22 12:05:58', '2025-07-22 12:05:58'),
(5, 17, 3, 'root', 'average in my opinion', 3, '2025-07-22 12:14:20', '2025-07-22 12:14:20'),
(6, 3, 2, 'Ajaya', 'good', 3, '2025-07-25 13:34:09', '2025-07-25 13:34:09'),
(16, 2, 2, 'Ajaya', 'This is  a good product', 3, '2025-07-25 15:26:12', '2025-07-25 15:26:12');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `tag_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `tag_name`) VALUES
(1, 'Action'),
(2, 'Adventure'),
(3, 'Shooting'),
(4, 'Horror'),
(5, 'Hardware');

-- --------------------------------------------------------

--
-- Table structure for table `user_order`
--

CREATE TABLE `user_order` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `amount_due` int(255) NOT NULL,
  `invoice_number` int(255) NOT NULL,
  `total_products` int(255) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `order_status` varchar(255) NOT NULL,
  `admin_status` varchar(100) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_order`
--

INSERT INTO `user_order` (`order_id`, `user_id`, `product_id`, `amount_due`, `invoice_number`, `total_products`, `order_date`, `order_status`, `admin_status`) VALUES
(1, 2, 2, 100, 1007576434, 1, '2025-07-25 06:30:50', 'complete', 'complete'),
(2, 2, 2, 100, 1725054760, 1, '2025-07-25 06:30:48', 'complete', 'complete'),
(4, 2, 4, 1150, 154291620, 6, '2025-07-25 10:33:39', 'complete', 'complete'),
(5, 2, 9, 10, 1334200051, 1, '2025-07-25 10:33:37', 'complete', 'complete'),
(6, 2, 2, 400, 362029646, 4, '2025-07-25 10:33:41', 'complete', 'complete'),
(7, 2, 2, 800, 88245633, 8, '2025-07-25 10:36:02', 'complete', 'complete');

-- --------------------------------------------------------

--
-- Table structure for table `user_payments`
--

CREATE TABLE `user_payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `invoice_number` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `payment_mode` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_payments`
--

INSERT INTO `user_payments` (`payment_id`, `order_id`, `invoice_number`, `amount`, `payment_mode`, `date`) VALUES
(1, 2, 1725054760, 100, 'Stripe', '2025-07-17 11:42:43'),
(2, 1, 1007576434, 100, 'Stripe', '2025-07-17 11:57:45'),
(3, 4, 154291620, 1150, 'Cash on delivery', '2025-07-25 10:16:23'),
(4, 5, 1334200051, 10, 'Stripe', '2025-07-25 10:19:53'),
(5, 6, 362029646, 400, 'Stripe', '2025-07-25 10:31:02'),
(6, 7, 88245633, 800, 'Stripe', '2025-07-25 10:34:51');

-- --------------------------------------------------------

--
-- Table structure for table `user_table`
--

CREATE TABLE `user_table` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(200) NOT NULL,
  `user_lname` varchar(255) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_image` varchar(255) NOT NULL,
  `user_address` varchar(255) NOT NULL,
  `user_mobile` varchar(20) NOT NULL,
  `verification_code` int(11) NOT NULL,
  `email_verified` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_table`
--

INSERT INTO `user_table` (`user_id`, `user_name`, `user_lname`, `user_email`, `user_password`, `user_image`, `user_address`, `user_mobile`, `verification_code`, `email_verified`) VALUES
(2, 'Ajaya', 'Tamang', 'ajayalama939@gmail.com', 'GoldenLucifer1', '1.webp', 'Bhaktapur, Gatthaghar', '9861744430', 162286, 1),
(3, 'root', 'toot', 'ddrakegoch@gmail.com', '456', '', 'Bhaktapur, Kathmandu', '9854643256', 945387, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_table`
--
ALTER TABLE `admin_table`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `cart_details`
--
ALTER TABLE `cart_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_details_ibfk_1` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_message`
--
ALTER TABLE `contact_message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_status`
--
ALTER TABLE `order_status`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `order_status_ibfk_1` (`user_id`),
  ADD KEY `order_status_ibfk_2` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_ibfk_1` (`tag_id`),
  ADD KEY `products_ibfk_2` (`category_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_order`
--
ALTER TABLE `user_order`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_order_ibfk_1` (`user_id`),
  ADD KEY `user_order_ibfk_2` (`product_id`);

--
-- Indexes for table `user_payments`
--
ALTER TABLE `user_payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `user_payments_ibfk_1` (`order_id`);

--
-- Indexes for table `user_table`
--
ALTER TABLE `user_table`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_table`
--
ALTER TABLE `admin_table`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart_details`
--
ALTER TABLE `cart_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contact_message`
--
ALTER TABLE `contact_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_status`
--
ALTER TABLE `order_status`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_order`
--
ALTER TABLE `user_order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_payments`
--
ALTER TABLE `user_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_table`
--
ALTER TABLE `user_table`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_details`
--
ALTER TABLE `cart_details`
  ADD CONSTRAINT `cart_details_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_status`
--
ALTER TABLE `order_status`
  ADD CONSTRAINT `order_status_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_table` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_status_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_order`
--
ALTER TABLE `user_order`
  ADD CONSTRAINT `user_order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_table` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_order_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_payments`
--
ALTER TABLE `user_payments`
  ADD CONSTRAINT `user_payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `user_order` (`order_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
