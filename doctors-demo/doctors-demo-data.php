<?php
/**
 * Doctors Plugin - Demo Data Setup
 *
 * Automatically creates demo data on first run.
 * Checks if data already exists before creating.
 * Remove or disable this file after setup.
 *
 * @package DoctorsPlugin
 * @since 1.0.0
 */

/**
 * Create demo data if it doesn't exist.
 *
 * @since 1.0.0
 */
function doctors_create_demo_data() {
    // Check if demo data already exists
    $existing_count = wp_count_posts( 'doctors' );

    if ( ! $existing_count || empty( $existing_count->publish ) ) {
        // No doctors exist, create demo data
        doctors_insert_demo_specializations();
        doctors_insert_demo_cities();
        doctors_insert_demo_doctors();

        // Show admin notice
        add_action( 'admin_notices', 'doctors_demo_data_notice' );
    }
}

/**
 * Insert demo specializations.
 *
 * @since 1.0.0
 */
function doctors_insert_demo_specializations() {
    $specializations = array( 'Терапевт', 'Кардиолог', 'Невролог', 'Дерматолог', 'Педиатр' );

    foreach ( $specializations as $spec ) {
        if ( ! term_exists( $spec, 'specialization' ) ) {
            wp_insert_term( $spec, 'specialization' );
        }
    }
}

/**
 * Insert demo cities.
 *
 * @since 1.0.0
 */
function doctors_insert_demo_cities() {
    $cities = array( 'Москва', 'Санкт-Петербург', 'Новосибирск', 'Екатеринбург' );

    foreach ( $cities as $city ) {
        if ( ! term_exists( $city, 'city' ) ) {
            wp_insert_term( $city, 'city' );
        }
    }
}

/**
 * Insert demo doctors.
 *
 * @since 1.0.0
 */
