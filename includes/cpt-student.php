<?php
// 1. Đăng ký Custom Post Type "Sinh viên"
function sm_register_student_cpt() {
    $labels = array(
        'name'               => 'Sinh viên',
        'singular_name'      => 'Sinh viên',
        'menu_name'          => 'Sinh viên',
        'add_new'            => 'Thêm mới',
        'add_new_item'       => 'Thêm Sinh viên mới',
        'edit_item'          => 'Sửa thông tin',
        'new_item'           => 'Sinh viên mới',
        'view_item'          => 'Xem Sinh viên',
        'search_items'       => 'Tìm kiếm',
        'not_found'          => 'Không tìm thấy sinh viên nào',
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
        'menu_icon'           => 'dashicons-welcome-learn-more',
        'supports'            => array( 'title', 'editor' ), // title: Họ tên, editor: Tiểu sử/Ghi chú
    );

    register_post_type( 'student', $args );
}
add_action( 'init', 'sm_register_student_cpt' );

// 2. Tạo Custom Meta Box
function sm_add_student_meta_boxes() {
    add_meta_box(
        'student_details',
        'Thông tin chi tiết Sinh viên',
        'sm_render_student_meta_box',
        'student', // Gắn vào CPT student
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'sm_add_student_meta_boxes' );

// 3. Giao diện nhập liệu Meta Box
function sm_render_student_meta_box( $post ) {
    // Tạo Nonce để bảo mật
    wp_nonce_field( 'sm_save_student_data', 'sm_student_meta_nonce' );

    // Lấy dữ liệu đã lưu (nếu có)
    $mssv  = get_post_meta( $post->ID, '_student_mssv', true );
    $major = get_post_meta( $post->ID, '_student_major', true );
    $dob   = get_post_meta( $post->ID, '_student_dob', true );
    ?>
    <p>
        <label for="student_mssv"><strong>Mã số sinh viên (MSSV):</strong></label><br />
        <input type="text" id="student_mssv" name="student_mssv" value="<?php echo esc_attr( $mssv ); ?>" style="width:100%;" required />
    </p>
    <p>
        <label for="student_major"><strong>Lớp/Chuyên ngành:</strong></label><br />
        <select id="student_major" name="student_major" style="width:100%;">
            <option value="CNTT" <?php selected( $major, 'CNTT' ); ?>>Công nghệ thông tin</option>
            <option value="Kinh tế" <?php selected( $major, 'Kinh tế' ); ?>>Kinh tế</option>
            <option value="Marketing" <?php selected( $major, 'Marketing' ); ?>>Marketing</option>
        </select>
    </p>
    <p>
        <label for="student_dob"><strong>Ngày sinh:</strong></label><br />
        <input type="date" id="student_dob" name="student_dob" value="<?php echo esc_attr( $dob ); ?>" style="width:100%;" />
    </p>
    <?php
}

// 4. Xử lý lưu dữ liệu (Validate & Sanitize)
function sm_save_student_meta_data( $post_id ) {
    // Kiểm tra Nonce
    if ( ! isset( $_POST['sm_student_meta_nonce'] ) || ! wp_verify_nonce( $_POST['sm_student_meta_nonce'], 'sm_save_student_data' ) ) {
        return;
    }

    // Bỏ qua nếu WordPress đang auto-save
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Kiểm tra quyền của người dùng
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Sanitize và cập nhật dữ liệu vào Database
    if ( isset( $_POST['student_mssv'] ) ) {
        update_post_meta( $post_id, '_student_mssv', sanitize_text_field( wp_unslash( $_POST['student_mssv'] ) ) );
    }
    if ( isset( $_POST['student_major'] ) ) {
        update_post_meta( $post_id, '_student_major', sanitize_text_field( wp_unslash( $_POST['student_major'] ) ) );
    }
    if ( isset( $_POST['student_dob'] ) ) {
        update_post_meta( $post_id, '_student_dob', sanitize_text_field( wp_unslash( $_POST['student_dob'] ) ) );
    }
}
add_action( 'save_post_student', 'sm_save_student_meta_data' );