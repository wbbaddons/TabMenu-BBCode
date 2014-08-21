<?php
namespace wcf\system\bbcode;
use wcf\system\Regex;
use wcf\system\WCF;
use wcf\util\StringUtil;
	
/**
 * Parses the tabmenuBBCode bbcode tag.
 * 
 * @author	Tim Düsterhus
 * @copyright	2014 Tim Düsterhus
 * @license	MIT License (http://opensource.org/licenses/MIT)
 * @package	be.bastelstu.wcf.tabMenuBBCode
 * @subpackage	system.bbcode
 */
class TabmenuBBCode extends AbstractBBCode {
	private static $tabMenuCounter = 0;
	
	/**
	 * Extracts the given type of tabs.
	 * 
	 * @param	string	$content
	 * @param	string	$type
	 * @return	array
	 */
	public static function fetchTabs($content, $type = 'tab') {
		if ($type !== 'tab' && $type !== 'subtab') throw new \wcf\system\exception\SystemException("Available types are 'tab' and 'subtab', but not '".$type."'");
		
		// see \wcf\system\bbcode\BBCodeParser
		$regex = new Regex('\['.$type.'(?:=
		(\'[^\'\\\\]*(?:\\\\.[^\'\\\\]*)*\'|[^,\]]*)
		(?:,(?:\'[^\'\\\\]*(?:\\\\.[^\'\\\\]*)*\'|[^,\]]*))*
		)?\]', Regex::CASE_INSENSITIVE | Regex::IGNORE_WHITESPACE);
		
		$result = $regex->split($content, Regex::CAPTURE_SPLIT_DELIMITER);
		$result = array_map('\wcf\system\bbcode\TabmenuBBCode::htmlTrim', $result);
		
		// remove an empty item in front of the very first tab, it is a left over of the lexer
		// and will totally break the parser
		if ($result[0] === '') array_shift($result);
		
		$tabs = array();
		// we got contents, without an associated tab, assign a pseudo tab for it
		if (count($result) % 2 === 1) {
			$tabs[] = array(
				'title' => null,
				'content' => StringUtil::trim(array_shift($result))
			);
		}
		
		$tabs = array_reduce(array_map(
			function($v) use (&$tab) {
				return array(
					array(
						'title' => $v[0],
						'content' => self::htmlTrim($v[1])
					)
				);
			},
			array_chunk($result, 2)
		), 'array_merge', $tabs);
		
		return $tabs;
	}
	
	/**
	 * Trims <br /> as well as ordinary whitespace.
	 * 
	 * @param	string	$string
	 * @return	string
	 */
	public static function htmlTrim($string) {
		static $regexStart = null;
		static $regexEnd = null;
		if ($regexStart === null) {
			$regexStart = new Regex('^(?:<br />|\s)*');
			$regexEnd = new Regex('(?:<br />|\s)*$');
		}
		
		return $regexEnd->replace($regexStart->replace(StringUtil::trim($string), ''), '');
	}
	
	/**
	 * @see	wcf\system\bbcode\IBBCode::getParsedTag()
	 */
	public function getParsedTag(array $openingTag, $content, array $closingTag, BBCodeParser $parser) {
		$trimmedContent = self::htmlTrim($content);
		
		$tabs = self::fetchTabs($trimmedContent);
		$tabs = array_map(function ($item) {
			$item['content'] = self::fetchTabs($item['content'], 'subtab');
			return $item;
		}, $tabs);
		
		// content before the first super tab is not allowed, don't parse any further
		if (!isset($tabs[0]) || $tabs[0]['title'] === null) {
			return $openingTag['source'].$content.$closingTag['source'];
		}
		
		foreach ($tabs as $tab) {
			// no subtabs are fine
			if (count($tab['content']) <= 1) continue; // return $tab;
			// ... but content before the first sub tab is not
			if ($tab['content'][0]['title'] === null) {
				return $openingTag['source'].$content.$closingTag['source'];
			}
		}
		
		if ($parser->getOutputType() === 'text/html') {
			WCF::getTPL()->assign(array(
				'tabMenuBBCodeTabs' => $tabs,
				'tabMenuBBCodeCounter' => self::$tabMenuCounter++
			));
			return WCF::getTPL()->fetch('tabmenuBBCodeTag');
		}
		else {
			// yadayada
			return '';
		}
	}
}
