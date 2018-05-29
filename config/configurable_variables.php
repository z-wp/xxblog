<?php
/*
 * This file is part of Xblog.
 * This file defines variables to config your blog.
 * Rendered in admin/settings.blade.php
 * Support type:
 *   1. text
 *   2. textarea
 *   3. radio
 *   4. number
 *   5. others
 */
return [
    'groups' => [
        [
            'group_name' => '常用',
            'children' => [
                [
                    'name' => 'google_analytics',
                    'type' => 'radio',
                    'default' => 'false',
                    'values' => [
                        'true' => '启用谷歌分析',
                        'false' => '禁用谷歌分析',
                    ],
                ],
                [
                    'name' => 'enable_mail_notification',
                    'type' => 'radio',
                    'default' => 'false',
                    'values' => [
                        'true' => '启用邮件通知',
                        'false' => '禁用邮件通知',
                    ],
                ],
                [
                    'name' => 'comment_type',
                    'type' => 'radio',
                    'default' => 'raw',
                    'values' => [
                        'none' => '关闭评(不显示)',
                        'raw' => '自带评论',
                        'disqus' => 'Disqus',
                    ],
                ],
                [
                    'name' => 'allow_comment',
                    'type' => 'radio',
                    'default' => 'true',
                    'values' => [
                        'true' => '允许评论',
                        'false' => '禁止评论(仍会显示已有评论)',
                    ],
                ],
                [
                    'name' => 'enable_hot_posts',
                    'type' => 'radio',
                    'default' => 'false',
                    'values' => [
                        'true' => '启用热门文章',
                        'false' => '禁用热门文章',
                    ],
                ],
                [
                    'name' => 'open_pay',
                    'type' => 'radio',
                    'default' => 'false',
                    'values' => [
                        'true' => '开启赞赏',
                        'false' => '关闭赞赏',
                    ],
                ],
                [
                    'name' => 'pay_description',
                    'label' => '赞赏描述',
                    'default' => '写的不错，赞助一下主机费'
                ],
            ]
        ],

        [
            'group_name' => '个人信息',
            'children' => [
                [
                    'name' => 'author',
                    'label' => '作者',
                ],
                [
                    'name' => 'description',
                    'label' => '描述',
                ],
                [
                    'name' => 'avatar',
                    'label' => '头像',
                ],
                [
                    'name' => 'other_information',
                    'type' => 'textarea',
                    "placeholder" => "支持 text 和 html",
                    'label' => '其他信息',
                ],
                [
                    'name' => 'social_facebook',
                    'label' => 'Facebook link',
                ],
                [
                    'name' => 'social_twitter',
                    'label' => 'Twitter link',
                ],
                [
                    'name' => 'social_github',
                    'label' => 'GitHub link',
                ],
                [
                    'name' => 'social_weibo',
                    'label' => 'Weibo link',
                ],
                [
                    'name' => 'social_instagram',
                    'label' => 'Instagram link',
                ],
                [
                    'name' => 'social_googleplus',
                    'label' => 'Google+ link',
                ],
                [
                    'name' => 'social_tumblr',
                    'label' => 'Tumblr link',
                ],
                [
                    'name' => 'social_stackoverflow',
                    'label' => 'StackOverflow link',
                ],
                [
                    'name' => 'social_dribbble',
                    'label' => 'Dribbble link',
                ],
                [
                    'name' => 'social_linkedin',
                    'label' => 'LinkedIn link',
                ],
                [
                    'name' => 'social_gitlab',
                    'label' => 'GitLab link',
                ],
                [
                    'name' => 'social_pinterest',
                    'label' => 'Pinterest link',
                ],
                [
                    'name' => 'social_youtube',
                    'label' => 'YouTube link',
                ],
            ]
        ],
        [
            'group_name' => '网站',
            'children' => [
                [
                    'name' => 'google_trace_id',
                    'label' => '跟踪ID',
                    'placeholder' => '谷歌跟踪ID'
                ],
                [
                    'name' => 'disqus_shortname',
                    'label' => 'Disqus ID',
                ],
                [
                    'name' => 'github_username',
                    'label' => 'Github用户名',
                ],
                [
                    'name' => 'blog_brand',
                    'label' => 'Logo',
                    "placeholder" => "支持 text 和 html",
                    "type" => "textarea"
                ],
                [
                    'name' => 'site_title',
                    'label' => '标题',
                ],
                [
                    'name' => 'site_keywords',
                    'label' => '关键字',
                    "placeholder" => "网站关键字"
                ],
                [
                    'name' => 'site_description',
                    'label' => '网站描述',
                ],
                [
                    'name' => 'site_header_title',
                    'label' => '网站 Head 标题',
                ],
                [
                    'name' => 'page_size',
                    'label' => '每页数量',
                    'default' => 7,
                    "type" => "number"
                ],
                [
                    'name' => 'hot_posts_count',
                    'label' => '热门文章数量',
                    'default' => 5,
                    "type" => "number"
                ],
                [
                    'name' => 'case_number',
                    'label' => '备案号'
                ],
            ]
        ],
        [
            'group_name' => '图片',
            'children' => [

                [
                    'name' => 'profile_image',
                    'label' => '简介图片',
                ],
                [
                    'name' => 'header_bg_image',
                    'label' => 'Header背景图片',
                ],
                [
                    'name' => 'header_image_provider',
                    'type' => 'radio',
                    'default' => 'none',
                    'label' => '动态Header背景图片',
                    'values' => [
                        'none' => '关闭',
                        'bing' => '必应每日壁纸',
                        'picsum' => 'Picsum',
                    ],
                ],
                [
                    'name' => 'header_image_update_rate',
                    'type' => 'radio',
                    'default' => 'every_day',
                    'label' => '动态Header背景图片更新频率',
                    'values' => [
                        'every_minute' => '每分钟',
                        'every_hour' => '每小时',
                        'every_day' => '每天',
                        'every_week' => '每周',
                    ],
                ],
                [
                    'name' => 'admin_sidebar_bg_image',
                    'label' => 'Dashboard背景图片',
                ],
                [
                    'name' => 'home_bg_images',
                    'label' => 'Home背景图片',
                    "type" => "textarea",
                    "placeholder" => "可以多个, 一行一个"
                ],
                [
                    'name' => 'zhifubao_pay_image_url',
                    'label' => '支付宝支付二维码',
                ],
                [
                    'name' => 'wechat_pay_image_url',
                    'label' => '微信支付二维码',
                ],
            ]
        ],
    ],
];