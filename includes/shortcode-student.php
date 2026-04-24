<?php
// Đăng ký Shortcode [danh_sach_sinh_vien]
function sm_student_list_shortcode() {
    // Nạp file CSS
    wp_enqueue_style( 'sm-student-style', STUDENT_MANAGER_URL . 'assets/style.css' );

    // Truy vấn dữ liệu
    $args = array(
        'post_type'      => 'student',
        'posts_per_page' => -1, // Lấy toàn bộ sinh viên
        'post_status'    => 'publish'
    );
    $query = new WP_Query( $args );

    ob_start(); // Bắt đầu bộ nhớ đệm output

    if ( $query->have_posts() ) :
        ?>
        <div class="sm-student-wrapper">
            <table class="sm-student-table">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>MSSV</th>
                        <th>Họ tên</th>
                        <th>Lớp</th>
                        <th>Ngày sinh</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stt = 1;
                    while ( $query->have_posts() ) : $query->the_post();
                        $post_id = get_the_ID();
                        $mssv    = get_post_meta( $post_id, '_student_mssv', true );
                        $major   = get_post_meta( $post_id, '_student_major', true );
                        $dob     = get_post_meta( $post_id, '_student_dob', true );
                        
                        // Chuyển đổi định dạng ngày sinh sang DD/MM/YYYY cho thân thiện
                        $dob_formatted = $dob ? date( 'd/m/Y', strtotime( $dob ) ) : '';
                        ?>
                        <tr>
                            <td><?php echo $stt++; ?></td>
                            <td><?php echo esc_html( $mssv ); ?></td>
                            <td><?php the_title(); ?></td>
                            <td><?php echo esc_html( $major ); ?></td>
                            <td><?php echo esc_html( $dob_formatted ); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php
        wp_reset_postdata(); // Khôi phục lại Post Data gốc
    else :
        echo '<p>Hệ thống chưa có dữ liệu sinh viên.</p>';
    endif;

    return ob_get_clean(); // Trả về nội dung HTML
}
add_shortcode( 'danh_sach_sinh_vien', 'sm_student_list_shortcode' );