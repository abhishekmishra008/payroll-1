<?php
/**
 * A PHP class to provide the basic functionality to create a pdf document without
 * any requirement for additional modules.
 *
 * Extended by Orion Richardson to support Unicode / UTF-8 characters using
 * TCPDF and others as a guide.
 *
 * @author  Wayne Munro <pdf@ros.co.nz>
 * @author  Orion Richardson <orionr@yahoo.com>
 * @author  Helmut Tischer <htischer@weihenstephan.org>
 * @author  Ryan H. Masten <ryan.masten@gmail.com>
 * @author  Brian Sweeney <eclecticgeek@gmail.com>
 * @author  Fabien Ménager <fabien.menager@gmail.com>
 * @license Public Domain http://creativecommons.org/licenses/publicdomain/
 * @package Cpdf
 */
use FontLib\Font;
use FontLib\BinaryStream;

class Cpdf
{

    /**
     * @var integer The current number of pdf objects in the document
     */
    public $numObj = 0;

    /**
     * @var array This array contains all of the pdf objects, ready for final assembly
     */
    public $objects = array();

    /**
     * @var integer The objectId (number within the objects array) of the document catalog
     */
    public $catalogId;

    /**
     * @var array Array carrying information about the fonts that the system currently knows about
     * Used to ensure that a font is not loaded twice, among other things
     */
    public $fonts = array();

    /**
     * @var string The default font metrics file to use if no other font has been loaded.
     * The path to the directory containing the font metrics should be included
     */
    public $defaultFont = './fonts/Helvetica.afm';

    /**
     * @string A record of the current font
     */
    public $currentFont = '';

    /**
     * @var string The current base font
     */
    public $currentBaseFont = '';

    /**
     * @var integer The number of the current font within the font array
     */
    public $currentFontNum = 0;

    /**
     * @var integer
     */
    public $currentNode;

    /**
     * @var integer Object number of the current page
     */
    public $currentPage;

    /**
     * @var integer Object number of the currently active contents block
     */
    public $currentContents;

    /**
     * @var integer Number of fonts within the system
     */
    public $numFonts = 0;

    /**
     * @var integer Number of graphic state resources used
     */
    private $numStates = 0;

    /**
     * @var array Number of graphic state resources used
     */
    private $gstates = array();

    /**
     * @var array Current color for fill operations, defaults to inactive value,
     * all three components should be between 0 and 1 inclusive when active
     */
    public $currentColor = null;

    /**
     * @var array Current color for stroke operations (lines etc.)
     */
    public $currentStrokeColor = null;

    /**
     * @var string Fill rule (nonzero or evenodd)
     */
    public $fillRule = "nonzero";

    /**
     * @var string Current style that lines are drawn in
     */
    public $currentLineStyle = '';

    /**
     * @var array Current line transparency (partial graphics state)
     */
    public $currentLineTransparency = array("mode" => "Normal", "opacity" => 1.0);

    /**
     * array Current fill transparency (partial graphics state)
     */
    public $currentFillTransparency = array("mode" => "Normal", "opacity" => 1.0);

    /**
     * @var array An array which is used to save the state of the document, mainly the colors and styles
     * it is used to temporarily change to another state, then change back to what it was before
     */
    public $stateStack = array();

    /**
     * @var integer Number of elements within the state stack
     */
    public $nStateStack = 0;

    /**
     * @var integer Number of page objects within the document
     */
    public $numPages = 0;

    /**
     * @var array Object Id storage stack
     */
    public $stack = array();

    /**
     * @var integer Number of elements within the object Id storage stack
     */
    public $nStack = 0;

    /**
     * an array which contains information about the objects which are not firmly attached to pages
     * these have been added with the addObject function
     */
    public $looseObjects = array();

    /**
     * array contains information about how the loose objects are to be added to the document
     */
    public $addLooseObjects = array();

    /**
     * @var integer The objectId of the information object for the document
     * this contains authorship, title etc.
     */
    public $infoObject = 0;

    /**
     * @var integer Number of images being tracked within the document
     */
    public $numImages = 0;

    /**
     * @var array An array containing options about the document
     * it defaults to turning on the compression of the objects
     */
    public $options = array('compression' => true);

    /**
     * @var integer The objectId of the first page of the document
     */
    public $firstPageId;

    /**
     * @var integer The object Id of the procset object
     */
    public $procsetObjectId;

    /**
     * @var array Store the information about the relationship between font families
     * this used so that the code knows which font is the bold version of another font, etc.
     * the value of this array is initialised in the constructor function.
     */
    public $fontFamilies = array();

    /**
     * @var string Folder for php serialized formats of font metrics files.
     * If empty string, use same folder as original metrics files.
     * This can be passed in from class creator.
     * If this folder does not exist or is not writable, Cpdf will be **much** slower.
     * Because of potential trouble with php safe mode, folder cannot be created at runtime.
     */
    public $fontcache = '';

    /**
     * @var integer The version of the font metrics cache file.
     * This value must be manually incremented whenever the internal font data structure is modified.
     */
    public $fontcacheVersion = 6;

    /**
     * @var string Temporary folder.
     * If empty string, will attempt system tmp folder.
     * This can be passed in from class creator.
     */
    public $tmp = '';

    /**
     * @var string Track if the current font is bolded or italicised
     */
    public $currentTextState = '';

    /**
     * @var string Messages are stored here during processing, these can be selected afterwards to give some useful debug information
     */
    public $messages = '';

    /**
     * @var string The encryption array for the document encryption is stored here
     */
    public $arc4 = '';

    /**
     * @var integer The object Id of the encryption information
     */
    public $arc4_objnum = 0;

    /**
     * @var string The file identifier, used to uniquely identify a pdf document
     */
    public $fileIdentifier = '';

    /**
     * @var boolean A flag to say if a document is to be encrypted or not
     */
    public $encrypted = false;

    /**
     * @var string The encryption key for the encryption of all the document content (structure is not encrypted)
     */
    public $encryptionKey = '';

    /**
     * @var array Array which forms a stack to keep track of nested callback functions
     */
    public $callback = array();

    /**
     * @var integer The number of callback functions in the callback array
     */
    public $nCallback = 0;

    /**
     * @var array Store label->id pairs for named destinations, these will be used to replace internal links
     * done this way so that destinations can be defined after the location that links to them
     */
    public $destinations = array();

    /**
     * @var array Store the stack for the transaction commands, each item in here is a record of the values of all the
     * publiciables within the class, so that the user can rollback at will (from each 'start' command)
     * note that this includes the objects array, so these can be large.
     */
    public $checkpoint = '';

    /**
     * @var array Table of Image origin filenames and image labels which were already added with o_image().
     * Allows to merge identical images
     */
    public $imagelist = array();

    /**
     * @var boolean Whether the text passed in should be treated as Unicode or just local character set.
     */
    public $isUnicode = false;

    /**
     * @var string the JavaScript code of the document
     */
    public $javascript = '';

    /**
     * @var boolean whether the compression is possible
     */
    protected $compressionReady = false;

    /**
     * @var array Current page size
     */
    protected $currentPageSize = array("width" => 0, "height" => 0);

    /**
     * @var array All the chars that will be required in the font subsets
     */
    protected $stringSubsets = array();

    /**
     * @var string The target internal encoding
     */
    static protected $targetEncoding = 'Windows-1252';

    /**
     * @var array The list of the core fonts
     */
    static protected $coreFonts = array(
        'courier',
        'courier-bold',
        'courier-oblique',
        'courier-boldoblique',
        'helvetica',
        'helvetica-bold',
        'helvetica-oblique',
        'helvetica-boldoblique',
        'times-roman',
        'times-bold',
        'times-italic',
        'times-bolditalic',
        'symbol',
        'zapfdingbats'
    );

    /**
     * Class constructor
     * This will start a new document
     *
     * @param array   $pageSize  Array of 4 numbers, defining the bottom left and upper right corner of the page. first two are normally zero.
     * @param boolean $isUnicode Whether text will be treated as Unicode or not.
     * @param string  $fontcache The font cache folder
     * @param string  $tmp       The temporary folder
     */
    function __construct($pageSize = array(0, 0, 612, 792), $isUnicode = false, $fontcache = '', $tmp = '')
    {
        $this->isUnicode = $isUnicode;
        $this->fontcache = rtrim($fontcache, DIRECTORY_SEPARATOR."/\\");
        $this->tmp = ($tmp !== '' ? $tmp : sys_get_temp_dir());
        $this->newDocument($pageSize);

        $this->compressionReady = function_exists('gzcompress');

        if (in_array('Windows-1252', mb_list_encodings())) {
            self::$targetEncoding = 'Windows-1252';
        }

        // also initialize the font families that are known about already
        $this->setFontFamily('init');
    }

