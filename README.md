# CodeIgniter User Authentication

Simple Codeigniter, REST Server, JWT implementation for User Authentication.

How To Use
=====

Create Database on MySQL
    
    CREATE TABLE `keys` (
       `id` int(11) NOT NULL,
       `user_id` int(11) NOT NULL,
       `key` varchar(40) NOT NULL,
       `level` int(2) NOT NULL,
       `ignore_limits` tinyint(1) NOT NULL DEFAULT '0',
       `is_private_key` tinyint(1) NOT NULL DEFAULT '0',
       `ip_addresses` text,
       `date_created` int(11) NOT NULL
     ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
     
     -- --------------------------------------------------------
     
     --
     -- Table structure for table `m_user`
     --
     
     CREATE TABLE `m_user` (
       `user_id` int(11) NOT NULL,
       `username` varchar(255) NOT NULL,
       `password` varchar(255) NOT NULL,
       `namalengkap` varchar(255) NOT NULL,
       `status` enum('active','banned','','') NOT NULL DEFAULT 'active'
     ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
     