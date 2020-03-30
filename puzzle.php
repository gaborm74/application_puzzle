<?php

class Puzzle
{

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

	public function print()
	{
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

	/**
	 * Fill the known cells from the parsed input
	 * 
	 * @param integer $row The row index
	 * @param string $parsedLine The full row text from the input
	 */
	private function fillRowFromParse($row, $parsedLine)
	{
		$matches = [];
		if (preg_match('/[<|>]/', $parsedLine, $matches, PREG_OFFSET_CAPTURE)) {
			// echo "Row #$row matches:" . PHP_EOL;
			// print_r($matches);

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

	/**
	 * Ater the parsing the remaining empty cells should be filled with the given control character
	 */
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
	 * Create a dim x dim matrix array
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
}