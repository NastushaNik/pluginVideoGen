<?php 
/*
 Plugin Name: Video_generator
 Description: Структура плагина
 Version: 1.0
 Author: Автор плагина
 Author URI: сайт автора
*/
 
 if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

if (!class_exists('Video_generator')) {
 class Video_generator {

 	private $orientation_fixed;

	private $previous_meta;

	protected static $instance = null;

	public $latitude = array();
	public $latitude_ref = array(); 
	public $longitude = array(); 
	public $longitude_ref = array();
 
	## Конструктор объекта
	## Инициализация основных переменных
	public function __construct() {
		## Объявляем константу инициализации нашего плагина
		DEFINE('Video_generator', true);
		
		## Название файла нашего плагина 
		$this->plugin_name = plugin_basename(__FILE__);
		
		## URL адресс для нашего плагина
		$this->plugin_url = trailingslashit(WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)));

		$this->orientation_fixed = array();
		$this->previous_meta     = array();
		
		## Функция которая исполняется при активации плагина
		register_activation_hook( $this->plugin_name, array(&$this, 'activate') );
		
		## Функция которая исполняется при деактивации плагина
		register_deactivation_hook( $this->plugin_name, array(&$this, 'deactivate') );
		
		##  Функция которая исполняется удалении плагина
		register_uninstall_hook( $this->plugin_name, 'uninstall' );

		add_action('init', array(&$this, 'vg_register_video_post_types'));
		
		// Если мы в адм. интерфейсе
		if ( is_admin() ) {
			
			// Добавляем стили и скрипты
			//add_action('admin_print_scripts', array(&$this, 'admin_load_scripts'));
			//add_action('admin_print_styles', array(&$this, 'admin_load_styles'));
			
			// Добавляем меню для плагина
			//add_action( 'admin_menu', array(&$this, 'admin_generate_menu') );
			
		} else {
		    // Добавляем стили и скрипты
			add_action('wp_print_scripts', array(&$this, 'site_load_scripts'));

			add_action('wp_print_scripts2', array(&$this, 'site_load_scripts2'));

			add_action('wp_print_scripts', array(&$this, 'site_load_scripts3'));

			add_action('wp_print_styles', array(&$this, 'site_load_styles'));

			add_action('wp_print_styles2', array(&$this, 'site_load_styles2'));

			add_action('wp_print_styles', array(&$this, 'site_load_styles2'));

			add_action('wp_print_content', array(&$this, 'site_load_content'));

			add_action('wp_print_upload', array(&$this, 'site_upload_content'));

			add_action('wp_enqueue_scripts', array(&$this, 'mup_load_scripts'));

			add_action('wp_print_scripts', array(&$this, 'slick_slider_script'));

			add_action('wp_print_styles', array(&$this, 'slick_slider_styles'));

			add_action('wp_print_styles', array(&$this, 'slick_slider_styles2'));

			add_action('wp_print_styles', array(&$this, 'site_load_styles3'));

			add_action('wp_print_styles', array(&$this, 'site_load_styles4'));

			//add_action('wp_print_scripts', array(&$this, 'exif_script'));
	
			//add_action('admin_init', array(&$this, 'mup_allow_contributor_to_uploads'));
			
			add_shortcode('video_generator', array (&$this, 'site_show'));

			add_shortcode('vg-facebook-share', array (&$this, 'site_share'));

			add_shortcode('vg-latest-secs', 'latest_secs');

			add_shortcode('vg-best-raiting-secs', 'best_raiting_secs');

			add_shortcode('vg-best-raiting-home', 'best_raiting_home');
		}
	}
	
	public function site_load_scripts()
	{
		// wp_register_script('videositeJS', $this->plugin_url . 'js/main.js' );
		// wp_enqueue_script('videositeJS');
	}

	public function mup_load_scripts() {
    wp_enqueue_script('fileuploadwidgetJS', plugin_dir_url( __FILE__ ) . 'js/jquery.ui.widget.js', array('jquery'), '0.1.0', true);
    wp_enqueue_script('fileuploadtransJS', plugin_dir_url( __FILE__ ) . 'js/jquery.iframe-transport.js', array('jquery'), '0.1.0', true);
    wp_enqueue_script('fileuploadknobJS', plugin_dir_url( __FILE__ ) . 'js/jquery.knob.js', array('jquery'), '0.1.0', true);
    wp_enqueue_script('fileuploadJS', plugin_dir_url( __FILE__ ) . 'js/jquery.fileupload.js', array('jquery'), '0.1.0', true);
    wp_enqueue_script('vgIsotop', 'https://isotope.metafizzy.co/isotope.pkgd.js', array('jquery'), '0.1.0', true);
    wp_enqueue_script('vgMasonry', plugin_dir_url( __FILE__ ) . 'js/masonry-horizontal.js', array('vgIsotop'), '0.1.0', true);
     wp_enqueue_script('vgJustifiedGallery', plugin_dir_url( __FILE__ ) . 'js/jquery.justifiedGallery.min.js', array('vgIsotop'), '0.1.0', true);
    wp_enqueue_script('videositeJS', plugin_dir_url( __FILE__ ) . 'js/main.js', array('jquery', 'fileuploadJS', 'vgMasonry', ), '0.1.0', true);

	add_action('wp_enqueue_scripts', array(&$this, 'vg_localized_scripts'));
    //$true_posts = serialize($wp_query->query_vars);
	//$current_page = (get_query_var('paged')) ? get_query_var('paged') : 1;
	//$max_pages = $wp_query->max_num_pages;
 
    $data = array(
                'upload_url' => admin_url('async-upload.php'),
                'ajax_url'   => admin_url('admin-ajax.php'),
                'nonce'      => wp_create_nonce('media-form'),
	            'ui'         => um_profile_id(),
                //'true_posts' => $true_posts,
                //'current_page' => $current_page,
                //'max_pages' => $max_pages
            );
 
    wp_localize_script( 'videositeJS', 'mup_config', $data );
	}

	public function vg_localized_script() {

	}

	public function site_load_scripts2()
	{
		wp_register_script('videositeJS2', $this->plugin_url . 'js/jquery-3.2.1.min.js' );
		wp_enqueue_script('videositeJS2');
	}

	public function site_load_scripts3()
	{
		wp_register_script('videositeJS3', $this->plugin_url . 'js/bootstrap.min.js' );
		wp_enqueue_script('videositeJS3');
	}

	public function site_load_styles()
	{
		wp_register_style('videositeCss', $this->plugin_url . 'css/bootstrap.min.css' );
		wp_enqueue_style('videositeCss');
	}

	public function site_load_styles2()
	{
		wp_register_style('videositeCss2', $this->plugin_url . 'css/font-awesome.min.css' );
		wp_enqueue_style('videositeCss2');
	}

	public function site_load_styles3()
	{
		wp_register_style('videositeCss3', $this->plugin_url . 'css/style.css' );
		wp_enqueue_style('videositeCss3');
	}

	public function site_load_styles4()
	{
		wp_register_style('justifiedGallery', $this->plugin_url . 'css/justifiedGallery.min.css' );
		wp_enqueue_style('justifiedGallery');
	}


	public function slick_slider_script()
	{
		wp_register_script('slickJS', $this->plugin_url . 'js/slick.min.js' );
		wp_enqueue_script('slickJS');
	}

	public function slick_slider_styles()
	{
		wp_register_style('slickCss', $this->plugin_url . 'css/slick.css' );
		wp_enqueue_style('slickCss');
	}

	public function slick_slider_styles2()
	{
		wp_register_style('slickCss2', $this->plugin_url . 'css/slick-theme.css' );
		wp_enqueue_style('slickCss2');
	}
	// public function exif_script()
	// {
	// 	wp_register_script('exifJS', $this->plugin_url . 'js/exif.js', array( 'jquery' ), '', true );
	// 	wp_enqueue_script('exifJS');
	// }


	public function site_load_content()
	{
		
		if( isset( $_POST['data'] ) && isset( $_POST['sound'] ) ){

			$frames = 25;
			$time_frame = 5;

			$info = 'info.txt';
			$outvid = 'output.mp4';
			$data = json_decode ($_POST['data']);
			$audio = ($_POST['sound']);
			$instr = $filt = $over = '';
			if ( is_array( $data ) ) {
				session_start();
				$use_dir_url = '/uploads/' . session_id() .'/';

				chdir ( '../uploads/'.session_id().'/' );

				if( file_exists( $outvid ) ){

					unlink( $outvid );

				}
				foreach ( $data as $i => $img ) {
					$file = str_replace( $use_dir_url, '', $img );
					if ( file_exists( $file ) ) {
						$instr .= ' -i ' . $file;
						$efects = array('if(lte(zoom,1.0),1.5,max(1.001,zoom-0.0015))', 'zoom+0.0015');
						$zoom = array_rand($efects, 1);
						$filt .= "[{$i}:v]format=pix_fmts=yuva420p,zoompan='{$efects[$zoom]}':d={$frames}*{$time_frame}:s=720x480,fade=t=in:st=0:d=1:alpha=1,fade=t=out:st={$time_frame}:d=1:alpha=0,setpts=PTS-STARTPTS+{$i}*{$time_frame}/TB[v{$i}];";
						switch( count( $data )-1 ) {
							case $i: $over .= "[v{$i}]"; break;
							default: $over .= "[v{$i}]overlay[ov{$i}];[ov{$i}]";
						}
					}
				$req[] = $efects[$zoom];
					
				$req[] = str_replace( $use_dir_url, '', $img );
				}
				$audio = str_replace( $use_dir_url, '', $audio );
				$time = count($data) * $time_frame;
				
				exec("ffmpeg $instr -i $audio -t $time -filter_complex \"color=c=black:r={$frames}:size=720x480:d=7.0[black];{$filt}[black]{$over}overlay=format=yuv420\" -c:v libx264 $outvid", $output, $exit_status);

				if ($exit_status === 0) {
					foreach ( $data as $img ) {
					$file = str_replace( $use_dir_url, '', $img );
					if ( file_exists( $file ) ) { 
						unlink( $file ); 
					}			
					$req[] = str_replace( $use_dir_url, '', $img );
				}
					unlink( $audio ); 
					$req['video_link'] = $use_dir_url . $outvid;
				} else {
				    $req[] = 'O no!' . $use_dir_url.$outvid;
				}	
			}
		} else if ( !isset( $_POST['data'] ) ) {
			$req[] = 'Upload images';
		} else if ( !isset( $_POST['sound'] ) ) {
			$req[] = 'Upload sound';
		} else {
			$req[] = 'Upload files';
		}
		echo json_encode($req);

		}
	
	/**
	 * Отображение на сайте
	 */
	public function site_show($atts, $content=null)
	{
		//if ( is_user_logged_in() ) {

	// ini_set( 'upload_max_size' , '55M' );
	// ini_set( 'post_max_size', '55M');
			$this->mup_allow_contributor_to_uploads();
	        global $path;
	        global $ultimatemember;
	        $path=__DIR__;
			## Включаем буферизацию вывода
			ob_start ();
			include_once('site.php');
			## Получаем данные
			$output = ob_get_contents ();
			## Отключаем буферизацию
			ob_end_clean ();	
		//} else {
			//$output = do_shortcode('[ultimatemember_social_login id=148]');
			//$output = '';
		//}
		
		return $output;
	}

	public function mup_allow_contributor_to_uploads() {
	    $subscriber = get_role('contributor');
	    if ( ! $subscriber->has_cap('upload_files') ) {
	        $subscriber->add_cap('upload_files');
	    }
	}

	public function site_share($atts, $content=null) {
		// $upload_dir = wp_upload_dir();
		// $fb = new Facebook\Facebook([
		// 	'app_id' => '350656032067756', // Replace {app-id} with your app id
		// 	  'app_secret' => '21870901d480d6136b5beb6d9daa9d04',
		// 	'default_graph_version' => 'v2.2',
		// 	]);

		// 	$data = [
		// 	'title' => 'My Foo Video',
		// 	'description' => 'This video is full of foo and bar action.',
		// 	'source' => $fb->videoToUpload($upload_dir['baseurl'].'/output.mp4'),
		// 	];

		// 	try {
		// 	$response = $fb->post('/me/videos', $data, $_SESSION['facebook_access_token']);
		// 	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		// 	// When Graph returns an error
		// 	echo 'Graph returned an error: ' . $e->getMessage();
		// 	exit;
		// 	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		// 	// When validation fails or other local issues
		// 	echo 'Facebook SDK returned an error: ' . $e->getMessage();
		// 	exit;
		// 	}

		// 	$graphNode = $response->getGraphNode();
		// 	var_dump($graphNode);

		// 	echo 'Video ID: ' . $graphNode['id'];
	}

	public function site_upload_content(){

		// Здесь нужно сделать все проверки передаваемых файлов и вывести ошибки если нужно
		 
		// Переменная ответа
		 
		$data = array();
		 
		if( isset( $_GET['uploadfiles'] ) ){
		    $error = false;
		    $files = array();

		    session_start();
		 
		    $uploaddir = '../uploads/' . session_id() .'/'; // . - текущая папка где находится submit.php
		 
		    // Создадим папку если её нет
		 
		    if( ! is_dir( $uploaddir ) ) mkdir( $uploaddir, 0777 );
		 
		    // переместим файлы из временной директории в указанную
		    foreach( $_FILES as $file ){
		        if( move_uploaded_file( $file['tmp_name'], $uploaddir . basename($file['name']) ) ){
		            //$files[] = realpath( $uploaddir . $file['name'] );
		            $files[] = '/uploads/' . session_id() .'/' . $file['name'];
		        }
		        else{
		            $error = true;
		        }
		    }
		 
		    $data = $error ? array('error' => 'Ошибка загрузки файлов.') : array('files' => $files );
		 
		    echo json_encode( $data );
		}

	}

	public function vg_register_video_post_types(){
		register_post_type('vg_video', array(
			// 'label'  => null,
			'labels' => array(
				'name'               => __( 'Videos' ), // основное название для типа записи
				'singular_name'      => __( 'Video' ), // название для одной записи этого типа
				'add_new'            => __( 'Add new video' ), // для добавления новой записи
				'add_new_item'       => __( 'Add new video title' ), // заголовка у вновь создаваемой записи в админ-панели.
				'edit_item'          => __( 'Edit new video' ), // для редактирования типа записи
				'new_item'           => '____', // текст новой записи
				'view_item'          => __( 'View video' ), // для просмотра записи этого типа.
				'search_items'       => __( 'Search video' ), // для поиска по этим типам записи
				'not_found'          => __( 'Nothing found' ), // если в результате поиска ничего не было найдено
				'not_found_in_trash' => __( 'Nothing found in the trash' ), // если не было найдено в корзине
				'parent_item_colon'  => '', // для родителей (у древовидных типов)
				'menu_name'          => __( 'Videos' ), // название меню
				'view_items' => __( 'View Videos' ), // Название в тулбаре, для страницы архива типа записей. По умолчанию: «View Posts» / «View Pages». С WP 4.7.
			),
			'description'         => '',
			'public'              => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_menu'        => null, // показывать ли в меню адмнки
			'show_in_admin_bar'   => null, // по умолчанию значение show_in_menu
			'show_in_nav_menus'   => null,
			'show_in_rest'        => null, // добавить в REST API. C WP 4.7
			'rest_base'           => null, // $post_type. C WP 4.7
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-video-alt3', 
			//'capability_type'   => 'post',
			//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
			//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
			'hierarchical'        => false,
			'supports'            => array('title','editor','thumbnail'), // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
			'taxonomies'          => array(),
			'has_archive'         => true,
			'rewrite'             => array('slug' => 'videos'),
			'query_var'           => true,
		) );
	}
	
	/**
	 * Активация плагина
	 */
	public function activate() 
	{
		
	}
	
	public function deactivate() 
	{
		return true;
	}
	
	/**
	 * Удаление плагина
	 */
	public static function uninstall() 
	{
	}

	/* Functions for image rotation */


	public function register_hooks() {
		add_filter( 'wp_handle_upload_prefilter', array( $this, 'filter_wp_handle_upload_prefilter' ), 10, 1 );
		add_filter( 'wp_handle_upload', array( $this, 'filter_wp_handle_upload' ), 1, 3 );

		add_filter( 'wp_prepare_attachment_for_js', array( $this, 'prepare_attachment_for_js' ), 10, 3 );
	}

	public function filter_wp_handle_upload( $file ) {
		$suffix = substr( $file['file'], strrpos( $file['file'], '.', -1 ) + 1 );
		if ( in_array( $suffix, array( 'jpg', 'jpeg', 'tiff' ), true ) ) {
			$this->fix_image_orientation( $file['file'] );
		}
		return $file;
	}

	public function filter_wp_handle_upload_prefilter( $file ) {
		$suffix = substr( $file['name'], strrpos( $file['name'], '.', -1 ) + 1 );
		if ( in_array( $suffix, array( 'jpg', 'jpeg', 'tiff' ), true ) ) {
			$this->fix_image_orientation( $file['tmp_name'] );
		}
		return $file;
	}

	public function fix_image_orientation( $file ) {
		if ( is_callable( 'exif_read_data' ) && ! isset( $this->orientation_fixed[ $file ] ) ) {
			$exif = exif_read_data( $file );

			if (!empty($exif['GPSLatitude'])){
				if ( is_array( $exif['GPSLatitude'] ) ) {
					foreach ( $exif['GPSLatitude'] as &$value) {
						$value = explode ( '/' , $value );
						$value = $value[0]/$value[1];
						//$this->latitude[] = $value;
						unset($value);
					}
				}
				$this->latitude = $exif['GPSLatitude'] ;
			}else{$this->latitude = array(46, 27, 48.8841);}
			if (!empty($exif['GPSLatitudeRef'])){
				$this->latitude_ref = trim( $exif['GPSLatitudeRef'] );
			}else{}
			if (!empty($exif['GPSLongitude'])){
				if ( is_array( $exif['GPSLongitude'] ) ) {
					foreach ( $exif['GPSLongitude'] as &$value) {
						$value = explode ( '/' , $value );
						$value = $value[0]/$value[1];
						//$this->longitude[] = $value;
						unset($value);
					}
				}
				$this->longitude = $exif['GPSLongitude'] ;
			}else{}
			if (!empty($exif['GPSLongitudeRef'])){
				$this->longitude_ref = trim( $exif['GPSLongitudeRef'] );
			}else{}

			if ( isset( $exif ) && isset( $exif['Orientation'] ) && $exif['Orientation'] > 1 ) {

				// Need it so that image editors are available to us.
				include_once( ABSPATH . 'wp-admin/includes/image-edit.php' );

				// Calculate the operations we need to perform on the image.
				$operations = $this->calculate_flip_and_rotate( $file, $exif );

				if ( false !== $operations ) {
					// Lets flip flop and rotate the image as needed.
					$this->do_flip_and_rotate( $file, $operations );
				} else {
					// $editor = wp_get_image_editor( $file );

					// // If GD Library is being used, then we need to store metadata to restore later.
					// if ( 'WP_Image_Editor_GD' === get_class( $editor ) ) {
					// 	$this->previous_meta[ $file ] = wp_read_image_metadata( $file );
					// }

					// if ( ! is_wp_error( $editor ) ) {
					// 	// Lets rotate and flip the image based on exif orientation.
					// 	// 
					// 	$editor->save( $file );
					// 	//$this->orientation_fixed[ $file ] = true;
					// 	add_filter( 'wp_read_image_metadata', array( $this, 'restore_meta_data' ), 10, 2 );
					// 	return true;
					// }
				}
			}
			add_filter( 'wp_read_image_metadata', array( $this, 'restore_meta_data' ), 10, 2 );
		}
	}

	private function calculate_flip_and_rotate( $file, $exif ) {

		$rotator = false;
		$flipper = false;

		// Lets switch to the orientation defined in the exif data.
		switch ( $exif['Orientation'] ) {
			case 1:
				// We don't want to fix an already correct image :).
				$this->orientation_fixed[ $file ]    = true;
				return false;
			case 2:
				$flipper                             = array( false, true );
				break;
			case 3:
				$orientation                         = -180;
				$rotator                             = true;
				break;
			case 4:
				$flipper                             = array( true, false );
				break;
			case 5:
				$orientation                         = -90;
				$rotator                             = true;
				$flipper                             = array( false, true );
				break;
			case 6:
				$orientation                         = -90;
				$rotator                             = true;
				break;
			case 7:
				$orientation                         = -270;
				$rotator                             = true;
				$flipper                             = array( false, true );
				break;
			case 8:
			case 9:
				$orientation                         = -270;
				$rotator                             = true;
				break;
			default:
				$orientation                         = 0;
				$rotator                             = true;
				break;
		}

		$operations = compact( 'orientation', 'rotator', 'flipper' );
		return $operations;
	}

	private function do_flip_and_rotate( $file, $operations ) {

		$editor = wp_get_image_editor( $file );

		// If GD Library is being used, then we need to store metadata to restore later.
		if ( 'WP_Image_Editor_GD' === get_class( $editor ) ) {
			$this->previous_meta[ $file ] = wp_read_image_metadata( $file );
		}

		if ( ! is_wp_error( $editor ) ) {
			// Lets rotate and flip the image based on exif orientation.
			if ( true === $operations['rotator'] ) {
				$editor->rotate( $operations['orientation'] );
			}
			if ( false !== $operations['flipper'] ) {
				$editor->flip( $operations['flipper'][0], $operations['flipper'][1] );
			}
			$editor->save( $file );
			$this->orientation_fixed[ $file ] = true;
			add_filter( 'wp_read_image_metadata', array( $this, 'restore_meta_data' ), 10, 2 );
			return true;
		}
		return false;
	}

	public function restore_meta_data( $meta, $file ) {
			
		$meta['GPSLatitude'] = $this->latitude;
		$meta['GPSLatitudeRef'] = $this->latitude_ref;
		$meta['GPSLongitude'] = $this->longitude;
		$meta['GPSLongitudeRef'] = $this->longitude_ref;

		if ( isset( $this->previous_meta[ $file ] ) ) {
			$meta = $this->previous_meta[ $file ];
			// Setting the Orientation meta to the new value after fixing the rotation.
			$meta['orientation'] = 1;
			return $meta;
		}

		return $meta;
	}
	//apply_filters( 'wp_prepare_attachment_for_js', $response, $attachment, $meta );
	public function prepare_attachment_for_js( $response, $attachment, $meta ) {
		//$meta = wp_get_attachment_metadata( $attachment->ID );
		$response['GPSLatitude'] = $meta['image_meta']['GPSLatitude'];
		$response['GPSLatitudeRef'] = $meta['image_meta']['GPSLatitudeRef'];
		$response['GPSLongitude'] = $meta['image_meta']['GPSLongitude'];
		$response['GPSLongitudeRef'] = $meta['image_meta']['GPSLongitudeRef'];
		//$response = $meta;
		return $response;
	}

 }
}

