<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Provider;

use App\Repository\BannerRepository;

class BannerProvider
{
    public function __construct(
        private BannerRepository $bannerRepository
    ) {
    }

    public function lastBanner()
    {
        return $this->bannerRepository->getLatestBanner();
    }
}
