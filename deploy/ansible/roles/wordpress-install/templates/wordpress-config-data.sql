--
-- Dumping data for table `wp_options`
--
LOCK TABLES `wp_options` WRITE;
UPDATE wp_options
  SET option_value='http://{{ server_hostname }}'
  WHERE option_name = 'siteurl' OR option_name = 'home';
UNLOCK TABLES;