    /**
     * Document object methods (internal use only)
     *
     * There is about one object method for each type of object in the pdf document
     * Each function has the same call list ($id,$action,$options).
     * $id = the object ID of the object, or what it is to be if it is being created
     * $action = a string specifying the action to be performed, though ALL must support:
     *           'new' - create the object with the id $id
     *           'out' - produce the output for the pdf object
     * $options = optional, a string or array containing the various parameters for the object
     *
     * These, in conjunction with the output function are the ONLY way for output to be produced
     * within the pdf 'file'.
     */

    /**
     * Destination object, used to specify the location for the user to jump to, presently on opening
     *
     * @param $id
     * @param $action
     * @param string $options
     * @return string|null
     */
    protected function o_destination($id, $action, $options = '')
    {
        switch ($action) {
            case 'new':
                $this->objects[$id] = array('t' => 'destination', 'info' => array());
                $tmp = '';
                switch ($options['type']) {
                    case 'XYZ':
                    /** @noinspection PhpMissingBreakStatementInspection */
                    case 'FitR':
                        $tmp = ' ' . $options['p3'] . $tmp;
                    case 'FitH':
                    case 'FitV':
                    case 'FitBH':
                    /** @noinspection PhpMissingBreakStatementInspection */
                    case 'FitBV':
                        $tmp = ' ' . $options['p1'] . ' ' . $options['p2'] . $tmp;
                    case 'Fit':
                    case 'FitB':
                        $tmp = $options['type'] . $tmp;
                        $this->objects[$id]['info']['string'] = $tmp;
                        $this->objects[$id]['info']['page'] = $options['page'];
                }
                break;

            case 'out':
                $o = &$this->objects[$id];

                $tmp = $o['info'];
                $res = "\n$id 0 obj\n" . '[' . $tmp['page'] . ' 0 R /' . $tmp['string'] . "]\nendobj";

                return $res;
        }

        return null;
    }

    /**
     * set the viewer preferences
     *
     * @param $id
     * @param $action
     * @param string|array $options
     * @return string|null
     */
    protected function o_viewerPreferences($id, $action, $options = '')
    {
        switch ($action) {
            case 'new':
                $this->objects[$id] = array('t' => 'viewerPreferences', 'info' => array());
                break;

            case 'add':
                $o = &$this->objects[$id];

                foreach ($options as $k => $v) {
                    switch ($k) {
                        // Boolean keys
                        case 'HideToolbar':
                        case 'HideMenubar':
                        case 'HideWindowUI':
                        case 'FitWindow':
                        case 'CenterWindow':
                        case 'DisplayDocTitle':
                        case 'PickTrayByPDFSize':
                            $o['info'][$k] = (bool)$v;
                            break;

                        // Integer keys
                        case 'NumCopies':
                            $o['info'][$k] = (int)$v;
                            break;

                        // Name keys
                        case 'ViewArea':
                        case 'ViewClip':
                        case 'PrintClip':
                        case 'PrintArea':
                            $o['info'][$k] = (string)$v;
                            break;

                        // Named with limited valid values
                        case 'NonFullScreenPageMode':
                            if (!in_array($v, array('UseNone', 'UseOutlines', 'UseThumbs', 'UseOC'))) {
                                continue;
                            }
                            $o['info'][$k] = $v;
                            break;

                        case 'Direction':
                            if (!in_array($v, array('L2R', 'R2L'))) {
                                continue;
                            }
                            $o['info'][$k] = $v;
                            break;

                        case 'PrintScaling':
                            if (!in_array($v, array('None', 'AppDefault'))) {
                                continue;
                            }
                            $o['info'][$k] = $v;
                            break;

                        case 'Duplex':
                            if (!in_array($v, array('None', 'AppDefault'))) {
                                continue;
                            }
                            $o['info'][$k] = $v;
                            break;

                        // Integer array
                        case 'PrintPageRange':
                            // Cast to integer array
                            foreach ($v as $vK => $vV) {
                                $v[$vK] = (int)$vV;
                            }
                            $o['info'][$k] = array_values($v);
                            break;
                    }
                }
                break;

            case 'out':
                $o = &$this->objects[$id];
                $res = "\n$id 0 obj\n<< ";

                foreach ($o['info'] as $k => $v) {
                    if (is_string($v)) {
                        $v = '/' . $v;
                    } elseif (is_int($v)) {
                        $v = (string) $v;
                    } elseif (is_bool($v)) {
                        $v = ($v ? 'true' : 'false');
                    } elseif (is_array($v)) {
                        $v = '[' . implode(' ', $v) . ']';
                    }
                    $res .= "\n/$k $v";
                }
                $res .= "\n>>\n";

                return $res;
        }

        return null;
    }

    /**
     * define the document catalog, the overall controller for the document
     *
     * @param $id
     * @param $action
     * @param string|array $options
     * @return string|null
     */
    protected function o_catalog($id, $action, $options = '')
    {
        if ($action !== 'new') {
            $o = &$this->objects[$id];
        }

        switch ($action) {
            case 'new':
                $this->objects[$id] = array('t' => 'catalog', 'info' => array());
                $this->catalogId = $id;
                break;

            case 'outlines':
            case 'pages':
            case 'openHere':
            case 'javascript':
                $o['info'][$action] = $options;
                break;

            case 'viewerPreferences':
                if (!isset($o['info']['viewerPreferences'])) {
                    $this->numObj++;
                    $this->o_viewerPreferences($this->numObj, 'new');
                    $o['info']['viewerPreferences'] = $this->numObj;
                }

                $vp = $o['info']['viewerPreferences'];
                $this->o_viewerPreferences($vp, 'add', $options);

                break;

            case 'out':
                $res = "\n$id 0 obj\n<< /Type /Catalog";

                foreach ($o['info'] as $k => $v) {
                    switch ($k) {
                        case 'outlines':
                            $res .= "\n/Outlines $v 0 R";
                            break;

                        case 'pages':
                            $res .= "\n/Pages $v 0 R";
                            break;

                        case 'viewerPreferences':
                            $res .= "\n/ViewerPreferences $v 0 R";
                            break;

                        case 'openHere':
                            $res .= "\n/OpenAction $v 0 R";
                            break;

                        case 'javascript':
                            $res .= "\n/Names <</JavaScript $v 0 R>>";
                            break;
                    }
                }

                $res .= " >>\nendobj";

                return $res;
        }

        return null;
    }

