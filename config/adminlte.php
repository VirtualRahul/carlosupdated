<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => 'PinkBear',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => '<b>Pink</b>Bear',
    'logo_img' => 'pinkbear/logo.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'PinkBear',

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => 'admin/home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For detailed instructions you can look the laravel mix section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'menu' => [

        // ['header' => 'Reports'],
        // [
        //     'text' => 'Reports',
        //     'title' => 'Reports',
        //     'icon' => 'fas fa-fw fa-list-alt',
        //     'roles' => [
        //         'Admin', 'Customer Care', 'Super Admin'
        //     ],
        //     'submenu' => [
        //         [
        //             'text' => 'Order Report',
        //             'title' => 'Order Report',
        //             'url'  => 'order/report',
        //             'icon' => 'fas fa-fw fa-plus',
        //         ],
        //         [
        //             'text' => 'UC Log',
        //             'title' => 'UC Log',
        //             'url'  => 'report/uc-log',
        //             'icon' => 'fas fa-fw fa-file'
        //         ]


        //     ]
        // ],



            ['header' => 'Category'],        
            [
                'text' => 'Category',
                'title' => 'Category',
                'icon' => 'fas fa-fw fa-list-alt',
                'roles' => [
                    'Admin',
                ],
                'submenu' => [
                    [
                        'text' => 'Add',
                        'title' => 'Category Add',
                        'url' => 'admin/catalogue/category/add-category',
                        'icon' => 'fas fa-fw fa-plus',
                    ],
                    [
                        'text' => 'View',
                        'title' => 'Category View',
                        'url' => 'admin/catalogue/category/',
                        'icon' => 'fas fa-fw fa-list-alt'
                    ]
                ]
            ],


        // ['header' => 'Order'],
        // [
        //     'text' => 'Order List',
        //     'title' => 'Order List',
        //     'url'  => 'order/order-list',
        //     'icon' => 'fas fa-fw fa-list-alt ',
        //     'roles' => [
        //         'Admin', 'Customer Care'
        //     ],
        // ],
        ['header' => 'Catalogue', 'roles' => [
            'Admin',
        ],],
        // [
        //     'text' => 'Bulk Catalogue',
        //     'title' => 'Bulk Catalogue',
        //     'icon' => 'fas fa-fw fa-list-alt',
        //     'roles' => [
        //         'Admin',
        //     ],
        //     'submenu' => [
        //         [
        //             'text' => 'Bulk Catalogue',
        //             'title' => 'Bulk Create Catalogue',
        //             'url' => 'catalogue/bulk/create-bulk-catalogue',
        //             'icon' => 'fas fa-fw fa-plus',
        //         ],
        //         [
        //             'text' => 'Bulk Catalogue Report',
        //             'title' => 'Bulk Catalogue Report',
        //             'url' => 'catalogue/bulk/report',
        //             'icon' => 'fas fa-fw fa-list-alt'
        //         ]
        //     ]
        // ],
     
       
        [
            'text' => 'Bands',
            'title' => 'Bands',
            'icon' => 'fas fa-fw fa-list-alt',
            'roles' => [
                'Admin',
            ],
            'submenu' => [
                [
                    'text' => 'Add',
                    'title' => 'Bands Add',
                    'url' => 'admin/catalogue/band/add-band',
                    'icon' => 'fas fa-fw fa-plus',
                ],
                [
                    'text' => 'View',
                    'title' => 'Bands View',
                    'url' => 'admin/catalogue/band/bands',
                    'icon' => 'fas fa-fw fa-list-alt'
                ],
                // [
                //     'text' => 'Product Setting',
                //     'title' => 'Products Page Setting',
                //     'url' => 'admin/catalogue/product/edit-product-setting',
                //     'icon' => 'fas fa-fw fa-edit'
                // ]
            ]
        ],
        [
            'text' => 'carousel',
            'title' => 'carousel',
            'icon' => 'fas fa-fw fa-list-alt',
            'roles' => [
                'Admin',
            ],
            'submenu' => [
                [
                    'text' => 'Add',
                    'title' => 'carousel Add',
                    'url' => 'admin/carousel/new-carousel',
                    'icon' => 'fas fa-fw fa-plus',
                ],
                [
                    'text' => 'View',
                    'title' => 'carousel View',
                    'url' => 'admin/carousel/list',
                    'icon' => 'fas fa-fw fa-list-alt'
                ],
                // [
                //     'text' => 'Product Setting',
                //     'title' => 'Products Page Setting',
                //     'url' => 'admin/catalogue/product/edit-product-setting',
                //     'icon' => 'fas fa-fw fa-edit'
                // ]
            ]
        ],
        [
            'text' => 'Attributes',
            'title' => 'Attributes',
            'icon' => 'fas fa-fw fa-list-alt',
            'roles' => [
                'Admin',
            ],
            'submenu' => [
                [
                    'text' => 'Add',
                    'title' => 'Attributes Add',
                    'url' => 'admin/catalogue/attribute/add-attribute',
                    'icon' => 'fas fa-fw fa-plus',
                ],
                [
                    'text' => 'View',
                    'title' => 'Attributes View',
                    'url' => 'admin/catalogue/attribute/attributes',
                    'icon' => 'fas fa-fw fa-list-alt'
                ]
            ]
        ],
        // [
        //     'text' => 'Promotion',
        //     'title' => 'Promotion',
        //     'icon' => 'fas fa-fw fa-list-alt',
        //     'roles' => [
        //         'Admin',
        //     ],
        //     'submenu' => [
        //         [
        //             'text' => 'Add',
        //             'title' => 'Promotion Add',
        //             'url' => 'promotion/add-promotion',
        //             'icon' => 'fas fa-fw fa-plus',
        //         ],
        //         [
        //             'text' => 'View',
        //             'title' => 'Promotion View',
        //             'url' => 'promotion/list',
        //             'icon' => 'fas fa-fw fa-list-alt'
        //         ]
        //     ]
        // ],
        // [
        //     'text' => 'Index Download',
        //     'title' => 'Index Download',
        //     'icon' => 'fas fa-fw fa-list-alt',
        //     'roles' => [
        //         'Admin',
        //     ],
        //     'submenu' => [
        //         [
        //             'text' => 'Category Index',
        //             'title' => 'Category Index',
        //             'url' => 'https://pim.wforwomanonline.com/pim/categoryIndex.php',
        //             'icon' => 'fas fa-fw fa-plus',
        //         ],
        //         [
        //             'text' => 'Attribute Index',
        //             'title' => 'Attribute Index',
        //             'url' => 'https://pim.wforwomanonline.com/pim/attributeIndex.php',
        //             'icon' => 'fas fa-fw fa-list-alt'
        //         ]
        //     ]
        // ],

        // [
        //     'text' => 'Blog',
        //     'title' => 'Blog',
        //     'icon' => 'fas fa-fw fa-list-alt',
        //     'roles' => [
        //         'Admin',
        //     ],
        //     'submenu' => [
        //         [
        //             'text' => 'Add',
        //             'title' => 'Blog Add',
        //             'url' => 'blog/add-blog',
        //             'icon' => 'fas fa-fw fa-plus',
        //         ],
        //         [

        //             'text' => 'View',
        //             'title' => 'Blog View',
        //             'url' => 'blog/list',
        //             'icon' => 'fas fa-fw fa-list-alt'
        //         ]
        //     ]
        // ],

        [
            'text' => 'Blog Management',
            'title' => 'Blog Management',
            'icon' => 'fas fa-fw fa-list-alt',
            'roles' => 
            [
                'Admin',
            ],
            'submenu' => 
            [
                [
                'text' => 'Add',
                'title' => 'Blog Add',
                'url' => 'admin/myblog/new-myblog',
                'icon' => 'fas fa-fw fa-plus',
                ],
                [
                'text' => 'View',
                'title' => 'Blog View',
                'url' => 'admin/myblog/list',
                'icon' => 'fas fa-fw fa-list-alt',
                ],
            ],
        ],


        
        [
            'text' => 'Page Management',
            'title' => 'Page Management',
            'icon' => 'fas fa-fw fa-list-alt',
            'roles' => [
                'Admin',
            ],
            'submenu' => [
                [
                    'text' => 'Add',
                    'title' => 'Page Add',
                    'url' => 'admin/page_management/new-page_management',
                    'icon' => 'fas fa-fw fa-plus',
                ],
                [

                    'text' => 'View',
                    'title' => 'Page View',
                    'url' => 'admin/page_management/list',
                    'icon' => 'fas fa-fw fa-list-alt'
                ]
            ]
        ],


        [
            'text' => 'Banner',
            'title' => 'Banner',
            'icon' => 'fas fa-fw fa-list-alt',
            'roles' => [
                'Admin',
            ],
            'submenu' => [
                [
                    'text' => 'Add',
                    'title' => 'Banner Add',
                    'url' => 'admin/banner/add-banner',
                    'icon' => 'fas fa-fw fa-plus',
                ],
                [

                    'text' => 'View',
                    'title' => 'Banner View',
                    'url' => 'admin/banner/list',
                    'icon' => 'fas fa-fw fa-list-alt'
                ]
            ]
        ],
        [
            'text' => 'Tags',
            'title' => 'Tags',
            'icon' => 'fas fa-fw fa-list-alt',
            'roles' => [
                'Admin',
            ],
            'submenu' => [
                [
                    'text' => 'Add',
                    'title' => 'Tag Add',
                    'url' => 'admin/musictags/new-tag',
                    'icon' => 'fas fa-fw fa-plus',
                ],
                [

                    'text' => 'View',
                    'title' => 'Tags View',
                    'url' => 'admin/musictags/list',
                    'icon' => 'fas fa-fw fa-list-alt'
                ]
            ]
        ],
        [
            'text' => 'Inquiries',
            'title' => 'Inquiries',
            'icon' => 'fas fa-fw fa-list-alt',
            'roles' => [
                'Admin',
            ],
            'submenu' =>
            [
                [

                    'text' => 'View',
                    'title' => 'Inquiries View',
                    'url' => 'admin/inquiries/list',
                    'icon' => 'fas fa-fw fa-list-alt'
                ]
            ]
            
        ],
        // [
        //     'text' => 'Music',
        //     'title' => 'Music',
        //     'icon' => 'fas fa-fw fa-list-alt',
        //     'roles' => [
        //         'Admin',
        //     ],
        //     'submenu' => [
        //         [
        //             'text' => 'Add',
        //             'title' => 'Music Add',
        //             'url' => 'admin/musics/new-musics',
        //             'icon' => 'fas fa-fw fa-plus',
        //         ],
        //         [

        //             'text' => 'View',
        //             'title' => 'Music View',
        //             'url' => 'admin/musics/list',
        //             'icon' => 'fas fa-fw fa-list-alt'
        //         ]
        //     ]
        // ],
        
        // [
        //     'text' => 'Tag Type',
        //     'title' => 'Tag Type',
        //     'icon' => 'fas fa-fw fa-list-alt',
        //     'roles' => [
        //         'Admin',
        //     ],
        //     'submenu' => [
        //         [
        //             'text' => 'Add',
        //             'title' => 'Tag Type Add',
        //             'url' => 'tagtypes/new-type',
        //             'icon' => 'fas fa-fw fa-plus',
        //         ],
        //         [

        //             'text' => 'View',
        //             'title' => 'Tag type View',
        //             'url' => 'tagtypes/list',
        //             'icon' => 'fas fa-fw fa-list-alt'
        //         ]
        //     ]
        // ],

        [
            'text' => 'Testimonials',
            'title' => 'Testimonials',
            'icon' => 'fas fa-fw fa-list-alt',
            'roles' => 
            [
                0 => 'Admin',
            ],
            'submenu' => 
            [
                [
                'text' => 'Add',
                'title' => 'Testimonials Add',
                'url' => 'admin/testimonials/new-testimonial',
                'icon' => 'fas fa-fw fa-plus',
                ],
                [
                'text' => 'View',
                'title' => 'Testimonials View',
                'url' => 'admin/testimonials/list',
                'icon' => 'fas fa-fw fa-list-alt',
                ],
            ],
        ],
        [
            'text' => 'Review',
            'title' => 'Review',
            'icon' => 'fas fa-fw fa-list-alt',
            'roles' => [
                'Admin',
            ],
            'submenu' => [
                [
                    'text' => 'Add',
                    'title' => 'review Add',
                    'url' => 'admin/review/update-Review',
                    'icon' => 'fas fa-fw fa-plus',
                    ],
                [

                    'text' => 'View',
                    'title' => 'Review View',
                    'url' => 'admin/review/list',
                    'icon' => 'fas fa-fw fa-list-alt'
                ]
            ]
        ],
        // [
        //     'text' => 'CMS',
        //     'title' => 'CMS',
        //     'icon' => 'fas fa-fw fa-list-alt',
        //     'roles' => [
        //         'Admin',
        //     ],
        //     'submenu' => [
        //         [
        //             'text' => 'Add',
        //             'title' => 'CMS Add',
        //             'url' => 'cms/add-page',
        //             'icon' => 'fas fa-fw fa-plus',
        //         ],
        //         [
        //             'text' => 'View',
        //             'title' => 'CMS View',
        //             'url' => 'cms/list',
        //             'icon' => 'fas fa-fw fa-list-alt'
        //         ]
        //     ]
        // ],
     
         
        // [
        //     'text' => 'Social Network',
        //     'title' => 'Social Network',
        //     'icon' => 'fas fa-fw fa-list-alt',
        //     'roles' => [
        //                 'Admin',
        //             ],
        //     'submenu' => [
        //         [
        //             'text' => 'Add',
        //             'title' => 'Social Add',
        //             'url' => 'admin/social/add-social',
        //             'icon' => 'fas fa-fw fa-plus',
        //         ],
        //         [
        //             'text' => 'View',
        //             'title' => 'Social View',
        //             'url' => 'admin/social/list-social',
        //             'icon' => 'fas fa-fw fa-list-alt'
        //         ],
        //         [
        //             'text' => 'File Upload',
        //             'title' => 'File Add',
        //             'url' => 'admin/social/add-fileupload',
        //             'icon' => 'fas fa-fw fa-list-alt'
        //         ]
        //     ]
        // ],
       

        [
            'text' => 'User',
            'title' => 'User',
            'icon' => 'fas fa-fw fa-user-alt',
            'roles' => [
                'Admin',
            ],
            'submenu' => [
                [
                    'text' => 'Add User',
                    'title' => 'User Add User',
                    'url' => 'user/user-add',
                    'icon' => 'fas fa-fw fa-plus',
                ],
                [
                    'text' => 'User List',
                    'title' => 'User User List',
                    'url' => 'user/user-list',
                    'icon' => 'fas fa-fw fa-list',
                ],

                [
                    'text' => 'Add Role',
                    'title' => 'User Add Role',
                    'url' => 'user/add-role',
                    'icon' => 'fas fa-fw fa-plus',
                ],
                [
                    'text' => 'Role List',
                    'title' => 'User Role List',
                    'url' => 'user/get-role',
                    'icon' => 'fas fa-fw fa-list-alt'
                ],

                [
                    'text' => 'Add Permission',
                    'title' => 'User Add Permission',
                    'url' => 'user/add-permission',
                    'icon' => 'fas fa-fw fa-plus',
                ],
                [
                    'text' => 'Permission List',
                    'title' => 'User Permission List',
                    'url' => 'user/get-permission',
                    'icon' => 'fas fa-fw fa-list-alt'
                ],


            ]
        ],


        // ['header' => 'Store Locator'],
        // [
        //     'text' => 'Store Locator ',
        //     'title' => 'Store Locator',
        //     'icon' => 'fas fa-fw fa-list-alt',
        //     'roles' => [
        //         'Admin',
        //     ],
        //     'submenu' => [
        //         [
        //             'text' => 'View',
        //             'title' => 'Store Locator View',
        //             'url' => 'storelocator/view',
        //             'icon' => 'fas fa-fw fa-list-alt',
        //         ],
        //         [
        //             'text' => 'Add',
        //             'title' => 'Store Locator Add',
        //             'url' => 'storelocator/add',
        //             'icon' => 'fas fa-fw fa-plus'
        //         ]
        //     ]
        // ],






        /* [
            'text' => 'Attribute Group',
            'icon' => 'fas fa-fw fa-list-alt',
            'roles' => [
                        'Admin',
                    ],
            'submenu' => [
                [
                    'text' => 'Add',
                    'url' => 'catalogue/attribute-group/add-attribute-group',
                    'icon' => 'fas fa-fw fa-plus',
                ],
                [
                    'text' => 'View',
                    'url' => 'catalogue/attribute-group',
                    'icon' => 'fas fa-fw fa-list-alt'
                ]
            ]
        ],*/
        // [
        //     'text' => 'Re indexing',
        //     'title' => 'Re indexing',
        //     'icon' => 'fas fa-fw fa-list-alt',
        //     'roles' => [
        //         'Admin',
        //     ],
        //     'url' => 'catalogue/re-indexing',
        // ],
        // [
        //     'text' => 'Category Cache Clear',
        //     'title' => 'Category Cache Clear',
        //     'icon' => 'fas fa-fw fa-list-alt',
        //     'roles' => [
        //         'Admin',
        //     ],
        //     'url' => 'catalogue/category-cache-clear',
        // ],
        // [
        //     'text' => 'Product Cache Clear',
        //     'title' => 'Product Cache Clear',
        //     'icon' => 'fas fa-fw fa-list-alt',
        //     'roles' => [
        //         'Admin',
        //     ],
        //     'url' => 'catalogue/product-cache-clear',
        // ],
        // [
        //     'text' => 'Other Cache Clear',
        //     'title' => 'Other Cache Clear',
        //     'icon' => 'fas fa-fw fa-list-alt',
        //     'roles' => [
        //         'Admin',
        //     ],
        //     'url' => 'catalogue/other-cache-clear',
        // ],
        // [
        //     'text' => 'Export',
        //     'title' => 'Export',
        //     'icon' => 'fas fa-fw fa-list-alt',
        //     'roles' => [
        //         'Admin',
        //     ],
        //     'url' => 'catalogue/export',
        // ], 
        [
            'text' => 'Change Password',
            'title' => 'Change Password',
            'icon' => 'fas fa-fw fa-list-alt',

            'url' => 'admin/user/change-password-own',
        ],

        /*['header' => 'account_settings'],
        [
            'text' => 'profile',
            'url'  => 'admin/settings',
            'icon' => 'fas fa-fw fa-user',
        ],
        [
            'text' => 'change_password',
            'url'  => 'admin/settings',
            'icon' => 'fas fa-fw fa-lock',
        ],*/

    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        //App\Helper\RoleMenuFilter::class,    
        //JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
        App\Helper\RoleMenuFilter::class,   
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => false,
];