global $videoGen;
$videoGen = new Video_generator();
$videoGen->register_hooks();

add_action('wp_ajax_my_upload_attachment', 'my_upload_attachment');

add_action('wp_ajax_my_raiting', 'vg_my_raiting');

			//add_action('wp_ajax_nopriv_my_upload_attachment', 'my_upload_attachment');

function my_upload_size_limit( $limit ) {
add_filter( 'upload_size_limit', 'my_upload_size_limit' );
    return wp_convert_hr_to_bytes( '15M' );
}

function vg_my_raiting() {

	$whatever = array();

	$whatever['user_chanse_win'] = vg_update_user_chsw( wp_get_current_user()->ID, 1 );
	
	if(function_exists('the_ratings')) {
		$img_id = ( isset( $_POST['img_id'] ) ) ? $_POST['img_id'] : '';
		$post = get_post($img_id); 
		$slug = $post->post_name;
		$whatever['raiting'] = the_ratings('div', $img_id, false);
		$metkaTag = ( isset( $_POST['term_name'] ) ) ? $_POST['term_name'] : '';
		if($img_id !== ''){
			if ($metkaTag !== '') {
				$all_tags = explode( ', ', $metkaTag, 4);
				$tags_l = count($all_tags)-1;
				$whatever['tagUl'] = '<ul class="attachmentTag">';
				for($i=0;$i<=$tags_l;$i++){
				    if(is_numeric($all_tags[$i])){
				        break;
				    }
	    				wp_insert_category( array('cat_name' => $all_tags[$i], 'taxonomy' => 'post_tag' ) );
						$createdTerm = wp_set_post_tags( $img_id, $all_tags[$i], true );
						if ( $createdTerm !== false) {
							wp_update_term_count_now(array($createdTerm[0]), 'post_tag' );
							$cur_term = get_term( (int) $createdTerm[0], 'post_tag' );
							$whatever['tagUl'] .= '<li><a href="/tag/'. $cur_term->slug .'">#'. $all_tags[$i] .'</a><button type="button" class="vg-image-tag-delete close" data-term-name="'.$cur_term->slug.'" data-img-name="' . $img_id . '">&times;</button></li>';
						}
				    //print_r($all_tags[$i]);
				}
				$whatever['tagUl'] .= '</ul>';
				
			}
			$whatever['postFace'] .= '<div class="clearfix"></div><a href="https://www.facebook.com/sharer/sharer.php?u=http://takeasec.co/'.$slug.'" onclick=\'window.open(this.href, "mywin","left=20,top=20,width=500,height=500,toolbar=1,resizable=0"); return false;\' class="post-to-facebook button"><img src="/wp-content/uploads/2018/admin/share-white.png"></a>';
			if(function_exists('wp_ulike')){
				$args = array(
					'id' => $img_id,
				);
				$like = wp_ulike('put', $args);
			}

			$whatever['likes'] = '<div class="vg-video-like" data-like-video="'.$img_id.'">'.$like.'</div>';

			$whatever['menu'] = '<button class="button btn-xs delete-video button-size-2 button-js" data-delete-video="'.$img_id.'"><span>X</span></button>';

			$whatever['author_name'] = '<a href="/user/'.mb_strtolower(get_the_author_meta('user_login', $post->post_author)).'/" class="author-name" title="User name">'.get_the_author_meta('display_name', $post->post_author).'</a>';

			
		}
		echo json_encode($whatever);
	}else{
		$whatever[] = 'no';
		echo json_encode($whatever);
	}
	// $whatever[] = 'yes';
	// echo json_encode($whatever);
	wp_die();
}


