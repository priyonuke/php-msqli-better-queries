<?php
//database.php
//Refer to docs/sql-class-queries.txt

class query {
	private $conn;
	private $error;
	public function __construct() {
		$this->conn = mysqli_connect("localhost", "root", "", "social_network");
		if (!$this->conn) {
			die('Database Connection Error ' . mysqli_connect_error($this->conn));
		}
	}
	public function insert($table_name, $data) {
		//Create query with placeholder question marks(?)
		$string = "INSERT INTO " . $table_name . " (";
		//Columns
		$h = 0;
		foreach ($data as $key => $value) {
			if ($h === count($data) - 1) {
				$string .= "$key";
			} else {
				$string .= "$key" . ", ";
			}
			$h++;
		}
		//Corresponding values
		$string .= ") VALUES(";
		$i = 0;
		foreach ($data as $key => $value) {
			if ($i === count($data) - 1) {
				$string .= "?)";
			} else {
				$string .= "?" . ", ";
			}
			$i++;
		}
		$dataTypes = '';
		$values_arr = array();
		foreach ($data as $key => $value) {
			$type = gettype($value);
			$str = '';
			if ($type == "integer") {
				$str = 'i';
			} else if ($type == "string") {
				$str = 's';
			} else if ($type == "double") {
				$str = 'd';
			} else if ($type == "blob") {
				$str = 'b';
			}
			$dataTypes .= $str; //Get all datatypes
			array_push($values_arr, $value); //New array of values
		}
		$stmt = ($this->conn)->prepare($string);
		$stmt->bind_param($dataTypes, ...$values_arr); //Bind parameters
		if ($stmt->execute()) {
			return true;
		} else {
			die(mysqli_error($this->conn));
		}
	}

	public function select($table_name, $getData, $conditions) {
		$string = "SELECT " . $getData . " FROM " . $table_name;
		if (count($conditions) > 0) {
			$string .= " WHERE ";
			for ($i = 0; $i < count($conditions); $i++) {
				$string .= $conditions[$i][0] . " " . $conditions[$i][1] . ' ? ' . $conditions[$i][3] . " ";
			}
			$values_arr = array();
			$dataTypes = '';
			for ($i = 0; $i < count($conditions); $i++) {
				$type = gettype($conditions[$i][2]);
				$str = '';
				if ($type == "integer") {
					$str = 'i';
				} else if ($type == "string") {
					$str = 's';
				} else if ($type == "double") {
					$str = 'd';
				} else if ($type == "blob") {
					$str = 'b';
				}
				$dataTypes .= $str; //Get all datatypes
				array_push($values_arr, $conditions[$i][2]); //New array of values

			}
			$stmt = ($this->conn)->prepare($string);
			$stmt->bind_param($dataTypes, ...$values_arr);
			$stmt->execute();
			$result = $stmt->get_result();
		} else {
			$result = mysqli_query($this->conn, $string);
		}
		$data = array();
		$num_rows = mysqli_num_rows($result);
		$array = array();
		if ($num_rows > 0) {
			while ($row = mysqlI_fetch_assoc($result)) {
				$array[] = $row;
			}
		}
		$data['data_array'] = $array;
		$data['data_num_rows'] = $num_rows;
		if ($result) {
			return $data;
		} else {
			die(mysqli_error($this->conn));
		}
	}
	public function delete($table_name, $conditions) {
		$string = "DELETE FROM " . $table_name;
		if (count($conditions) > 0) {
			$string .= " WHERE ";
			for ($i = 0; $i < count($conditions); $i++) {
				$string .= $conditions[$i][0] . " " . $conditions[$i][1] . ' ? ' . $conditions[$i][3] . " ";
			}
			$values_arr = array();
			$dataTypes = '';
			for ($i = 0; $i < count($conditions); $i++) {
				$type = gettype($conditions[$i][2]);
				$str = '';
				if ($type == "integer") {
					$str = 'i';
				} else if ($type == "string") {
					$str = 's';
				} else if ($type == "double") {
					$str = 'd';
				} else if ($type == "blob") {
					$str = 'b';
				}
				$dataTypes .= $str; //Get all datatypes
				array_push($values_arr, $conditions[$i][2]); //New array of values
			}

			$stmt = ($this->conn)->prepare($string);
			$stmt->bind_param($dataTypes, ...$values_arr);
			$result = $stmt->execute();
		} else {
			$result = mysqli_query($this->conn, $string);
		}

		if ($result) {
			return true;
		} else {
			die(mysqli_error($this->conn));
		}
	}
	public function update($table_name, $data, $conditions) {
		$string = "UPDATE " . $table_name . " SET ";
		$i = 0;
		foreach ($data as $key => $value) {
			if ($i === (count($data) - 1)) {
				$string .= $key . " = " . "?";
			} else {
				$string .= $key . " = " . "?" . ", ";
			}
			$i++;
		}
		$string .= " WHERE ";
		for ($i = 0; $i < count($conditions); $i++) {
			$string .= $conditions[$i][0] . " " . $conditions[$i][1] . ' ? ' . $conditions[$i][3] . " ";
		}
		//Datatypes of data
		$dataTypes = '';
		$values_arr = array();
		foreach ($data as $key => $value) {
			$type = gettype($value);
			$str = '';
			if ($type == "integer") {
				$str = 'i';
			} else if ($type == "string") {
				$str = 's';
			} else if ($type == "double") {
				$str = 'd';
			} else if ($type == "blob") {
				$str = 'b';
			}
			$dataTypes .= $str; //Get all datatypes
			array_push($values_arr, $value); //New array of values

		}

		//Data types of conditions
		for ($i = 0; $i < count($conditions); $i++) {
			$type = gettype($conditions[$i][2]);
			$str = '';
			if ($type == "integer") {
				$str = 'i';
			} else if ($type == "string") {
				$str = 's';
			} else if ($type == "double") {
				$str = 'd';
			} else if ($type == "blob") {
				$str = 'b';
			}
			$dataTypes .= $str; //Get all datatypes
			array_push($values_arr, $conditions[$i][2]); //New array of values
		}

		$stmt = ($this->conn)->prepare($string);
		$stmt->bind_param($dataTypes, ...$values_arr);

		if ($stmt->execute()) {
			return true;
		} else {
			die(mysqli_error($this->conn));
		}
	}
}
