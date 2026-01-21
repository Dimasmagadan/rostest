<?php
/**
 * Doctors Plugin - Custom Meta Boxes
 *
 * @package DoctorsPlugin
 * @since 1.0.0
 */

/**
 * Add meta box for doctor details.
 *
 * @since 1.0.0
 */
function doctors_add_meta_boxes() {
    add_meta_box(
        'doctors_details',
        __( 'Информация о враче', 'doctors-plugin' ),
        'doctors_render_meta_box',
        'doctors',
        'normal',
        'default'
    );
}

add_action( 'add_meta_boxes', 'doctors_add_meta_boxes' );

/**
 * Render the doctor details meta box.
 *
 * @since 1.0.0
 * @param WP_Post $post The post object.
 */
function doctors_render_meta_box( $post ) {
    wp_nonce_field( 'doctors_save_meta_box', 'doctors_meta_box_nonce' );

    $experience = get_post_meta( $post->ID, '_doctors_experience', true );
    $price_from = get_post_meta( $post->ID, '_doctors_price_from', true );
    $rating     = get_post_meta( $post->ID, '_doctors_rating', true );

    ?>
    <table class="form-table" role="presentation">
        <tr>
            <th scope="row">
                <label for="doctors_experience"><?php esc_html_e( 'Стаж врача (лет)', 'doctors-plugin' ); ?></label>
            </th>
            <td>
                <input type="number"
                       id="doctors_experience"
                       name="doctors_experience"
                       value="<?php echo esc_attr( $experience ); ?>"
                       min="0"
                       max="100"
                       class="regular-text" />
                <p class="description"><?php esc_html_e( 'Введите стаж работы врача в годах', 'doctors-plugin' ); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="doctors_price_from"><?php esc_html_e( 'Цена от (руб)', 'doctors-plugin' ); ?></label>
            </th>
            <td>
                <input type="number"
                       id="doctors_price_from"
                       name="doctors_price_from"
                       value="<?php echo esc_attr( $price_from ); ?>"
                       min="0"
                       class="regular-text" />
                <p class="description"><?php esc_html_e( 'Минимальная цена приема', 'doctors-plugin' ); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="doctors_rating"><?php esc_html_e( 'Рейтинг (0-5)', 'doctors-plugin' ); ?></label>
            </th>
            <td>
                <input type="number"
                       id="doctors_rating"
                       name="doctors_rating"
                       value="<?php echo esc_attr( $rating ); ?>"
                       min="0"
                       max="5"
                       step="0.1"
                       class="regular-text" />
                <p class="description"><?php esc_html_e( 'Рейтинг врача от 0 до 5', 'doctors-plugin' ); ?></p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Save doctor meta box data.
 *
 * @since 1.0.0
 * @param int $post_id The post ID.
 */
function doctors_save_meta_box( $post_id ) {
    if ( ! isset( $_POST['doctors_meta_box_nonce'] ) ) {
        return;
    }

    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['doctors_meta_box_nonce'] ) ), 'doctors_save_meta_box' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['doctors_experience'] ) ) {
        update_post_meta( $post_id, '_doctors_experience', absint( $_POST['doctors_experience'] ) );
    }

    if ( isset( $_POST['doctors_price_from'] ) ) {
        update_post_meta( $post_id, '_doctors_price_from', absint( $_POST['doctors_price_from'] ) );
    }

    if ( isset( $_POST['doctors_rating'] ) ) {
        $rating = floatval( $_POST['doctors_rating'] );
        $rating = min( 5, max( 0, $rating ) );
        update_post_meta( $post_id, '_doctors_rating', $rating );
    }
}

add_action( 'save_post', 'doctors_save_meta_box' );

/**
 * Get doctor experience in years.
 *
 * @since 1.0.0
 * @param int|null $post_id Post ID or null for current post.
 * @return int Experience in years.
 */
function doctors_get_experience( $post_id = null ) {
    if ( null === $post_id ) {
        $post_id = get_the_ID();
    }

    $experience = get_post_meta( $post_id, '_doctors_experience', true );
    if ( empty( $experience ) ) {
        return 0;
    }

    return intval( $experience );
}

/**
 * Get doctor minimum price.
 *
 * @since 1.0.0
 * @param int|null $post_id Post ID or null for current post.
 * @return int Minimum price in rubles.
 */
function doctors_get_price_from( $post_id = null ) {
    if ( null === $post_id ) {
        $post_id = get_the_ID();
    }

    $price = get_post_meta( $post_id, '_doctors_price_from', true );
    if ( empty( $price ) ) {
        return 0;
    }

    return intval( $price );
}

/**
 * Get doctor rating.
 *
 * @since 1.0.0
 * @param int|null $post_id Post ID or null for current post.
 * @return float Rating value (0-5).
 */
function doctors_get_rating( $post_id = null ) {
    if ( null === $post_id ) {
        $post_id = get_the_ID();
    }

    $rating = get_post_meta( $post_id, '_doctors_rating', true );
    if ( empty( $rating ) ) {
        return 0.0;
    }

    return floatval( $rating );
}

/**
 * Display rating as stars.
 *
 * @since 1.0.0
 * @param float $rating Rating value (0-5).
 * @return string HTML output for rating stars.
 */
function doctors_display_rating_stars( $rating ) {
    $rating     = floatval( $rating );
    $full_stars = floor( $rating );
    $half_star  = ( $rating - $full_stars ) >= 0.5;
    $empty      = 5 - $full_stars - ( $half_star ? 1 : 0 );

    $stars  = str_repeat( '<span class="dashicons dashicons-star-filled"></span>', $full_stars );
    $stars .= $half_star ? '<span class="dashicons dashicons-star-half"></span>' : '';
    $stars .= str_repeat( '<span class="dashicons dashicons-star-empty"></span>', $empty );

    return '<div class="doctor-rating">' . $stars . '<span class="rating-value">' . esc_html( number_format( $rating, 1 ) ) . '</span></div>';
}