function doctors_insert_demo_doctors() {
    $doctors = array(
        array(
            'name'       => 'Иванов Иван Иванович',
            'spec'       => 'Кардиолог',
            'city'       => 'Москва',
            'experience' => 15,
            'price'      => 2500,
            'rating'     => 4.8,
            'excerpt'    => 'Опытный кардиолог с 15-летним стажем работы.',
            'content'    => 'Закончил МГУ им. Ломоносова. Специализируется на лечении заболеваний сердечно-сосудистой системы.',
        ),
        array(
            'name'       => 'Петрова Анна Сергеевна',
            'spec'       => 'Терапевт',
            'city'       => 'Москва',
            'experience' => 8,
            'price'      => 1500,
            'rating'     => 4.5,
            'excerpt'    => 'Врач-терапевт широкого профиля.',
            'content'    => 'Опыт работы в городской поликлинике. Занимается диагностикой и лечением широкого спектра заболеваний.',
        ),
        array(
            'name'       => 'Сидоров Алексей Петрович',
            'spec'       => 'Невролог',
            'city'       => 'Санкт-Петербург',
            'experience' => 20,
            'price'      => 3000,
            'rating'     => 4.9,
            'excerpt'    => 'Врач-невролог высшей категории.',
            'content'    => 'Доктор медицинских наук. Специализируется на лечении заболеваний нервной системы.',
        ),
        array(
            'name'       => 'Козлова Елена Владимировна',
            'spec'       => 'Дерматолог',
            'city'       => 'Новосибирск',
            'experience' => 12,
            'price'      => 1800,
            'rating'     => 4.6,
            'excerpt'    => 'Дерматолог-косметолог.',
            'content'    => 'Занимается диагностикой и лечением кожных заболеваний, проводит косметологические процедуры.',
        ),
        array(
            'name'       => 'Смирнов Дмитрий Николаевич',
            'spec'       => 'Педиатр',
            'city'       => 'Екатеринбург',
            'experience' => 10,
            'price'      => 2000,
            'rating'     => 4.7,
            'excerpt'    => 'Педиатр с опытом работы в крупных клиниках.',
            'content'    => 'Специализируется на наблюдении за здоровьем детей от рождения до 18 лет.',
        ),
        array(
            'name'       => 'Морозова Ольга Игоревна',
            'spec'       => 'Кардиолог',
            'city'       => 'Санкт-Петербург',
            'experience' => 18,
            'price'      => 2800,
            'rating'     => 4.4,
            'excerpt'    => 'Кардиолог, специалист по ЭКГ.',
            'content'    => 'Проводит расшифровку ЭКГ, холтеровское мониторирование, консультирует пациентов с заболеваниями сердца.',
        ),
        array(
            'name'       => 'Волков Андрей Михайлович',
            'spec'       => 'Терапевт',
            'city'       => 'Новосибирск',
            'experience' => 5,
            'price'      => 1200,
            'rating'     => 4.2,
            'excerpt'    => 'Молодой специалист с современным подходом.',
            'content'    => 'Закончил медицинский университет с отличием. Применяет современные методы диагностики.',
        ),
        array(
            'name'       => 'Николаева Мария Павловна',
            'spec'       => 'Дерматолог',
            'city'       => 'Москва',
            'experience' => 25,
            'price'      => 3500,
            'rating'     => 4.9,
            'excerpt'    => 'Дерматолог-трихолог, эксперт.',
            'content'    => 'Специализируется на лечении заболеваний волос и кожи головы. Автор научных публикаций.',
        ),
        array(
            'name'       => 'Лебедев Сергей Викторович',
            'spec'       => 'Невролог',
            'city'       => 'Екатеринбург',
            'experience' => 14,
            'price'      => 2200,
            'rating'     => 4.5,
            'excerpt'    => 'Невролог, специалист по головным болям.',
            'content'    => 'Занимается диагностикой и лечением головных болей, мигрени, заболеваний позвоночника.',
        ),
        array(
            'name'       => 'Кузьмина Татьяна Александровна',
            'spec'       => 'Педиатр',
            'city'       => 'Москва',
            'experience' => 7,
            'price'      => 1800,
            'rating'     => 4.6,
            'excerpt'    => 'Педиатр-неонатолог.',
            'content'    => 'Специализируется на уходе за новорожденными, консультирует по вопросам грудного вскармливания.',
        ),
    );

    foreach ( $doctors as $doctor ) {
        $spec_term = get_term_by( 'name', $doctor['spec'], 'specialization' );
        $city_term = get_term_by( 'name', $doctor['city'], 'city' );

        $post_data = array(
            'post_title'   => $doctor['name'],
            'post_content' => $doctor['content'],
            'post_excerpt' => $doctor['excerpt'],
            'post_status'  => 'publish',
            'post_type'    => 'doctors',
        );

        $post_id = wp_insert_post( $post_data );

        if ( $post_id && $spec_term ) {
            wp_set_object_terms( $post_id, $spec_term->term_id, 'specialization' );
        }
        if ( $post_id && $city_term ) {
            wp_set_object_terms( $post_id, $city_term->term_id, 'city' );
        }

        if ( $post_id ) {
            update_post_meta( $post_id, '_doctors_experience', $doctor['experience'] );
            update_post_meta( $post_id, '_doctors_price_from', $doctor['price'] );
            update_post_meta( $post_id, '_doctors_rating', $doctor['rating'] );
        }
    }
}

/**
 * Show admin notice when demo data is created.
 *
 * @since 1.0.0
 */
function doctors_demo_data_notice() {
    ?>
    <div class="notice notice-success is-dismissible">
        <p>
            <strong>Doctors Plugin:</strong>
            <?php esc_html_e( 'Демо-данные успешно созданы! Проверьте страницу', 'doctors-plugin' ); ?>
            <a href="<?php echo esc_url( home_url( '/doctors/' ) ); ?>">/doctors/</a>
        </p>
        <p>
            <em>
                <?php esc_html_e( 'Для отключения автоматического создания данных удалите файл', 'doctors-plugin' ); ?>
                <code>mu-plugins/doctors-demo-data.php</code>
            </em>
        </p>
    </div>
    <?php
}

// Run on init, priority 20 to ensure CPT is registered
add_action( 'init', 'doctors_create_demo_data', 20 );
