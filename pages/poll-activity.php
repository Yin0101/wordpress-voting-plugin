<div class="award_green_area p-5 text-center page_title">
	<img class="vce-single-image" width="180" height="179" src="<?php echo plugins_url( 'public/images/award_2022.jpg' , dirname(__FILE__) ); ?>" alt="award_2022" title="award_2022">
</div>
<link rel="stylesheet" href="<?=plugins_url( 'public/css/plugin.css', dirname(__FILE__) )?>">
<!-- <link rel="stylesheet" href="<?=plugins_url( 'public/css/custom.css', dirname(__FILE__) )?>"> -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
<script src="<?=plugins_url( 'public/js/spiral-poll-public.js', dirname(__FILE__) )?>"></script>
<style>
	.submit-btn{
		padding:15px;
		background:black;
		color:white !important; 
	}
	.submit-btn:hover{
		cursor: pointer;
	}
</style>
<?php
global $wpdb;

$awards = $wpdb->get_results("
	SELECT t.term_id, t.name, t.slug, tt.description
	FROM $wpdb->terms AS t
	INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
	WHERE tt.taxonomy = 'award'
");
?>

<?php foreach ($awards as $award) : ?>
	<?php
		$user_id = get_current_user_id();
	?>
	<div class="award_vote_box p-4 open-vote" data-award_set="<?php echo $award->term_id; ?>" data-user_id="<?=$user_id?>">

		<div class="row text-center m-3">
			<h3 class="mb-0 text-dark fw-bold"><?php echo $award->name; ?></h3>
			<h4 class="mb-3 mb-lg-0"><?php echo $award->description; ?></h4>
		</div>

		<?php
		$args = array(
			'post_type' => 'poll',
			'tax_query' => array(
				array(
					'taxonomy' => 'award',
					'field'    => 'slug',
					'terms'    => $award->slug,
				),
			),
		);

		$query = new WP_Query($args);

		// get current voted
		$voted = false;
		$table_name = $wpdb->prefix . 'polls';

		$voted = $wpdb->get_var("
			SELECT voted FROM $table_name
			WHERE user_id = $user_id AND award_id = $award->term_id
		");

		$voted = explode(",",$voted);
		// if
		
		?>

		<?php if ($query->have_posts()) : ?>

			<div class="row flex-column flex-md-row align-content-center" style="flex-wrap: wrap;">

				<?php while ($query->have_posts()) : ?>

					<?php $query->the_post(); ?>

					<div class="col text-center mb-4 pt-2 watch <?php echo in_array(get_the_ID(),$voted) ? 'voted' : ''; ?>" data-user_id="<?php echo get_current_user_id(); ?>" data-post_id="<?php echo get_the_ID(); ?>" data-award_id="<?php echo $award->term_id; ?>">
						<img class="vce-single-image" width="217" height="298" src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>">
						<h4 class="title"><?php the_title(); ?></h4>
						<p class="text-center mb-3"><?php the_content(); ?></p>
					</div>

				<?php endwhile; ?>

			</div>

			<?php wp_reset_postdata(); ?>

		<?php endif; ?>

	</div>

<?php endforeach; ?>
<div class="submit-section" style="text-align:center;">
	<a class="submit-btn">Vote</a>
</div>