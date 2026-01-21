<?php
/**
 * Archive Doctors Template
 *
 * Uses standard WordPress loop with pre_get_posts filtering.
 *
 * @package DoctorsPlugin
 * @since 1.0.0
 */

get_header();
?>

<div class="doctors-archive-container">
    <header class="doctors-archive-header">
        <h1 class="page-title"><?php post_type_archive_title( '', false ); ?></h1>
    </header>

    <?php doctors_render_filters(); ?>

    <div class="doctors-archive-content">
        <?php if ( have_posts() ) : ?>
            <div class="doctors-grid">
                <?php
                while ( have_posts() ) :
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'doctor-card' ); ?>>
                        <div class="doctor-card-inner">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="doctor-card-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail( 'medium', array( 'alt' => get_the_title() ) ); ?>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <div class="doctor-card-content">
                                <h2 class="doctor-card-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>

                                <?php
                                $specializations = get_the_terms( get_the_ID(), 'specialization' );
                                if ( ! empty( $specializations ) && ! is_wp_error( $specializations ) ) :
                                    $spec_count = 0;
                                    $spec_links = array();
                                    foreach ( $specializations as $spec ) {
                                        if ( $spec_count < 2 ) {
                                            $spec_links[] = sprintf(
                                                '<span class="doctor-spec">%s</span>',
                                                esc_html( $spec->name )
                                            );
                                            $spec_count++;
                                        }
                                    }
                                    ?>
                                    <div class="doctor-card-specializations">
                                        <?php echo wp_kses_post( implode( ', ', $spec_links ) ); ?>
                                    </div>
                                <?php endif; ?>

                                <?php
                                $experience = doctors_get_experience();
                                if ( $experience > 0 ) :
                                    ?>
                                    <div class="doctor-card-experience">
                                        <span class="dashicons dashicons-clock"></span>
                                        <?php echo esc_html( sprintf( _n( '%d год', '%d лет', $experience, 'doctors-plugin' ), $experience ) ); ?>
                                    </div>
                                <?php endif; ?>

                                <?php
                                $price = doctors_get_price_from();
                                if ( $price > 0 ) :
                                    ?>
                                    <div class="doctor-card-price">
                                        <span class="dashicons dashicons-money-alt"></span>
                                        <?php echo esc_html( sprintf( __( 'От %d руб.', 'doctors-plugin' ), number_format( $price, 0, '', ' ' ) ) ); ?>
                                    </div>
                                <?php endif; ?>

                                <?php
                                $rating = doctors_get_rating();
                                if ( $rating > 0 ) :
                                    ?>
                                    <div class="doctor-card-rating">
                                        <?php echo doctors_display_rating_stars( $rating ); ?>
                                    </div>
                                <?php endif; ?>

                                <a href="<?php the_permalink(); ?>" class="doctor-card-link">
                                    <?php esc_html_e( 'Подробнее', 'doctors-plugin' ); ?>
                                </a>
                            </div>
                        </div>
                    </article>
                    <?php
                endwhile;
                ?>
            </div>

            <?php doctors_pagination(); ?>

        <?php else : ?>
            <div class="no-doctors-found">
                <p><?php esc_html_e( 'Врачи не найдены.', 'doctors-plugin' ); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
get_footer();