add_action('wp_ajax_vg_add_new_tag', 'vg_add_new_tag');

function vg_add_new_tag() {
	$img_id = ( isset( $_POST['img_id'] ) ) ? $_POST['img_id'] : '';
	$metkaTag = ( isset( $_POST['term_name'] ) ) ? $_POST['term_name'] : '';
	if($img_id !== '' && $metkaTag !== ''){
			wp_insert_category( array('cat_name' => $metkaTag, 'taxonomy' => 'post_tag' ) );
			$createdTerm = wp_set_post_tags( $img_id, $metkaTag, true );
			if ( $createdTerm !== false) {
				wp_update_term_count_now(array($createdTerm[0]), 'post_tag' );
				$cur_term = get_term( (int) $createdTerm[0], 'post_tag' );
				$whatever['tagLi'] = '<li><a class="" href="/tag/'. $cur_term->slug .'">#'. $metkaTag .'</a>
				<button type="button" class="vg-image-tag-delete close" data-term-name="'.$cur_term->slug.'" data-img-name="' . $img_id . '">&times;</button></li>';
			}

			
		}
		echo json_encode($whatever);
	wp_die();
}

add_action('wp_ajax_vg_generate_new_video', 'vg_generate_new_video');

function vg_generate_new_video() {

	if( isset( $_POST['images'] ) && is_array( $_POST['images'] ) ){

	$user_id = get_current_user_id();
	$vg_user_last_vid = (int)get_user_meta( $user_id, 'vg_user_last_vid', true );
	$req['vg_user_last_vid'] = $vg_user_last_vid;

	// if( $vg_user_last_vid < strtotime("now") ){
	if( 1 ){

	$req['end_of_day'] = $end_of_day = strtotime('tomorrow');
	//$info = 'info.txt';
	$frames = 25;
	$time_frame = 5;
	$images = $_POST['images'];
	$first_image = array (
		'img_url' => strtolower(wp_get_upload_dir()['path']).'/first_image.jpg',
		'img_title'=> "", 
		'img_id'=> 0
	);
	$last_image = array (
		'img_url' => strtolower(wp_get_upload_dir()['path']).'/last_image.jpg',
		'img_title'=> "", 
		'img_id'=> 0
	);
	$outvid = 'video' . time ();
	$imgs_ids = [];
	//$data = json_decode ($_POST['data']);
	$audio = isset( $_POST['sound'] ) ? strtolower(wp_get_upload_dir()['path']).'/'.$_POST['sound'] : strtolower(wp_get_upload_dir()['path']).'/audio1.mp3';
	$instr = $filt = $over = '';
	//$user_dir_url = strtolower(wp_get_upload_dir()['url']) . '/' . strtolower(date('Y'))  . '/' . strtolower(wp_get_current_user()->user_login) . '/';
	$user_dir_url = strtolower(wp_get_upload_dir()['url']) . '/' . strtolower(date('Y'))  . '/' . strtolower(wp_get_current_user()->user_login) . '/';

		chdir ( strtolower(wp_get_upload_dir()['path']) . '/' . strtolower(date('Y'))  . '/' . strtolower(wp_get_current_user()->user_login) . '/' );

		if( file_exists( $outvid ) ){

			unlink( $outvid );

		}
		array_unshift( $images, $first_image );
		array_push ( $images, $last_image );
		$req['audio'] = $audio;

		foreach ( $images as $i => $img ) {

			$file = str_replace( $user_dir_url, '', $img['img_url'] );
			if ( file_exists( $file ) ) {
				if ( $img['img_id'] != 0 ) {
					$imgs_ids[] = (int)$img['img_id'];
				}
				$instr .= ' -i ' . $file;
				$img_text = strip_tags( $img['img_title'] );
				$efects = array('if(lte(zoom,1.0),1.5,max(1.001,zoom-0.0015))', 'zoom+0.0015');
				$zoom = array_rand($efects, 1);
				$filt .= "[{$i}:v]format=pix_fmts=yuva420p,zoompan='{$efects[$zoom]}':d={$frames}*{$time_frame}:s=720x480,fade=t=in:st=0:d=1:alpha=1,fade=t=out:st={$time_frame}:d=1:alpha=0,setpts=PTS-STARTPTS+{$i}*{$time_frame}/TB,drawtext=fontfile=/wamp64/www/takeasec/wp-content/uploads/Replica-Light.ttf:text='{$img_text}':fontsize=56:fontcolor=#00ff00:x=(w-text_w)/2:y=(h-text_h)/2[v{$i}];";
				switch( count( $images )-1 ) {
					case $i: $over .= "[v{$i}]"; break;
				 	default: $over .= "[v{$i}]overlay[ov{$i}];[ov{$i}]";
				}
				//$req[] = $file;
				//$req[] = $user_dir_url;
			}
		// $req[] = $efects[$zoom];
		}

		// $suc = fwrite($fp, $instr); // Запись в файл
		// if ( $suc ) { $req[] = 'Данные в файл успешно занесены.'; }
		// else { $req[] = 'Ошибка при записи в файл.'; }
		// fclose($fp); //Закрытие файла

		//$audio = 'C:/wamp64/www/takeasec/wp-content/uploads/audio.mp3';
		$time = count($images) * $time_frame;
      if( file_exists( 'output_no_logo.mp4' ) ){

			unlink( 'output_no_logo.mp4' );

		}
		//$exit_status = 0;
		// exec("ffmpeg -f concat -i $info -c:v libx264 -t $time -pix_fmt yuv420p -vf scale=720:480 $outvid.mp4", $output, $exit_status);
		// $req[]= "ffmpeg -f concat -i $info -i $audio -t $time -pix_fmt yuv420p $outvid.mp4";

		exec("ffmpeg $instr -i $audio -t $time -filter_complex \"color=c=black:r={$frames}:size=720x480:d=7.0[black];{$filt}[black]{$over}overlay=format=yuv420\" -c:v libx264 output_no_logo.mp4", $output, $exit_status);
 		$req[]= "ffmpeg $instr -i $audio -t $time -filter_complex \"color=c=black:r={$frames}:size=720x480:d=7.0[black];{$filt}[black]{$over}overlay=format=yuv420\" -c:v libx264 output_no_logo.mp4";

// // 		ffmpeg -i output.mp4 -i logo.png -filter_complex "overlay=10:y=(main_h-overlay_h)" birds1.mp4

// // ffmpeg -i output.mp4 -vf drawtext="fontfile=arial.ttf: \text='Stack Overflow': fontcolor=white: fontsize=24: box=1: boxcolor=black@0.5: \boxborderw=5: x=(w-text_w)/2: y=(h-text_h)/2" birds1.mp4

// // ffmpeg  -i logo.png -i 2.jpg -i 3.jpg -i 4.jpg -i audio.mp3 -t 20  -filter_complex "color=c=black:r=25:size=720x480:d=7.0[black];[0:v]fade=t=in:st=0:d=1:alpha=0,fade=t=out:st=5:d=1:alpha=1,setpts=PTS-STARTPTS+0*5/TB[v0];[1:v]format=pix_fmts=yuva420p,zoompan='if(lte(zoom,1.0),1.5,max(1.001,zoom-0.0015))':d=25*5:s=720x480,fade=t=in:st=0:d=1:alpha=1,fade=t=out:st=5:d=1:alpha=0,setpts=PTS-STARTPTS+1*5/TB,drawtext=fontfile=arial.ttf:text='Test Text':fontsize=36:fontcolor=#00ff00:x=(w-text_w)/2:y=(h-text_h)/2,movie='watermark.png'[v1];[2:v]format=pix_fmts=yuva420p,zoompan='if(lte(zoom,1.0),1.5,max(1.001,zoom-0.0015))':d=25*5:s=720x480,fade=t=in:st=0:d=1:alpha=1,fade=t=out:st=5:d=1:alpha=0,setpts=PTS-STARTPTS+2*5/TB[v2];[3:v]format=pix_fmts=yuva420p,zoompan='zoom+0.0015':d=25*5:s=720x480,fade=t=in:st=0:d=1:alpha=1,fade=t=out:st=5:d=1:alpha=0,setpts=PTS-STARTPTS+3*5/TB[v3];[black][v0]overlay=10:y=(main_h-overlay_h)[ov0];[ov0][v1]overlay[ov1];[ov1][v2]overlay[ov2];[ov2][v3]overlay=format=yuv420"  -c:v  libx264 output.mp4
 		//ffmpeg -f concat -i info.txt -c:v libx264 -t 15 -pix_fmt yuv420p -vf scale=720:480 out.mp4

		//$exit_status = 0;

		if ($exit_status === 0) {

			exec("ffmpeg -i output_no_logo.mp4 -i C:/wamp64/www/takeasec/wp-content/uploads/logo.png -filter_complex \"overlay=10:y=(main_h-overlay_h)\" $outvid.mp4", $output, $exit_status_logo);

			if ($exit_status_logo === 0) {
				//unlink( $info );
				unlink( 'output_no_logo.mp4' ); 
		
				$video_title = ( "" != $_POST['video_title'] ) ? strip_tags( $_POST['video_title'] ) : $outvid ;

				$video_tags = wp_get_object_terms($imgs_ids, 'post_tag', array('fields' => 'ids'));
				$video_tags_all = wp_get_object_terms($imgs_ids, 'post_tag', array('orderby' => 'none'));
				$req[] = $imgs_ids;

				$post_data = array(
					'post_title'    => $video_title,
					'post_content'  => $user_dir_url . $outvid . '.mp4',
					'post_status'   => 'publish',
					'post_type'   => 'vg_video',
					'post_author'   => $user_id,
					'tax_input'      => array( 'post_tag' => $video_tags ),
				);

				$req['video_title'] = $video_title;

				// Вставляем запись в базу данных
				$new_post_id = wp_insert_post( $post_data );

				$post = get_post($new_post_id); 

				$author_id = $post->post_author;

				$slug = $post->post_name;

				//0 != wp_insert_post( $post_data )

				if ( 0 != $new_post_id ) {

					update_user_meta( $user_id, 'vg_user_last_vid', $end_of_day, $vg_user_last_vid );

					if( set_post_thumbnail( $new_post_id, $images[1]['img_id'] ) ) {
						$req['thmb'] = $images[1]['img_id'] ;
					} else {
						$req['thmb'] = 'Thumbnail add error' ;
					}

					if(function_exists('the_ratings')) {
						$raiting = $req['raiting'] = the_ratings('div', $new_post_id, false);
					}else{
						$raiting = $req['raiting'] = '<div>no raiting!</div>';
					}

					if ( is_array( $video_tags_all ) ) {
						$tag = '<ul class="attachmentTag">';
						foreach( $video_tags_all as $cur_term ){
							$tag .= '<li><a class="" href="'. get_term_link( (int)$cur_term->term_id, $cur_term->taxonomy ) .'">#'. $cur_term->name .'</a>
								<button type="button" class="vg-image-tag-delete close" data-term-name="'.$cur_term->slug.'" data-img-name="' . $new_post_id . '">&times;</button></li>';
						}
						$tag .= '</ul><div class="clearfix"></div><a href="https://www.facebook.com/sharer/sharer.php?u=http://takeasec.co/video/'.$slug.'" onclick=\'window.open(this.href, "mywin","left=20,top=20,width=500,height=500,toolbar=1,resizable=0"); return false;\' class="post-to-facebook button"><img src="/wp-content/uploads/2018/admin/share-white.png"></a>';
						$req['tagUl'] = $tag;
					}else{
						$tag = '';
					}

					$button = '<button class="button btn-xs delete-video button-size-2 button-js" data-delete-video="'.$new_post_id.'"><span>X</span></button>';

					$author_name = '<a href="/user/'.mb_strtolower(get_the_author_meta('user_login', $author_id)).'/" class="author-name" title="User name">'.get_the_author_meta('display_name', $author_id).'</a>';

    				if(function_exists('wp_ulike')){
						$args = array(
							'id' => $new_post_id,
						);
						$like = wp_ulike('put', $args);
					}

					$btn_like= '<div class="vg-video-like" data-like-video="'.$new_post_id.'">'.$like.'</div>';

					$req['out'] = '<div>'.get_the_post_thumbnail( $new_post_id, 'large').'<a href="#" class="vg-watch-video" data-video-src="'.$user_dir_url . $outvid . '.mp4'.'" data-modal-id="vg-modal-video"><span class="fvg-video-icon">
						  <i class="fa fa-play-circle-o "></i>
						</span></a>'.$button.$author_name.'
						<div class="images-attr">'.$raiting.$tag.$btn_like.'</div>
						</div>';

					$req['del_button'] = '<button class="button_label_video button btn-xs delete-video button-size-2 button-js" data-delete-video="' . $new_post_id . '"  0="">X</button>';
					$req['postFace'] = '<div class="clearfix"></div><a href="https://www.facebook.com/sharer/sharer.php?u=http://takeasec.co/video/'.$post->post_name.'" onclick=\'window.open(this.href, "mywin","left=20,top=20,width=500,height=500,toolbar=1,resizable=0"); return false;\' class="post-to-facebook button"><img src="/wp-content/uploads/2018/admin/share-white.png"></a>';
					$req['author_name'] = '<a href="/user/'.mb_strtolower(get_the_author_meta('user_login', $post->post_author)).'/" class="author-name" title="User name">'.get_the_author_meta('display_name', $post->post_author).'</a>';
					$req['user_chanse_win'] = vg_update_user_chsw( wp_get_current_user()->ID, 10 );
					$req['video_link'] = $user_dir_url . $outvid . '.mp4';
					//$req['upload_button'] = '<a href="' . $user_dir_url . $outvid . '.mp4" class="button btn-xs download-video button-size-2 button-js" 0=""><span class="button_label_video" download>DOWNLOAD</span></a>';
				}
			}
			 else {
			    $req[] = 'O no logo!' . $use_dir_url.$outvid;
			}

		} else {
		    $req[] = 'O no!' . $user_dir_url.$outvid . '.mp4';
		} 
	} else { //Check data
		$req[] = 'You have already generated video today.';
	}
} else if ( !isset( $_POST['images'] ) ) {
	$req[] = 'Upload images';
} else {
	$req[] = 'Upload files';
}
echo json_encode($req);
	wp_die();
}


