<?php
// print_r($menus);
// die;
// function buildMenu($menus, $parent_id = null) {
//     $html = '';
//     $subMenu = array_filter($menus, function($menu) use ($parent_id) {
//         return $menu['parent_id'] == $parent_id;
//     });

//     if (!empty($subMenu)) {
//         $html .= $parent_id ? '<ul class="nav nav-treeview">' : '<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">';
//         foreach ($subMenu as $menu) {
//             $hasChild = !empty(array_filter($menus, function($m) use ($menu) { return $m['parent_id'] == $menu['menu_id']; }));
//             $active = (current_url() == base_url($menu['menu_url'])) ? 'active' : '';
//             $html .= '<li class="nav-item' . ($hasChild ? ' has-treeview' : '') . '">';
//             $html .= '<a href="' . base_url($menu['menu_url']) . '" class="nav-link ' . $active . '">';
//             $html .= '<i class="nav-icon ' . $menu['menu_icon'] . '"></i>';
//             $html .= '<p>' . $menu['menu_name'];
//             if ($hasChild) {
//                 $html .= '<i class="right fas fa-angle-left"></i>';
//             }
//             $html .= '</p></a>';
//             $html .= buildMenu($menus, $menu['menu_id']);
//             $html .= '</li>';
//         }
//         $html .= '</ul>';
//     }
//     return $html;
// }
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="<?php echo base_url('assets/backend/adminlte/dist/img/AdminLTELogo.png'); ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?php echo base_url('assets/backend/adminlte/dist/img/user2-160x160.jpg'); ?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Alexander Pierce</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <?php
                function render_menu($menus, $current_url) {
                    foreach ($menus as $menu) {
                        $is_active = ($current_url == $menu->menu_url) ? 'active' : '';
                        $has_children = !empty($menu->children);
                        $open_class = $has_children && in_array($current_url, array_column($menu->children, 'menu_url')) ? 'menu-open' : '';
                        $icon_class = $has_children ? 'right fas fa-angle-left' : '';

                        echo '<li class="nav-item has-treeview ' . $open_class . ' ' . $is_active . '">';
                        echo '<a href="' . base_url($menu->menu_url) . '" class="nav-link ' . $is_active . '">';
                        echo '<i class="nav-icon fas fa-' . $menu->menu_icon . '"></i>';
                        echo '<p>' . $menu->menu_name . ($has_children ? '<i class="' . $icon_class . '"></i>' : '') . '</p>';
                        echo '</a>';

                        if ($has_children) {
                            echo '<ul class="nav nav-treeview">';
                            render_menu($menu->children, $current_url);
                            echo '</ul>';
                        }

                        echo '</li>';
                    }
                }

                $current_url = uri_string();
                render_menu($menus, $current_url);
                ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
