<?php defined('SYSPATH') OR die('No direct script access.') ?>

<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
    <?php for ($i = 0; $i < count($breadcrumbs); $i++): ?>
    <?php if ($i == 0): ?>
    <a href="<?php echo $breadcrumbs[0]['url'] ?>" itemprop="url"<?php if ($breadcrumbs[0]['active']): ?> class="<?php echo $active_class ?>"<?php endif ?>><span itemprop="title"><?php echo $breadcrumbs[0]['title'] ?></span></a><?php if ($children > 0): ?> › <?php endif ?>
    <?php else: ?>
    <div itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="<?php echo $breadcrumbs[$i]['url'] ?>" itemprop="url"<?php if ($breadcrumbs[$i]['active']): ?> class="<?php echo $active_class ?>"<?php endif ?>><span itemprop="title"><?php echo $breadcrumbs[$i]['title'] ?></span></a><?php if ($i < $children): ?> › <?php endif ?>
        <?php endif ?>
        <?php endfor ?>
        <?php echo str_repeat("</div>\r\n", $children) ?>
</div>