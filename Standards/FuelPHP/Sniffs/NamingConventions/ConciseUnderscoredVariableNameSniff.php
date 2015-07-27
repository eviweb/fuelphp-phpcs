<?php

/**
 * FuelPHP_Sniffs_NamingConventions_ConciseUnderscoredVariableNameSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Eric VILLARD <dev@eviweb.fr>
 * @copyright 2012 Eric VILLARD <dev@eviweb.fr>
 * @license   http://opensource.org/licenses/MIT MIT License
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
if (class_exists('FuelPHP_Sniffs_NamingConventions_UnderscoredWithScopeFunctionNameSniff', true) === false) {
    throw new PHP_CodeSniffer_Exception(
        'Class FuelPHP_Sniffs_NamingConventions_UnderscoredWithScopeFunctionNameSniff not found'
    );
}

/**
 * FuelPHP_Sniffs_NamingConventions_ConciseUnderscoredVariableNameSniff.
 *
 * Ensures variable names use underscore format and are not too long;
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Eric VILLARD <dev@eviweb.fr>
 * @copyright 2012 Eric VILLARD <dev@eviweb.fr>
 * @license   http://opensource.org/licenses/MIT MIT License
 * @version   Release: 1.0.0
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class FuelPHP_Sniffs_NamingConventions_ConciseUnderscoredVariableNameSniff 
    implements PHP_CodeSniffer_Sniff
{

    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array(
        'PHP',
        'JS',
        'CSS',
    );

    /**
     * variable name length limit
     * 
     * @var integer 
     */
    public $maxlength = 30;

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_VAR, T_VARIABLE);
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile All the tokens found in the document.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Make sure this is a variable.
        if ($tokens[$stackPtr]['type'] !== 'T_VARIABLE'
            && $tokens[$stackPtr]['type'] !== 'T_VARIABLE'
        ) {
            return;
        }

        $name = $tokens[$stackPtr]['content'];

        if (FuelPHP_Sniffs_NamingConventions_UnderscoredWithScopeFunctionNameSniff::isUnderscoreName($name) === false) {
            $error = 'Variable name "%s" does not use underscore format.
                Upper case forbidden.';
            $data  = array($name);
            $phpcsFile->addError($error, $stackPtr, 'NotUnderscore', $data);
        }

        if (strlen($name) > $this->maxlength) {
            $warning = 'Variable name "%s" should be more concise.
                Actually more than ' . $this->maxlength . ' chars.';
            $data  = array($name);
            $phpcsFile->addWarning($warning, $stackPtr, 'VariableNameTooLong', $data);
        }
    }
}
?>
