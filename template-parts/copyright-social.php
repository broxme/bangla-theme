<?php 

$attrs['class']        = get_theme_mod( 'bangla_toolbar_social_style' ) ? 'broxme_wp-icon-button' : 'broxme_wp-icon-link';
$attrs['target']       = get_theme_mod( 'bangla_toolbar_social_target' ) ? '_blank' : '';

// Grid
$attrs_grid            = [];
$attrs_grid['class'][] = 'broxme_wp-grid-small broxme_wp-flex-middle';
$attrs_grid['broxme_wp-grid'] = true;

$social_links = (get_theme_mod( 'bangla_toolbar_social' )) ? explode(',', get_theme_mod( 'bangla_toolbar_social' )) : [];


if (count($social_links)) : ?>
	<div class="social-link">
		<ul<?php echo bangla_helper::attrs($attrs_grid) ?>>
		    <?php foreach($social_links as $link) : ?>
		    <li>
		        <a<?php echo bangla_helper::attrs(['href' => $link], $attrs); ?> broxme_wp-icon="icon: <?php echo bangla_helper::icon($link); ?>; ratio: 0.8" title="<?php echo ucfirst(bangla_helper::icon($link)); ?>" broxme_wp-tooltip=""></a>
		    </li>
		    <?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>