add_action('wp_ajax_vg_loading_next_masonry', 'vg_loading_next_masonry');
add_action('wp_ajax_nopriv_vg_loading_next_masonry', 'vg_loading_next_masonry');
function vg_loading_next_masonry() {
	check_ajax_referer( 'media-form', 'nonce_code' );
	$ui = (isset($_POST['ui']) && !empty($_POST['ui']))? $_POST['ui'] : get_current_user_id();
	$all_images = '';
	$page = (isset($_POST['page']))? $_POST['page'] : 1;
	$args = array( 
		'post_type' =>  array( 'vg_video', 'attachment'),
		'post_status' => array('publish', 'inherit'),
		'posts_per_page' => 5,
		'paged' => $page,
		'author' =>  $ui,);
		//тут мы указываем на тип записи по которой желаем пройтись и количество записей на одной странице 
		$loop = new WP_Query( $args );
		// получаем результат запроса в переменное loop	

		if ($loop->have_posts()) { 

			 foreach ($loop->posts as $post) {
					
				$all_images .= vg_get_day_block($post, $user_id);

				} 

				$infin['all_images'] = $all_images;
			
		 }else{
		 	$infin['all_images'] = '';
		 }
		 $infin['page'] = $page;
		 $infin['curent'] = get_current_user_id();
		 $infin['ultim'] = $ultimatemember->user->id;

		echo json_encode($infin);
		wp_die();

}

