<?php
/**
 * Doctors Plugin - Archive Filtering
 *
 * Uses pre_get_posts for efficient filtering.
 * GET parameters: specialization, city, sort
 *
 * @package DoctorsPlugin
 * @since 1.0.0
 */

/**
 * Modify main query for doctors archive.
 *
 * @since 1.0.0
 * @param WP_Query $query The main query object.
 */
function doctors_archive_filters( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    // Check if on doctors archive OR on taxonomy archives for doctors
    $is_doctors_archive = is_post_type_archive( 'doctors' );
    $is_specialization_archive = is_tax( 'specialization' );
    $is_city_archive = is_tax( 'city' );

    if ( ! $is_doctors_archive && ! $is_specialization_archive && ! $is_city_archive ) {
        return;
    }

    $specialization = isset( $_GET['specialization'] ) ? sanitize_text_field( wp_unslash( $_GET['specialization'] ) ) : '';
    $city           = isset( $_GET['city'] ) ? sanitize_text_field( wp_unslash( $_GET['city'] ) ) : '';
    $sort           = isset( $_GET['sort'] ) ? sanitize_text_field( wp_unslash( $_GET['sort'] ) ) : '';

    // Get current term from taxonomy archive (if on one)
    $tax_query = array();

    if ( $is_specialization_archive && empty( $specialization ) ) {
        $current_spec = get_queried_object();
        if ( $current_spec && ! is_wp_error( $current_spec ) ) {
            $specialization = $current_spec->slug;
        }
    }

    if ( $is_city_archive && empty( $city ) ) {
        $current_city = get_queried_object();
        if ( $current_city && ! is_wp_error( $current_city ) ) {
            $city = $current_city->slug;
        }
    }

    if ( ! empty( $specialization ) ) {
        $tax_query[] = array(
            'taxonomy' => 'specialization',
            'field'    => 'slug',
            'terms'    => $specialization,
        );
    }

    if ( ! empty( $city ) ) {
        $tax_query[] = array(
            'taxonomy' => 'city',
            'field'    => 'slug',
            'terms'    => $city,
        );
    }

    if ( ! empty( $tax_query ) ) {
        $query->set( 'tax_query', $tax_query );
    }

    $allowed_sorts = array( 'rating_desc', 'price_asc', 'experience_desc' );
    if ( ! in_array( $sort, $allowed_sorts, true ) ) {
        $sort = '';
    }

    switch ( $sort ) {
        case 'rating_desc':
            $query->set( 'meta_key', '_doctors_rating' );
            $query->set( 'orderby', 'meta_value_num' );
            $query->set( 'order', 'DESC' );
            break;
        case 'price_asc':
            $query->set( 'meta_key', '_doctors_price_from' );
            $query->set( 'orderby', 'meta_value_num' );
            $query->set( 'order', 'ASC' );
            break;
        case 'experience_desc':
            $query->set( 'meta_key', '_doctors_experience' );
            $query->set( 'orderby', 'meta_value_num' );
            $query->set( 'order', 'DESC' );
            break;
        default:
            $query->set( 'orderby', 'date' );
            $query->set( 'order', 'DESC' );
            break;
    }

    $query->set( 'posts_per_page', 9 );
}

add_action( 'pre_get_posts', 'doctors_archive_filters' );

/**
 * Display pagination for doctors archive.
 *
 * @since 1.0.0
 * @param string $base_url The base URL for pagination links.
 */
function doctors_pagination( $base_url = '' ) {
    global $wp_query;

    if ( $wp_query->max_num_pages <= 1 ) {
        return;
    }

    $current_page = max( 1, get_query_var( 'paged' ) );
    $total_pages  = $wp_query->max_num_pages;

    // Use CPT archive URL as base
    if ( empty( $base_url ) ) {
        $base_url = get_post_type_archive_link( 'doctors' );
    }

    // Get current GET params (filters), sanitize and filter
    $get_params = array_filter(
        isset( $_GET ) ? $_GET : array(),
        function( $key ) {
            return 'paged' !== $key;
        },
        ARRAY_FILTER_USE_KEY
    );

    // Sanitize values
    $get_params = array_map( 'sanitize_text_field', $get_params );

    // Build pagination base URL
    if ( ! empty( $get_params ) ) {
        $base_url = add_query_arg( $get_params, $base_url );
    }

    echo '<nav class="doctors-pagination" aria-label="Pagination">';
    echo '<ul class="page-numbers">';

    if ( $current_page > 1 ) {
        $prev_url = add_query_arg( 'paged', $current_page - 1, $base_url );
        echo '<li><a class="prev page-numbers" href="' . esc_url( $prev_url ) . '">&laquo;</a></li>';
    }

    for ( $i = 1; $i <= $total_pages; $i++ ) {
        $page_url = add_query_arg( 'paged', $i, $base_url );
        if ( $i == $current_page ) {
            echo '<li><span class="page-numbers current">' . esc_html( $i ) . '</span></li>';
        } else {
            echo '<li><a class="page-numbers" href="' . esc_url( $page_url ) . '">' . esc_html( $i ) . '</a></li>';
        }
    }

    if ( $current_page < $total_pages ) {
        $next_url = add_query_arg( 'paged', $current_page + 1, $base_url );
        echo '<li><a class="next page-numbers" href="' . esc_url( $next_url ) . '">&raquo;</a></li>';
    }

    echo '</ul>';
    echo '</nav>';
}

