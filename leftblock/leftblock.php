<?
namespace LeftBlock
{

// this class created HTML-tags for advertisement in the left block on the index-page
class LeftAdvBlock
{
	public static $advBlock = NULL;
	
	private static function createAdvBlock(){
		self::$advBlock = '<div id="leftAdvBlock">';
		self::$advBlock .= '<div id="LABAdv1"></div>';
		self::$advBlock .= '<div id="LABAdv2"></div>';
		self::$advBlock .= '<div id="LABAdv3"></div>';
		self::$advBlock .= '</div>';
	}
	
	final public static function construct(){
		self::createAdvBlock();
	}
} // end of class LeftAdvBlock

LeftAdvBlock::construct();

} // end of namespace LeftBlock

?>