add_action('wp_ajax_vg_loading_latest_masonry', 'vg_loading_latest_masonry');
add_action('wp_ajax_nopriv_vg_loading_latest_masonry', 'vg_loading_latest_masonry');
function vg_loading_latest_masonry() {
	check_ajax_referer( 'media-form', 'nonce_code' );
	$all_images = '';
	$page = (isset($_POST['page']))? $_POST['page'] : 1;
	$args = array( 
		'post_type' =>  array( 'vg_video', 'attachment'),
		'post_status' => array('publish', 'inherit'),
		'posts_per_page' => 25,
		'paged' => $page,);
		//тут мы указываем на тип записи по которой желаем пройтись и количество записей на одной странице 
		$loop = new WP_Query( $args );
		// получаем результат запроса в переменное loop	

		if ($loop->have_posts()) { 

			 foreach ($loop->posts as $post) {
					
				$all_images .= vg_get_day_block($post);

				} 

				$infin['all_images'] = $all_images;
			
		 }else{
		 	$infin['all_images'] = '';
		 }
		 $infin['page'] = $page;
		 $infin['curent'] = get_current_user_id();
		 $infin['ultim'] = $ultimatemember->user->id;

		echo json_encode($infin);
		wp_die();

}

add_action('wp_ajax_vg_loading_best_raiting_masonry', 'vg_loading_best_raiting_masonry');
add_action('wp_ajax_nopriv_vg_loading_best_raiting_masonry', 'vg_loading_best_raiting_masonry');
function vg_loading_best_raiting_masonry() {
	check_ajax_referer( 'media-form', 'nonce_code' );
	$all_images = '';
	$page = (isset($_POST['page']))? $_POST['page'] : 1;
	$args = array( 
		'post_type' =>  array( 'vg_video', 'attachment'),
		'post_status' => array('publish', 'inherit'),
		'meta_key' => '_liked', 
		'orderby' => 'meta_value_num', 
		'order' => 'DESC',
		'posts_per_page' => 25,
		'paged' => $page,);
		//тут мы указываем на тип записи по которой желаем пройтись и количество записей на одной странице 
		$loop = new WP_Query( $args );
		// получаем результат запроса в переменное loop	

		if ($loop->have_posts()) { 

			 foreach ($loop->posts as $post) {
					
				$all_images .= vg_get_day_block($post);

				} 

				$infin['all_images'] = $all_images;
			
		 }else{
		 	$infin['all_images'] = '';
		 }
		 $infin['page'] = $page;
		 $infin['curent'] = get_current_user_id();
		 $infin['ultim'] = $ultimatemember->user->id;

		echo json_encode($infin);
		wp_die();

}

