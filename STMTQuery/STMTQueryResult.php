<?php

declare(strict_types=1);

final class STMTQueryResult{
	public int $errno = 0;
	public string $error = '';

	public string $pattern;

	/**
	 * @var array<int, int|float|string>
	 */
	public array $values;

	public mysqli_result $result;
}