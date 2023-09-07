<?php
	$current_page_name = explode('.', basename($_SERVER['PHP_SELF']));
	$current_page_name_only = $current_page_name[0];
	
	// SEVER VARIABLES
	define ('SITE_HOST', $_SERVER['REQUEST_SCHEME'] .'://'. $_SERVER['HTTP_HOST'] );
	define ('SITE_NAME', 'umtab');
	//define ('SITE_COM', '');
	define ('SITE_COM', '.com');
	define ('SITE_DOMAIN_NAME', SITE_HOST); // there is need of adding the site_name after the host
	define ('CURRENT_TIMESTAMP',  time() );
	define ('CURRENT_PAGE', SITE_HOST ."". $_SERVER['REQUEST_URI'] );
	define ('CURRENT_PAGE_NAME', $current_page_name_only );
	
	define ('SUM_XS_TXT', 5);
	define ('SUM_MIN_TXT', 10);
	define ('SUM_MID_TXT', 35);
	define ('SUM_MAX_TXT', 50);

	define ('LMT_PER_PG', 10);

	//user
	define('USERS_COLUMN', 9);
	define('CHARS', 'aaaabbbbccccddddeeeeffffgggghhhhiiiijjjjkkkkllllmmmmnnnnooooppppqqqqrrrrssssttttuuuuvvvvwwwwxxxxyyyyzzzz-AAAABBBBCCCCDDDDEEEEFFFFGGGGHHHHIIIIJJJJKKKKLLLLMMMMNNNNOOOOPPPPQQQQRRRRSSSSTTTTUUUUVVVVWWWWXXXXYYYYZZZZ_000111222333444555666777888999');
	
	//line break
	define('BR', '<br>');

	define('LOGGED_IN', logged_in());
	define('SPACE', '&nbsp;');
?>