    /**
     * object which is a parent to the pages in the document
     *
     * @param $id
     * @param $action
     * @param string $options
     * @return string|null
     */
    protected function o_pages($id, $action, $options = '')
    {
        if ($action !== 'new') {
            $o = &$this->objects[$id];
        }

        switch ($action) {
            case 'new':
                $this->objects[$id] = array('t' => 'pages', 'info' => array());
                $this->o_catalog($this->catalogId, 'pages', $id);
                break;

            case 'page':
                if (!is_array($options)) {
                    // then it will just be the id of the new page
                    $o['info']['pages'][] = $options;
                } else {
                    // then it should be an array having 'id','rid','pos', where rid=the page to which this one will be placed relative
                    // and pos is either 'before' or 'after', saying where this page will fit.
                    if (isset($options['id']) && isset($options['rid']) && isset($options['pos'])) {
                        $i = array_search($options['rid'], $o['info']['pages']);
                        if (isset($o['info']['pages'][$i]) && $o['info']['pages'][$i] == $options['rid']) {

                            // then there is a match
                            // make a space
                            switch ($options['pos']) {
                                case 'before':
                                    $k = $i;
                                    break;

                                case 'after':
                                    $k = $i + 1;
                                    break;

                                default:
                                    $k = -1;
                                    break;
                            }

                            if ($k >= 0) {
                                for ($j = count($o['info']['pages']) - 1; $j >= $k; $j--) {
                                    $o['info']['pages'][$j + 1] = $o['info']['pages'][$j];
                                }

                                $o['info']['pages'][$k] = $options['id'];
                            }
                        }
                    }
                }
                break;

            case 'procset':
                $o['info']['procset'] = $options;
                break;

            case 'mediaBox':
                $o['info']['mediaBox'] = $options;
                // which should be an array of 4 numbers
                $this->currentPageSize = array('width' => $options[2], 'height' => $options[3]);
                break;

            case 'font':
                $o['info']['fonts'][] = array('objNum' => $options['objNum'], 'fontNum' => $options['fontNum']);
                break;

            case 'extGState':
                $o['info']['extGStates'][] = array('objNum' => $options['objNum'], 'stateNum' => $options['stateNum']);
                break;

            case 'xObject':
                $o['info']['xObjects'][] = array('objNum' => $options['objNum'], 'label' => $options['label']);
                break;

            case 'out':
                if (count($o['info']['pages'])) {
                    $res = "\n$id 0 obj\n<< /Type /Pages\n/Kids [";
                    foreach ($o['info']['pages'] as $v) {
                        $res .= "$v 0 R\n";
                    }

                    $res .= "]\n/Count " . count($this->objects[$id]['info']['pages']);

                    if ((isset($o['info']['fonts']) && count($o['info']['fonts'])) ||
                        isset($o['info']['procset']) ||
                        (isset($o['info']['extGStates']) && count($o['info']['extGStates']))
                    ) {
                        $res .= "\n/Resources <<";

                        if (isset($o['info']['procset'])) {
                            $res .= "\n/ProcSet " . $o['info']['procset'] . " 0 R";
                        }

                        if (isset($o['info']['fonts']) && count($o['info']['fonts'])) {
                            $res .= "\n/Font << ";
                            foreach ($o['info']['fonts'] as $finfo) {
                                $res .= "\n/F" . $finfo['fontNum'] . " " . $finfo['objNum'] . " 0 R";
                            }
                            $res .= "\n>>";
                        }

                        if (isset($o['info']['xObjects']) && count($o['info']['xObjects'])) {
                            $res .= "\n/XObject << ";
                            foreach ($o['info']['xObjects'] as $finfo) {
                                $res .= "\n/" . $finfo['label'] . " " . $finfo['objNum'] . " 0 R";
                            }
                            $res .= "\n>>";
                        }

                        if (isset($o['info']['extGStates']) && count($o['info']['extGStates'])) {
                            $res .= "\n/ExtGState << ";
                            foreach ($o['info']['extGStates'] as $gstate) {
                                $res .= "\n/GS" . $gstate['stateNum'] . " " . $gstate['objNum'] . " 0 R";
                            }
                            $res .= "\n>>";
                        }

                        $res .= "\n>>";
                        if (isset($o['info']['mediaBox'])) {
                            $tmp = $o['info']['mediaBox'];
                            $res .= "\n/MediaBox [" . sprintf(
                                    '%.3F %.3F %.3F %.3F',
                                    $tmp[0],
                                    $tmp[1],
                                    $tmp[2],
                                    $tmp[3]
                                ) . ']';
                        }
                    }

                    $res .= "\n >>\nendobj";
                } else {
                    $res = "\n$id 0 obj\n<< /Type /Pages\n/Count 0\n>>\nendobj";
                }

                return $res;
        }

        return null;
    }

    /**
     * define the outlines in the doc, empty for now
     *
     * @param $id
     * @param $action
     * @param string $options
     * @return string|null
     */
    protected function o_outlines($id, $action, $options = '')
    {
        if ($action !== 'new') {
            $o = &$this->objects[$id];
        }

        switch ($action) {
            case 'new':
                $this->objects[$id] = array('t' => 'outlines', 'info' => array('outlines' => array()));
                $this->o_catalog($this->catalogId, 'outlines', $id);
                break;

            case 'outline':
                $o['info']['outlines'][] = $options;
                break;

            case 'out':
                if (count($o['info']['outlines'])) {
                    $res = "\n$id 0 obj\n<< /Type /Outlines /Kids [";
                    foreach ($o['info']['outlines'] as $v) {
                        $res .= "$v 0 R ";
                    }

                    $res .= "] /Count " . count($o['info']['outlines']) . " >>\nendobj";
                } else {
                    $res = "\n$id 0 obj\n<< /Type /Outlines /Count 0 >>\nendobj";
                }

                return $res;
        }

        return null;
    }

    /**
     * an object to hold the font description
     *
     * @param $id
     * @param $action
     * @param string|array $options
     * @return string|null
     */
    protected function o_font($id, $action, $options = '')
    {
        if ($action !== 'new') {
            $o = &$this->objects[$id];
        }

        switch ($action) {
            case 'new':
                $this->objects[$id] = array(
                    't'    => 'font',
                    'info' => array(
                        'name'         => $options['name'],
                        'fontFileName' => $options['fontFileName'],
                        'SubType'      => 'Type1'
                    )
                );
                $fontNum = $this->numFonts;
                $this->objects[$id]['info']['fontNum'] = $fontNum;

                // deal with the encoding and the differences
                if (isset($options['differences'])) {
                    // then we'll need an encoding dictionary
                    $this->numObj++;
                    $this->o_fontEncoding($this->numObj, 'new', $options);
                    $this->objects[$id]['info']['encodingDictionary'] = $this->numObj;
                } else {
                    if (isset($options['encoding'])) {
                        // we can specify encoding here
                        switch ($options['encoding']) {
                            case 'WinAnsiEncoding':
                            case 'MacRomanEncoding':
                            case 'MacExpertEncoding':
                                $this->objects[$id]['info']['encoding'] = $options['encoding'];
                                break;

                            case 'none':
                                break;

                            default:
                                $this->objects[$id]['info']['encoding'] = 'WinAnsiEncoding';
                                break;
                        }
                    } else {
                        $this->objects[$id]['info']['encoding'] = 'WinAnsiEncoding';
                    }
                }

                if ($this->fonts[$options['fontFileName']]['isUnicode']) {
                    // For Unicode fonts, we need to incorporate font data into
                    // sub-sections that are linked from the primary font section.
                    // Look at o_fontGIDtoCID and o_fontDescendentCID functions
                    // for more information.
                    //
                    // All of this code is adapted from the excellent changes made to
                    // transform FPDF to TCPDF (http://tcpdf.sourceforge.net/)

                    $toUnicodeId = ++$this->numObj;
                    $this->o_toUnicode($toUnicodeId, 'new');
                    $this->objects[$id]['info']['toUnicode'] = $toUnicodeId;

                    $cidFontId = ++$this->numObj;
                    $this->o_fontDescendentCID($cidFontId, 'new', $options);
                    $this->objects[$id]['info']['cidFont'] = $cidFontId;
                }

                // also tell the pages node about the new font
                $this->o_pages($this->currentNode, 'font', array('fontNum' => $fontNum, 'objNum' => $id));
                break;

            case 'add':
                foreach ($options as $k => $v) {
                    switch ($k) {
                        case 'BaseFont':
                            $o['info']['name'] = $v;
                            break;
                        case 'FirstChar':
                        case 'LastChar':
                        case 'Widths':
                        case 'FontDescriptor':
                        case 'SubType':
                            $this->addMessage('o_font ' . $k . " : " . $v);
                            $o['info'][$k] = $v;
                            break;
                    }
                }

                // pass values down to descendent font
                if (isset($o['info']['cidFont'])) {
                    $this->o_fontDescendentCID($o['info']['cidFont'], 'add', $options);
                }
                break;

            case 'out':
                if ($this->fonts[$this->objects[$id]['info']['fontFileName']]['isUnicode']) {
                    // For Unicode fonts, we need to incorporate font data into
                    // sub-sections that are linked from the primary font section.
                    // Look at o_fontGIDtoCID and o_fontDescendentCID functions
                    // for more information.
                    //
                    // All of this code is adapted from the excellent changes made to
                    // transform FPDF to TCPDF (http://tcpdf.sourceforge.net/)

                    $res = "\n$id 0 obj\n<</Type /Font\n/Subtype /Type0\n";
                    $res .= "/BaseFont /" . $o['info']['name'] . "\n";

                    // The horizontal identity mapping for 2-byte CIDs; may be used
                    // with CIDFonts using any Registry, Ordering, and Supplement values.
                    $res .= "/Encoding /Identity-H\n";
                    $res .= "/DescendantFonts [" . $o['info']['cidFont'] . " 0 R]\n";
                    $res .= "/ToUnicode " . $o['info']['toUnicode'] . " 0 R\n";
                    $res .= ">>\n";
                    $res .= "endobj";
                } else {
                    $res = "\n$id 0 obj\n<< /Type /Font\n/Subtype /" . $o['info']['SubType'] . "\n";
                    $res .= "/Name /F" . $o['info']['fontNum'] . "\n";
                    $res .= "/BaseFont /" . $o['info']['name'] . "\n";

                    if (isset($o['info']['encodingDictionary'])) {
                        // then place a reference to the dictionary
                        $res .= "/Encoding " . $o['info']['encodingDictionary'] . " 0 R\n";
                    } else {
                        if (isset($o['info']['encoding'])) {
                            // use the specified encoding
                            $res .= "/Encoding /" . $o['info']['encoding'] . "\n";
                        }
                    }

                    if (isset($o['info']['FirstChar'])) {
                        $res .= "/FirstChar " . $o['info']['FirstChar'] . "\n";
                    }

                    if (isset($o['info']['LastChar'])) {
                        $res .= "/LastChar " . $o['info']['LastChar'] . "\n";
                    }

                    if (isset($o['info']['Widths'])) {
                        $res .= "/Widths " . $o['info']['Widths'] . " 0 R\n";
                    }

                    if (isset($o['info']['FontDescriptor'])) {
                        $res .= "/FontDescriptor " . $o['info']['FontDescriptor'] . " 0 R\n";
                    }

                    $res .= ">>\n";
                    $res .= "endobj";
                }

                return $res;
        }

        return null;
    }

