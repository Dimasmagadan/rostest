<?php
/**
 * Archive Filtering for Doctors CPT
 * Uses pre_get_posts for efficient filtering
 * GET parameters: specialization, city, sort
 */

function doctors_archive_filters( $query ) {
    if ( ! is_admin() && $query->is_main_query() && is_post_type_archive( 'doctors' ) ) {

        $meta_query = array();

        $specialization = isset( $_GET['specialization'] ) ? sanitize_text_field( wp_unslash( $_GET['specialization'] ) ) : '';
        $city           = isset( $_GET['city'] ) ? sanitize_text_field( wp_unslash( $_GET['city'] ) ) : '';
        $sort           = isset( $_GET['sort'] ) ? sanitize_text_field( wp_unslash( $_GET['sort'] ) ) : '';

        if ( ! empty( $specialization ) ) {
            $tax_query = array(
                'taxonomy' => 'specialization',
                'field'    => 'slug',
                'terms'    => $specialization,
            );
            $query->set( 'tax_query', array( $tax_query ) );
        }

        if ( ! empty( $city ) ) {
            $tax_query = array(
                'taxonomy' => 'city',
                'field'    => 'slug',
                'terms'    => $city,
            );
            $query->set( 'tax_query', array( $tax_query ) );
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
}

add_action( 'pre_get_posts', 'doctors_archive_filters' );

function doctors_get_filtered_posts() {
    $args = array(
        'post_type'      => 'doctors',
        'posts_per_page' => 9,
        'post_status'    => 'publish',
    );

    $specialization = isset( $_GET['specialization'] ) ? sanitize_text_field( wp_unslash( $_GET['specialization'] ) ) : '';
    $city           = isset( $_GET['city'] ) ? sanitize_text_field( wp_unslash( $_GET['city'] ) ) : '';
    $sort           = isset( $_GET['sort'] ) ? sanitize_text_field( wp_unslash( $_GET['sort'] ) ) : '';
    $paged          = isset( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 1;

    if ( ! empty( $specialization ) ) {
        $args['tax_query'][] = array(
            'taxonomy' => 'specialization',
            'field'    => 'slug',
            'terms'    => $specialization,
        );
    }

    if ( ! empty( $city ) ) {
        $args['tax_query'][] = array(
            'taxonomy' => 'city',
            'field'    => 'slug',
            'terms'    => $city,
        );
    }

    switch ( $sort ) {
        case 'rating_desc':
            $args['meta_key'] = '_doctors_rating';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'DESC';
            break;
        case 'price_asc':
            $args['meta_key'] = '_doctors_price_from';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'ASC';
            break;
        case 'experience_desc':
            $args['meta_key'] = '_doctors_experience';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'DESC';
            break;
        default:
            $args['orderby'] = 'date';
            $args['order']   = 'DESC';
            break;
    }

    $args['paged'] = $paged;

    return new WP_Query( $args );
}

function doctors_pagination( $query = null, $base_url = '' ) {
    if ( empty( $query ) ) {
        global $wp_query;
        $query = $wp_query;
    }

    if ( $query->max_num_pages <= 1 ) {
        return;
    }

    $current_page = max( 1, get_query_var( 'paged' ) );
    $total_pages  = $query->max_num_pages;

    $base_url = empty( $base_url ) ? get_permalink( get_option( 'page_for_posts' ) ) : $base_url;

    $get_params = $_GET;
    unset( $get_params['paged'] );

    $query_string = http_build_query( $get_params );
    if ( ! empty( $query_string ) ) {
        $base_url .= '?' . $query_string . '&';
    } else {
        $base_url .= '?';
    }

    echo '<nav class="doctors-pagination" aria-label="Pagination">';
    echo '<ul class="page-numbers">';

    if ( $current_page > 1 ) {
        echo '<li><a class="prev page-numbers" href="' . esc_url( $base_url . 'paged=' . ( $current_page - 1 ) ) . '">&laquo;</a></li>';
    }

    for ( $i = 1; $i <= $total_pages; $i++ ) {
        if ( $i == $current_page ) {
            echo '<li><span class="page-numbers current">' . esc_html( $i ) . '</span></li>';
        } else {
            echo '<li><a class="page-numbers" href="' . esc_url( $base_url . 'paged=' . $i ) . '">' . esc_html( $i ) . '</a></li>';
        }
    }

    if ( $current_page < $total_pages ) {
        echo '<li><a class="next page-numbers" href="' . esc_url( $base_url . 'paged=' . ( $current_page + 1 ) ) . '">&raquo;</a></li>';
    }

    echo '</ul>';
    echo '</nav>';
}

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

function doctors_render_filters() {
    $current_specialization = isset( $_GET['specialization'] ) ? sanitize_text_field( wp_unslash( $_GET['specialization'] ) ) : '';
    $current_city           = isset( $_GET['city'] ) ? sanitize_text_field( wp_unslash( $_GET['city'] ) ) : '';
    $current_sort           = isset( $_GET['sort'] ) ? sanitize_text_field( wp_unslash( $_GET['sort'] ) ) : '';

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
