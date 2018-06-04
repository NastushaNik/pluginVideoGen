<?php

	// разрешенные форматы
	$allowed = array('png', 'jpg', 'gif', 'mp3', 'aac', 'flac', 'm4a', 'aif', 'iff', 'm3u', 'mid', 'mpa', 'ra', 'wav', 'wma' );
	// global $post;
	if ( $_FILES && is_array( $_FILES ) && isset($_FILES['upl']) && $_FILES['upl']['error'] == 0 ) {
	$file = $_FILES['upl'];


	$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
	 
    if(!in_array(strtolower($extension), $allowed)){
        echo '{"status":"error"}';
		wp_die();
    }

    if(function_exists(	'media_handle_upload' )){
    		media_handle_upload('upl', 0);
    		$overrides = array( 'test_form' => true );
    }else{
    	$overrides = array( 'test_form' => false );
    }

   
			echo json_encode($overrides);
	}
	

	// выход нужен для того, чтобы в ответе не было ничего лишнего, только то что возвращает функция
	wp_die();

	?>