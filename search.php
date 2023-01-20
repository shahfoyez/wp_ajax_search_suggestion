<div class="searchform">
    <form id="search" action="">
        <input type="hidden" name="post_type" value="course">
        <input type="text" class="s" id="ss" name="s" placeholder="Search over 3000+ courses..." value="" autocomplete="off" onkeyup="foyFunction()">
        <div id="foy-loading" class="spinner-border" role="status">
            <img src="https://project12.wpengine.com/wp-content/uploads/2023/01/1494.gif">
        </div>
        <button type="submit" class="sbtn"><i class="fa fa-search"></i></button>
            
    </form>
    <div class="foy-suggestion-box" id="foy-suggestion-box">
        <!-- course suggestion -->
    </div>
</div>
<script type="text/javascript">
function foyFunction(){
    jQuery('#foy-loading').css( 'display', 'block' );
    var keyword = jQuery('#ss').val();
    if(keyword.length < 3){
        jQuery('#foy-suggestion-box').html("");
        jQuery('#foy-suggestion-box').css( 'display', 'none' );
        jQuery('#foy-loading').css( 'display', 'none' );
    } else {
        jQuery.ajax({
            url: ajaxurl,
            type: 'get',
            data: { 
                action: 'data_fetch', 
                keyword: keyword  
            },
            success: function(data) { 
                jQuery('#foy-suggestion-box').html( data );
                jQuery('#foy-suggestion-box').css( 'display', 'block' );
                jQuery('#foy-loading').css( 'display', 'none' );
            }
                        
        });
    }
}
</script>
// inside function.php
// Search Suggestion
function data_fetch(){
	$keyword = $_REQUEST['keyword'];
	function title_filter( $where, &$wp_query )
	{
		global $wpdb;
		if ( $search_term = $wp_query->get( 'search_prod_title' ) ) {
			$where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $wpdb->esc_like( $search_term ) ) . '%\'';
		}
		return $where;
	}
	$args = array(
		'post_status' => 'publish',
		'post_type' => 'course',
		'orderby'   => 'meta_value_num',
		// 1. define a custom query variable here to pass your term through
		'search_prod_title' => $keyword,
		'meta_query' => array(
			array(
				// 'key' => 'average_rating',
				'key' => 'vibe_students',
			),
			array(
				'key' => 'vibe_product',
				'value'   => array(''),
				'compare' => 'NOT IN'
			)
		),
		'order' => 'DESC',
		'posts_per_page' => 10,
	);
	add_filter('posts_where', 'title_filter', 10, 2 );
	$the_query = new WP_Query($args);
	remove_filter( 'posts_where', 'title_filter', 10 );
	
    if( $the_query->have_posts() ){
        while($the_query->have_posts() ): $the_query->the_post(); 
		$meta = get_post_meta(get_the_ID());
		// echo "<pre>";
		// var_dump($meta);
		// echo "</pre>";
		$product_meta = get_post_meta(get_the_ID(), 'vibe_students', true);
		echo "<pre>";
		var_dump($product_meta);
		echo "</pre>";
		?>
			
			<div class="foy-course-list">
				<?php
					$default =  get_theme_file_uri('/assets/images/defaultCourse.png');
					$image_url = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
					$img_url = $image_url ? $image_url : $default

				?>
				<img src="<?php echo $img_url;?>">
				<a href="<?php echo esc_url( the_permalink() ); ?>"><?php the_title();?></a>
			</div>
			<hr>
        <?php endwhile;
		wp_reset_postdata();  
	}else{
		echo '<h3>No Results Found</h3>';
	}
    die();
}
add_action('wp_ajax_data_fetch', 'data_fetch');
add_action('wp_ajax_nopriv_data_fetch', 'data_fetch');

<style>
.makingTheAlilikeSearchForm .searchform input#s {
    border: none;
     
}

.makingTheAlilikeSearchForm .searchform {
    max-width: 700px;
    margin: 0 auto;
}
#s {
    padding: 20px 20px 20px 20px;
    border-radius: 50%;
    border: 1px solid rgba(0,0,0,.08);
    color: #888;
    width: 100%;
    margin: 0px;
}
input#ss {
    border: 1px solid #B3BDC0;
    border-bottom-left-radius: 4px;
    border-right: none;
    border-top-left-radius: 4px;
    font-size: 12px;
    /* height: 36px; */
    /* padding: 5px; */
    width: calc(100% - 38px);
    border: none;
    margin: 0px;
    padding: 20px;
}
i.fa.fa-search {
    color: #000000;
    font-size: 20px;
    border: none !important;
}
button.sbtn {
    border: none;
    background: #ffffff;
}
form#search {
    display: flex;
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: baseline;
    align-content: center;
    border: 1px solid #d5d5d5;
    border-radius: 50px;
    margin: 8px 5px;
    padding: 5px 10px;
}

.foy-suggestion-box {
    background: #ffffff;
    width: 100%;
    padding: 15px;
    border-radius: 8px;
    box-shadow: rgb(0 0 0 / 16%) 0px 1px 4px;
    display: none;
    position: absolute;
    z-index: 600;
    max-width: 700px;
}
.foy-course-list img {
    height: 45px;
    width: 60px;
    border-radius: 3px;
    margin-right: 10px;
}
.foy-suggestion-box hr{
    margin-top: 10px !important;
    margin-bottom: 10px !important;
}
.foy-suggestion-box hr:last-child {
    display: none;
}
#foy-loading{ 
    display: none;
}
#foy-loading img {
    height: 40px;
    width: 40px;
}
.foy-suggestion-box h3 {
    margin: 0px;
    font-size: 12px;
}
.foy-course-list {
    align-items: center;
    display: flex;
    justify-content: start;
}
</style>