    /**
     * A toUnicode section, needed for unicode fonts
     *
     * @param $id
     * @param $action
     * @return null|string
     */
    protected function o_toUnicode($id, $action)
    {
        switch ($action) {
            case 'new':
                $this->objects[$id] = array(
                    't'    => 'toUnicode'
                );
                break;
            case 'add':
                break;
            case 'out':
                $ordering = '(UCS)';
                $registry = '(Adobe)';

                if ($this->encrypted) {
                    $this->encryptInit($id);
                    $ordering = $this->ARC4($ordering);
                    $registry = $this->ARC4($registry);
                }

                $stream = <<<EOT
/CIDInit /ProcSet findresource begin
12 dict begin
begincmap
/CIDSystemInfo
<</Registry $registry
/Ordering $ordering
/Supplement 0
>> def
/CMapName /Adobe-Identity-UCS def
/CMapType 2 def
1 begincodespacerange
<0000> <FFFF>
endcodespacerange
1 beginbfrange
<0000> <FFFF> <0000>
endbfrange
endcmap
CMapName currentdict /CMap defineresource pop
end
end
EOT;

                $res = "\n$id 0 obj\n";
                $res .= "<</Length " . mb_strlen($stream, '8bit') . " >>\n";
                $res .= "stream\n" . $stream . "\nendstream" . "\nendobj";;

                return $res;
        }

        return null;
    }

    /**
     * a font descriptor, needed for including additional fonts
     *
     * @param $id
     * @param $action
     * @param string $options
     * @return null|string
     */
    protected function o_fontDescriptor($id, $action, $options = '')
    {
        if ($action !== 'new') {
            $o = &$this->objects[$id];
        }

        switch ($action) {
            case 'new':
                $this->objects[$id] = array('t' => 'fontDescriptor', 'info' => $options);
                break;

            case 'out':
                $res = "\n$id 0 obj\n<< /Type /FontDescriptor\n";
                foreach ($o['info'] as $label => $value) {
                    switch ($label) {
                        case 'Ascent':
                        case 'CapHeight':
                        case 'Descent':
                        case 'Flags':
                        case 'ItalicAngle':
                        case 'StemV':
                        case 'AvgWidth':
                        case 'Leading':
                        case 'MaxWidth':
                        case 'MissingWidth':
                        case 'StemH':
                        case 'XHeight':
                        case 'CharSet':
                            if (mb_strlen($value, '8bit')) {
                                $res .= "/$label $value\n";
                            }

                            break;
                        case 'FontFile':
                        case 'FontFile2':
                        case 'FontFile3':
                            $res .= "/$label $value 0 R\n";
                            break;

                        case 'FontBBox':
                            $res .= "/$label [$value[0] $value[1] $value[2] $value[3]]\n";
                            break;

                        case 'FontName':
                            $res .= "/$label /$value\n";
                            break;
                    }
                }

                $res .= ">>\nendobj";

                return $res;
        }

        return null;
    }

    /**
     * the font encoding
     *
     * @param $id
     * @param $action
     * @param string $options
     * @return null|string
     */
    protected function o_fontEncoding($id, $action, $options = '')
    {
        if ($action !== 'new') {
            $o = &$this->objects[$id];
        }

        switch ($action) {
            case 'new':
                // the options array should contain 'differences' and maybe 'encoding'
                $this->objects[$id] = array('t' => 'fontEncoding', 'info' => $options);
                break;

            case 'out':
                $res = "\n$id 0 obj\n<< /Type /Encoding\n";
                if (!isset($o['info']['encoding'])) {
                    $o['info']['encoding'] = 'WinAnsiEncoding';
                }

                if ($o['info']['encoding'] !== 'none') {
                    $res .= "/BaseEncoding /" . $o['info']['encoding'] . "\n";
                }

                $res .= "/Differences \n[";

                $onum = -100;

                foreach ($o['info']['differences'] as $num => $label) {
                    if ($num != $onum + 1) {
                        // we cannot make use of consecutive numbering
                        $res .= "\n$num /$label";
                    } else {
                        $res .= " /$label";
                    }

                    $onum = $num;
                }

                $res .= "\n]\n>>\nendobj";

                return $res;
        }

        return null;
    }

    /**
     * a descendent cid font, needed for unicode fonts
     *
     * @param $id
     * @param $action
     * @param string|array $options
     * @return null|string
     */
    protected function o_fontDescendentCID($id, $action, $options = '')
    {
        if ($action !== 'new') {
            $o = &$this->objects[$id];
        }

        switch ($action) {
            case 'new':
                $this->objects[$id] = array('t' => 'fontDescendentCID', 'info' => $options);

                // we need a CID system info section
                $cidSystemInfoId = ++$this->numObj;
                $this->o_cidSystemInfo($cidSystemInfoId, 'new');
                $this->objects[$id]['info']['cidSystemInfo'] = $cidSystemInfoId;

                // and a CID to GID map
                $cidToGidMapId = ++$this->numObj;
                $this->o_fontGIDtoCIDMap($cidToGidMapId, 'new', $options);
                $this->objects[$id]['info']['cidToGidMap'] = $cidToGidMapId;
                break;

            case 'add':
                foreach ($options as $k => $v) {
                    switch ($k) {
                        case 'BaseFont':
                            $o['info']['name'] = $v;
                            break;

                        case 'FirstChar':
                        case 'LastChar':
                        case 'MissingWidth':
                        case 'FontDescriptor':
                        case 'SubType':
                            $this->addMessage("o_fontDescendentCID $k : $v");
                            $o['info'][$k] = $v;
                            break;
                    }
                }

                // pass values down to cid to gid map
                $this->o_fontGIDtoCIDMap($o['info']['cidToGidMap'], 'add', $options);
                break;

            case 'out':
                $res = "\n$id 0 obj\n";
                $res .= "<</Type /Font\n";
                $res .= "/Subtype /CIDFontType2\n";
                $res .= "/BaseFont /" . $o['info']['name'] . "\n";
                $res .= "/CIDSystemInfo " . $o['info']['cidSystemInfo'] . " 0 R\n";
                //      if (isset($o['info']['FirstChar'])) {
                //        $res.= "/FirstChar ".$o['info']['FirstChar']."\n";
                //      }

                //      if (isset($o['info']['LastChar'])) {
                //        $res.= "/LastChar ".$o['info']['LastChar']."\n";
                //      }
                if (isset($o['info']['FontDescriptor'])) {
                    $res .= "/FontDescriptor " . $o['info']['FontDescriptor'] . " 0 R\n";
                }

                if (isset($o['info']['MissingWidth'])) {
                    $res .= "/DW " . $o['info']['MissingWidth'] . "\n";
                }

                if (isset($o['info']['fontFileName']) && isset($this->fonts[$o['info']['fontFileName']]['CIDWidths'])) {
                    $cid_widths = &$this->fonts[$o['info']['fontFileName']]['CIDWidths'];
                    $w = '';
                    foreach ($cid_widths as $cid => $width) {
                        $w .= "$cid [$width] ";
                    }
                    $res .= "/W [$w]\n";
                }

                $res .= "/CIDToGIDMap " . $o['info']['cidToGidMap'] . " 0 R\n";
                $res .= ">>\n";
                $res .= "endobj";

                return $res;
        }

        return null;
    }

