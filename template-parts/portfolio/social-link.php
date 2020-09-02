<?php
$social_link = get_post_meta( get_the_ID(), 'bdthemes_portfolio_social_link', true );
if($social_link != null and is_array($social_link)) : ?>
	<div class="broxme_wp-portfolio-social broxme_wp-position-absolute broxme_wp-position-bottom-center">
		<ul class="broxme_wp-list broxme_wp-margin-remove-bottom">
	    <?php foreach ($social_link as $link) : ?>
	        <?php $tooltip = ucfirst(bangla_helper::icon($link)); ?>
	        <li class="broxme_wp-display-inline-block">
	            <a<?php echo bangla_helper::attrs(['href' => $link, 'class' => 'broxme_wp-margin-small-right']); ?> broxme_wp-icon="icon: <?php echo bangla_helper::icon($link); ?>" title="<?php echo esc_html($tooltip); ?>" broxme_wp-tooltip></a>
	        </li>
	    <?php endforeach ?>
	    </ul>
	</div>
<?php endif; ?>