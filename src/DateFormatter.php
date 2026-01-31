<?php

/*
 * SPDX-FileCopyrightText: (c) Respect Project Contributors
 * SPDX-License-Identifier: ISC
 * SPDX-FileContributor: Alexandre Gomes Gaigalas <alganet@gmail.com>
 */

declare(strict_types=1);

namespace Respect\StringFormatter;

use DateTime;
use Throwable;

final readonly class DateFormatter implements Formatter
{
    public function __construct(private string $format = 'Y-m-d H:i:s')
    {
    }

    public function format(string $input): string
    {
        try {
            $dateTime = new DateTime($input);
            $errors = DateTime::getLastErrors();
            if ($errors !== false && (!empty($errors['warning_count']) || !empty($errors['error_count']))) {
                return $input;
            }

            return $dateTime->format($this->format);
        } catch (Throwable) {
            return $input;
        }
    }
}
