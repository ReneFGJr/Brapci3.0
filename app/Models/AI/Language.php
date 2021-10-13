<?php

namespace App\Models\AI;

use CodeIgniter\Model;

class Language extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'languages';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [];

	// Dates
	protected $useTimestamps        = false;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';

	// Validation
	protected $validationRules      = [];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks       = true;
	protected $beforeInsert         = [];
	protected $afterInsert          = [];
	protected $beforeUpdate         = [];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];

	//https://www.ti-enxame.com/pt/php/detectar-idioma-da-string-php/967079314/
 function pt()
	{
		/* Digrafos */
		// "gu", "qu", "lh", "nh" e "ch"
		
		$s = ''
	}

 function getTextLanguage($text, $default) {
      $supported_languages = array(
          'en',
          'de',
		  'pt',
		  'es',
      );
      // German Word list
      // from http://wortschatz.uni-leipzig.de/Papers/top100de.txt
      $wordList['de'] = array ('der', 'die', 'und', 'in', 'den', 'von', 
          'zu', 'das', 'mit', 'sich', 'des', 'auf', 'für', 'ist', 'im', 
          'dem', 'nicht', 'ein', 'Die', 'eine');
      // English Word list
      // from http://en.wikipedia.org/wiki/Most_common_words_in_English
      $wordList['en'] = array ('the', 'be', 'to', 'of', 'and', 'a', 'in', 
          'that', 'have', 'I', 'it', 'for', 'not', 'on', 'with', 'he', 
          'as', 'you', 'do', 'at');
      // from http://wortschatz.uni-leipzig.de/Papers/top100de.txt
      $wordList['pt'] = array ('de', 'da', 'em', 'ção', 'cao', 'vel', 
          'ico', 'das', 'mit', 'sich', 'des', 'auf', 'für', 'ist', 'im', 
          'dem', 'nicht', 'ein', 'Die', 'eine');
      // from http://wortschatz.uni-leipzig.de/Papers/top100de.txt
      $wordList['es'] = array ('der', 'die', 'und', 'in', 'den', 'von', 
          'zu', 'das', 'mit', 'sich', 'des', 'auf', 'für', 'ist', 'im', 
          'dem', 'nicht', 'ein', 'Die', 'eine');

      // clean out the input string - note we don't have any non-ASCII 
      // characters in the Word lists... change this if it is not the 
      // case in your language wordlists!
      $text = preg_replace("/[^A-Za-z]/", ' ', $text);
      // count the occurrences of the most frequent words
      foreach ($supported_languages as $language) {
        $counter[$language]=0;
      }
      for ($i = 0; $i < 20; $i++) {
        foreach ($supported_languages as $language) {
          $counter[$language] = $counter[$language] + 
            // I believe this is way faster than fancy RegEx solutions
            substr_count($text, ' ' .$wordList[$language][$i] . ' ');;
        }
      }
      // get max counter value
      // from http://stackoverflow.com/a/1461363
      $max = max($counter);
      $maxs = array_keys($counter, $max);
      // if there are two winners - fall back to default!
      if (count($maxs) == 1) {
        $winner = $maxs[0];
        $second = 0;
        // get runner-up (second place)
        foreach ($supported_languages as $language) {
          if ($language <> $winner) {
            if ($counter[$language]>$second) {
              $second = $counter[$language];
            }
          }
        }
        // apply arbitrary threshold of 10%
        if (($second / $max) < 0.1) {
          return $winner;
        } 
      }
      return $default;
    }	
}
