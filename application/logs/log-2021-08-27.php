<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-08-27 02:29:27 --> 404 Page Not Found: Robotstxt/index
ERROR - 2021-08-27 02:39:58 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near '-10, 10' at line 16 - Invalid query: SELECT `ma_vehicle`.*, `ma_customer`.`customer_name`, `ma_customer`.`customer_pic`, `ma_vehicle`.`fk_listing_type` as `is_customer`, `ma_vehicle`.`indate` as `post_at`, `ma_make`.*, `ma_model`.*, 0 as `is_added`, `ma_region`.*, `ma_dealership`.`rec_img_base64`, `ma_dealership`.`img_base64`, `ma_dealership`.`dealership_name`, `ma_fuel_type`.*
FROM `ma_vehicle`
JOIN `ma_make` ON `ma_vehicle`.`fk_vehicule_make` = `ma_make`.`make_id`
JOIN `ma_model` ON `ma_vehicle`.`fk_vehicule_model` = `ma_model`.`model_id`
JOIN `ma_region` ON `ma_vehicle`.`fk_region` = `ma_region`.`region_id`
LEFT OUTER JOIN `ma_dealership` ON `ma_vehicle`.`fk_dealership_id` = `ma_dealership`.`dealership_id`
JOIN `ma_fuel_type` ON `ma_fuel_type`.`fuel_id` = `ma_vehicle`.`fk_vehicule_fuel`
LEFT OUTER JOIN `ma_customer` ON `ma_vehicle`.`fk_customer` = `ma_customer`.`customer_id`
WHERE `ma_vehicle`.`vehicule_price` >= '0'
AND `ma_vehicle`.`vehicule_year` >= '0'
AND `ma_vehicle`.`vehicule_year` <= '2021'
AND `ma_vehicle`.`vehicule_odometer` >= '0'
AND `ma_vehicle`.`vehicule_odometer` <= '300000'
AND `ma_vehicle`.`fk_vehicule_type` = '0'
AND `ma_vehicle`.`delete_flag` = '0'
 LIMIT -10, 10
ERROR - 2021-08-27 02:39:59 --> 404 Page Not Found: Faviconico/index
ERROR - 2021-08-27 03:02:47 --> Severity: Core Warning --> PHP Startup: Unable to load dynamic library 'php_openssl.dll' (tried: /usr/lib64/php/modules/php_openssl.dll (/usr/lib64/php/modules/php_openssl.dll: cannot open shared object file: No such file or directory), /usr/lib64/php/modules/php_openssl.dll.so (/usr/lib64/php/modules/php_openssl.dll.so: cannot open shared object file: No such file or directory)) Unknown 0
ERROR - 2021-08-27 03:21:19 --> 404 Page Not Found: Robotstxt/index
ERROR - 2021-08-27 05:06:23 --> 404 Page Not Found: Faviconico/index
ERROR - 2021-08-27 06:26:37 --> 404 Page Not Found: Robotstxt/index
ERROR - 2021-08-27 06:26:57 --> 404 Page Not Found: Apple-touch-iconpng/index
ERROR - 2021-08-27 06:26:58 --> 404 Page Not Found: Faviconico/index
ERROR - 2021-08-27 06:26:59 --> 404 Page Not Found: Css/pe-icon-7-stroke.css
ERROR - 2021-08-27 06:26:59 --> 404 Page Not Found: Faviconico/index
ERROR - 2021-08-27 10:29:47 --> 404 Page Not Found: Owa/auth
ERROR - 2021-08-27 11:52:33 --> 404 Page Not Found: Robotstxt/index
ERROR - 2021-08-27 14:05:48 --> 404 Page Not Found: Robotstxt/index
ERROR - 2021-08-27 14:05:51 --> 404 Page Not Found: Robotstxt/index
ERROR - 2021-08-27 16:29:38 --> 404 Page Not Found: Owa/auth.owa
ERROR - 2021-08-27 17:09:41 --> 404 Page Not Found: Owa/auth
ERROR - 2021-08-27 17:19:07 --> 404 Page Not Found: Ecp/Current
ERROR - 2021-08-27 17:29:56 --> 404 Page Not Found: Owa/auth
ERROR - 2021-08-27 20:16:45 --> 404 Page Not Found: Actuator/health
ERROR - 2021-08-27 20:27:25 --> 404 Page Not Found: Faviconico/index
ERROR - 2021-08-27 20:27:26 --> 404 Page Not Found: Robotstxt/index
ERROR - 2021-08-27 21:19:06 --> 404 Page Not Found: Robotstxt/index
ERROR - 2021-08-27 22:09:18 --> 404 Page Not Found: Robotstxt/index
ERROR - 2021-08-27 22:17:24 --> 404 Page Not Found: Faviconico/index
ERROR - 2021-08-27 23:13:44 --> 404 Page Not Found: Ecp/Current
