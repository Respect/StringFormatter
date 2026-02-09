<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
 */

declare(strict_types=1);

use Respect\StringFormatter\FormatterBuilder;

if (!class_exists('f')) {
    class_alias(FormatterBuilder::class, 'f');
}
