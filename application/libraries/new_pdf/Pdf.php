// <?php 
// if (!defined('BASEPATH')) exit('No direct script access allowed');  
 

// require_once( APPPATH. 'libraries/dompdf/autoload.inc.php');

// use Dompdf\Dompdf;

// class Pdf extends Dompdf
// {
	// public function __construct()
	// {
		 // parent::__construct();
	// } 
// }

// ?>
<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * CodeIgniter PDF Library
 *
 * Generate PDF in CodeIgniter applications.
 *
 * @package            CodeIgniter
 * @subpackage        Libraries
 * @category        Libraries
 * @author            CodexWorld
 * @license            https://www.codexworld.com/license/
 * @link            https://www.codexworld.com
 */

// reference the Dompdf namespace
use Dompdf\Dompdf;

class Pdf
{
    public function __construct(){
        
        // include autoloader
       require_once( APPPATH. 'libraries/dompdf/autoload.inc.php');
        
        // instantiate and use the dompdf class
        $pdf = new DOMPDF();
        
        $CI =& get_instance();
        $CI->dompdf = $pdf;
        
    }
}
?>