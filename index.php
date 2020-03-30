<?php
include_once 'config.php';
include_once 'puzzle.php';

switch ($_GET['q']) {
	case 'Ping':
		echo PING_RESPONSE;
		break;
	case 'Degree':
		echo DEGREE_RESPONSE;
		break;
	case 'Status':
		echo STATUS_RESPONSE;
		break;
	case 'Resume':
		echo RESUME_RESPONSE;
		break;
	case 'Referrer':
		echo REFERRER_RESPONSE;
		break;
	case 'Email Address':
		echo EMAIL_RESPONSE;
		break;
	case 'Puzzle':
		$puzzle = new Puzzle(MATRIX_DIMENSION);
		$puzzle->solve($_GET['d']);
		$puzzle->print();
		break;
	case 'Years':
		echo YEARS_RESPONSE;
		break;
	case 'Source':
		echo SOURCE_RESPONSE;
		break;
	case 'Name':
		echo NAME_RESPONSE;
		break;
	case 'Position':
		echo POSITION_RESPONSE;
		break;
	case 'Phone':
		echo PHONE_RESPONSE;
		break;
	default:
		echo 'Missing or Unknown Request';
		break;
}