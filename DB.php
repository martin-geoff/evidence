<?php

class Db
{
	//statická proměnná pro uložení připojení
	private static $pripojeni;

	//PDO funkce pro připojení k databázi a reakce na chyby
	private static $nastaveni = array(
		PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
		PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
		PDO::ATTR_EMULATE_PREPARES => false,
	);

	//připojí se k databázi
	public static function connect($host, $database, $user, $password)
	{
		if (!isset(self::$pripojeni)) {
			$dsn = "mysql:host=$host;dbname=$database";
			self::$pripojeni = new PDO($dsn, $user, $password, self::$nastaveni);
		}
	}

        //vložení do databáze
	public static function insert($table, $data) {
		$keys = array_keys($data);
		self::checkIdentifiers(array($table) + $keys);
		$query = "
			INSERT INTO `$table` (`" . implode('`, `', $keys) . "`)
			VALUES (" . str_repeat('?,', count($data) - 1) . "?)
		";
		$params = array_merge(array($query), array_values($data));
		$statement = self::executeStatement($params);
		return $statement->rowCount();
	}
                
	//spustí dotaz
	private static function executeStatement($params)
	{
		$query = array_shift($params);
		$statement = self::$pripojeni->prepare($query);
		$statement->execute($params);
		return $statement;
	}

	//spustí dotaz a vrátí počet řádků
	public static function query($query) {
		$statement = self::executeStatement(func_get_args());
		return $statement->rowCount();
	}

	//spustí dotaz a vrátí první sloupec
	public static function querySingle($query) {
		$statement = self::executeStatement(func_get_args());
		$data = $statement->fetch();
		return $data[0];
	}

	//spustí dotaz a vrátí první řádek
	public static function queryOne($query) {
		$statement = self::executeStatement(func_get_args());
		return $statement->fetch(PDO::FETCH_ASSOC);
	}

	//spustí dotaz a vrátí všechny jeho řádky
	public static function queryAll($query) {
		$statement = self::executeStatement(func_get_args());
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}



	//modifikace záznamů
	public static function update($table, $data, $condition) {
		$keys = array_keys($data);
		self::checkIdentifiers(array($table) + $keys);
		$query = "
			UPDATE `$table` SET `".
			implode('` = ?, `', array_keys($data)) . "` = ?
			$condition
		";
		$params = array_merge(array($query), array_values($data), array_slice(func_get_args(), 3));
		$statement = self::executeStatement($params);
		return $statement->rowCount();
	}

	//vrátí poslední id posledního záznamu
	public static function getLastId()
	{
		return self::$pripojeni->lastInsertId();
	}

	//ošetří string proti sql infekci
	public static function quote($string)
	{
		return self::$pripojeni->quote($string);
	}

	//zkontroluje zda identifikátory odpovídají formátu
	private static function checkIdentifiers($identifiers)
	{
		foreach ($identifiers as $identifier)
		{
			if (!preg_match('/^[a-zA-Z0-9\_\-]+$/u', $identifier))
				throw new Exception('Dangerous identifier in SQL query');
		}
	}
}