    /**
     * CID system info section, needed for unicode fonts
     *
     * @param $id
     * @param $action
     * @return null|string
     */
    protected function o_cidSystemInfo($id, $action)
    {
        switch ($action) {
            case 'new':
                $this->objects[$id] = array(
                    't' => 'cidSystemInfo'
                );
                break;
            case 'add':
                break;
            case 'out':
                $ordering = '(UCS)';
                $registry = '(Adobe)';

                if ($this->encrypted) {
                    $this->encryptInit($id);
                    $ordering = $this->ARC4($ordering);
                    $registry = $this->ARC4($registry);
                }


                $res = "\n$id 0 obj\n";

                $res .= '<</Registry ' . $registry . "\n"; // A string identifying an issuer of character collections
                $res .= '/Ordering ' . $ordering . "\n"; // A string that uniquely names a character collection issued by a specific registry
                $res .= "/Supplement 0\n"; // The supplement number of the character collection.
                $res .= ">>";

                $res .= "\nendobj";;

                return $res;
        }

        return null;
    }

    /**
     * a font glyph to character map, needed for unicode fonts
     *
     * @param $id
     * @param $action
     * @param string $options
     * @return null|string
     */
    protected function o_fontGIDtoCIDMap($id, $action, $options = '')
    {
        if ($action !== 'new') {
            $o = &$this->objects[$id];
        }

        switch ($action) {
            case 'new':
                $this->objects[$id] = array('t' => 'fontGIDtoCIDMap', 'info' => $options);
                break;

            case 'out':
                $res = "\n$id 0 obj\n";
                $fontFileName = $o['info']['fontFileName'];
                $tmp = $this->fonts[$fontFileName]['CIDtoGID'] = base64_decode($this->fonts[$fontFileName]['CIDtoGID']);

                $compressed = isset($this->fonts[$fontFileName]['CIDtoGID_Compressed']) &&
                    $this->fonts[$fontFileName]['CIDtoGID_Compressed'];

                if (!$compressed && isset($o['raw'])) {
                    $res .= $tmp;
                } else {
                    $res .= "<<";

                    if (!$compressed && $this->compressionReady && $this->options['compression']) {
                        // then implement ZLIB based compression on this content stream
                        $compressed = true;
                        $tmp = gzcompress($tmp, 6);
                    }
                    if ($compressed) {
                        $res .= "\n/Filter /FlateDecode";
                    }

                    if ($this->encrypted) {
                        $this->encryptInit($id);
                        $tmp = $this->ARC4($tmp);
                    }

                    $res .= "\n/Length " . mb_strlen($tmp, '8bit') . ">>\nstream\n$tmp\nendstream";
                }

                $res .= "\nendobj";

                return $res;
        }

        return null;
    }

    /**
     * the document procset, solves some problems with printing to old PS printers
     *
     * @param $id
     * @param $action
     * @param string $options
     * @return null|string
     */
    protected function o_procset($id, $action, $options = '')
    {
        if ($action !== 'new') {
            $o = &$this->objects[$id];
        }

        switch ($action) {
            case 'new':
                $this->objects[$id] = array('t' => 'procset', 'info' => array('PDF' => 1, 'Text' => 1));
                $this->o_pages($this->currentNode, 'procset', $id);
                $this->procsetObjectId = $id;
                break;

            case 'add':
                // this is to add new items to the procset list, despite the fact that this is considered
                // obsolete, the items are required for printing to some postscript printers
                switch ($options) {
                    case 'ImageB':
                    case 'ImageC':
                    case 'ImageI':
                        $o['info'][$options] = 1;
                        break;
                }
                break;

            case 'out':
                $res = "\n$id 0 obj\n[";
                foreach ($o['info'] as $label => $val) {
                    $res .= "/$label ";
                }
                $res .= "]\nendobj";

                return $res;
        }

        return null;
    }

    /**
     * define the document information
     *
     * @param $id
     * @param $action
     * @param string $options
     * @return null|string
     */
    protected function o_info($id, $action, $options = '')
    {
        switch ($action) {
            case 'new':
                $this->infoObject = $id;
                $date = 'D:' . @date('Ymd');
                $this->objects[$id] = array(
                    't'    => 'info',
                    'info' => array(
                        'Producer'      => 'CPDF (dompdf)',
                        'CreationDate' => $date
                    )
                );
                break;
            case 'Title':
            case 'Author':
            case 'Subject':
            case 'Keywords':
            case 'Creator':
            case 'Producer':
            case 'CreationDate':
            case 'ModDate':
            case 'Trapped':
                $this->objects[$id]['info'][$action] = $options;
                break;

            case 'out':
                $encrypted = $this->encrypted;
                if ($encrypted) {
                    $this->encryptInit($id);
                }

                $res = "\n$id 0 obj\n<<\n";
                $o = &$this->objects[$id];
                foreach ($o['info'] as $k => $v) {
                    $res .= "/$k (";

                    // dates must be outputted as-is, without Unicode transformations
                    if ($k !== 'CreationDate' && $k !== 'ModDate') {
                        $v = $this->filterText($v, true, false);
                    }

                    if ($encrypted) {
                        $v = $this->ARC4($v);
                    }

                    $res .= $v;
                    $res .= ")\n";
                }

                $res .= ">>\nendobj";

                return $res;
        }

        return null;
    }

    /**
     * an action object, used to link to URLS initially
     *
     * @param $id
     * @param $action
     * @param string $options
     * @return null|string
     */
    protected function o_action($id, $action, $options = '')
    {
        if ($action !== 'new') {
            $o = &$this->objects[$id];
        }

        switch ($action) {
            case 'new':
                if (is_array($options)) {
                    $this->objects[$id] = array('t' => 'action', 'info' => $options, 'type' => $options['type']);
                } else {
                    // then assume a URI action
                    $this->objects[$id] = array('t' => 'action', 'info' => $options, 'type' => 'URI');
                }
                break;

            case 'out':
                if ($this->encrypted) {
                    $this->encryptInit($id);
                }

                $res = "\n$id 0 obj\n<< /Type /Action";
                switch ($o['type']) {
                    case 'ilink':
                        if (!isset($this->destinations[(string)$o['info']['label']])) {
                            break;
                        }

                        // there will be an 'label' setting, this is the name of the destination
                        $res .= "\n/S /GoTo\n/D " . $this->destinations[(string)$o['info']['label']] . " 0 R";
                        break;

                    case 'URI':
                        $res .= "\n/S /URI\n/URI (";
                        if ($this->encrypted) {
                            $res .= $this->filterText($this->ARC4($o['info']), false, false);
                        } else {
                            $res .= $this->filterText($o['info'], false, false);
                        }

                        $res .= ")";
                        break;
                }

                $res .= "\n>>\nendobj";

                return $res;
        }

        return null;
    }

