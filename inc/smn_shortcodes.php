<?php 
add_shortcode('acf_author_tratamientos', function($atts) {
    // Obtener el usuario actual del archivo de autor
    if (!is_author()) return '';

    $author = get_queried_object();
    if (!$author || empty($author->ID)) return '';

    // Obtener el campo de relación (array de posts/páginas)
    $relacionados = get_field('author_tratamientos', 'user_' . $author->ID);

    if (empty($relacionados)) return '<p>No hay tratamientos asignados.</p>';

    $output = '<ul class="wp-block-list is-style-check-list">';
    foreach ($relacionados as $post) {
        // Si es un objeto WP_Post
        if (is_object($post)) {
            $output .= '<li><a href="' . get_permalink($post->ID) . '">' . esc_html(get_the_title($post->ID)) . '</a></li>';
        } else {
            // Si es solo el ID
            $output .= '<li><a href="' . get_permalink($post) . '">' . esc_html(get_the_title($post)) . '</a></li>';
        }
    }
    $output .= '</ul>';

    return $output;
});

add_shortcode('acf_author_linkedin', function($atts) {
    if (!is_author()) return '';

    $author = get_queried_object();
    if (!$author || empty($author->ID)) return '';

    $linkedin = get_field('linkedin_user', 'user_' . $author->ID);

    if (empty($linkedin)) return '';

    return '<ul class="wp-block-social-links is-style-default is-layout-flex wp-block-social-links-is-layout-flex"><li class="wp-social-link wp-social-link-linkedin  wp-block-social-link"><a href="' . esc_url($linkedin) . '" class="wp-block-social-link-anchor" target="_blank"><svg width="24" height="24" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M19.7,3H4.3C3.582,3,3,3.582,3,4.3v15.4C3,20.418,3.582,21,4.3,21h15.4c0.718,0,1.3-0.582,1.3-1.3V4.3 C21,3.582,20.418,3,19.7,3z M8.339,18.338H5.667v-8.59h2.672V18.338z M7.004,8.574c-0.857,0-1.549-0.694-1.549-1.548 c0-0.855,0.691-1.548,1.549-1.548c0.854,0,1.547,0.694,1.547,1.548C8.551,7.881,7.858,8.574,7.004,8.574z M18.339,18.338h-2.669 v-4.177c0-0.996-0.017-2.278-1.387-2.278c-1.389,0-1.601,1.086-1.601,2.206v4.249h-2.667v-8.59h2.559v1.174h0.037 c0.356-0.675,1.227-1.387,2.526-1.387c2.703,0,3.203,1.779,3.203,4.092V18.338z"></path></svg><span class="wp-block-social-link-label">LinkedIn</span></a></li></ul>';
});


add_shortcode('acf_editors_cards', function($atts) {
    // Atributos por defecto
    $atts = shortcode_atts([
        'role' => 'author', // valor por defecto
    ], $atts, 'acf_editors_cards');

    // Permitir varios roles separados por coma
    $roles = array_map('trim', explode(',', $atts['role']));

    $args = array(
        'role__in' => $roles,
        'orderby' => 'post_count',
        'order'   => 'DESC',
        'fields'  => 'all'
    );
    $users = get_users($args);

    if (empty($users)) return '<p>No hay profesionales disponibles.</p>';

    $output = '<div class="wp-block-group is-style-group-horizontal-scroll cards-team">';
    foreach ($users as $user) {
        $avatar = get_avatar($user->ID, 280, '', $user->display_name, ['class' => 'wp-block-cover__image-background']);
        $name = esc_html($user->display_name);
        $number = get_field('number_colegiado', 'user_' . $user->ID);
        $profile_url = get_author_posts_url($user->ID);

        $output .= '
        <div class="wp-block-cover has-custom-content-position is-position-bottom-left card-team" style="border-radius:32px;padding:var(--wp--preset--spacing--10);min-height:320px;">
            ' . $avatar . '
            <span aria-hidden="true" class="wp-block-cover__background has-neutral-black-background-color has-background-dim-20 has-background-dim" style="opacity:0.2;"></span>
            <div class="wp-block-cover__inner-container is-layout-flow wp-block-cover-is-layout-flow">
                <div class="wp-block-group has-foreground-color has-secondary-transparent-background-color has-text-color has-background has-link-color" style="border-radius:8px;padding:0.8rem;">
                    <p class="has-text-align-left">' . $name . '</p>
                    <p class="has-small-font-size">' . esc_html($number) . '</p>
                    <a class="author-profile-link" href="' . esc_url($profile_url) . '">Ver perfil</a>
                </div>
            </div>
        </div>';
    }
    $output .= '</div>';

    return $output;
});


add_shortcode('acf_users_for_tratamiento', function($atts) {
    if (!is_page()) return '';

    global $post;
    if (!$post || empty($post->ID)) return '';

    // Obtener todos los usuarios que tengan este tratamiento relacionado
    $user_query = new WP_User_Query([
        'meta_query' => [
            [
                'key'     => 'author_tratamientos',
                'value'   => '"' . $post->ID . '"',
                'compare' => 'LIKE'
            ]
        ],
        'orderby' => 'post_count',
        'order'   => 'DESC'
    ]);
    $users = $user_query->get_results();

    if (empty($users)) return '<p>No hay profesionales asignados a este tratamiento.</p>';

    // Ordenar: primero autores, luego colaboradores
    $ordered_roles = ['author', 'contributor'];
    $sorted_users = [];
    foreach ($ordered_roles as $role) {
        foreach ($users as $user) {
            if (in_array($role, (array) $user->roles)) {
                $sorted_users[$user->ID] = $user;
            }
        }
    }
    // Añadir cualquier usuario que no sea author ni contributor al final
    foreach ($users as $user) {
        if (!isset($sorted_users[$user->ID])) {
            $sorted_users[$user->ID] = $user;
        }
    }

    $output = '<div class="wp-block-group is-style-group-horizontal-scroll cards-team">';
    foreach ($sorted_users as $user) {
        $avatar_url = get_avatar_url($user->ID, ['size' => 280]);
        $avatar = '<img src="' . esc_url($avatar_url) . '" alt="' . esc_attr($user->display_name) . '" class="wp-block-cover__image-background" style="width:100%;height:auto;object-fit:cover;border-radius:32px;">';
        $name = esc_html($user->display_name);
        $number = get_field('number_colegiado', 'user_' . $user->ID);
        $profile_url = get_author_posts_url($user->ID);

        $output .= '
        <div class="wp-block-cover has-custom-content-position is-position-bottom-left card-team" style="border-radius:32px;padding:var(--wp--preset--spacing--10);min-height:320px;">
            ' . $avatar . '
            <span aria-hidden="true" class="wp-block-cover__background has-neutral-black-background-color has-background-dim-20 has-background-dim" style="opacity:0.2;"></span>
            <div class="wp-block-cover__inner-container is-layout-flow wp-block-cover-is-layout-flow">
                <div class="wp-block-group has-foreground-color has-secondary-transparent-background-color has-text-color has-background has-link-color" style="border-radius:8px;padding:0.8rem;">
                    <p class="has-text-align-left">' . $name . '</p>
                    <p class="has-small-font-size">' . esc_html($number) . '</p>
                    <a class="author-profile-link" href="' . esc_url($profile_url) . '">Ver perfil</a>
                </div>
            </div>
        </div>';
    }
    $output .= '</div>';

    return $output;
});