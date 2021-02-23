<?php
/*
*	Class Name:	Cyrillic to Latin Transliteration
* Filename:		translit.class.php
* Author: 		Filip Filipov <primecode@gmail.com>
*	Website:		htp://www.xamex.com/
*	Version:	 	1.0
*	Created:    30-May-2011
*	Purpose:   	Transliteration of Cyrillic to Latin (GOST 7.79 B).
* 						Can be used for Bulgarian or/and Russian transliteration.
* 						Usefull for creating friendly urls (like blog post title or url).
* 						Important!!! Is case insensitive.
*	Requires : 	PHP4 or later
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* To read the license please visit http://www.gnu.org/licenses/gpl.html
*/

/******************* USAGE ********************

You can set transliteration for Russian or Bulgarian.
You can set encoding of the input string and encoding of the output string in result.

// Include class file
include('translit.class.php');
$translit = new Transliteration();

// Not not necessarily ('bg' for Bulgarian, 'ru' for Russian). Default transliteration is Bulgarian.
$translit->Table = 'bg';

// Set encoding of the input string. Not necessarily. By default is utf-8.
$translit->EncodeIn = 'cp1251';

// Set encoding of the output string. Not necessarily. By default is utf-8.
$translit->EncodeOut = 'utf-8';

// Sample text
$text = 'Това е примерен текст на кирилица.'; // This is sample text in cyrillic.

// Send text to transliteration
$string = $translit->Translit($text);

// Print transliterated text
echo $string;

**********************************************/

namespace App\Http\Controllers\excelImporter;

class Transliteration {
	var $EncodeIn 					= 'utf-8'; 	// default encoding of the input string
	var $EncodeOut 					= 'utf-8'; 	// default encoding of the output string
	var $Table 							= 'uz'; 		// default language is Bulgarian
	var $TranslationTable 	= null;
	var $string							= '';

	// Bulgarian translation table
	var $TableBG = array(
	// small letters
	'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y',
	'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u',
	'ф' => 'f', 'х' => 'h', 'ц' => 'ts', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sht', 'ъ' => 'a', 'ь' => 'y', 'ю' => 'yu', 'я' => 'ya',
	// capital letters
	'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I', 'Й' => 'Y',
	'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U',
	'Ф' => 'F', 'Х' => 'H', 'Ц' => 'Ts', 'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sht', 'Ъ' => 'A', 'Ь' => 'Y', 'Ю' => 'Yu', 'Я' => 'Ya'
	);

	// Russian translation table
	var $TableRu = array(
	// small letters
	'a' => 'а', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
	'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
	'у' => 'u', 'ф' => 'f', 'х' => 'x', 'ц' => 'cz', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shh', 'ъ' => '"', 'ы' => 'y', 'ь' => '\'',
	'э' => 'eh', 'ю' => 'yu', 'я' => 'ya',
	// capital letters
	'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I',
	'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T',
	'У' => 'U', 'Ф' => 'F', 'Х' => 'X', 'Ц' => 'Cz', 'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Shh', 'Ъ' => '"', 'Ы' => 'Y', 'Ь' => '\'',
	'Э' => 'Eh', 'Ю' => 'Yu', 'Я' => 'Ya',
	'№' => '#'
	);

	// Uzbek translation table
	var $TableUz = array(
	// small letters
	'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'j', 'з' => 'z', 'и' => 'i',
	'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
	'у' => 'u', 'ф' => 'f', 'х' => 'x', 'ц' => 'cz', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shh', 'ъ' => '"', 'ы' => 'y', 'ь' => '\'',
	'э' => 'eh', 'ю' => 'yu', 'я' => 'ya', 'қ'=>'q', 'ў'=>'o\'', 'ҳ'=>'h', 'ғ'=>'g\'',
	// capital letters
	'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'J', 'З' => 'Z', 'И' => 'I',
	'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T',
	'У' => 'U', 'Ф' => 'F', 'Х' => 'X', 'Ц' => 'Cz', 'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Shh', 'Ъ' => '"', 'Ы' => 'Y', 'Ь' => '\'',
	'Э' => 'Eh', 'Ю' => 'Yu', 'Я' => 'Ya', 'Қ'=>'Q', 'Ў'=>'O\'', 'Ҳ'=>'H', 'Ғ'=>'G\'',
	'№' => '#'
	);

	private function SetTranslationTable() {
		switch ($this->Table) {
			case 'ru':
				$this->TranslationTable = $this->TableRu;
				break;
			case 'uz':
				$this->TranslationTable = $this->TableUz;
				break;
			case 'bg':
				$this->TranslationTable = $this->TableBG;
				break;
			default:
				# code...
				break;
		}
		#return $this->Table == 'ru' ? $this->TableRu : $this->TableBG;
	}

	private function convert($in = false) {
		$this->EncodeIn = strtolower($this->EncodeIn);
		$this->EncodeOut = strtolower($this->EncodeOut);
		$this->string = $in === true ? iconv($this->EncodeIn, "utf-8", $this->string) : iconv("utf-8", $this->EncodeOut, $this->string);
	}

	private function letter($chr) {
		return mb_strtolower($chr, "utf-8") != $chr;
	}

	private function Transliterate() {
		$newString = '';
		for ( $i = 0; $i < mb_strlen($this->string, $this->EncodeIn); $i++ ) {
			$char = mb_substr($this->string, $i, 1, "utf-8");
			$next_char = mb_substr($this->string, $i+1, 1, "utf-8");
			$UpLow = $this->letter($next_char) === true ? 1 : 0;
			$translit = strtr($char, $this->TranslationTable);
			$newString .= $this->letter($char) === true && $this->letter($next_char) === true ? strtoupper($translit) : $translit;
		}
		$this->string = $newString;
	}

	function Translit($string) {
		if ( empty($string) ) {
			return false;
		}
		$this->string = trim($string);
		$this->SetTranslationTable();
		$this->convert(true);
		$this->Transliterate();
		$this->convert();
		return $this->string;
	}
}

?>