function insert_attachment1($file_handler,$post_id,$setthumb='false') {
  // check to make sure its a successful upload
  if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();
  require_once(ABSPATH . "wp-admin" . '/includes/image.php');
  require_once(ABSPATH . "wp-admin" . '/includes/file.php');
  require_once(ABSPATH . "wp-admin" . '/includes/media.php');
  $attach_id = media_handle_upload( $file_handler, $post_id );
  if ($setthumb) update_post_meta($post_id,'_thumbnail_id',$attach_id);
  return $attach_id;
}

add_action('wp_ajax_vg_delete_tag', 'vg_delete_tag');

function vg_delete_tag() {
	
	$whatever[] = $img_id = ( isset( $_POST['img_id'] ) ) ? $_POST['img_id'] : '';
	$whatever[] = $metkaTag = ( isset( $_POST['term_id'] ) ) ? $_POST['term_id'] : '';
	if($img_id !== '' && $metkaTag !== ''){
		$done = wp_remove_object_terms( $img_id, $metkaTag, 'post_tag' );
		if( $done ) {
			$whatever['tagLi'] = "Was deleted.";
		}
		else {
			$whatever['tagLi'] = "Could not remove tag.";

		}
			
		}
		echo json_encode($whatever);
	wp_die();
}

add_action('wp_ajax_vg_delete_video', 'vg_delete_video');

