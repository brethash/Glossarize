<?php

/*	
* File: glossarize.php
* Author: Bret Hash
* Copyright: 2011 Bret Hash
* Date: 07/29/2011
* 
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*  
*/

function glossarize($string){
	// Build a list of terms and definitions
	$terms = array(
			'foo' => 'Classic placeholder variable numero uno.',
			'bar' => 'Classic placeholder variable numero dos.',
			'frogs' => 'There are all sorts of frogs in nearly every part of the world.',
			'plants' => 'Plants have colonized almost every corner of our planet.',
			'orchids' => 'There are over 25,000 species of orchids on earth.',
			'stuff' => 'I\'ve got it all over my pants!'
		);
	$start = array();
	// Check to see if the term is found in the input string
	foreach(array_keys($terms) as $term){
		if(!empty($term)){
			if(stripos($string,$term) != false || stripos($string,$term) == 0){
				$temppos = stripos($string,$term) + strlen($term);
				/* If the word is in the middle of the sentence, beginning, or end of the sentence 
				 * add it to the array (not part of another word)
				 */
				if ((substr($string,$temppos,1) == ' ' && substr($string,stripos($string,$term) - 1,1) == ' ') || (stripos($string,$term) == 0 && substr($string,$temppos,1) == ' ') || substr($string,$temppos,1) == '.'){
					$length[] = strlen($term);
					$start[] = stripos($string,$term);
					$found[] = $term;
					$def[strtolower($term)] = $terms[$term];
				}
			}
		}			
	}
	
	// If there are terms found, slice and dice the input string. Otherwise, return the original string.
	if (isset($found)){
		if (count($found) > 1){
			asort($start);
			$order = array_keys($start);
			
			// Get the part of the string before the first glossary word
			$sparts[] = substr($string,0,$start[$order[0]]);
			
			// Loop through the rest of the string and divide it into parts (word, string before glossary word, word)
			$i = 0;
			while($i < count($start)){
					
					// Store current found word in $founds array
					$founds[] = substr($string,$start[$order[$i]],$length[$order[$i]]);
					
					// Position of current found word
					$pos = ($start[$order[$i]]) + strlen($founds[$i]);
					
					// Length of current found word
					if(substr($string,$pos + 1,1) != ' '){
						$thisword = substr($string,$start[$order[$i]],$length[$order[$i]] + 1);
					}
					else{
						$thisword = substr($string,$start[$order[$i]],$length[$order[$i]]);
					}

					if ($i == count($start) - 1){
						// Length of the next found word
						$nextword = $thisword;

						// Total length of string between two found terms
						$totlength = $start[$order[$i]] - $start[$order[$i]] - strlen($thisword);
					}
					else{
						// Length of the next found word
						$nextword = substr($string,$start[$order[$i + 1]],$length[$i + 1]);

						// Total length of string between two found terms
						$totlength = $start[$order[$i + 1]] - $start[$order[$i]] - strlen($thisword);
					}
					$sparts[] = substr($string,$pos,$totlength);

					$i++;
			}
		
			// Reassemble the string	
			$j = 0;
			while($j < count($start)){
				$output[] = $sparts[$j] . ' <dfn title="' . $def[strtolower($founds[$j])] . '">' . $founds[$j] . '</dfn>';
				$j++;
			}

			$output[] = substr($string,end($start) + strlen($nextword) - 1,(strlen($string) - end($start)));
		}
		else if (count($found) == 1){
			$found = substr($string,$start[0],$length[0] + 1);
			$beginning = substr($string,0,$start[0]);
			$end = substr($string,$start[0] + strlen($found),strlen($string));
			$output[] = $beginning . '<dfn title="' . end($def) . '">' . $found . '</dfn>' . $end;
		}
		return join('',$output);
	}
	else{
		return $string;
	}
	unset($start,$defs,$founds,$terms,$output);
}

echo glossarize('My frogs and orchids keep telling me to foo and bar my stuff all over my pants.');
?>