    /**
     * an annotation object, this will add an annotation to the current page.
     * initially will support just link annotations
     *
     * @param $id
     * @param $action
     * @param string $options
     * @return null|string
     */
    protected function o_annotation($id, $action, $options = '')
    {
        if ($action !== 'new') {
            $o = &$this->objects[$id];
        }

        switch ($action) {
            case 'new':
                // add the annotation to the current page
                $pageId = $this->currentPage;
                $this->o_page($pageId, 'annot', $id);

                // and add the action object which is going to be required
                switch ($options['type']) {
                    case 'link':
                        $this->objects[$id] = array('t' => 'annotation', 'info' => $options);
                        $this->numObj++;
                        $this->o_action($this->numObj, 'new', $options['url']);
                        $this->objects[$id]['info']['actionId'] = $this->numObj;
                        break;

                    case 'ilink':
                        // this is to a named internal link
                        $label = $options['label'];
                        $this->objects[$id] = array('t' => 'annotation', 'info' => $options);
                        $this->numObj++;
                        $this->o_action($this->numObj, 'new', array('type' => 'ilink', 'label' => $label));
                        $this->objects[$id]['info']['actionId'] = $this->numObj;
                        break;
                }
                break;

            case 'out':
                $res = "\n$id 0 obj\n<< /Type /Annot";
                switch ($o['info']['type']) {
                    case 'link':
                    case 'ilink':
                        $res .= "\n/Subtype /Link";
                        break;
                }
                $res .= "\n/A " . $o['info']['actionId'] . " 0 R";
                $res .= "\n/Border [0 0 0]";
                $res .= "\n/H /I";
                $res .= "\n/Rect [ ";

                foreach ($o['info']['rect'] as $v) {
                    $res .= sprintf("%.4F ", $v);
                }

                $res .= "]";
                $res .= "\n>>\nendobj";

                return $res;
        }

        return null;
    }

    /**
     * a page object, it also creates a contents object to hold its contents
     *
     * @param $id
     * @param $action
     * @param string $options
     * @return null|string
     */
    protected function o_page($id, $action, $options = '')
    {
        if ($action !== 'new') {
            $o = &$this->objects[$id];
        }

        switch ($action) {
            case 'new':
                $this->numPages++;
                $this->objects[$id] = array(
                    't'    => 'page',
                    'info' => array(
                        'parent'  => $this->currentNode,
                        'pageNum' => $this->numPages,
                        'mediaBox' => $this->objects[$this->currentNode]['info']['mediaBox']
                    )
                );

                if (is_array($options)) {
                    // then this must be a page insertion, array should contain 'rid','pos'=[before|after]
                    $options['id'] = $id;
                    $this->o_pages($this->currentNode, 'page', $options);
                } else {
                    $this->o_pages($this->currentNode, 'page', $id);
                }

                $this->currentPage = $id;
                //make a contents object to go with this page
                $this->numObj++;
                $this->o_contents($this->numObj, 'new', $id);
                $this->currentContents = $this->numObj;
                $this->objects[$id]['info']['contents'] = array();
                $this->objects[$id]['info']['contents'][] = $this->numObj;

                $match = ($this->numPages % 2 ? 'odd' : 'even');
                foreach ($this->addLooseObjects as $oId => $target) {
                    if ($target === 'all' || $match === $target) {
                        $this->objects[$id]['info']['contents'][] = $oId;
                    }
                }
                break;

            case 'content':
                $o['info']['contents'][] = $options;
                break;

            case 'annot':
                // add an annotation to this page
                if (!isset($o['info']['annot'])) {
                    $o['info']['annot'] = array();
                }

                // $options should contain the id of the annotation dictionary
                $o['info']['annot'][] = $options;
                break;

            case 'out':
                $res = "\n$id 0 obj\n<< /Type /Page";
                if (isset($o['info']['mediaBox'])) {
                    $tmp = $o['info']['mediaBox'];
                    $res .= "\n/MediaBox [" . sprintf(
                            '%.3F %.3F %.3F %.3F',
                            $tmp[0],
                            $tmp[1],
                            $tmp[2],
                            $tmp[3]
                        ) . ']';
                }
                $res .= "\n/Parent " . $o['info']['parent'] . " 0 R";

                if (isset($o['info']['annot'])) {
                    $res .= "\n/Annots [";
                    foreach ($o['info']['annot'] as $aId) {
                        $res .= " $aId 0 R";
                    }
                    $res .= " ]";
                }

                $count = count($o['info']['contents']);
                if ($count == 1) {
                    $res .= "\n/Contents " . $o['info']['contents'][0] . " 0 R";
                } else {
                    if ($count > 1) {
                        $res .= "\n/Contents [\n";

                        // reverse the page contents so added objects are below normal content
                        //foreach (array_reverse($o['info']['contents']) as $cId) {
                        // Back to normal now that I've got transparency working --Benj
                        foreach ($o['info']['contents'] as $cId) {
                            $res .= "$cId 0 R\n";
                        }
                        $res .= "]";
                    }
                }

                $res .= "\n>>\nendobj";

                return $res;
        }

        return null;
    }

    /**
     * the contents objects hold all of the content which appears on pages
     *
     * @param $id
     * @param $action
     * @param string|array $options
     * @return null|string
     */
    protected function o_contents($id, $action, $options = '')
    {
        if ($action !== 'new') {
            $o = &$this->objects[$id];
        }

        switch ($action) {
            case 'new':
                $this->objects[$id] = array('t' => 'contents', 'c' => '', 'info' => array());
                if (mb_strlen($options, '8bit') && intval($options)) {
                    // then this contents is the primary for a page
                    $this->objects[$id]['onPage'] = $options;
                } else {
                    if ($options === 'raw') {
                        // then this page contains some other type of system object
                        $this->objects[$id]['raw'] = 1;
                    }
                }
                break;

            case 'add':
                // add more options to the declaration
                foreach ($options as $k => $v) {
                    $o['info'][$k] = $v;
                }

            case 'out':
                $tmp = $o['c'];
                $res = "\n$id 0 obj\n";

                if (isset($this->objects[$id]['raw'])) {
                    $res .= $tmp;
                } else {
                    $res .= "<<";
                    if ($this->compressionReady && $this->options['compression']) {
                        // then implement ZLIB based compression on this content stream
                        $res .= " /Filter /FlateDecode";
                        $tmp = gzcompress($tmp, 6);
                    }

                    if ($this->encrypted) {
                        $this->encryptInit($id);
                        $tmp = $this->ARC4($tmp);
                    }

                    foreach ($o['info'] as $k => $v) {
                        $res .= "\n/$k $v";
                    }

                    $res .= "\n/Length " . mb_strlen($tmp, '8bit') . " >>\nstream\n$tmp\nendstream";
                }

                $res .= "\nendobj";

                return $res;
        }

        return null;
    }

    /**
     * @param $id
     * @param $action
     * @return string|null
     */
    protected function o_embedjs($id, $action)
    {
        switch ($action) {
            case 'new':
                $this->objects[$id] = array(
                    't'    => 'embedjs',
                    'info' => array(
                        'Names' => '[(EmbeddedJS) ' . ($id + 1) . ' 0 R]'
                    )
                );
                break;

            case 'out':
                $o = &$this->objects[$id];
                $res = "\n$id 0 obj\n<< ";
                foreach ($o['info'] as $k => $v) {
                    $res .= "\n/$k $v";
                }
                $res .= "\n>>\nendobj";

                return $res;
        }

        return null;
    }

    /**
     * @param $id
     * @param $action
     * @param string $code
     * @return null|string
     */
    protected function o_javascript($id, $action, $code = '')
    {
        switch ($action) {
            case 'new':
                $this->objects[$id] = array(
                    't'    => 'javascript',
                    'info' => array(
                        'S'  => '/JavaScript',
                        'JS' => '(' . $this->filterText($code, true, false) . ')',
                    )
                );
                break;

            case 'out':
                $o = &$this->objects[$id];
                $res = "\n$id 0 obj\n<< ";

                foreach ($o['info'] as $k => $v) {
                    $res .= "\n/$k $v";
                }
                $res .= "\n>>\nendobj";

                return $res;
        }

        return null;
    }

