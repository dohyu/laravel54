<?php

if (! function_exists('markdown')) {
    function markdown($text = null)
    {
        return app(ParsedownExtra::class)->text($text);
    }
}

function link_for_sort($column, $text, $params = [])
{
    $direction = request()->input('order');
    $reverse = ($direction == 'asc') ? 'desc' : 'asc';

    if (request()->input('sort') == $column) {
        $text = sprintf("%s %s",
            $direction == 'asc'
                ? '<i class="fa fa-sort-alpha-asc"></i>'
                : '<i class="fa fa-sort-alpha-desc"></i>',
            $text
        );
    }

    $queryString = http_build_query(array_merge(
        request()->except(['sort', 'order']),
        ['sort' => $column, 'order' => $reverse],
        $params
    ));

    return sprintf(
        '<a href="%s?%s">%s</a>',
        urldecode(request()->url()),
        $queryString,
        $text
    );
}

function gravatar_url($email, $size = 48)
{
    return sprintf("//www.gravatar.com/avatar/%s?s=%s", md5($email), $size);
}

function gravatar_profile_url($email)
{
    return sprintf("//www.gravatar.com/%s", md5($email));
}
