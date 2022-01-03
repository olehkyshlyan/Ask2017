<?
namespace IndexPageElements
{

// no reason to use static property or method
// just for learning purposes

class MainButtons
{
	public static $openTag = NULL;
	public static $closeTag = NULL;
	public static $categoriesButton = NULL;
	public static $askAQuestionButton = NULL;
	
	private static function createWrapperSection(){
		self::$openTag = '<div id="mainButtons">';
		self::$closeTag = '</div>';
	}
	
	private static function createCategoriesButton(){
		self::$categoriesButton = '<div id="wrCategoriesButton">';
		self::$categoriesButton .= '<span id="categoriesButton">Categories</span>';
		self::$categoriesButton .= '</div>';
	}
	
	private static function createAskAQuestionButton(){
		self::$askAQuestionButton = '<div id="wrMBAskWord">';
		self::$askAQuestionButton .= '<span id="mbAskWord">Ask a question !</span>';
		self::$askAQuestionButton .= '</div>';
	}
	
	final public static function constructImitation(){
		self::createWrapperSection();
		self::createCategoriesButton();
		self::createAskAQuestionButton();
	}
}

MainButtons::constructImitation();

class IndexPageRoute
{
	public static $route = NULL;
	private static $categoryLink = '';
	
	private static function createCategoryLink(){
		global $categories;
		if($categories->category != 'other'){
			self::$categoryLink .= 'href="index.php?category='.$categories->category.'"';
		}
	}
	
	private static function createRoute(){
		global $categories;
		global $subcategories;
		self::$route = '<div id="MBPageRoute">';
		self::$route .= '<a href="index.php" id="MBMainPageLink" class="MBPageRouteElement">Main page</a>';
		if($categories->category != NULL){
			self::$route .= '<span> > </span><a '.self::$categoryLink.' class="MBPageRouteElement">'.\Categories\Categories::CATEGORIES[$categories->category].'</a>';
		}
		if($subcategories->subcategory != NULL){
			self::$route .= '<span> > </span>'.\Subcategories\Subcategories::SUBCATEGORIES[$categories->category][$subcategories->subcategory];
		}
		self::$route .= '</div>';
	}
	
	final public static function constructImitation(){
		self::createCategoryLink();
		self::createRoute();
	}
}

IndexPageRoute::constructImitation();

class MiddleBlock
{
	public static $openTag = NULL;
	public static $closeTag = NULL;
	
	private static function createMiddleBlockTag(){
		self::$openTag = '<div id="middleBlock">';
		self::$closeTag = '</div>';
	}
	
	final public static function constructImitation(){
		self::createMiddleBlockTag();
	}
}

MiddleBlock::constructImitation();

class MBQuestionBlock
{
	public static $questionBlock = NULL;
	
	private static function createQuestionBlock(){
		//global $qrow;
		if(isset($qrow) && $qrow != false){
			foreach($qrow as $row){
				self::$questionBlock = '<div class="MBQuestionBlock">';
				
				self::$questionBlock .= '<div class="MBQuestionPhoto">';
				self::$questionBlock .= '<a href="uq.php?uid='.$row['uid'].'" target="_blank">';
				self::$questionBlock .= '</a>';
				self::$questionBlock .= '</div>';
				
				self::$questionBlock .= '</div>';
			}
		}
	}
	
	final public static function constructImitation(){
		self::createQuestionBlock();
	}
}

MBQuestionBlock::constructImitation();

class Footer
{
	public static $footer = NULL;
	
	private static function createFooter(){
		self::$footer = '<div id="footer">';
		self::$footer .= '<div id="footerSiteName">Questions and answers</div>';
		self::$footer .= '</div>';
	}
	
	final public static function construct(){
		self::createFooter();
	}
}

Footer::construct();

}

?>