    /**
     * an image object, will be an XObject in the document, includes description and data
     *
     * @param $id
     * @param $action
     * @param string $options
     * @return null|string
     */
    protected function o_image($id, $action, $options = '')
    {
        switch ($action) {
            case 'new':
                // make the new object
                $this->objects[$id] = array('t' => 'image', 'data' => &$options['data'], 'info' => array());

                $info =& $this->objects[$id]['info'];

                $info['Type'] = '/XObject';
                $info['Subtype'] = '/Image';
                $info['Width'] = $options['iw'];
                $info['Height'] = $options['ih'];

                if (isset($options['masked']) && $options['masked']) {
                    $info['SMask'] = ($this->numObj - 1) . ' 0 R';
                }

                if (!isset($options['type']) || $options['type'] === 'jpg') {
                    if (!isset($options['channels'])) {
                        $options['channels'] = 3;
                    }

                    switch ($options['channels']) {
                        case  1:
                            $info['ColorSpace'] = '/DeviceGray';
                            break;
                        case  4:
                            $info['ColorSpace'] = '/DeviceCMYK';
                            break;
                        default:
                            $info['ColorSpace'] = '/DeviceRGB';
                            break;
                    }

                    if ($info['ColorSpace'] === '/DeviceCMYK') {
                        $info['Decode'] = '[1 0 1 0 1 0 1 0]';
                    }

                    $info['Filter'] = '/DCTDecode';
                    $info['BitsPerComponent'] = 8;
                } else {
                    if ($options['type'] === 'png') {
                        $info['Filter'] = '/FlateDecode';
                        $info['DecodeParms'] = '<< /Predictor 15 /Colors ' . $options['ncolor'] . ' /Columns ' . $options['iw'] . ' /BitsPerComponent ' . $options['bitsPerComponent'] . '>>';

                        if ($options['isMask']) {
                            $info['ColorSpace'] = '/DeviceGray';
                        } else {
                            if (mb_strlen($options['pdata'], '8bit')) {
                                $tmp = ' [ /Indexed /DeviceRGB ' . (mb_strlen($options['pdata'], '8bit') / 3 - 1) . ' ';
                                $this->numObj++;
                                $this->o_contents($this->numObj, 'new');
                                $this->objects[$this->numObj]['c'] = $options['pdata'];
                                $tmp .= $this->numObj . ' 0 R';
                                $tmp .= ' ]';
                                $info['ColorSpace'] = $tmp;

                                if (isset($options['transparency'])) {
                                    $transparency = $options['transparency'];
                                    switch ($transparency['type']) {
                                        case 'indexed':
                                            $tmp = ' [ ' . $transparency['data'] . ' ' . $transparency['data'] . '] ';
                                            $info['Mask'] = $tmp;
                                            break;

                                        case 'color-key':
                                            $tmp = ' [ ' .
                                                $transparency['r'] . ' ' . $transparency['r'] .
                                                $transparency['g'] . ' ' . $transparency['g'] .
                                                $transparency['b'] . ' ' . $transparency['b'] .
                                                ' ] ';
                                            $info['Mask'] = $tmp;
                                            break;
                                    }
                                }
                            } else {
                                if (isset($options['transparency'])) {
                                    $transparency = $options['transparency'];

                                    switch ($transparency['type']) {
                                        case 'indexed':
                                            $tmp = ' [ ' . $transparency['data'] . ' ' . $transparency['data'] . '] ';
                                            $info['Mask'] = $tmp;
                                            break;

                                        case 'color-key':
                                            $tmp = ' [ ' .
                                                $transparency['r'] . ' ' . $transparency['r'] . ' ' .
                                                $transparency['g'] . ' ' . $transparency['g'] . ' ' .
                                                $transparency['b'] . ' ' . $transparency['b'] .
                                                ' ] ';
                                            $info['Mask'] = $tmp;
                                            break;
                                    }
                                }
                                $info['ColorSpace'] = '/' . $options['color'];
                            }
                        }

                        $info['BitsPerComponent'] = $options['bitsPerComponent'];
                    }
                }

                // assign it a place in the named resource dictionary as an external object, according to
                // the label passed in with it.
                $this->o_pages($this->currentNode, 'xObject', array('label' => $options['label'], 'objNum' => $id));

                // also make sure that we have the right procset object for it.
                $this->o_procset($this->procsetObjectId, 'add', 'ImageC');
                break;

            case 'out':
                $o = &$this->objects[$id];
                $tmp = &$o['data'];
                $res = "\n$id 0 obj\n<<";

                foreach ($o['info'] as $k => $v) {
                    $res .= "\n/$k $v";
                }

                if ($this->encrypted) {
                    $this->encryptInit($id);
                    $tmp = $this->ARC4($tmp);
                }

                $res .= "\n/Length " . mb_strlen($tmp, '8bit') . ">>\nstream\n$tmp\nendstream\nendobj";

                return $res;
        }

        return null;
    }

    /**
     * graphics state object
     *
     * @param $id
     * @param $action
     * @param string $options
     * @return null|string
     */
    protected function o_extGState($id, $action, $options = "")
    {
        static $valid_params = array(
            "LW",
            "LC",
            "LC",
            "LJ",
            "ML",
            "D",
            "RI",
            "OP",
            "op",
            "OPM",
            "Font",
            "BG",
            "BG2",
            "UCR",
            "TR",
            "TR2",
            "HT",
            "FL",
            "SM",
            "SA",
            "BM",
            "SMask",
            "CA",
            "ca",
            "AIS",
            "TK"
        );

        switch ($action) {
            case "new":
                $this->objects[$id] = array('t' => 'extGState', 'info' => $options);

                // Tell the pages about the new resource
                $this->numStates++;
                $this->o_pages($this->currentNode, 'extGState', array("objNum" => $id, "stateNum" => $this->numStates));
                break;

            case "out":
                $o = &$this->objects[$id];
                $res = "\n$id 0 obj\n<< /Type /ExtGState\n";

                foreach ($o["info"] as $k => $v) {
                    if (!in_array($k, $valid_params)) {
                        continue;
                    }
                    $res .= "/$k $v\n";
                }

                $res .= ">>\nendobj";

                return $res;
        }

        return null;
    }

    /**
     * encryption object.
     *
     * @param $id
     * @param $action
     * @param string $options
     * @return string|null
     */
    protected function o_encryption($id, $action, $options = '')
    {
        switch ($action) {
            case 'new':
                // make the new object
                $this->objects[$id] = array('t' => 'encryption', 'info' => $options);
                $this->arc4_objnum = $id;
                break;

            case 'keys':
                // figure out the additional parameters required
                $pad = chr(0x28) . chr(0xBF) . chr(0x4E) . chr(0x5E) . chr(0x4E) . chr(0x75) . chr(0x8A) . chr(0x41)
                    . chr(0x64) . chr(0x00) . chr(0x4E) . chr(0x56) . chr(0xFF) . chr(0xFA) . chr(0x01) . chr(0x08)
                    . chr(0x2E) . chr(0x2E) . chr(0x00) . chr(0xB6) . chr(0xD0) . chr(0x68) . chr(0x3E) . chr(0x80)
                    . chr(0x2F) . chr(0x0C) . chr(0xA9) . chr(0xFE) . chr(0x64) . chr(0x53) . chr(0x69) . chr(0x7A);

                $info = $this->objects[$id]['info'];

                $len = mb_strlen($info['owner'], '8bit');

                if ($len > 32) {
                    $owner = substr($info['owner'], 0, 32);
                } else {
                    if ($len < 32) {
                        $owner = $info['owner'] . substr($pad, 0, 32 - $len);
                    } else {
                        $owner = $info['owner'];
                    }
                }

                $len = mb_strlen($info['user'], '8bit');
                if ($len > 32) {
                    $user = substr($info['user'], 0, 32);
                } else {
                    if ($len < 32) {
                        $user = $info['user'] . substr($pad, 0, 32 - $len);
                    } else {
                        $user = $info['user'];
                    }
                }

                $tmp = $this->md5_16($owner);
                $okey = substr($tmp, 0, 5);
                $this->ARC4_init($okey);
                $ovalue = $this->ARC4($user);
                $this->objects[$id]['info']['O'] = $ovalue;

                // now make the u value, phew.
                $tmp = $this->md5_16(
                    $user . $ovalue . chr($info['p']) . chr(255) . chr(255) . chr(255) . hex2bin($this->fileIdentifier)
                );

                $ukey = substr($tmp, 0, 5);
                $this->ARC4_init($ukey);
                $this->encryptionKey = $ukey;
                $this->encrypted = true;
                $uvalue = $this->ARC4($pad);
                $this->objects[$id]['info']['U'] = $uvalue;
                // initialize the arc4 array
                break;

            case 'out':
                $o = &$this->objects[$id];

                $res = "\n$id 0 obj\n<<";
                $res .= "\n/Filter /Standard";
                $res .= "\n/V 1";
                $res .= "\n/R 2";
                $res .= "\n/O (" . $this->filterText($o['info']['O'], false, false) . ')';
                $res .= "\n/U (" . $this->filterText($o['info']['U'], false, false) . ')';
                // and the p-value needs to be converted to account for the twos-complement approach
                $o['info']['p'] = (($o['info']['p'] ^ 255) + 1) * -1;
                $res .= "\n/P " . ($o['info']['p']);
                $res .= "\n>>\nendobj";

                return $res;
        }

        return null;
    }