function vg_delete_video() {
	
	$whatever[] = $video_id = ( isset( $_POST['video_id'] ) ) ? $_POST['video_id'] : '';
	if ( is_user_logged_in() ) {
		if( $video_id !== '' ){
			$content_post = get_post($video_id);
			$cur_user = get_current_user_id();
			if ( $cur_user == $content_post->post_author ) {
				$video_file = str_replace(site_url(), get_home_path(), $content_post->post_content);
				if ( file_exists( $video_file ) ) {
					$file_del = unlink ( $video_file );
					if ( $file_del  ) {
						$whatever['file_del'] = 'File ' . $content_post->post_content .' was deleted';
					} else {
						$whatever['file_del'] = 'File wasn\'t deleted';
					}		
				}
				$post_delete = wp_delete_post( $video_id, true );
				if ( $post_delete !== false ) {
					$whatever['post_del'] = 'Video ' . $content_post->post_title .' was deleted';
				} else {
						$whatever['post_del'] = 'Video wasn\'t deleted';
				}
				$args = array( 
    			'post_type' => 'vg_video', 
    			'posts_per_page' => 30, 
    			'author' =>  $cur_user);//тут мы указываем на тип записи по которой желаем пройтись и количество записей на одной странице 
				$loop = new WP_Query( $args );
				if ($loop->have_posts()) {
					$today_videos = array();
					while ( $loop->have_posts() ) : $loop->the_post();
						$today_videos[] = $loop->posts;						
					endwhile;
					$last_video = array_pop($today_videos)[0];
					$whatever['last_video_src'] = $last_video->post_content;
					if(function_exists('the_ratings')) {
						$whatever['raiting'] = the_ratings('div', $last_video->ID, false);
					}else{
						$whatever['raiting'] = '<div>no raiting!</div>';
					}
					$whatever['video_title'] = $last_video->post_title;
					$video_tags_all = wp_get_object_terms($last_video->ID, 'post_tag', array('orderby' => 'none'));
					if ( is_array( $video_tags_all ) ) {
						$out = '<ul class="attachmentTag">';
						foreach( $video_tags_all as $cur_term ){
							$out .= '<li><a class="" href="'. get_term_link( (int)$cur_term->term_id, $cur_term->taxonomy ) .'">#'. $cur_term->name .'</a>
								<button type="button" class="vg-image-tag-delete close" data-term-name="'.$cur_term->slug.'" data-img-name="' . $last_video->ID . '">&times;</button></li>';
						}
						$out .= '</ul>';
						$whatever['tagUl'] = $out;
					}
					$whatever['postFace'] = '<div class="clearfix"></div><a href="https://www.facebook.com/sharer/sharer.php?u=http://takeasec.co/video/'.$post->post_name.'" onclick=\'window.open(this.href, "mywin","left=20,top=20,width=500,height=500,toolbar=1,resizable=0"); return false;\' class="post-to-facebook button"><img src="/wp-content/uploads/2018/admin/share-white.png"></a>';
					$whatever['del_button'] = '<button class="button_label_video button btn-xs delete-video button-size-2 button-js" data-delete-video="' . $last_video->ID. '"  0="">X</button>';
					$whatever['author_name'] = '<a href="/user/'.mb_strtolower(get_the_author_meta('user_login', $post->post_author)).'/" class="author-name" title="User name">'.get_the_author_meta('display_name', $post->post_author).'</a>';
				} else {
					$whatever['last_video_src'] = '/wp-content/uploads/2017/admin/output.mp4';
				}
			}			
		}
		echo json_encode($whatever);
	}
	wp_die();
}

