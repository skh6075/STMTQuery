# STMTQuery
Using PHP mysqli queries safely

## Requirements
* PHP Version >= ```8.2```

## Usage

### STMT library connection
```php
require_once "/your/library/path/STMTQuery/STMTConnector.php";

$connector = STMTConnector::connect('hostname', 'username', 'password', 'database');

$query = " SELECT user_name FROM member_tb WHERE `user_email` = ? AND `user_age` = ? LIMIT 1 ";
```

### Using the STMT Query Syntax Library Format
```php
$result = $connector->stmt_query($query,
	new STMTQueryParam(STMTQueryParam::TYPE_STRING, "steve"),
	new STMTQueryParam(STMTQueryParam::TYPE_INTEGER, 12)
);
```

### Using queries after handling STMT parameter errors
```php
$result = $connector->stmt_query($query,
	STMTQueryParam::safeCreate(STMTQueryParam::TYPE_STRING, "steve"),
	STMTQueryParam::safeCreate(STMTQueryParam::TYPE_INTEGER, 12)
);
```

### Get stmt resource from STMTQueryResult
```php
if($result->errno){
	die($result->error);
}

$stmt_query_pattern = $result->pattern;
$stmt_filter_variables = $result->values;
$mysql_result = $result->result;
```