/**
 * Get all specializations.
 *
 * @since 1.0.0
 * @return array Array of WP_Term objects.
 */
function doctors_get_specializations() {
    $terms = get_terms( array(
        'taxonomy'   => 'specialization',
        'hide_empty' => false,
    ) );

    if ( is_wp_error( $terms ) ) {
        return array();
    }

    return $terms;
}

/**
 * Get all cities.
 *
 * @since 1.0.0
 * @return array Array of WP_Term objects.
 */
function doctors_get_cities() {
    $terms = get_terms( array(
        'taxonomy'   => 'city',
        'hide_empty' => false,
    ) );

    if ( is_wp_error( $terms ) ) {
        return array();
    }

    return $terms;
}

/**
 * Render filter form for doctors archive.
 *
 * @since 1.0.0
 */
function doctors_render_filters() {
    // First, check GET parameters (form submission)
    $current_specialization = isset( $_GET['specialization'] ) ? sanitize_text_field( wp_unslash( $_GET['specialization'] ) ) : '';
    $current_city           = isset( $_GET['city'] ) ? sanitize_text_field( wp_unslash( $_GET['city'] ) ) : '';
    $current_sort           = isset( $_GET['sort'] ) ? sanitize_text_field( wp_unslash( $_GET['sort'] ) ) : '';

    // If on taxonomy archive, get current term (overrides GET if set)
    if ( is_tax( 'specialization' ) ) {
        $current_spec_term = get_queried_object();
        if ( $current_spec_term && ! is_wp_error( $current_spec_term ) ) {
            $current_specialization = $current_spec_term->slug;
        }
    }

    if ( is_tax( 'city' ) ) {
        $current_city_term = get_queried_object();
        if ( $current_city_term && ! is_wp_error( $current_city_term ) ) {
            $current_city = $current_city_term->slug;
        }
    }

    // Get all terms (no dynamic filtering)
    $specializations = doctors_get_specializations();
    $cities          = doctors_get_cities();

    $base_url = get_post_type_archive_link( 'doctors' );

    ?>
    <form class="doctors-filters" method="get" action="<?php echo esc_url( $base_url ); ?>">
        <div class="filter-group">
            <label for="filter-specialization"><?php esc_html_e( 'Специализация', 'doctors-plugin' ); ?></label>
            <select id="filter-specialization" name="specialization">
                <option value=""><?php esc_html_e( 'Все специализации', 'doctors-plugin' ); ?></option>
                <?php foreach ( $specializations as $spec ) : ?>
                    <option value="<?php echo esc_attr( $spec->slug ); ?>" <?php selected( $current_specialization, $spec->slug ); ?>>
                        <?php echo esc_html( $spec->name ); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filter-group">
            <label for="filter-city"><?php esc_html_e( 'Город', 'doctors-plugin' ); ?></label>
            <select id="filter-city" name="city">
                <option value=""><?php esc_html_e( 'Все города', 'doctors-plugin' ); ?></option>
                <?php foreach ( $cities as $city ) : ?>
                    <option value="<?php echo esc_attr( $city->slug ); ?>" <?php selected( $current_city, $city->slug ); ?>>
                        <?php echo esc_html( $city->name ); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filter-group">
            <label for="filter-sort"><?php esc_html_e( 'Сортировка', 'doctors-plugin' ); ?></label>
            <select id="filter-sort" name="sort">
                <option value=""><?php esc_html_e( 'По умолчанию', 'doctors-plugin' ); ?></option>
                <option value="rating_desc" <?php selected( $current_sort, 'rating_desc' ); ?>>
                    <?php esc_html_e( 'По рейтингу (убыв.)', 'doctors-plugin' ); ?>
                </option>
                <option value="price_asc" <?php selected( $current_sort, 'price_asc' ); ?>>
                    <?php esc_html_e( 'По цене (возр.)', 'doctors-plugin' ); ?>
                </option>
                <option value="experience_desc" <?php selected( $current_sort, 'experience_desc' ); ?>>
                    <?php esc_html_e( 'По стажу (убыв.)', 'doctors-plugin' ); ?>
                </option>
            </select>
        </div>

        <div class="filter-group filter-submit">
            <button type="submit" class="button button-primary">
                <?php esc_html_e( 'Применить', 'doctors-plugin' ); ?>
            </button>
        </div>
    </form>
    <?php
}
