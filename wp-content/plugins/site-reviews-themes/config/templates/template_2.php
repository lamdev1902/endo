<?php

return [
    [
        'align' => 'stretch',
        'direction' => 'col',
        'flex' => 'grow',
        'gap' => 12,
        'minwidth' => 0,
        'wrap' => false,
        'children' => [
            [
                'align' => 'start',
                'direction' => 'row',
                'flex' => '',
                'gap' => 2,
                'minwidth' => 0,
                'wrap' => false,
                'children' => [
                    [
                        'flex' => 'grow',
                        'is_bold' => true,
                        'is_hidden' => false,
                        'is_italic' => false,
                        'is_uppercase' => false,
                        'tag' => 'title',
                        'text' => 'large'
                    ],
                    [
                        'flex' => 'shrink',
                        'is_bold' => false,
                        'is_hidden' => false,
                        'is_italic' => false,
                        'is_uppercase' => false,
                        'tag' => 'rating',
                        'text' => 'normal'
                    ]
                ]
            ],
            [
                'flex' => '',
                'is_bold' => false,
                'is_hidden' => false,
                'is_italic' => false,
                'is_uppercase' => false,
                'tag' => 'assigned_links',
                'text' => 'normal'
            ],
            [
                'flex' => 'grow',
                'is_bold' => false,
                'is_hidden' => false,
                'is_italic' => false,
                'is_uppercase' => false,
                'tag' => 'content',
                'text' => 'normal'
            ],
            [
                'align' => 'center',
                'direction' => 'row',
                'flex' => '',
                'gap' => 12,
                'minwidth' => 0,
                'wrap' => false,
                'children' => [
                    [
                        'flex' => 'shrink',
                        'is_bold' => false,
                        'is_hidden' => false,
                        'is_italic' => false,
                        'is_uppercase' => false,
                        'tag' => 'avatar',
                        'text' => 'normal'
                    ],
                    [
                        'align' => 'stretch',
                        'direction' => 'col',
                        'flex' => 'grow',
                        'gap' => 0,
                        'minwidth' => 0,
                        'wrap' => false,
                        'children' => [
                            [
                                'flex' => '',
                                'is_bold' => true,
                                'is_hidden' => false,
                                'is_italic' => false,
                                'is_uppercase' => false,
                                'tag' => 'author',
                                'text' => 'normal'
                            ],
                            [
                                'flex' => '',
                                'is_bold' => false,
                                'is_hidden' => false,
                                'is_italic' => false,
                                'is_uppercase' => false,
                                'tag' => 'date',
                                'text' => 'small'
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