    /**
     * ARC4 functions
     * A series of function to implement ARC4 encoding in PHP
     */

    /**
     * calculate the 16 byte version of the 128 bit md5 digest of the string
     *
     * @param $string
     * @return string
     */
    function md5_16($string)
    {
        $tmp = md5($string);
        $out = '';
        for ($i = 0; $i <= 30; $i = $i + 2) {
            $out .= chr(hexdec(substr($tmp, $i, 2)));
        }

        return $out;
    }

    /**
     * initialize the encryption for processing a particular object
     *
     * @param $id
     */
    function encryptInit($id)
    {
        $tmp = $this->encryptionKey;
        $hex = dechex($id);
        if (mb_strlen($hex, '8bit') < 6) {
            $hex = substr('000000', 0, 6 - mb_strlen($hex, '8bit')) . $hex;
        }
        $tmp .= chr(hexdec(substr($hex, 4, 2)))
            . chr(hexdec(substr($hex, 2, 2)))
            . chr(hexdec(substr($hex, 0, 2)))
            . chr(0)
            . chr(0)
        ;
        $key = $this->md5_16($tmp);
        $this->ARC4_init(substr($key, 0, 10));
    }

    /**
     * initialize the ARC4 encryption
     *
     * @param string $key
     */
    function ARC4_init($key = '')
    {
        $this->arc4 = '';

        // setup the control array
        if (mb_strlen($key, '8bit') == 0) {
            return;
        }

        $k = '';
        while (mb_strlen($k, '8bit') < 256) {
            $k .= $key;
        }

        $k = substr($k, 0, 256);
        for ($i = 0; $i < 256; $i++) {
            $this->arc4 .= chr($i);
        }

        $j = 0;

        for ($i = 0; $i < 256; $i++) {
            $t = $this->arc4[$i];
            $j = ($j + ord($t) + ord($k[$i])) % 256;
            $this->arc4[$i] = $this->arc4[$j];
            $this->arc4[$j] = $t;
        }
    }

    /**
     * ARC4 encrypt a text string
     *
     * @param $text
     * @return string
     */
    function ARC4($text)
    {
        $len = mb_strlen($text, '8bit');
        $a = 0;
        $b = 0;
        $c = $this->arc4;
        $out = '';
        for ($i = 0; $i < $len; $i++) {
            $a = ($a + 1) % 256;
            $t = $c[$a];
            $b = ($b + ord($t)) % 256;
            $c[$a] = $c[$b];
            $c[$b] = $t;
            $k = ord($c[(ord($c[$a]) + ord($c[$b])) % 256]);
            $out .= chr(ord($text[$i]) ^ $k);
        }

        return $out;
    }

    /**
     * functions which can be called to adjust or add to the document
     */

    /**
     * add a link in the document to an external URL
     *
     * @param $url
     * @param $x0
     * @param $y0
     * @param $x1
     * @param $y1
     */
    function addLink($url, $x0, $y0, $x1, $y1)
    {
        $this->numObj++;
        $info = array('type' => 'link', 'url' => $url, 'rect' => array($x0, $y0, $x1, $y1));
        $this->o_annotation($this->numObj, 'new', $info);
    }

    /**
     * add a link in the document to an internal destination (ie. within the document)
     *
     * @param $label
     * @param $x0
     * @param $y0
     * @param $x1
     * @param $y1
     */
    function addInternalLink($label, $x0, $y0, $x1, $y1)
    {
        $this->numObj++;
        $info = array('type' => 'ilink', 'label' => $label, 'rect' => array($x0, $y0, $x1, $y1));
        $this->o_annotation($this->numObj, 'new', $info);
    }

    /**
     * set the encryption of the document
     * can be used to turn it on and/or set the passwords which it will have.
     * also the functions that the user will have are set here, such as print, modify, add
     *
     * @param string $userPass
     * @param string $ownerPass
     * @param array $pc
     */
    function setEncryption($userPass = '', $ownerPass = '', $pc = array())
    {
        $p = bindec("11000000");

        $options = array('print' => 4, 'modify' => 8, 'copy' => 16, 'add' => 32);

        foreach ($pc as $k => $v) {
            if ($v && isset($options[$k])) {
                $p += $options[$k];
            } else {
                if (isset($options[$v])) {
                    $p += $options[$v];
                }
            }
        }

        // implement encryption on the document
        if ($this->arc4_objnum == 0) {
            // then the block does not exist already, add it.
            $this->numObj++;
            if (mb_strlen($ownerPass) == 0) {
                $ownerPass = $userPass;
            }

            $this->o_encryption($this->numObj, 'new', array('user' => $userPass, 'owner' => $ownerPass, 'p' => $p));
        }
    }

    /**
     * should be used for internal checks, not implemented as yet
     */
    function checkAllHere()
    {
    }

    /**
     * return the pdf stream as a string returned from the function
     *
     * @param bool $debug
     * @return string
     */
    function output($debug = false)
    {
        if ($debug) {
            // turn compression off
            $this->options['compression'] = false;
        }

        if ($this->javascript) {
            $this->numObj++;

            $js_id = $this->numObj;
            $this->o_embedjs($js_id, 'new');
            $this->o_javascript(++$this->numObj, 'new', $this->javascript);

            $id = $this->catalogId;

            $this->o_catalog($id, 'javascript', $js_id);
        }

        if ($this->fileIdentifier === '') {
            $tmp = implode('',  $this->objects[$this->infoObject]['info']);
            $this->fileIdentifier = md5('DOMPDF' . __FILE__ . $tmp . microtime() . mt_rand());
        }

        if ($this->arc4_objnum) {
            $this->o_encryption($this->arc4_objnum, 'keys');
            $this->ARC4_init($this->encryptionKey);
        }

        $this->checkAllHere();

        $xref = array();
        $content = '%PDF-1.3';
        $pos = mb_strlen($content, '8bit');

        foreach ($this->objects as $k => $v) {
            $tmp = 'o_' . $v['t'];
            $cont = $this->$tmp($k, 'out');
            $content .= $cont;
            $xref[] = $pos + 1; //+1 to account for \n at the start of each object
            $pos += mb_strlen($cont, '8bit');
        }

        $content .= "\nxref\n0 " . (count($xref) + 1) . "\n0000000000 65535 f \n";

        foreach ($xref as $p) {
            $content .= str_pad($p, 10, "0", STR_PAD_LEFT) . " 00000 n \n";
        }

        $content .= "trailer\n<<\n" .
            '/Size ' . (count($xref) + 1) . "\n" .
            '/Root 1 0 R' . "\n" .
            '/Info ' . $this->infoObject . " 0 R\n"
        ;

        // if encryption has been applied to this document then add the marker for this dictionary
        if ($this->arc4_objnum > 0) {
            $content .= '/Encrypt ' . $this->arc4_objnum . " 0 R\n";
        }

        $content .= '/ID[<' . $this->fileIdentifier . '><' . $this->fileIdentifier . ">]\n";

        // account for \n added at start of xref table
        $pos++;

        $content .= ">>\nstartxref\n$pos\n%%EOF\n";

        return $content;
    }

    /**
     * initialize a new document
     * if this is called on an existing document results may be unpredictable, but the existing document would be lost at minimum
     * this function is called automatically by the constructor function
     *
     * @param array $pageSize
     */
    private function newDocument($pageSize = array(0, 0, 612, 792))
    {
        $this->numObj = 0;
        $this->objects = array();

        $this->numObj++;
        $this->o_catalog($this->numObj, 'new');

        $this->numObj++;
        $this->o_outlines($this->numObj, 'new');

        $this->numObj++;
        $this->o_pages($this->numObj, 'new');

        $this->o_pages($this->numObj, 'mediaBox', $pageSize);
        $this->currentNode = 3;

        $this->numObj++;
        $this->o_procset($this->numObj, 'new');

     