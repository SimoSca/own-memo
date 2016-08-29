<?php

/**
 * Raccolta di script per creare dinamicamente e visivametne la tree di una directory
 */

class Tree {

	/**
	 * transform directory tree to associative array recursively
	 * @param  string $path   [description]
	 * @param  array  $ignore [description]
	 * @return [type]         [description]
	 */
	public static function dirTreeToArray($path = '.', $ignore = array()){
		$ritit = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::CHILD_FIRST);
		$r = array();
		foreach ($ritit as $splFileInfo) {

			$toIgnore = false;
			foreach ($ignore as $value) {
				if(preg_match($value, $splFileInfo->getRealPath()) || preg_match($value, $splFileInfo->getFilename())) $toIgnore = true;
			}

			if($toIgnore) continue;
		   $path = $splFileInfo->isDir()
		         ? array($splFileInfo->getFilename() => array())
		         : array($splFileInfo->getFilename());

		   for ($depth = $ritit->getDepth() - 1; $depth >= 0; $depth--) {
		       $path = array($ritit->getSubIterator($depth)->current()->getFilename() => $path);
		   }
		   $r = array_merge_recursive($r, $path);
		}

		return $r;
	}

	/**
	 * Transform array to Tree view
	 * @param  [type]  $arr        [description]
	 * @param  integer $indent     [description]
	 * @param  boolean $mother_run [description]
	 * @return [type]              [description]
	 */
	public static function plotTree($arr, $indent=0, $mother_run=true){
		if ($mother_run) {
			print "<br/>";
			// the beginning of plotTree. We're at rootlevel
			print "start<br />";
		}
		foreach ($arr as $k=>$v){
			// skip the baseval thingy. Not a real node.
			// echo $k;
			if ($k == "__base_val"){
				// echo $v;
				print str_repeat("    |", $indent)."- $v<br/>";
				continue;	
			} 
			// determine the real value of this node.
			$show_val = (is_array($v) ? $v["__base_val"] : $v);
			// show the indents
			$n = ($indent == 0) ? 0 : $indent-1;
			print str_repeat("    |", $n);
			if( $indent>0 ) print str_repeat("    ", 1);
			if (is_array($v)){
				// this is a normal node. parents and children
				print "+";
			} else {
				// this is a leaf node. no children
				print "-";
			}
			// show the actual node
			print "  $k /<br/>";//. " (" . $show_val. ")" . "<br />";
			if (is_array($v)) {
				// this is what makes it recursive, rerun for childs
				self::plotTree($v, ($indent+1), false);
			}
		}
		if ($mother_run) {
			print "end<br />";
		}
	}

}