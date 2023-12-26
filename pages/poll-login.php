<div class="award_banner_page">
	<?php
		global $post;
		$banner = get_the_post_thumbnail_url($post) ? get_the_post_thumbnail_url($post) : plugins_url( 'public/images/banner_awards.jpg' , dirname(__FILE__) );
	?>
	<img src="<?php echo $banner; ?>" alt="<?php echo $post->post_title; ?>" />
</div>

<div class="award_green_area d-flex align-items-center flex-column flex-lg-row p-0">
	<div class="col-12 col-lg-5 px-5 py-5 text-center text-lg-end">
		<img class="vce-single-image" width="180" height="179" src="<?php echo plugins_url( 'public/images/award_2022.jpg' , dirname(__FILE__) ); ?>"  alt="award_2022" title="award_2022">
	</div>
	<div class="col-12 col-lg-7 px-3 px-lg-5 py-3 py-lg-5 text">
		<p class="mb-0">
			是次選舉以去年10 月至今年7 月之間問世的新表為基礎，劃分成最佳好表10 大組別。<br />
			經過我們編輯部的嚴格挑選，各組別分別擁有10 枚入圍的腕表，以供獨立評審委員會、<br />
			讀者及《Spiral 游絲雜誌》編輯部投票，從中挑選每個組別得票最高的3 枚腕表。<br />
			是次投票已經完成，投票結果將於12 月《游絲雜誌》出版後公布
		</p>
	</div>
</div>

<div class="award_login my-5 d-flex flex-column align-items-center mx-auto px-3">
<?php
if (!is_user_logged_in()) {
	wp_login_form([
		'label_username' => '登入名稱',
		'label_password' => '密碼',
		'label_log_in' => '立即投票',
		'remember' => false,
	]);
}
?>
</div>