<?php defined('SYSPATH') OR die('No direct script access.') ?>

<div xmlns:v="http://rdf.data-vocabulary.org/#">
    <span typeof="v:Breadcrumb">
    <?php for ($i = 0; $i < count($breadcrumbs); $i++): ?>
    <?php if ($i == 0): ?>
        <a href="<?php echo $breadcrumbs[0]['url'] ?>" rel="v:url" property="v:title"<?php if ($breadcrumbs[0]['active']): ?> class="<?php echo $active_class ?>"<?php endif ?>><?php echo $breadcrumbs[0]['title'] ?></a><?php if ($children > 0): ?> › <?php endif ?>
    <?php else: ?>
        <span rel="v:child">
            <span typeof="v:Breadcrumb">
                <a href="<?php echo $breadcrumbs[$i]['url'] ?>" rel="v:url" property="v:title"<?php if ($breadcrumbs[$i]['active']): ?> class="<?php echo $active_class ?>"<?php endif ?>><?php echo $breadcrumbs[$i]['title'] ?></a><?php if ($i < $children):?> › <?php endif ?>
    <?php endif ?>
    <?php endfor ?>
            <?php echo str_repeat("</span>\r\n</span>\r\n", $children) ?>
    </span>
</div>