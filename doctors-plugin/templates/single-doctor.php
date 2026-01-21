<?php
/**
 * Single Doctor Template
 */

get_header();
?>

<div class="doctor-single-container">
    <article id="post-<?php the_ID(); ?>" <?php post_class( 'doctor-single' ); ?>>
        
        <div class="doctor-header">
            <h1 class="doctor-title"><?php the_title(); ?></h1>
            
            <?php
            $experience = doctors_get_experience();
            $rating     = doctors_get_rating();
            $price      = doctors_get_price_from();
            ?>

            <div class="doctor-meta-header">
                <?php if ( $experience > 0 ) : ?>
                    <span class="doctor-experience">
                        <span class="dashicons dashicons-clock"></span>
                        <?php echo esc_html( sprintf( _n( '%d год', '%d лет', $experience, 'doctors-plugin' ), $experience ) ); ?>
                    </span>
                <?php endif; ?>

                <?php if ( $rating > 0 ) : ?>
                    <span class="doctor-rating-display">
                        <?php echo doctors_display_rating_stars( $rating ); ?>
                    </span>
                <?php endif; ?>

                <?php if ( $price > 0 ) : ?>
                    <span class="doctor-price">
                        <span class="dashicons dashicons-money-alt"></span>
                        <?php echo esc_html( sprintf( __( 'От %d руб.', 'doctors-plugin' ), number_format( $price, 0, '', ' ' ) ) ); ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <div class="doctor-content-wrapper">
            <?php if ( has_post_thumbnail() ) : ?>
                <div class="doctor-thumbnail">
                    <?php the_post_thumbnail( 'medium_large', array( 'alt' => get_the_title() ) ); ?>
                </div>
            <?php endif; ?>

            <div class="doctor-content">
                <?php if ( has_excerpt() ) : ?>
                    <div class="doctor-excerpt">
                        <h3><?php esc_html_e( 'Краткое описание', 'doctors-plugin' ); ?></h3>
                        <?php the_excerpt(); ?>
                    </div>
                <?php endif; ?>

                <div class="doctor-bio">
                    <h3><?php esc_html_e( 'О враче', 'doctors-plugin' ); ?></h3>
                    <?php the_content(); ?>
                </div>
            </div>
        </div>

        <div class="doctor-taxonomies">
            <div class="doctor-specializations">
                <h3><?php esc_html_e( 'Специализации', 'doctors-plugin' ); ?></h3>
                <?php
                $specializations = get_the_terms( get_the_ID(), 'specialization' );
                if ( ! empty( $specializations ) && ! is_wp_error( $specializations ) ) :
                    $spec_links = array();
                    foreach ( $specializations as $spec ) {
                        $spec_links[] = sprintf(
                            '<a href="%s" class="doctor-term-link">%s</a>',
                            esc_url( get_term_link( $spec ) ),
                            esc_html( $spec->name )
                        );
                    }
                    echo wp_kses_post( implode( ', ', $spec_links ) );
                else :
                    esc_html_e( 'Не указаны', 'doctors-plugin' );
                endif;
                ?>
            </div>

            <div class="doctor-cities">
                <h3><?php esc_html_e( 'Города', 'doctors-plugin' ); ?></h3>
                <?php
                $cities = get_the_terms( get_the_ID(), 'city' );
                if ( ! empty( $cities ) && ! is_wp_error( $cities ) ) :
                    $city_links = array();
                    foreach ( $cities as $city ) {
                        $city_links[] = sprintf(
                            '<a href="%s" class="doctor-term-link">%s</a>',
                            esc_url( get_term_link( $city ) ),
                            esc_html( $city->name )
                        );
                    }
                    echo wp_kses_post( implode( ', ', $city_links ) );
                else :
                    esc_html_e( 'Не указан', 'doctors-plugin' );
                endif;
                ?>
            </div>
        </div>

        <div class="doctor-archive-link">
            <a href="<?php echo esc_url( get_post_type_archive_link( 'doctors' ) ); ?>" class="button button-primary">
                <span class="dashicons dashicons-arrow-left-alt2"></span>
                <?php esc_html_e( 'Все врачи', 'doctors-plugin' ); ?>
            </a>
        </div>

    </article>
</div>

<?php
get_footer();
