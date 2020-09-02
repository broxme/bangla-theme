<?php if(is_home() or is_single() or is_category() or is_archive()) :?>
    <p class="bdt-article-meta">
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

        <?php if(comments_open() || get_comments_number()) : ?>
      
            <?php comments_popup_link(esc_html__('No Comments', 'bangla'), esc_html__('1 Comment', 'bangla'), esc_html__('% Comments', 'bangla'), "", ""); ?>
    
        <?php endif; ?>

        <?php edit_post_link(esc_html__('Edit this post', 'bangla'), '<div class="bdt-link-reset">','</div>'); ?>
    </p>
<?php endif; ?>