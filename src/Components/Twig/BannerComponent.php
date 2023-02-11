<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Components\Twig;

use App\Entity\Banner;
use App\Enum\BannerType;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent('banner', template: 'components/twig/banner.html.twig')]
class BannerComponent
{
    public Banner $banner;

    #[ExposeInTemplate('icon')]
    public function getIcon()
    {
        switch ($this->banner->getType()) {
            case BannerType::EVENT:
                return 'bell';
            case BannerType::INFO:
                return 'info';

            default:
                return 'info';
        }
    }
}
