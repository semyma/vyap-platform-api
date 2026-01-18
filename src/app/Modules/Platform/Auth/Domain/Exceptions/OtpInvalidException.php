<?php

declare(strict_types=1);

namespace App\Modules\Platform\Auth\Domain\Exceptions;

use RuntimeException;

final class OtpInvalidException extends RuntimeException {}
