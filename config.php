<?php

return [
    'baseUrl' => 'http://localhost:3000/',
    'production' => false,
    'siteName' => 'AnonAddy',
    'siteDescription' => 'Create unlimited aliases for free. Protect your email from spam using disposable addresses. Encrypt forwarded emails with PGP encryption using this service.',
    'siteAuthor' => 'AnonAddy',

    // collections
    'collections' => [
        'posts' => [
            'author' => 'Will Browning', // Default author, if not provided in a post
            'sort' => '-date',
            'path' => 'blog/{filename}',
        ],
        'categories' => [
            'path' => '/blog/category/{filename}',
            'posts' => function ($page, $allPosts) {
                return $allPosts->filter(function ($post) use ($page) {
                    return $post->categories ? in_array($page->getFilename(), $post->categories, true) : false;
                });
            },
        ],
        'articles' => [
            'author' => 'Will Browning', // Default author, if not provided in a post
            'sort' => '-date',
            'path' => 'help/{filename}',
        ],
        'helpCategories' => [
            'path' => '/help/category/{filename}',
            'articles' => function ($page, $allPosts) {
                return $allPosts->filter(function ($post) use ($page) {
                    return $post->helpCategories ? in_array($page->getFilename(), $post->helpCategories, true) : false;
                });
            },
        ],
        'newsletter' => [
            'path' => 'newsletters/{filename}',
        ],
    ],

    // helpers
    'getDate' => function ($page) {
        return Datetime::createFromFormat('U', $page->date);
    },
    'getExcerpt' => function ($page, $length = 255) {
        $content = $page->excerpt ?? $page->getContent();
        $cleaned = strip_tags(
            preg_replace(['/<pre>[\w\W]*?<\/pre>/', '/<h\d>[\w\W]*?<\/h\d>/'], '', $content),
            '<code>'
        );

        $truncated = substr($cleaned, 0, $length);

        if (substr_count($truncated, '<code>') > substr_count($truncated, '</code>')) {
            $truncated .= '</code>';
        }

        return strlen($cleaned) > $length
            ? preg_replace('/\s+?(\S+)?$/', '', $truncated) . '...'
            : $cleaned;
    },
    'isActive' => function ($page, $path) {
        return ends_with(trimPath($page->getPath()), trimPath($path));
    },
    'startsWith' => function ($page, $needle) {
        if ($needle !== '' && substr(trimPath($page->getPath()), 0, strlen($needle)) === (string) $needle) {
            return true;
        }

        return false;
    }
];
