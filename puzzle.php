<?php

class Puzzle
{

// 	private $testInput = 'Please+solve+this+puzzle%3A%0A+ABCD%0AA-%3E--%0AB-%3D--%0AC-%3C--%0AD%3E---%0A';

	private $matrix = [];

	private $coordinateChars = [];

	// Public Functions
	// ================
	public function __construct($dimension)
	{
		$this->initMatrix($dimension);
	}

	public function solve(string $puzzleInput)
	{
		$parsedString = $this->parseInput($puzzleInput);
		if ($parsedString == []) {
			return 'Failed to parse input';
		}

		for ($row = 0; $row < count($this->matrix); $row ++) {
			$this->fillRowFromParse($row, $parsedString[$row + 2]);
		}

		$this->fillEmptyCells();
	}

	public function print() {
		echo ' ' . implode('', $this->coordinateChars) . PHP_EOL;
		foreach ($this->matrix as $index => $rowList) {
			echo $this->coordinateChars[$index] . implode('', $rowList) . PHP_EOL;
		}
	}
	// Private Functions
	// =================

	/**
	 *
	 * @param string $puzzleInput
	 *        	The d parameter value from the GET request
	 *        	
	 * @return boolean TRUE Successful parsing | FALSE If we don't have enough input lines to fill all rows of the initialized matrix
	 */
	private function parseInput(string $puzzleInput): array
	{
		$parsedString = explode("\n", urldecode($puzzleInput));

		if (count($parsedString) - 3 != count($this->matrix)) {
			return [];
		}

		$this->coordinateChars = str_split(trim($parsedString[1]));

		return $parsedString;
	}

	private function fillRowFromParse($row, $parsedLine)
	{
		$matches = [];
		if (preg_match('/[<|>]/', $parsedLine, $matches, PREG_OFFSET_CAPTURE)) {
// 			echo "Row #$row matches:" . PHP_EOL;
// 			print_r($matches);

			// Add control char to $row / $matches[1] coordinates and also mirror it
			$controlChar = $matches[0][0];
			$controlCharPosition = $matches[0][1] - 1;

			$this->matrix[$row][$controlCharPosition] = $controlChar;
			$this->matrix[$controlCharPosition][$row] = ($controlChar == '>') ? '<' : '>';

			// Fill empty cells with control char in the row
			foreach ($this->matrix[$row] as $position => $rowChar) {
				if ($rowChar == '') {
					$this->matrix[$row][$position] = $controlChar;
				}
			}
		}
	}

	private function fillEmptyCells()
	{
		for ($row = 0; $row < count($this->matrix); $row ++) {
			for ($col = 0; $col < count($this->matrix); $col ++) {
				if ($this->matrix[$row][$col] == '') {
					$this->matrix[$row][$col] = ($this->matrix[$col][$row] == '>') ? '<' : '>';
				}
			}
		}
	}

	/**
	 * Create a matrix array
	 *
	 * @param integer $dimension
	 */
	private function initMatrix($dimension)
	{
		for ($i = 0; $i < $dimension; $i ++) {
			$row = [];
			$row = array_fill(0, $dimension, '');
			// Fill the diagonal separator cells with '='
			$row[$i] = '=';
			$this->matrix[] = $row;
		}
	}

	// -----------------------------------------------
	// Debug
	// -----------------------------------------------
// 	public function printTestInput()
// 	{
// 		return explode("\n", urldecode($this->testInput));
// 	}

// 	public function printArrays()
// 	{
// 		print_r($this->coordinateChars);
// 		print_r($this->matrix);
// 	}
}