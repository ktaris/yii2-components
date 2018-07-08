<?php

namespace ktaris\widgets;

use yii\widgets\Breadcrumbs as BaseBreadcrumbs;

class Breadcrumbs extends BaseBreadcrumbs
{
    public $tag = 'ol';
    public $itemTemplate = "<li class=\"breadcrumb-item\"  aria-current=\"page\">{link}</li>\n";
    public $activeItemTemplate = "<li class=\"breadcrumb-item active\"  aria-current=\"page\">{link}</li>\n";
}
