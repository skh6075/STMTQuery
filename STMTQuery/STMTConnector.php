<?php

declare(strict_types=1);

require_once "./STMTQueryParam.php";
require_once "./STMTQueryResult.php";

final readonly class STMTConnector{
	private function __construct(private mysqli $mysqli){
		if($mysqli->errno){
			die("Connect Error: $mysqli->error");
		}
	}

	public static function connect(
		?string $hostname = null,
		?string $username = null,
		?string $password = null,
		?string $database = null,
		?int $port = null,
		?string $socket = null
	): STMTConnector{
		return new STMTConnector(new mysqli($hostname, $username, $password, $database, $port, $socket));
	}

	public function getMySQL(): mysqli{
		return $this->mysqli;
	}

	/**
	 * @deprecated mysqli->query(...)
	 *
	 * @param string $query
	 *
	 * @return bool|mysqli_result
	 */
	public function query(string $query): bool|mysqli_result{
		return $this->mysqli->query($query);
	}

	public function stmt_query(
		string $query,
		STMTQueryParam ...$params
	): STMTQueryResult{
		$pattern = '';
		$filters = [];

		$result = new STMTQueryResult;

		$mysqli = $this->mysqli;
		$state = $mysqli->stmt_init();
		if($state){
			$stmt = $mysqli->prepare($query);
			foreach($params as $_ => $param){
				if(!$param->isValid()){
					$result->errno = 1;
					$result->error = $param->getErrorMessage();
					break;
				}

				$pattern .= $param->type;
				$filters[] = $param->value;
			}

			$stmt->bind_param($pattern, ...$filters);
			$stmt->execute();

			$result->pattern = $pattern;
			$result->values = $filters;

			$get_result = $stmt->get_result();
			if($get_result === false){
				$result->errno = 1;
				$result->error = 'Unable to get query result: bad query expected';
			}else{
				$result->result = $stmt->get_result();
			}
		}else{
			$result->errno = 1;
			$result->error = 'STMT is not initialized';
		}

		return $result;
	}
}