<?php

declare(strict_types=1);

final class STMTQueryParam{
	public const TYPE_INTEGER = 'i';
	public const TYPE_DOUBLE = 'd';
	public const TYPE_STRING = 's';
	public const TYPE_BLOB = 'b';

	public function __construct(
		public string $type,
		public int|float|string $value
	){}

	public static function safeCreate(string $type, int|float|string $value): STMTQueryParam{
		$result = new self($type, $value);
		if(!$result->isValid()){
			die($result->getErrorMessage());
		}

		return $result;
	}

	public function isValid(): bool{
		switch($this->type){
			case self::TYPE_INTEGER:
				if(is_numeric($this->value)){
					$this->value = (int) $this->value;
				}
				return is_int($this->value);
			case self::TYPE_DOUBLE:
				if(is_numeric($this->value)){
					$this->value = (double) $this->value;
				}
				return is_double($this->value);
			case self::TYPE_STRING:
			case self::TYPE_BLOB:
				if(!is_string($this->value) && !is_object($this->value) && !is_array($this->value)){
					$this->value = (string) $this->value;
				}
				return is_string($this->value);
			default:
				return false;
		}
	}

	public function getErrorMessage(): string{
		if(!in_array($this->type, [self::TYPE_INTEGER, self::TYPE_DOUBLE, self::TYPE_STRING, self::TYPE_BLOB])){
			return "$this->type type is not allowed";
		}
		return "Variable attribute does not match type";
	}
}
