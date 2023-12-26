<div class=" vcv-content--boxed fix_error_width">
<div class="entry-content fix_error_width">
<div class="vce-row-container fix_error_width bg-white" data-vce-boxed-width="true">

<div class="award_green_area p-5 text-center page_title">
	<p>現時投票結果</p>
</div>
<p class="text-center p-4">以下為直至現時的實時投票情況，所有獎項的最後結果，將於12月號的《游絲腕表雜誌》及游絲網頁內公布。</p>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
<?php
global $wpdb;

if( isset($_POST['poll_status']) && !empty($_POST['poll_status']) ) {
	
	
	$user_id = get_current_user_id();
	
	$sql= "SELECT status FROM {$wpdb->prefix}polled_user WHERE user_id = %d";
	$result = $wpdb->get_var($wpdb->prepare($sql,$user_id));
	if(empty($result)){
		$wpdb->insert($wpdb->prefix.'polled_user',array(
		'user_id' => $user_id,
		'status' => 'submited',
		));
	}
}

$awards = $wpdb->get_results("
	SELECT t.term_id, t.name, t.slug, tt.description
	FROM $wpdb->terms AS t
	INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
	WHERE tt.taxonomy = 'award'
	
");
?>
<?php
// echo "<pre>";
// print_r($awards);
// echo "</pre>";
?>
<?php foreach ($awards as $award) : ?>
	<div class="award_content_area row d-flex align-items-center py-5 flex-column flex-lg-row">
		<div class="col-12 col-lg-4 text-center text-lg-start">
			<h3 class="mb-0 text-dark fw-bold"><?php echo $award->name; ?></h3>
			<h4 class="mb-3 mb-lg-0"><?php echo $award->description; ?></h4>
		</div>

		<?php
		// var_dump("123");
		$table_name = $wpdb->prefix . 'polls';
		$results = $wpdb->get_results("
			SELECT * FROM $table_name WHERE award_id = $award->term_id; 
		");

		$posts = [];

		// foreach ($results as $result) {
		// 	$posts[] = get_post($result->post_id);
		// }
		$voted_array = array();
		for($i=0;$i<sizeof($results);$i++){
			$results[$i]->voted = explode(",",$results[$i]->voted);
			for($a=0;$a<sizeof($results[$i]->voted);$a++){
				if(isset($voted_array[$results[$i]->voted[$a]])){
					$voted_array[$results[$i]->voted[$a]] += 1;
				}
				else{
					$voted_array[$results[$i]->voted[$a]] = 1;
				}
			}

		}
		// echo "<pre>";
		// print_r($voted_array);
		arsort($voted_array);
		$count = 0;
		foreach($voted_array as $x => $x_value) {
			// echo "Key=" . $x . ", Value=" . $x_value;
			if($count<3){
				$posts[] = get_post($x);
			}
			$count +=1;
		}
		?>
		<div class="col-12 col-lg-8">
			<div class="row  align-content-center">
				<?php foreach ($posts as $post) : ?>
					<div class="col-4 text-center mb-0">
						<img class="vce-single-image" width="217" height="298" src="<?php echo get_the_post_thumbnail_url($post); ?>" alt="<?php echo $post->post_title; ?>" title="<?php echo $post->post_title; ?>">
						<h4 class="title"><?php echo $post->post_title; ?></h4>
						<p class="text-center mb-0"><?php echo $post->post_content; ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<!-- <div class="col-12 col-lg-8">
			<div class="row  align-content-center">
				
			</div>
		</div> -->
		
				
	</div>

	<hr class="w-100 award_border mx-auto my-4">
	<div style="clear: both; display: table;"></div>
<?php endforeach; ?>
</div>
</div>
</div>