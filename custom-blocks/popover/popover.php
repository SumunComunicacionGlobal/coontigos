<?php
/**
 * Block Name: Popover
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */


// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'wp-container--project-card';
if ( ! empty( $block['className'] ) ) {
    $class_name .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
    $class_name .= ' align' . $block['align'];
}

$position = get_field('popover_position') ?: 'top';
$is_open = get_field('is_open');
$anchor = uniqid('popover_');
?>


    <button class="wp-block-popover--button" popovertarget="<?php echo esc_attr($anchor); ?>">
        +
    </button>
    <div 
        id="<?php echo esc_attr($anchor); ?>" 
        class="wp-block-popover--content" 
        popover 
        data-position="<?php echo esc_attr($position); ?>"
        <?php if( $is_open ): ?> open <?php endif; ?>
    >
        <InnerBlocks />
    </div>