function vg_get_day_block($post){

	$user_id = get_current_user_id();

	$out = '';
	
	if ($post->post_type === 'attachment') {
		$out .= '<div><img src="'.$post->guid.'" class="image_for_video" data-img-id="'.$post->ID.'" data-img-url="'.$post->guid.'">';
		if ($user_id == $post->post_author && !is_front_page() ) {				
			$out .= '<div class="vg-select-icons"><i class="icon-check"></i></div>';
		}
		$out .= '<div style="display:none;" class="vg-image-add-text-container" role="add text"><input type="text" class="text" placeholder="Image title" value=""></div>';
		if ($user_id == $post->post_author && !is_front_page()) {

			$out .= '<button class="button btn-xs delete-video button-size-2 button-js" data-delete-video="'.$post->ID.'"><span>X</span></button>';

		}
		$out .= '<a href="/user/'.mb_strtolower(get_the_author_meta('user_login', $post->post_author)).'/" class="author-name" title="User name">'.get_the_author_meta('display_name', $post->post_author).'</a>';
		$out .= '<div class="images-attr">';
				$video_tags_all = wp_get_object_terms($post->ID, 'post_tag', array('orderby' => 'none', 'number' => 3 ));
				if ( is_array( $video_tags_all ) ) {

					if( function_exists( 'the_ratings' ) && isset( $post ) ) {
						$out .= the_ratings('div', $post->ID, false);
					} else {
						$out .= '<div>There is no raiting for this video</div>';
					}

					$out .= '<ul class="attachmentTag">';
					foreach( $video_tags_all as $cur_term ){
						$out .= '<li><a class="" href="'. get_term_link( (int)$cur_term->term_id, $cur_term->taxonomy ) .'">#'. $cur_term->name .'</a>';
						if ($user_id == $post->post_author && !is_front_page()) {				
							$out .= '<button type="button" class="vg-image-tag-delete close" data-term-name="'.$cur_term->slug.'" data-img-name="' . $post->ID . '">&times;</button>';
						}
						$out .= '</li>';
					}
					$out .= '</ul><div class="clearfix"></div><a href="https://www.facebook.com/sharer/sharer.php?u=http://takeasec.co/'.$post->post_name.'" onclick=\'window.open(this.href, "mywin","left=20,top=20,width=500,height=500,toolbar=1,resizable=0"); return false;\' class="post-to-facebook button"><img src="/wp-content/uploads/2018/admin/share-white.png"></a>';
				}
				

				if(function_exists('wp_ulike')){
					$args = array(
						'id' => $post->ID,
					);
					$like = wp_ulike('put', $args);
				}

				$out .= '<div class="vg-video-like" data-like-video="'.$post->ID.'">'.$like.'</div>';

				// if ($user_id == $post->post_author) {

				// 	$out .= '<button class="button btn-xs delete-video button-size-2 button-js" data-delete-video="'.$post->ID.'"><span>X</span></button>';

				// }
				if ($user_id == $post->post_author && !is_front_page()) {
						$out .= '<div class="vg-image-add-tag-wraper"><div  class="vg-image-add-tag-container" role="add tag"><button type="button" class="vg-image-add-tag-close close" data-dismiss="modal">&times;</button><input type="text" class="text" id="attachments-' . $post->ID . '-post_tag" name="attachments[' . $post->ID . '][post_tag]" value=""></div>';
						$out .= '<button class="btn btn-xs vg-image-add-tag" data-img-id="' . $post->ID . '">' . esc_attr__( 'Add tag', 'vg' ) . '</button></div>';
					}

			$out .= '</div></div>';
	}else if ($post->post_type === 'vg_video') {
		 $first_image_video = get_the_post_thumbnail( $post->ID, 'large');
		 if ($first_image_video != '') {
		 	$out .= '<div>'.$first_image_video.'<a href="#" class="vg-watch-video" data-video-src="'.$post->post_content.'" data-modal-id="vg-modal-video"><span class="fvg-video-icon"><i class="fa fa-play-circle-o "></i></span></a>';


				if ($user_id == $post->post_author && !is_front_page()) {

					$out .= '<button class="button btn-xs delete-video button-size-2 button-js" data-delete-video="'.$post->ID.'"><span>X</span></button>';

				}
				$out .= '<a href="/user/'.mb_strtolower(get_the_author_meta('user_login', $post->post_author)).'/" class="author-name" title="User name">'.get_the_author_meta('display_name', $post->post_author).'</a>';

				$out .= '<div class="images-attr">';
			$video_tags_all = wp_get_object_terms($post->ID, 'post_tag', array('orderby' => 'none', 'number' => 3 ));
				if ( is_array( $video_tags_all ) ) {

					if( function_exists( 'the_ratings' ) && isset( $post ) ) {
						$out .= the_ratings('div', $post->ID, false);
					} else {
						$out .= '<div>There is no raiting for this video</div>';
					}

					$out .= '<ul class="attachmentTag">';
					foreach( $video_tags_all as $cur_term ){
						$out .= '<li><a class="" href="'. get_term_link( (int)$cur_term->term_id, $cur_term->taxonomy ) .'">#'. $cur_term->name .'</a>';
						if ($user_id == $post->post_author && !is_front_page()) {
							$out .= '<button type="button" class="vg-image-tag-delete close" data-term-name="'.$cur_term->slug.'" data-img-name="' . $post->ID . '">&times;</button>';
						}
						$out .= '</li>';
					}					
					$out .= '</ul><div class="clearfix"></div><a href="https://www.facebook.com/sharer/sharer.php?u=http://takeasec.co/video/'.$post->post_name.'" onclick=\'window.open(this.href, "mywin","left=20,top=20,width=500,height=500,toolbar=1,resizable=0"); return false;\' class="post-to-facebook button"><img src="/wp-content/uploads/2018/admin/share-white.png"></a>';
				}
				
				
				if(function_exists('wp_ulike')){
					$args = array(
						'id' => $post->ID,
					);
					$like = wp_ulike('put', $args);
				}

				$out .= '<div class="vg-video-like" data-like-video="'.$post->ID.'">'.$like.'</div>';

				$out .= '</div></div>';
		 }else{
		 	$out .= '<div><img src="'.strtolower(wp_get_upload_dir()['baseurl']).'/first_image.jpg"><a href="#" class="vg-watch-video" data-video-src="'.$post->post_content.'" data-modal-id="vg-modal-video"><span class="fvg-video-icon"><i class="fa fa-play-circle-o "></i></span></a>';

			if ($user_id == $post->post_author && !is_front_page()) {

				$out .= '<button class="button btn-xs delete-video button-size-2 button-js" data-delete-video="'.$post->ID.'"><span>X</span></button>';

			}
			$out .= '<a href="/user/'.mb_strtolower(get_the_author_meta('user_login', $post->post_author)).'/" class="author-name" title="User name">'.get_the_author_meta('display_name', $post->post_author).'</a>';

			$out .= '<div class="images-attr">';
			$video_tags_all = wp_get_object_terms($post->ID, 'post_tag', array('orderby' => 'none'));
				if ( is_array( $video_tags_all ) ) {

					if( function_exists( 'the_ratings' ) && isset( $post ) ) {
						$out .= the_ratings('div', $post->ID, false);
					} else {
						$out .= '<div>There is no raiting for this video</div>';
					}
					
					$out .= '<ul class="attachmentTag">';
					foreach( $video_tags_all as $cur_term ){
						$out .= '<li><a class="" href="'. get_term_link( (int)$cur_term->term_id, $cur_term->taxonomy ) .'">#'. $cur_term->name .'</a>';
						if ($user_id == $post->post_author && !is_front_page()) {
							$out .= '<button type="button" class="vg-image-tag-delete close" data-term-name="'.$cur_term->slug.'" data-img-name="' . $post->ID . '">&times;</button>';
						}
						$out .= '</li>';
					}
					$out .= '</ul><div class="clearfix"></div><a href="https://www.facebook.com/sharer/sharer.php?u=http://takeasec.co/video/'.$post->post_name.'" onclick=\'window.open(this.href, "mywin","left=20,top=20,width=500,height=500,toolbar=1,resizable=0"); return false;\' class="post-to-facebook button"><img src="/wp-content/uploads/2018/admin/share-white.png"></a>';
				}
				

				if(function_exists('wp_ulike')){
					$args = array(
						'id' => $post->ID,
					);
					$like = wp_ulike('put', $args);
				}

				$out .= '<div class="vg-video-like" data-like-video="'.$post->ID.'">'.$like.'</div>';
				$out .= '</div></div>';
		 }
		
	}

	return $out;

}

function vg_update_user_chsw( $ui, $chb ){
 
	$user_chanse_win = get_user_meta( $ui, 'user_chanse_win', true );
	$user_chanse_win = $user_chanse_win+$chb;
	update_usermeta( $ui, 'user_chanse_win', $user_chanse_win );
	return $user_chanse_win;

}