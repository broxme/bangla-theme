<?php if(is_home() or is_single() or is_category() or is_archive()) :?>
    <p class="broxme_wp-article-meta">
        <?php if(get_the_date()) : ?>
      
            <time>
            <?php printf(get_the_date()); ?>
            </time>

        <?php endif; ?>

        <?php if(get_the_author()) : ?>
       
            <?php
                printf(esc_html__('Written by %s.', 'bangla'), '<a href="'.get_author_posts_url(get_the_author_meta('ID')).'" title="'.get_the_author().'">'.get_the_author().'</a>');
            ?>
      
        <?php endif; ?>

        <?php if(get_the_category_list()) : ?>
    
            <?php
                printf(esc_html__('Posted in %s', 'bangla'), get_the_category_list(', '));
            ?>
     
        <?php endif; ?>

    </p>
<?php endif; ?>